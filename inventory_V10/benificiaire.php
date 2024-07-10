<?php
include('connection.php');
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
    <title>Bénéficiaires</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa; /* Light gray background */
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
            background-color: #007bff; /* Blue */
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
            text-align: left;
        }

        .table tbody tr:hover {
            background-color: #f3f4f6; /* Light gray */
            transition: background-color 0.3s ease;
        }

        .btn {
            font-weight: bold;
            font-size: 0.9rem;
        }

        .btn-danger,
        .btn-primary {
            transition: all 0.3s ease;
            border-radius: 25px;
            padding: 12px 24px;
            font-size: 1.1rem;
            margin-right: 10px; /* Added margin between buttons */
        }

        .btn-danger:hover,
        .btn-primary:hover {
            filter: brightness(90%); /* Dim the button on hover */
            transform: translateY(-2px);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5 py-3">
        <?php
        include "nav.php";
        ?>
        <?php
        $sql = 'SELECT * FROM `beneficiaire`';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
        ?>
        <div class="px-md-5">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th colspan="2">Gérer les bénéficiaires</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($resultat as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['matricule']; ?></td>
                            <td><?php echo $row['nom']; ?></td>
                            <td><?php echo $row['prenom']; ?></td>
                            <td>
                                <a href="supprimer_benificiaire.php?id=<?php echo $row['matricule'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                <a href="modifier_benificiaire.php?id=<?php echo $row['matricule'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        } else {
            echo "Aucun bénéficiaire trouvé dans la base de données.";
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
