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
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        tbody tr:hover {
        background-color: cyan ;
        cursor: pointer;
    }
    </style>
</head>
<body>
    <div class="container">
        <?php
        $sql = 'SELECT * FROM `stock`';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
        ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Article</th>
                        <th>Location</th>
                        <th>Quantity</th>
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
            echo "No articles found in the database.";
        }
        ?>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
