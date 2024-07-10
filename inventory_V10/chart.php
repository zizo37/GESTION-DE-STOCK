<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

$beneficiaire_matricule = isset($_GET['beneficiaire_matricule']) ? $_GET['beneficiaire_matricule'] : null;

if ($beneficiaire_matricule) {
    $sql_beneficiaire = "SELECT nom, prenom FROM beneficiaire WHERE matricule = :beneficiaire_matricule";
    $stmt_beneficiaire = $conn->prepare($sql_beneficiaire);
    $stmt_beneficiaire->bindParam(':beneficiaire_matricule', $beneficiaire_matricule, PDO::PARAM_INT);
    $stmt_beneficiaire->execute();
    $beneficiaire = $stmt_beneficiaire->fetch(PDO::FETCH_ASSOC);

    $sql_amortissements = "SELECT ar.designation, SUM(am.QteSortie) AS total_consumption
                           FROM amortissement am
                           JOIN articles ar ON am.id_Article = ar.id
                           WHERE am.beneficiaire_matricule = :beneficiaire_matricule
                           GROUP BY ar.designation";

    $stmt_amortissements = $conn->prepare($sql_amortissements);
    $stmt_amortissements->bindParam(':beneficiaire_matricule', $beneficiaire_matricule, PDO::PARAM_INT);
    $stmt_amortissements->execute();
    $amortissements = $stmt_amortissements->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for the chart
    $dataPoints = [];
    foreach ($amortissements as $amortissement) {
        $dataPoints[] = array(
            "label" => $amortissement['designation'],
            "y" => $amortissement['total_consumption']
        );
    }
} else {
    header("Location: Art_benif.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Center the chart container */
        #chartContainer {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
    <script>
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "Total consomation by Article for <?php echo $beneficiaire['nom'] . ' ' . $beneficiaire['prenom']; ?>"
                },
                axisY: {
                    title: "Total consomation"
                },
                data: [{
                    type: "column",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();
        }
    </script>
</head>
<body>
    <div class="d-flex justify-content-center my-3">
        <a href="Art_Benif.php" class="btn btn-primary">Back to Beneficiary Consumption</a>
    </div>
    <div id="chartContainer" style="height: 370px; width: 50%;"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>