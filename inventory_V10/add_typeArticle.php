<?php
include "connection.php"; 
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomType = $_POST['type'];

    try {
        $sql = "INSERT INTO typeart (type) VALUES (:type)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':type', $nomType);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $successMessage = "Type added successfully!";
            header("Location: type_Article.php");
            exit();
        } else {
            $errorMessage = "An error occurred while adding the type.";
        }
    } catch (PDOException $e) {
        $errorMessage = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Beneficiaire</title>
    
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
    <div class="container mt-5">
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

        <form id="addTypeForm" method="post">
            <div class="form-group">
                <label for="type">Type Article :</label>
                <input type="text" class="form-control" name="type" id="type" placeholder="Saisir le type">
            </div>
            <button type="submit" class="btn btn-primary">Add type</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#addTypeForm').submit(function(event) {
                var type = $('#type').val().trim();
                let errors = "";

                if (type === '') {
                    errors = "<li>type is required.</li>";
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
