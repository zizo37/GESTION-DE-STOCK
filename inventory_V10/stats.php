<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>chart Article </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        #div1 {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
    </style>

</head>

<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $article_id = $_GET['id'];

    // SQL query to sum the QteSortie by each beneficiary
    $query = "
        SELECT b.nom AS benef_nom, b.prenom AS benef_prenom, SUM(a.QteSortie) AS total_consumption
        FROM amortissement a
        INNER JOIN beneficiaire b ON a.beneficiaire_matricule = b.Matricule
        WHERE a.id_Article = :article_id
        GROUP BY b.nom, b.prenom
        ORDER BY total_consumption DESC
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt->execute();
    $consumptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for Chart.js
    $beneficiaries = [];
    $totalConsumptions = [];
    foreach ($consumptions as $consumption) {
        $beneficiaries[] = $consumption['benef_nom'] . ' ' . $consumption['benef_prenom'];
        $totalConsumptions[] = $consumption['total_consumption'];
    }
} else {
    header("Location: article.php");
    exit();
}


?>


<body>
    <a href="article.php" class="btn btn-primary mx-5 mt-5">Article</a>
    <div id="div1">
        <div style="width: 1200px; display: flex; justify-content: center;">
            <canvas id="myChart"></canvas>
        </div>


        <script>
            const labels = <?php echo json_encode($beneficiaries) ?>;
            const data = {
                labels: labels,
                datasets: [{
                    label: 'My First Dataset',
                    data: <?php echo json_encode($totalConsumptions) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(255, 205, 86, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(201, 203, 207, 0.7)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(54, 162, 235)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                },
            };

            // === include 'setup' then 'config' above ===

            var myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        </script>


    </div>



</body>

</html>