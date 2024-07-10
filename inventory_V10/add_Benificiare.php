<?php
include "connection.php"; 
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

    try {
        $sql = "INSERT INTO beneficiaire (nom, prenom) VALUES (:nom, :prenom)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $successMessage = "Bénéficiaire added successfully!";
            header("Location: benificiaire.php");
            exit();
        } else {
            $errorMessage = "An error occurred while adding the bénéficiaire.";
        }
    } catch (PDOException $e) {
        // Handle database errors
       // $errorMessage = "Database error: " . $e->getMessage();
        //$errorMessage = "Database error. Please try again later.";
        // Et ici, vous pourriez vouloir logger l'erreur pour le débogage sans afficher les détails à l'utilisateur
        //error_log($e->getMessage());
        // Afficher le message d'erreur personnalisé
        //echo $errorMessage;
        if ($e->getCode() == 45000) { // Assurez-vous que ce code correspond à l'erreur spécifique que vous traitez
            $errorMessage = "Beneficiary already exists.";
        } else {
            // Pour toutes les autres erreurs, vous pouvez choisir de log l'erreur sans afficher les détails à l'utilisateur
            $errorMessage = "A database error has occurred. Please try again later.";
            // Assurez-vous de loguer $e->getMessage() pour le débogage interne
            error_log($e->getMessage());
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Beneficiaire</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        .alert-dismissible {
            display: none; 
        }
    </style>
</head>
<body>
    <div class="container mt-5 py-2 ">
    <?php
    include "nav.php";
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertPlaceholder">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Error!</strong> Please fix the following issues:
            <ul id="errorMessages"></ul>
        </div>
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
            <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success" role="alert">
            <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form id="beneficiaireForm" method="post"  class="mx-4 mt-2">
            <div class="mb-3">
                <label for="nom" class="form-label" >Nom :</label>
                <input type="text" name="nom" id="nom" class="form-control" placeholder="Nom du bénéficiaire">
            </div>

            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom :</label>
                <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Prénom du bénéficiaire">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter bénéficiaire</button>
        </form>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#beneficiaireForm").submit(function(event) {
                let nom = $("#nom").val().trim();
                let prenom = $("#prenom").val().trim();
                let errors = "";

                if (nom === "") {
                    errors += "<li>Nom is required.</li>";
                }

                if (prenom === "") {
                    errors += "<li>Prenom is required.</li>";
                }

                if (errors !== "") {
                    event.preventDefault(); 
                    $("#errorMessages").html(errors);
                    $("#alertPlaceholder").show(); 
                } else {
                    $("#alertPlaceholder").hide(); 
                }
            });
            $('.btn-close').click(function() {
                $('.alert-dismissible').hide();
            });
        });
    </script>
</body>
</html>
