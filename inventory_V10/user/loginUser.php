<?php
session_start();

include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $sql = "SELECT idU FROM usersauthentification WHERE email = :email AND password = :password";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':email', $email); 
  $stmt->bindParam(':password', $password);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $_SESSION["loggedIn_User"] = true;
    $_SESSION["id"] = $stmt->fetchColumn();
    header("Location: index.php");
    exit();
} else {
    $errorMessage = "Les informations d'identification sont invalides";
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
    }

    .login-container {
      max-width: 400px;
      margin: 100px auto;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
    }

    .login-container h1 {
      text-align: center;
      margin-bottom: 20px;
    }

    .btn-login {
      width: 100%;
    }

    .alert-dismissible {
      display: none;
    }
  </style>
</head>

<body>
  <div class="container login-container">
    <img src="../logo_Region.png" alt="Login image" class="img-fluid">
    <h1>Connexion</h1>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertPlaceholder">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      <strong>Error!</strong> Please fix the following issues:
      <ul id="errorMessages"></ul>
    </div>
    <?php
    if (isset($errorMessage)) {
      echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
    }
    ?>
    <form id="loginForm" method="post">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control" id="email" name="email">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password">
      </div>
      <button type="submit" class="btn btn-primary btn-login">Se connecter</button>
    </form>
  </div>
  <script>
    $(document).ready(function() {
      $("#loginForm").submit(function(event) {
        let login = $("#login").val().trim();
        let password = $("#password").val().trim();
        let errors = "";

        if (login === "") {
          errors += "<li>login is required.</li>";
        }

        if (password === "") {
          errors += "<li>password is required.</li>";
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