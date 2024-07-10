<?php

include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

$errorMessage = '';
$successMessage = '';

$sql_articles = 'SELECT id, designation, Qte_Total FROM articles ORDER BY designation ASC';
$stmt_articles = $conn->prepare($sql_articles);
$stmt_articles->execute();
$articles = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);

$sql_TypeArt = 'SELECT idT, type FROM typeart';
$stmt_TypeArt = $conn->prepare($sql_TypeArt);
$stmt_TypeArt->execute();
$TypeArt = $stmt_TypeArt->fetchAll(PDO::FETCH_ASSOC);

$sql_Location = 'SELECT idL, locationName FROM location';
$stmt_Location = $conn->prepare($sql_Location);
$stmt_Location->execute();
$Location = $stmt_Location->fetchAll(PDO::FETCH_ASSOC);

$sql_beneficiaire = 'SELECT matricule, nom , prenom FROM beneficiaire';
$stmt_beneficiaire = $conn->prepare($sql_beneficiaire);
$stmt_beneficiaire->execute();
$beneficiaire = $stmt_beneficiaire->fetchAll(PDO::FETCH_ASSOC);

$sql_bc_marche = 'SELECT idMarche, NomMarche FROM bc_marche';
$stmt_bc_marche = $conn->prepare($sql_bc_marche);
$stmt_bc_marche->execute();
$bc_marche = $stmt_bc_marche->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $article_id = $_POST['article_id'] ?? '';
    $id_typeArt = $_POST['id_typeArt'] ?? '';
    $qte_s = $_POST['qte_s'] ?? '';
    $Depot_id = $_POST['Depot_id'] ?? '';
    $bc_marche_id = $_POST['bc_marche_id'] ?? '';
    $beneficiaire_matricule = $_POST['beneficiaire_matricule'] ?? '';

    $sql_location_name = 'SELECT locationName FROM location WHERE idL = :location_id';
    $stmt_location_name = $conn->prepare($sql_location_name);
    $stmt_location_name->bindParam(':location_id', $Depot_id, PDO::PARAM_INT);
    $stmt_location_name->execute();

    if ($stmt_location_name->execute() && $stmt_location_name->rowCount() > 0) {
        $location = $stmt_location_name->fetch(PDO::FETCH_ASSOC);
        if ($location) {
            $location_name = $location['locationName'];

            if ($location_name === 'Région' || $location_name === 'ME') {
                $columnToChercher = $location_name === 'Région' ? 'QteRegion' : 'QteME';

                $sql_available_quantity = "SELECT $columnToChercher AS available_quantity
                                       FROM qte_stock
                                      WHERE article_id = :article_id";
                $stmt_available_quantity = $conn->prepare($sql_available_quantity);
                $stmt_available_quantity->bindParam(':article_id', $article_id, PDO::PARAM_INT);
                $stmt_available_quantity->execute();
                $available_quantity = $stmt_available_quantity->fetchColumn();

                if ($available_quantity < $qte_s) {
                    $errorMessage = "Error: La quantité demandée n'est pas disponible en stock pour " . $location_name . ".";
                } else {
                    try {
                        $conn->beginTransaction();

                        $sql_article_data = "SELECT QteRegion, QteME, total_quantity FROM qte_stock WHERE article_id = :article_id";
                        $stmt_article_data = $conn->prepare($sql_article_data);
                        $stmt_article_data->bindParam(':article_id', $article_id, PDO::PARAM_INT);
                        $stmt_article_data->execute();
                        $article_data = $stmt_article_data->fetch(PDO::FETCH_ASSOC);

                        $QteRegion = $article_data['QteRegion'];
                        $QteME = $article_data['QteME'];
                        $Qte_Total = $article_data['total_quantity'];

                        $QteReste = $Qte_Total - $qte_s;

                        if ($QteRegion !== null && $QteME !== null && $Qte_Total !== null && $qte_s !== null && $QteReste !== null) {
                            $insert_query = "INSERT INTO amortissement (id_Article, TypeArt_ID, QteRegion, QteME, Total, QteSortie, QteReste, bc_marche_id, beneficiaire_matricule, DateAmor, Location_Name)
                                 VALUES (:article_id, :id_typeArt, :QteRegion, :QteME, :Qte_Total, :qte_s, :QteReste, :bc_marche_id, :beneficiaire_matricule, CURRENT_TIMESTAMP, :location_name)";
                            $stmt_insert = $conn->prepare($insert_query);
                            $stmt_insert->bindParam(':article_id', $article_id, PDO::PARAM_INT);
                            $stmt_insert->bindParam(':id_typeArt', $id_typeArt, PDO::PARAM_INT);
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
            } else {
                $errorMessage = "Error : Invalid depot name. Veuillez sélectionner 'Région' ou 'EM'.";
            }
        } else {
            $errorMessage = "Error: Location not found.";
        }
    } else {
        $errorMessage = "Error: saisir location ";
    }
    if (isset($errorMessage)) {
        echo '<div class="alert-container"><div class="alert alert-danger py-3 mx-4" role="alert">' . $errorMessage . '</div></div>';
    } elseif (isset($successMessage)) {
        echo '<div class="alert-container"><div class="alert alert-success py-3 mx-4" role="alert">' . $successMessage . '</div></div>';
    }else{
        echo '';
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Add Article</title>
    <style>
  .alert-container {
    position: fixed;
    top: 5%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
  }
</style>
</head>

<body>
    
    <div class="container  py-3 ">
    <?php
    include "nav.php";
    ?>
        <form method="post" class="mx-3 mt-5">
            <div id="error-message" class="alert alert-danger alert-dismissible fade show" style="display: none;" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <label for="article_id">Designation:</label>
            <select name="article_id" id="article_id" class="form-select" >
                <option value="" disabled selected>Select Article</option>
                <?php foreach ($articles as $article) : ?>
                    <option value="<?php echo $article['id']; ?>"><?php echo $article['designation']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="id_typeArt">Type d'Article:</label>
            <select name="id_typeArt" id="id_typeArt" class="form-select" >
                <option value="" disabled selected>Select Type</option>
                <?php foreach ($TypeArt as $typeart) : ?>
                    <option value="<?php echo $typeart['idT']; ?>"><?php echo $typeart['type']; ?></option>
                <?php endforeach; ?>
            </select>

            <br>
            <label for="qte_s">Qte S:</label>
            <input type="number" name="qte_s" id="qte_s" class="form-control"  >
            <br>
            <label for="Depot_id">Depot:</label>
            <select name="Depot_id" id="Depot_id" class="form-select" >
                <option value="" disabled selected>Select Depot</option>
                <?php foreach ($Location as $depot) : ?>
                    <option value="<?php echo $depot['idL']; ?>"><?php echo $depot['locationName']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="bc_marche_id">BC Marche:</label>
            <select name="bc_marche_id" id="bc_marche_id" class="form-select" >
                <option value="" disabled selected>Select BC Marche</option>
                <?php foreach ($bc_marche as $bc) : ?>
                    <option value="<?php echo $bc['idMarche']; ?>"><?php echo $bc['NomMarche']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="beneficiaire_matricule">Beneficiaire:</label>
            <select name="beneficiaire_matricule" id="beneficiaire_matricule" class="form-select" >
                <option value="" disabled selected>Select Beneficiaire</option>
                <?php foreach ($beneficiaire as $benef) : ?>
                    <option value="<?php echo $benef['matricule']; ?>"><?php echo $benef['nom'] . ' ' . $benef['prenom']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <input type="submit" value="Ajouter Amortissement" class="btn btn-primary">
        </form>
    </div>

    <script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            event.preventDefault(); 
            var article_id = $('#article_id').val();
            var id_typeArt = $('#id_typeArt').val();
            var qte_s = $('#qte_s').val();
            var Depot_id = $('#Depot_id').val();
            var bc_marche_id = $('#bc_marche_id').val();
            var beneficiaire_matricule = $('#beneficiaire_matricule').val();
            if (article_id === '' || id_typeArt === '' || qte_s === '' || Depot_id === '' || bc_marche_id === '' || beneficiaire_matricule === '') {
                showError('Veuillez remplir tous les champs.');
                return;
            }
            if (qte_s <= 0) {
                showError('La quantité saisie doit être supérieure à zéro.');
                return;
            }
            this.submit();
        });
        function showError(message) {
            $('#error-message').html(message).show();
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
  setTimeout(function() {
    document.querySelector('.alert-container')?.remove();
  }, 5000);
</script>
</body>
</html>