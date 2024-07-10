<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $article_id = $_GET['id'];

    $sql_article = "SELECT designation FROM articles WHERE id = :article_id";
    $stmt_article = $conn->prepare($sql_article);
    $stmt_article->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt_article->execute();
    $article = $stmt_article->fetch(PDO::FETCH_ASSOC);
    

    $sql_consumption = "SELECT b.nom AS benef_nom, b.prenom AS benef_prenom, a.QteSortie, a.DateAmor
                    FROM amortissement a
                    INNER JOIN beneficiaire b ON a.beneficiaire_matricule = b.Matricule
                    WHERE a.id_Article = :article_id
                    ORDER BY a.DateAmor ASC"; 

    $stmt_consumption = $conn->prepare($sql_consumption);
    $stmt_consumption->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt_consumption->execute();
    $consumptions = $stmt_consumption->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: amortissement.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Beneficiaries</title>
    <!-- Joyful CSS/Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.3.0/journal/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f6f6f6;
            color: #333;
        }
        .container {
            margin-top: 50px;
        }
        .btn-primary {
            background-color: #ff5722;
            border-color: #ff5722;
        }
        .btn-primary:hover {
            background-color: #ff5722;
            border-color: #ff5722;
        }
        .table {
            background-color: #fff;
            border-radius: 10px;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">Article: <?php echo $article['designation']; ?></h1>
    <div class="table-responsive">
    <a href="stats.php?id=<?php echo $article_id; ?>" class="btn btn-primary">Stats</a>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Beneficiary</th>
                <th>Quantity</th>
                <th>Total Quantity</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $totalQuantity = 0;
            foreach ($consumptions as $consumption): 
                $totalQuantity += $consumption['QteSortie']; 
            ?>
                <tr>
                    <td><?php echo $consumption['benef_nom'] . ' ' . $consumption['benef_prenom']; ?></td>
                    <td><?php echo $consumption['QteSortie']; ?></td>
                    <td><?php echo $totalQuantity; ?></td>
                    <td><?php echo $consumption['DateAmor']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="text-center">
        <a href="article.php" class="btn btn-primary">Back to Article</a>
    </div>
</div>
</body>
</html>
