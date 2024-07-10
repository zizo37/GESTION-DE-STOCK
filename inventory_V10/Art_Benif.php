<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

$sql_beneficiaire = 'SELECT * FROM beneficiaire';
$stmt_beneficiaire = $conn->prepare($sql_beneficiaire);
$stmt_beneficiaire->execute();
$beneficiaires = $stmt_beneficiaire->fetchAll(PDO::FETCH_ASSOC);

$beneficiaire_filter = isset($_GET['beneficiaire_filter']) ? $_GET['beneficiaire_filter'] : '';
$beneficiaire_matricule = null;

if (!empty($beneficiaire_filter)) {
    $sql_filter = "SELECT b.matricule
                   FROM beneficiaire b
                   WHERE b.nom LIKE '%$beneficiaire_filter%' OR b.prenom LIKE '%$beneficiaire_filter%'
                   LIMIT 1";
    $stmt_filter = $conn->prepare($sql_filter);
    $stmt_filter->execute();
    $result = $stmt_filter->fetch(PDO::FETCH_ASSOC);
    $beneficiaire_matricule = $result['matricule'] ?? null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Articles by Beneficiary</title>
</head>
<body>
    <div class="container py-5">
    <?php
    include "nav.php";
    ?>
    
        <h2 class="text-center">Consommation d'un bénéficiaire</h2>
        <div class="px-md-5">

            <form method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" name="beneficiaire_filter" value="<?php echo $beneficiaire_filter; ?>" placeholder="Recherche par nom de bénéficiaire">
                    <button type="submit" class="btn btn-primary">Recherche</button>
                </div>
            </form>

            <?php if ($beneficiaire_matricule) { ?>
                <a href="chart.php?beneficiaire_matricule=<?php echo $beneficiaire_matricule; ?>" class="btn btn-primary mb-3">View Total Consumption Chart</a>
            <?php } ?>
    
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Bénéficiaire</th>
                        <th>Article</th>
                        <th>Type</th>
                        <th>Quantité consommée</th>
                        <th>Date de consommation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_amortissements = "SELECT am.beneficiaire_matricule, b.nom, b.prenom, ar.designation, t.type, am.QteSortie, am.DateAmor
                                            FROM amortissement am
                                            JOIN beneficiaire b ON am.beneficiaire_matricule = b.matricule
                                            JOIN articles ar ON am.id_Article = ar.id
                                            JOIN typeart t ON am.TypeArt_ID = t.idT";
    
                    if (!empty($beneficiaire_filter)) {
                        $sql_amortissements .= " WHERE b.nom LIKE '%$beneficiaire_filter%' OR b.prenom LIKE '%$beneficiaire_filter%'";
                    }
    
                    $stmt_amortissements = $conn->prepare($sql_amortissements);
                    $stmt_amortissements->execute();
                    $amortissements = $stmt_amortissements->fetchAll(PDO::FETCH_ASSOC);
    
                    foreach ($amortissements as $amortissement) {
                        ?>
                        <tr>
                            <td><?php echo $amortissement['nom'] . ' ' . $amortissement['prenom']; ?></td>
                            <td><?php echo $amortissement['designation']; ?></td>
                            <td><?php echo $amortissement['type']; ?></td>
                            <td><?php echo $amortissement['QteSortie']; ?></td>
                            <td><?php echo $amortissement['DateAmor']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>