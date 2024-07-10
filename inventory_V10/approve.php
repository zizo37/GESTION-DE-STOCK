<?php
include "connection.php";
session_start();

$errorMessage = '';
$successMessage = '';
$selectedMarche = '';

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

// Fetch the list of marches
$sql_marche = 'SELECT idMarche, NomMarche FROM bc_marche';
$stmt_marche = $conn->prepare($sql_marche);
$stmt_marche->execute();
$marcheList = $stmt_marche->fetchAll(PDO::FETCH_ASSOC);

$id = isset($_GET['id']) ? $_GET['id'] : null;

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['bc_marche_id'])) {
        $errorMessage = "Error: Marche not selected.";
    } else {
        $selectedMarcheId = $_POST['bc_marche_id'];

        // Find the selected marche
        foreach ($marcheList as $marche) {
            if ($marche['idMarche'] == $selectedMarcheId) {
                $selectedMarche = $marche['NomMarche'];
                break;
            }
        }

        if (empty($selectedMarche)) {
            $errorMessage = "Error: Invalid marche selected.";
        } else {
            $sql_notif = 'SELECT id, article_id, quantity, location_id, beneficiary_matricule FROM notification WHERE id = :id';
            $stmt_notif = $conn->prepare($sql_notif);
            $stmt_notif->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_notif->execute();
            
            $notification = $stmt_notif->fetch(PDO::FETCH_ASSOC);

            if ($notification) {
                $articleId = $notification['article_id'];
                $qte_s = $notification['quantity']; 
                $bc_marche_id = $selectedMarcheId; 
                $beneficiaire_matricule = $notification['beneficiary_matricule'];
                $location = $notification['location_id'];

                // Check if the beneficiary exists in the 'beneficiaire' table
                $sql_check_beneficiary = 'SELECT matricule FROM beneficiaire WHERE matriculeU = :beneficiaire_matricule';
                $stmt_check_beneficiary = $conn->prepare($sql_check_beneficiary);
                $stmt_check_beneficiary->bindParam(':beneficiaire_matricule', $beneficiaire_matricule, PDO::PARAM_STR);
                $stmt_check_beneficiary->execute();
                $beneficiary_exists = $stmt_check_beneficiary->fetchColumn();

                if ($beneficiary_exists) {
                    // Beneficiary exists, proceed with the insertion into the 'amortissement' table
                    $sql_location_name = 'SELECT locationName FROM location WHERE idL = :location_id';
                    $stmt_location_name = $conn->prepare($sql_location_name);
                    $stmt_location_name->bindParam(':location_id', $location, PDO::PARAM_INT);
                    $stmt_location_name->execute();

                    if ($stmt_location_name->rowCount() > 0) {
                        $location_name = $stmt_location_name->fetchColumn();
                        
                        if ($location_name === 'Région' || $location_name === 'ME') {
                            $columnToChercher = $location_name === 'Région' ? 'QteRegion' : 'QteME';
                
                            $sql_available_quantity = "SELECT $columnToChercher AS available_quantity
                                                       FROM qte_stock
                                                      WHERE article_id = :article_id";
                            $stmt_available_quantity = $conn->prepare($sql_available_quantity);
                            $stmt_available_quantity->bindParam(':article_id', $articleId, PDO::PARAM_INT);
                            $stmt_available_quantity->execute();
                            $available_quantity = $stmt_available_quantity->fetchColumn();

                            if ($available_quantity < $qte_s) {
                                $errorMessage = "Error: La quantité demandée n'est pas disponible en stock pour $location_name.";
                            } else {
                                try {
                                    $conn->beginTransaction();

                                    $sql_article_data = "SELECT QteRegion, QteME, total_quantity FROM qte_stock WHERE article_id = :article_id";
                                    $stmt_article_data = $conn->prepare($sql_article_data);
                                    $stmt_article_data->bindParam(':article_id', $articleId, PDO::PARAM_INT);
                                    $stmt_article_data->execute();
                                    $article_data = $stmt_article_data->fetch(PDO::FETCH_ASSOC);

                                    $QteRegion = $article_data['QteRegion'];
                                    $QteME = $article_data['QteME'];
                                    $Qte_Total = $article_data['total_quantity'];
                                    $QteReste = $Qte_Total - $qte_s;

                                    // Get the TypeArt_ID from the 'articles' table
                                    $sql_get_typeArt = "SELECT TypeArt_ID FROM articles WHERE id = :article_id";
                                    $stmt_get_typeArt = $conn->prepare($sql_get_typeArt);
                                    $stmt_get_typeArt->bindParam(':article_id', $articleId, PDO::PARAM_INT);
                                    $stmt_get_typeArt->execute();
                                    $typeArt_id = $stmt_get_typeArt->fetchColumn();

                                    if ($QteRegion !== null && $QteME !== null && $Qte_Total !== null && $qte_s !== null && $QteReste !== null && $typeArt_id !== null) {
                                        $insert_query = "INSERT INTO amortissement (id_Article, TypeArt_ID, QteRegion, QteME, Total, QteSortie, QteReste, bc_marche_id, beneficiaire_matricule, DateAmor, Location_Name)
                                             VALUES (:article_id, :id_typeArt, :QteRegion, :QteME, :Qte_Total, :qte_s, :QteReste, :bc_marche_id, :beneficiaire_matricule, CURRENT_TIMESTAMP, :location_name)";
                                        $stmt_insert = $conn->prepare($insert_query);
                                        $stmt_insert->bindParam(':article_id', $articleId, PDO::PARAM_INT);
                                        $stmt_insert->bindParam(':id_typeArt', $typeArt_id, PDO::PARAM_INT);
                                        $stmt_insert->bindParam(':QteRegion', $QteRegion, PDO::PARAM_INT);
                                        $stmt_insert->bindParam(':QteME', $QteME, PDO::PARAM_INT);
                                        $stmt_insert->bindParam(':Qte_Total', $Qte_Total, PDO::PARAM_INT);
                                        $stmt_insert->bindParam(':qte_s', $qte_s, PDO::PARAM_INT);
                                        $stmt_insert->bindParam(':QteReste', $QteReste, PDO::PARAM_INT);
                                        $stmt_insert->bindParam(':bc_marche_id', $bc_marche_id, PDO::PARAM_INT);
                                        $stmt_insert->bindParam(':beneficiaire_matricule', $beneficiaire_matricule, PDO::PARAM_STR);
                                        $stmt_insert->bindParam(':location_name', $location_name, PDO::PARAM_STR);
                                        $stmt_insert->execute();
                                        $conn->commit();
                                        $successMessage = "Amortization added successfully!";
                                        header("location:amortissement.php");
                                    } else {
                                        $errorMessage = "Erreur: Certains champs obligatoires sont manquants ou nuls.";
                                    }
                                } catch (PDOException $e) {
                                    $conn->rollBack();
                                    $errorMessage = "Error: " . $e->getMessage();
                                }
                            }
                        }
                    } else {
                        $errorMessage = "Error: Failed to retrieve location name.";
                    }
                } else {
                    // Beneficiary does not exist, insert into 'beneficiaire' table first
                    $errorMessage = "Error: Beneficiary does not exist. Please add the beneficiary first.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Marche</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Select Marche</h2>
        <?php if (!empty($errorMessage)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($successMessage)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="bc_marche_id">Choose Marche:</label>
            <select name="bc_marche_id" id="bc_marche_id" class="form-select">
                <option value="" disabled selected>Select Marche</option>
                <?php foreach ($marcheList as $marche) : ?>
                    <option value="<?php echo $marche['idMarche']; ?>"><?php echo $marche['NomMarche']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Submit" class="btn btn-primary">
        </form>

        <!-- Display selected marche data -->
        <!-- <?php if (!empty($articleId)) : ?>
            <div class="alert alert-info" role="alert">
                <strong>Selected Marche Data:</strong><br>
                Article ID: <?php echo $articleId; ?><br>
                Quantity: <?php echo $qte_s; ?><br>
                Marche: <?php echo $bc_marche_id; ?><br>
                Beneficiary Matricule: <?php echo $beneficiaire_matricule; ?><br>
                Location: <?php echo $location; ?><br>
            </div>
        <?php endif; ?> -->
    </div>
</body>
</html>
