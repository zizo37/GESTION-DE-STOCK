<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}


$sql_bc_marche = 'SELECT idMarche, NomMarche FROM bc_marche';
$stmt_bc_marche = $conn->prepare($sql_bc_marche);
$stmt_bc_marche->execute();
$bc_marche = $stmt_bc_marche->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Marche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container py-2">
    <?php
    include "nav.php";
    ?>
        <h1 class="mt-5 text-center">Sélectionnez une Marches</h1>
        <div class="d-flex justify-content-center">
            <form method="get" action="add_stock_marche.php" class="my-4">
                <div class="mb-3">
                    <label for="bc_marche_id" class="form-label">BC Marche:</label>
                    <select name="bc_marche_id" id="bc_marche_id" class="form-select" required>
                        <option value="" disabled selected>Select BC Marche</option>
                        <?php foreach ($bc_marche as $bc) : ?>
                            <option value="<?php echo $bc['idMarche']; ?>"><?php echo $bc['NomMarche']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Select</button>
            </form>
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>