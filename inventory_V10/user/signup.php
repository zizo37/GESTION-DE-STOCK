<?php
include "../connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricule = $_POST['matricule'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $address = $_POST['address'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if matricule already exists
    $checkMatriculeSql = "SELECT COUNT(*) FROM usersauthentification WHERE matricule = :matricule";
    $checkMatriculeStmt = $conn->prepare($checkMatriculeSql);
    $checkMatriculeStmt->bindParam(':matricule', $matricule);
    $checkMatriculeStmt->execute();
    $matriculeExists = $checkMatriculeStmt->fetchColumn();

    if ($matriculeExists) {
        $error = "Matricule already exists. Please choose a different one.";
    } else {
        // Insert user into usersauthentification table
        $insertUserSql = "INSERT INTO usersauthentification (matricule, nom, prenom, address, telephone, email, password) VALUES (:matricule, :nom, :prenom, :address, :telephone, :email, :password)";
        $insertUserStmt = $conn->prepare($insertUserSql);
        $insertUserStmt->bindParam(':matricule', $matricule);
        $insertUserStmt->bindParam(':nom', $nom);
        $insertUserStmt->bindParam(':prenom', $prenom);
        $insertUserStmt->bindParam(':address', $address);
        $insertUserStmt->bindParam(':telephone', $telephone);
        $insertUserStmt->bindParam(':email', $email);
        $insertUserStmt->bindParam(':password', $password);
        
        if ($insertUserStmt->execute()) {
            // Insert successful, redirect to a success page
            header("Location: loginUser.php");
            exit();
        } else {
            $error = "Error inserting user. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .container h4 {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-primary {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
        <h4>User Sign Up</h4>
        <?php if (isset($error)) : ?>
            <div id="errorMessages" class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form id="signupForm" method="POST">
            <div class="form-group">
                <label for="matricule">Matricule:</label>
                <input type="text" name="matricule" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" name="prenom" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="address">Adresse:</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="telephone">Téléphone:</label>
                <input type="text" name="telephone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#signupForm").submit(function (event) {
                let matricule = $("input[name='matricule']").val().trim();
                let nom = $("input[name='nom']").val().trim();
                let prenom = $("input[name='prenom']").val().trim();
                let address = $("input[name='address']").val().trim();
                let telephone = $("input[name='telephone']").val().trim();
                let email = $("input[name='email']").val().trim();
                let password = $("input[name='password']").val().trim();
                let errors= "";

                if (matricule === "") {
                    errors += "<li>Matricule is required.</li>";
                }

                if (nom === "") {
                    errors += "<li>Nom is required.</li>";
                }

                if (prenom === "") {
                    errors += "<li>Prénom is required.</li>";
                }

                if (address === "") {
                    errors += "<li>Adresse is required.</li>";
                }

                if (telephone === "") {
                    errors += "<li>Téléphone is required.</li>";
                }

                if (email === "") {
                    errors += "<li>Email is required.</li>";
                }

                if (password === "") {
                    errors += "<li>Mot de passe is required.</li>";
                }

                if (errors !== "") {
                    event.preventDefault();
                    $("#errorMessages").html(errors);
                    $("#errorMessages").show();
                } else {
                    $("#errorMessages").hide();
                }
            });
        });
    </script>
</body>

</html>