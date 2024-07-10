<?php
// include "connection.php"; // Assuming this file contains your database connection
// session_start();

// // Check if the user is logged in as admin
// if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
//   header("Location: login.php?erreur=1");
//   exit();
// }

// // Query to fetch articles for the select dropdown
// $sql_articles = 'SELECT id, designation FROM articles';
// $stmt_articles = $conn->prepare($sql_articles);
// $stmt_articles->execute();
// $articles = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);

// // Query to fetch locations for the select dropdown
// $sql_locations = 'SELECT idL, locationName FROM location';
// $stmt_locations = $conn->prepare($sql_locations);
// $stmt_locations->execute();
// $locations = $stmt_locations->fetchAll(PDO::FETCH_ASSOC);

// // Query to fetch BC Marche IDs for the select dropdown
// $sql_bc_marche = 'SELECT idMarche, NomMarche FROM bc_marche';
// $stmt_bc_marche = $conn->prepare($sql_bc_marche);
// $stmt_bc_marche->execute();
// $bc_marche = $stmt_bc_marche->fetchAll(PDO::FETCH_ASSOC);

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $article_id = $_POST["article_id"];
//     $location_id = $_POST["location_id"];
//     $quantity = $_POST["quantity"];
//     $bc_marche_id = $_POST["bc_marche_id"];



//     $sql_insert = "INSERT INTO stock (article_id, location, quantity, bc_marche_id) VALUES (:article_id, :location_id, :quantity, :bc_marche_id)";
//     $stmt_insert = $conn->prepare($sql_insert);
//     $stmt_insert->bindParam(':article_id', $article_id);
//     $stmt_insert->bindParam(':location_id', $location_id);
//     $stmt_insert->bindParam(':quantity', $quantity);
//     $stmt_insert->bindParam(':bc_marche_id', $bc_marche_id);


//     if ($stmt_insert->execute()) {

//         $sql_update = "UPDATE Articles SET Qte_Total = Qte_Total + :quantity WHERE id = :article_id";
//         $stmt_update = $conn->prepare($sql_update);
//         $stmt_update->bindParam(':quantity', $quantity);
//         $stmt_update->bindParam(':article_id', $article_id);

//         if ($stmt_update->execute()) {
//             echo "Stock information inserted successfully.";
            
//             header("Location: article.php");
//             exit(); 
//         } else {
//             echo "Error updating total quantity in Articles table: " . $stmt_update->errorInfo()[2];
//         }
//     } else {
//         echo "Error inserting stock information: " . $stmt_insert->errorInfo()[2];
//     }
// }







include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

$sql_articles = 'SELECT id, designation FROM articles';
$stmt_articles = $conn->prepare($sql_articles);
$stmt_articles->execute();
$articles = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);

$sql_locations = 'SELECT idL, locationName FROM location';
$stmt_locations = $conn->prepare($sql_locations);
$stmt_locations->execute();
$locations = $stmt_locations->fetchAll(PDO::FETCH_ASSOC);

$sql_bc_marche = 'SELECT idMarche, NomMarche FROM bc_marche';
$stmt_bc_marche = $conn->prepare($sql_bc_marche);
$stmt_bc_marche->execute();
$bc_marche = $stmt_bc_marche->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $article_id = $_POST["article_id"];
    $location_id = $_POST["location_id"];
    $quantity = $_POST["quantity"];
    $bc_marche_id = $_POST["bc_marche_id"];

    $sql_location_name = 'SELECT locationName FROM location WHERE idL = :location_id';
    $stmt_location_name = $conn->prepare($sql_location_name);
    $stmt_location_name->bindParam(':location_id', $location_id);
    $stmt_location_name->execute();
    $location = $stmt_location_name->fetch(PDO::FETCH_ASSOC);

    if ($location) {
        $location_name = $location['locationName'];

        $sql_insert = "INSERT INTO stock (article_id, location, quantity, bc_marche_id) VALUES (:article_id, :location_id, :quantity, :bc_marche_id)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':article_id', $article_id);
        $stmt_insert->bindParam(':location_id', $location_id);
        $stmt_insert->bindParam(':quantity', $quantity);
        $stmt_insert->bindParam(':bc_marche_id', $bc_marche_id);

        if ($stmt_insert->execute()) {
            $sql_update = "UPDATE Articles SET Qte_Total = Qte_Total + :quantity WHERE id = :article_id";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bindParam(':quantity', $quantity);
            $stmt_update->bindParam(':article_id', $article_id);
            $stmt_update->execute();

            if ($location_name === 'Région') {
                $columnToUpdate = 'QteRegion';
            } elseif ($location_name === 'EM') {
                $columnToUpdate = 'QteME';
            }

            if (isset($columnToUpdate)) {
                $sql_updateQte = "UPDATE Articles 
                                    SET $columnToUpdate = $columnToUpdate + :quantity 
                                    WHERE id = :article_id";
                $stmt_updateQte = $conn->prepare($sql_updateQte);
                $stmt_updateQte->bindParam(':quantity', $quantity);
                $stmt_updateQte->bindParam(':article_id', $article_id);
                $stmt_updateQte->execute();
            } else {
                echo "Error updating quantity in Articles table.";
            }

            echo "Stock information inserted successfully.";
            header("Location: article.php");
            exit();
        } else {
            echo "Error inserting stock information: " . $stmt_insert->errorInfo()[2];
        }
    } else {
        echo "Error retrieving location information.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Add Stock</h1>
        <form method="post">
            <div class="mb-3">
                <label for="article_id" class="form-label">Article:</label>
                <select name="article_id" id="article_id" class="form-select" required>
                    <option value="">Select Article</option>
                    <?php foreach ($articles as $article) : ?>
                        <option value="<?php echo $article['id']; ?>"><?php echo $article['designation']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="location_id" class="form-label">Location:</label>
                <select name="location_id" id="location_id" class="form-select" required>
                    <option value="">Select Location</option>
                    <?php foreach ($locations as $location) : ?>
                        <option value="<?php echo $location['idL']; ?>"><?php echo $location['locationName']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="bc_marche_id" class="form-label">BC Marche ID:</label>
                <select name="bc_marche_id" id="bc_marche_id" class="form-select" required>
                    <option value="">Select BC Marche</option>
                    <?php foreach ($bc_marche as $bc) : ?>
                        <option value="<?php echo $bc['idMarche']; ?>"><?php echo $bc['NomMarche']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Stock</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
