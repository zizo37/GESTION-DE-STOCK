<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            margin-top: 50px;
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .table thead th {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            text-align: center;
            border: none;
        }

        .table tbody tr:hover td {
            color: #333;
            font-weight: bold;
        }

        .alert {
            border-radius: 8px;
        }

        .btn-home {
            margin-bottom: 10px;
            border-radius: 15px;
            font-size: 16px;
            padding: 10px 20px;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-home:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
    </style>
</head>

<body>

    <div class="container py-4">
        <?php include "nav.php"; ?>
        <a href="article.php" class="btn btn-danger btn-home">Home</a>
        <?php
        $sql = 'SELECT * FROM `stock` ORDER BY id DESC'; // Fixed the typo here (missing space after FROM)
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
        ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Article</th>
                        <th>Dépôt</th>
                        <th>Quantité</th>
                        <th>Marché</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($resultat as $row) {
                        // Retrieve article designation
                        $sql_article = 'SELECT designation FROM `articles` WHERE id = :article_id';
                        $stmt_article = $conn->prepare($sql_article);
                        $stmt_article->bindParam(':article_id', $row['article_id']);
                        $stmt_article->execute();
                        $result_article = $stmt_article->fetch(PDO::FETCH_ASSOC);

                        // Retrieve location name
                        $sql_location = 'SELECT locationName FROM `location` WHERE idL = :location_id';
                        $stmt_location = $conn->prepare($sql_location);
                        $stmt_location->bindParam(':location_id', $row['location']);
                        $stmt_location->execute();
                        $result_location = $stmt_location->fetch(PDO::FETCH_ASSOC);

                        // Retrieve BC Marché name
                        $sql_bc_marche = 'SELECT NomMarche FROM `bc_marche` WHERE idMarche = :bc_marche_id';
                        $stmt_bc_marche = $conn->prepare($sql_bc_marche);
                        $stmt_bc_marche->bindParam(':bc_marche_id', $row['bc_marche_id']);
                        $stmt_bc_marche->execute();
                        $result_bc_marche = $stmt_bc_marche->fetch(PDO::FETCH_ASSOC);

                        // Check if fetch operations were successful and data is available
                        if ($result_article && $result_location && $result_bc_marche) {
                    ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $result_article['designation']; ?></td>
                                <td><?php echo $result_location['locationName']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo $result_bc_marche['NomMarche']; ?></td>
                            </tr>
                    <?php
                        } else {
                            // Debugging: Output the SQL queries and results for inspection
                            echo "Error fetching data for row with ID: " . $row['id'] . "<br>";
                            echo "SQL Article: " . $sql_article . "<br>";
                            echo "Result Article: ";
                            var_dump($result_article);
                            echo "<br>";
                            echo "SQL Location: " . $sql_location . "<br>";
                            echo "Result Location: ";
                            var_dump($result_location);
                            echo "<br>";
                            echo "SQL BC Marché: " . $sql_bc_marche . "<br>";
                            echo "Result BC Marché: ";
                            var_dump($result_bc_marche);
                            echo "<br>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        <?php
        } else {
            echo '<div class="alert alert-danger" role="alert">
                    Aucun article trouvé dans la base de données
                </div>';
        }
        ?>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
