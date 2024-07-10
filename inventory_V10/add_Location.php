<?php
include "connection.php"; 
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $LocationName = $_POST['LocationName'];

    if (empty($LocationName)) {
        $errorMessage = "Please fill in all fields.";
    } else {
        try {
            $sql = "INSERT INTO location (LocationName) VALUES (:LocationName)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':LocationName', $LocationName);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $successMessage = "Location added successfully!";
                header("Location: Location.php");
                exit();
            } else {
                $errorMessage = "An error occurred while adding the location.";
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
        <p class="error"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <p class="success"><?php echo $successMessage; ?></p>
        <?php endif; ?>

        <form id="addLocationForm" method="post" class="mx-4 mt-2">
            <div class="form-group">
                <label for="LocationName" >Nom Depot :</label>
                <input type="text" class="form-control" name="LocationName" id="LocationName" placeholder="Saisir le nom de Depot">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter Depot</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#addLocationForm').submit(function(event) {
                var locationName = $('#LocationName').val().trim();
                var errors = "";

                if (locationName === '') {
                    errors += "<li>Nom Depot is required.</li>";
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
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
