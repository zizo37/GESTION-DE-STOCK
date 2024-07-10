<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

$sql = "SELECT a.*, art.designation AS article_designation, ta.type AS type_name, a.QteRegion, a.QteME, a.Total, a.QteSortie, a.QteReste, bc.NomMarche AS marche_name, b.nom AS benef_nom, b.prenom AS benef_prenom
        FROM amortissement a
        INNER JOIN articles art ON a.id_Article = art.id
        INNER JOIN typeArt ta ON a.TypeArt_ID = ta.idT
        INNER JOIN bc_marche bc ON a.bc_marche_id = bc.idMarche
        INNER JOIN beneficiaire b ON a.beneficiaire_matricule = b.Matricule";

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $sql .= " WHERE DateAmor BETWEEN :start_date AND :end_date";
}

$sql .= " ORDER BY a.DateAmor DESC;";

$stmt = $conn->prepare($sql);

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $stmt->bindParam(':start_date', $_GET['start_date']);
    $stmt->bindParam(':end_date', $_GET['end_date']);
}

$stmt->execute();

$amortissements = $stmt->fetchAll(PDO::FETCH_ASSOC);


$total_amortissements = count($amortissements);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amortissement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        body {
            background-color: #f8fafc;
            font-family: 'Arial', sans-serif;
        }
        .container {
            padding-top: 20px;
        }
        .total-amortissements {
            background-color: #00CED1;
            border-radius: 10px;
            padding: 15px;
            color: #fff;
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .total-amortissements i {
            font-size: 35px;
            margin-right: 10px;
        }
        .total-amortissements .number {
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
        }
        .btn-primary, .btn-secondary, .btn-danger {
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #6cb2eb; 
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #3ca4e3; 
        }
        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-danger {
            background-color: #ff5c5c; 
            color: #fff;
        }
        .btn-danger:hover {
            background-color: #e74c3c; 
        }
        .form-label {
            font-weight: bold;
            color: #555;
        }
        .form-control {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: #6cb2eb; 
        }
        .table th, .table td {
            border: 1px solid #dee2e6; 
            padding: 10px;
            font-size: 0.9rem; 
        }
        .table th {
            background-color: #00CED1; 
            color: #fff;
            font-weight: bold;
        }
        tbody tr:hover {
            background-color: #e9ecef;
        }

        
        .btn-primary,
        .btn-secondary,
        .btn-danger {
            border-radius: 25px;
            padding: 12px 24px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover,
        .btn-secondary:hover,
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            transition: all 0.3s ease;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            border-color: #6cb2eb;
            box-shadow: 0px 2px 6px rgba(108, 178, 235, 0.5);
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        
        <div class="total-amortissements">
            <i class="bx bxs-archive-out"></i>
            Total Amortissements: <span class="number"><?php echo $total_amortissements; ?></span>
        </div>

        
        <div class="no-print py-3">
            <a href="add_Amort.php" class="btn btn-danger">Ajouter</a>
            <button id="printButton" class="btn btn-primary">Imprimer</button>
        </div>

       
        <div class="no-print mb-3">
            <form method="GET" class="mb-3">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Date de début:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Date de fin:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary mt-4">Filtrer</button>
                        <button type="reset" class="btn btn-secondary mt-4">Reset</button>
                    </div>
                </div>
            </form>
            
            <form method="GET" action="amortissement.php" class="mb-3">
                <input type="hidden" name="show_all" value="true">
                <button type="submit" class="btn btn-secondary mt-4">Afficher tous</button>
            </form>
        </div>


        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Article</th>
                            <th>Type</th>
                            <th>Total</th>
                            <th>Qte Sortie</th>
                            <th>Qte Reste</th>
                            <th>Date Amortissement</th>
                            <th>Bénéficiaire</th>
                            <th>Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($amortissements as $amortissement): ?>
                            <tr>
                                <td><?php echo $amortissement['id']; ?></td>
                                <td><?php echo $amortissement['article_designation']; ?></td>
                                <td><?php echo $amortissement['type_name']; ?></td>
                                <td><?php echo $amortissement['Total']; ?></td>
                                <td><?php echo $amortissement['QteSortie']; ?></td>
                                <td><?php echo $amortissement['QteReste']; ?></td>
                                <td><?php echo $amortissement['DateAmor']; ?></td>
                                <td><?php echo $amortissement['benef_nom'] . " " . $amortissement['benef_prenom']; ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
    $('#printButton').click(function(){
        
        var tableHTML = $('table').get(0).outerHTML;

        
        var printWindow = window.open('', '', 'height=500,width=800');
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(tableHTML);
        printWindow.document.write('</body></html>');

        
        printWindow.document.close();
        printWindow.print();
    });
});
    </script>
</body>
</html>
