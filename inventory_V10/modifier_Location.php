<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

if(isset($_GET['id'])){
    $id=$_GET['id'];

    $sql = 'SELECT * FROM `Location` WHERE idL = :id LIMIT 1';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC); 
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $NomDepot = $_POST['NomDepot'];

    $sql = 'UPDATE `Location` SET locationName = :NomDepot WHERE idL = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':NomDepot', $NomDepot);
    $stmt->execute();

    header("Location: Location.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Location</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>Modify Location</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $resultat['idL']; ?>">
                            <div class="form-group">
                                <label for="NomDepot">Nom Dépôt :</label>
                                <input type="text" name="NomDepot" id="NomDepot" value="<?php echo $resultat['locationName']; ?>" class="form-control" required>
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