<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

$sql = "SELECT * ,(Old_Qte_Region + Old_Qte_ME) AS Old_Qte FROM qte_stock";
$stmt = $conn->prepare($sql);
$stmt->execute();
$qte_stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qte Stock Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .table-hover tbody tr:hover td, 
    .table-hover tbody tr:hover th {
        background-color: #e9ecef; 
    }
</style>
</head>
<body>

<div class="container py-5 px-4">
    <?php
    include "nav.php";
    ?>
    
    <h1 class="text-center">Quantités en stock</h1>
    <div class="px-md-5">
        <table class="table table-bordered table-hover mt-3">
            <thead>
                <tr>
                    <th>Designation</th>
                    <th>marche </th>
                    <th>Total Quantity</th>
                    <th>Qte Region</th>
                    <th>Qte ME</th>
                    <th>Old Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($qte_stocks as $qte_stock): ?>
                    <tr>
                        <td><?php echo $qte_stock['designation']; ?></td>
                        <td><?php echo $qte_stock['marche']; ?></td>
                        <td><?php echo $qte_stock['total_quantity']; ?></td>
                        <td><?php echo $qte_stock['QteRegion']; ?></td>
                        <td><?php echo $qte_stock['QteME']; ?></td>
                        <td><?php echo $qte_stock['Old_Qte']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
