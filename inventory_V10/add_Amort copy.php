<?php

include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

$sql_articles = 'SELECT id, designation, Qte_Total FROM articles';
$stmt_articles = $conn->prepare($sql_articles);
$stmt_articles->execute();
$articles = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);

$sql_TypeArt = 'SELECT idT, type FROM typeart';
$stmt_TypeArt = $conn->prepare($sql_TypeArt);
$stmt_TypeArt->execute();
$TypeArt = $stmt_TypeArt->fetchAll(PDO::FETCH_ASSOC);

$sql_Location = 'SELECT * FROM Location';
$stmt_Location = $conn->prepare($sql_Location);
$stmt_Location->execute();
$Location = $stmt_Location->fetchAll(PDO::FETCH_ASSOC);

$sql_beneficiaire = 'SELECT * FROM beneficiaire';
$stmt_beneficiaire = $conn->prepare($sql_beneficiaire);
$stmt_beneficiaire->execute();
$beneficiaire = $stmt_beneficiaire->fetchAll(PDO::FETCH_ASSOC);

$sql_bc_marche = 'SELECT idMarche, NomMarche FROM bc_marche';
$stmt_bc_marche = $conn->prepare($sql_bc_marche);
$stmt_bc_marche->execute();
$bc_marche = $stmt_bc_marche->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $article_id = $_POST['article_id'];
    $id_typeArt = $_POST['id_typeArt'];
    $qte_s = $_POST['qte_s'];
    $Depot_id = $_POST['Depot_id'];
    $bc_marche_id = $_POST['bc_marche_id'];
    $beneficiaire_matricule = $_POST['beneficiaire_matricule'];

    // Get available stock quantity for the selected article
    $sql_available_quantity = "SELECT SUM(s.quantity) AS available_quantity
                                FROM stock s
                                INNER JOIN Location l ON s.location = l.idL
                                WHERE s.article_id = :article_id";
    $stmt_available_quantity = $conn->prepare($sql_available_quantity);
    $stmt_available_quantity->bindParam(':article_id', $article_id);
    $stmt_available_quantity->execute();
    $available_quantity = $stmt_available_quantity->fetchColumn();

    if ($available_quantity < $qte_s) {
        $errorMessage = "Error: Requested quantity is not available in stock.";
    } else {
        // Start transaction
        $conn->beginTransaction();

        try {

            $sql_article_data = "SELECT QteRegion, QteME, Qte_Total FROM articles WHERE id = :article_id";
            $stmt_article_data = $conn->prepare($sql_article_data);
            $stmt_article_data->bindParam(':article_id', $article_id);
            $stmt_article_data->execute();
            $article_data = $stmt_article_data->fetch(PDO::FETCH_ASSOC);
        
            $QteRegion = $article_data['QteRegion'];
            $QteME = $article_data['QteME'];
            $Qte_Total = $article_data['Qte_Total'];
        

            $QteReste = $Qte_Total - $qte_s;
        

            $insert_query = "INSERT INTO amortissement (id_Article, TypeArt_ID, QteRegion, QteME, Total, QteSortie, QteReste, bc_marche_id, beneficiaire_matricule, DateAmor)
                            VALUES (:article_id, :id_typeArt, :QteRegion, :QteME, :Qte_Total, :qte_s, :QteReste, :bc_marche_id, :beneficiaire_matricule, CURRENT_TIMESTAMP)";
            
            // Check quantity availability before insertion
            $sql_check_quantity = "SELECT QteRegion, QteME FROM articles WHERE id = :article_id";
            $stmt_check_quantity = $conn->prepare($sql_check_quantity);
            $stmt_check_quantity->bindParam(':article_id', $article_id);
            $stmt_check_quantity->execute();
            $article_quantity = $stmt_check_quantity->fetch(PDO::FETCH_ASSOC);
            
            $location_name = $Location[$Depot_id]['locationName'];
            if ($location_name === 'Région') {
                $available_quantity = $article_quantity['QteRegion'];
            } elseif ($location_name === 'EM') {
                $available_quantity = $article_quantity['QteME'];
            }

            if ($available_quantity >= $qte_s) {
                $stmt_insert = $conn->prepare($insert_query);
                $stmt_insert->bindParam(':article_id', $article_id);
                $stmt_insert->bindParam(':id_typeArt', $id_typeArt);
                $stmt_insert->bindParam(':QteRegion', $QteRegion);
                $stmt_insert->bindParam(':QteME', $QteME);
                $stmt_insert->bindParam(':Qte_Total', $Qte_Total);
                $stmt_insert->bindParam(':qte_s', $qte_s);
                $stmt_insert->bindParam(':QteReste', $QteReste);
                $stmt_insert->bindParam(':bc_marche_id', $bc_marche_id);
                $stmt_insert->bindParam(':beneficiaire_matricule', $beneficiaire_matricule);
                $stmt_insert->execute();
            } else {
                $errorMessage = "Error: Requested quantity is not available in the selected depot.";
                header("Location: article.php");
                exit();
            }

            $conn->commit();
            $successMessage = "Amortization added successfully!";
            header("Location: amortissement.php");
        } catch (PDOException $e) {
            $conn->rollBack();
            $errorMessage = "Error: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Add Article</title>
</head>
<body>
    <form method="post" class="mx-3 mt-3">
        <label for="article_id">Designation:</label>
        <select name="article_id" id="article_id" class="form-select" required>
            <option value="">Select Article</option>
            <?php foreach ($articles as $article) : ?>
                <option value="<?php echo $article['id']; ?>"><?php echo $article['designation']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="id_typeArt">Type d'Article:</label>
        <select name="id_typeArt" id="id_typeArt" class="form-select" required>
            <option value="">Select Type</option>
            <?php foreach ($TypeArt as $typeart) : ?>
                <option value="<?php echo $typeart['idT']; ?>"><?php echo $typeart['type']; ?></option>
            <?php endforeach; ?>
        </select>

        <br>
        <label for="qte_s">Qte S:</label>
        <input type="number" name="qte_s" id="qte_s" class="form-control" required>
        <br>
        <label for="Depot_id">Depot:</label>
        <select name="Depot_id" id="Depot_id" class="form-select" required>
            <option value="">Select Depot</option>
            <?php foreach ($Location as $depot) : ?>
                <option value="<?php echo $depot['idL']; ?>"><?php echo $depot['locationName']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="bc_marche_id">BC Marche:</label>
        <select name="bc_marche_id" id="bc_marche_id" class="form-select" required>
            <option value="">Select BC Marche</option>
            <?php foreach ($bc_marche as $bc) : ?>
                <option value="<?php echo $bc['idMarche']; ?>"><?php echo $bc['NomMarche']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="beneficiaire_matricule">Beneficiaire:</label>
        <select name="beneficiaire_matricule" id="beneficiaire_matricule" class="form-select" required>
            <option value="">Select Beneficiaire</option>
            <?php foreach ($beneficiaire as $benef) : ?>
                <option value="<?php echo $benef['matricule']; ?>"><?php echo $benef['nom']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" value="Add" class="btn btn-primary">
    </form>
</body>
</html>
