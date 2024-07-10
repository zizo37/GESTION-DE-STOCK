<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

$bc_marche_id = $_GET['bc_marche_id'] ?? null;

if ($bc_marche_id) {
    
    $sql_articles = 'SELECT id, designation FROM articles ORDER BY designation';
    $stmt_articles = $conn->prepare($sql_articles);
    $stmt_articles->execute();
    $articles = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);


    $sql_locations = 'SELECT idL, locationName FROM location';
    $stmt_locations = $conn->prepare($sql_locations);
    $stmt_locations->execute();
    $locations = $stmt_locations->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $quantities = $_POST['quantities'] ?? [];
        $error_message = '';

        foreach ($quantities as $article_id => $quantity_data) {
            foreach ($quantity_data as $location_id => $quantity) {
                if ($quantity < 0) {
                    $error_message = 'La quantité ne peut pas être inférieure à 0.';
                }
                if ($quantity > 0) {
                    $sql_insert = "INSERT INTO stock (article_id, location, quantity, bc_marche_id, marche)
                                VALUES (:article_id, :location_id, :quantity, :bc_marche_id, :marche)";
                    $stmt_insert = $conn->prepare($sql_insert);
                    $stmt_insert->bindParam(':article_id', $article_id, PDO::PARAM_INT);
                    $stmt_insert->bindParam(':location_id', $location_id, PDO::PARAM_INT);
                    $stmt_insert->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                    $stmt_insert->bindParam(':bc_marche_id', $bc_marche_id, PDO::PARAM_INT);
                    $stmt_insert->bindParam(':marche', $bc_marche_id, PDO::PARAM_INT);

                    if (!$stmt_insert->execute()) {
                        echo "Error inserting stock information: " . $stmt_insert->errorInfo()[2];
                    }
                }
            }
        }

        
        if ($error_message) {
            echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
        } else {
            
            header("Location: stockage.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter du stock pour Marche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
            width: 100%;
            background-color: #fff;
        }

        .table thead th {
            background-color: #007bff; 
            color: #fff;
            font-weight: bold;
            vertical-align: middle;
            border: none;
            text-align: center;
        }

        .table td,
        .table th {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
            text-align: center;
        }

        .table tbody tr:hover {
            background-color: #f3f4f6; 
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            font-weight: bold;
            font-size: 1rem;
            border-radius: 25px;
            padding: 12px 24px;
            transition: all 0.3s ease;
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3; 
        }

        input[type="number"] {
            width: 70px; 
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease;
        }

        input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Ajouter du stock pour Marche</h1>
        <form method="post">
            <?php if(isset($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Designation</th>
                            <?php foreach ($locations as $location) : ?>
                                <th><?php echo $location['locationName']; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article) : ?>
                            <tr>
                                <td><?php echo $article['designation']; ?></td>
                                <?php foreach ($locations as $location) : ?>
                                    <td>
                                        <input type="number" name="quantities[<?php echo $article['id']; ?>][<?php echo $location['idL']; ?>]" value="0" min="0" class="form-control">
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter du stock</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
