<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

if(isset($_GET['id'])){
    $id=$_GET['id'];

    $sql = 'SELECT * FROM `beneficiaire` WHERE matricule = :id LIMIT 1';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC); 
    } else {
        header("Location: benificiaire.php?error=no_beneficiary_found");
        exit();
    }
} else {
    header("Location: benificiaire.php?error=id_not_set");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricule = $_POST['matricule'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

    $sql = 'UPDATE `beneficiaire` SET nom = :nom , prenom=:prenom WHERE matricule = :matricule';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':matricule', $matricule);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->execute();

    header("Location: benificiaire.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Beneficiary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>Modify Beneficiary</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="matricule">Matricule :</label>
                                <input type="text" name="matricule" value="<?php echo $resultat['matricule']; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nom">Nom :</label>
                                <input type="text" name="nom" id="nom" value="<?php echo $resultat['nom']; ?>" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="prenom">Prenom :</label>
                                <input type="text" name="prenom" id="prenom" value="<?php echo $resultat['prenom']; ?>" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Changer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>