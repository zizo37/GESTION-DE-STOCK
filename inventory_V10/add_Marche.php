<?php
include "connection.php"; 
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NomMarche = $_POST['NomMarche'];

    if (empty($NomMarche)) {
        $errorMessage = "Please fill in all fields.";
    } else {
        try {
            $sql = "INSERT INTO bc_marche (NomMarche) VALUES (:NomMarche)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':NomMarche', $NomMarche);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $successMessage = "Type added successfully!";
                header("Location: marche.php");
                exit();
            } else {
                $errorMessage = "An error occurred while adding the type.";
            }
        } catch (PDOException $e) {
            $errorMessage = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter marche</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        .alert-dismissible {
            display: none; 
        }
    </style>
</head>

<body>
    <div class="container mt-5 py-2">
        <?php
        include "nav.php";
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertPlaceholder">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Error!</strong> Please fix the following issues:
            <ul id="errorMessages"></ul> 
        </div>
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form id="addMarcheForm" method="post" class="mx-4 mt-2">
            <div class="form-group">
                <label for="NomMarche">Nom Marche :</label>
                <input type="text" class="form-control" name="NomMarche" id="NomMarche" placeholder="Saisir le nom de marche">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter marche</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#addMarcheForm').submit(function(event) {
                var nomMarche = $('#NomMarche').val().trim();
                var errors = "";

                if (nomMarche === '') {
                    errors += "<li>Nom Marche is required.</li>";
                }

                if (errors !== "") {
                    event.preventDefault();
                    $('#errorMessages').html(errors);
                    $('#alertPlaceholder').show();
                } else {
                    $('#alertPlaceholder').hide();
                }
            });
            $('.btn-close').click(function() {
                $('.alert-dismissible').hide();
            });
        });
    </script>
</body>
</html>
