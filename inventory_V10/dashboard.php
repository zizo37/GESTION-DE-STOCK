<?php
include "connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

$sql_consumption = "SELECT b.nom AS benef_nom, b.prenom AS benef_prenom, SUM(a.QteSortie) AS total_consumption
                    FROM beneficiaire b
                    INNER JOIN amortissement a ON b.matricule = a.beneficiaire_matricule
                    GROUP BY b.nom, b.prenom";
$stmt_consumption = $conn->prepare($sql_consumption);
$stmt_consumption->execute();
$consumption_data = $stmt_consumption->fetchAll(PDO::FETCH_ASSOC);


$sql_category = "SELECT t.type, COUNT(*) AS total_articles
                 FROM typeart t
                 INNER JOIN articles a ON t.idT = a.type_id
                 GROUP BY t.type";
$stmt_category = $conn->prepare($sql_category);
$stmt_category->execute();
$category_data = $stmt_category->fetchAll(PDO::FETCH_ASSOC);


$sql_top_articles = "SELECT a.designation, SUM(am.QteSortie) AS total_consumption
                     FROM articles a
                     INNER JOIN amortissement am ON a.id = am.id_Article
                     GROUP BY a.designation
                     ORDER BY total_consumption DESC
                     LIMIT 10";
$stmt_top_articles = $conn->prepare($sql_top_articles);
$stmt_top_articles->execute();
$top_articles_data = $stmt_top_articles->fetchAll(PDO::FETCH_ASSOC);

$sql_amortissement = 'SELECT COUNT(*) AS total_amortissement FROM amortissement';
$stmt_amortissement = $conn->prepare($sql_amortissement);
$stmt_amortissement->execute();
$amortissement = $stmt_amortissement->fetch(PDO::FETCH_ASSOC)['total_amortissement'];

$sql_articles = 'SELECT COUNT(*) AS total_articles FROM articles';
$stmt_articles = $conn->prepare($sql_articles);
$stmt_articles->execute();
$articles = $stmt_articles->fetch(PDO::FETCH_ASSOC)['total_articles'];

$sql_TypeArt = 'SELECT COUNT(*) AS total_typeArt FROM typeart';
$stmt_TypeArt = $conn->prepare($sql_TypeArt);
$stmt_TypeArt->execute();
$TypeArt = $stmt_TypeArt->fetch(PDO::FETCH_ASSOC)['total_typeArt'];

$sql_beneficiaire = 'SELECT COUNT(*) AS total_beneficiaire FROM beneficiaire';
$stmt_beneficiaire = $conn->prepare($sql_beneficiaire);
$stmt_beneficiaire->execute();
$beneficiaire = $stmt_beneficiaire->fetch(PDO::FETCH_ASSOC)['total_beneficiaire'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Roboto', sans-serif;
        }

        .navbar {
            background-color: #6366f1;
            color: #fff;
        }

        .content {
            margin-top: 56px;
            padding-top: 20px;
        }

        .card {
            background-color: #fff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #6366f1;
            color: #fff;
            border-radius: 10px 10px 0 0;
            padding: 15px;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .indicator i {
            font-size: 1.2rem;
            margin-right: 5px;
        }

        .number {
            font-size: 2rem;
            font-weight: bold;
            color: #6366f1;
        }

        .table th, .table td {
            border-top: none;
        }

        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container content">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Amortissement</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Amortissement</h5>
                        <div class="number"><?php echo $amortissement; ?></div>
                        <div class="indicator">
                            <i class="bx bx-up-arrow-alt"></i>
                            <span class="text">Total</span>
                        </div>
                    </div>
                    <i class='bx bxs-archive-out' style='font-size: 35px; color: #6366f1;'></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Articles</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Articles</h5>
                        <div class="number"><?php echo $articles; ?></div>
                        <div class="indicator">
                            <i class="bx bx-up-arrow-alt"></i>
                            <span class="text">Total</span>
                        </div>
                    </div>
                    <i class="bx bx-cart cart" style="font-size: 35px; color: #6366f1;"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Type Article</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Type Article</h5>
                        <div class="number"><?php echo $TypeArt; ?></div>
                        <div class="indicator">
                            <i class="bx bx-up-arrow-alt"></i>
                            <span class="text">Total</span>
                        </div>
                    </div>
                    <i class="bx bxs-cart-add cart" style="font-size: 35px; color: #6366f1;"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Beneficiaire</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Beneficiaire</h5>
                        <div class="number"><?php echo $beneficiaire; ?></div>
                        <div class="indicator">
                            <i class="bx bx-down-arrow-alt"></i>
                            <span class="text">Total</span>
                        </div>
                    </div>
                    <i class='bx bxs-user' style='font-size: 35px; color: #6366f1;'></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Top Consumed Articles</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Article</th>
                                    <th>Total Consumption</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($top_articles_data as $data): ?>
                                    <tr>
                                        <td><?php echo $data['designation']; ?></td>
                                        <td><?php echo $data['total_consumption']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Article Category Distribution</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Total Articles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($category_data as $data): ?>
                                    <tr>
                                        <td><?php echo $data['type']; ?></td>
                                        <td><?php echo $data['total_articles']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Beneficiary Consumption Details</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Beneficiary Name</th>
                                    <th>Total Consumption</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($consumption_data as $data): ?>
                                    <tr>
                                        <td><?php echo $data['benef_nom'] . ' ' . $data['benef_prenom']; ?></td>
                                        <td><?php echo $data['total_consumption']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>