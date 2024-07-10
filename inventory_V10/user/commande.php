<?php
include "../connection.php";
session_start();



if (!isset($_SESSION["loggedIn_User"]) || $_SESSION["loggedIn_User"] !== true) {
 header("Location: loginUser.php?erreur=1");
 exit();
}



if (!isset($_GET['article_id'])) {
    header("Location: index.php");
    exit();
}

$articleId = $_GET['article_id'];
$sql = "SELECT articles.id, articles.designation, typeart.type, articles.QteRegion, articles.QteME, articles.image_path
        FROM articles
        INNER JOIN typeart ON articles.type_id = typeart.idT
        WHERE articles.id = :article_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':article_id', $articleId);
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC);

function getImageURL($imagePath)
{
    return '../' . $imagePath;
}

$sql = "SELECT * FROM location";
$stmt = $conn->prepare($sql);
$stmt->execute();
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = $_POST['quantity'];
    $locationId = $_POST['location'];

    if ($quantity <= 0) {
        header("Location: commande.php?article_id=$articleId&error=Please enter a valid quantity.");
        exit();
    }

    $totalQuantity = $article['QteRegion'] + $article['QteME'];
    if ($quantity > $totalQuantity) {
        header("Location: commande.php?article_id=$articleId&error=Insufficient quantity available.");
        exit();
    }


    $userId = $_GET['id'];
    $matriculeSql = "SELECT matricule FROM usersauthentification WHERE idU = :userId";
    $matriculeStmt = $conn->prepare($matriculeSql);
    $matriculeStmt->bindParam(':userId', $userId);
    $matriculeStmt->execute();
    $matricule = $matriculeStmt->fetchColumn();  

    // Insert notification into the table
    $notificationSql = "INSERT INTO notification (article_id, quantity, location_id, beneficiary_matricule) VALUES (:article_id, :quantity, :location_id, :matricule)";
    $notificationStmt = $conn->prepare($notificationSql);
    $notificationStmt->bindParam(':article_id', $articleId);
    $notificationStmt->bindParam(':quantity', $quantity);
    $notificationStmt->bindParam(':location_id', $locationId);
    $notificationStmt->bindParam(':matricule', $matricule);
    $notificationStmt->execute();

    // Redirect to a success page or display a success message
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        
        
        .container {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background-color: #d9d9d9;
                width: 100%;
            }

        /* Increase content width and add some padding */
        .row {
            width: 80%;
            padding: 30px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        
        .form-label {
            font-weight: bold;
        }

        .form-control,
        .form-select {
            border-radius: 5px;
        }

        
        .btn-primary {
            padding: 10px 20px;
            font-size: 16px;
        }

        
        .alert-danger {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
                <?php if (isset($_GET['error'])) : ?>
                    <div id="error-alert" class="alert alert-danger" role="alert">
                        <?php echo $_GET['error']; ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['insufficient_quantity_error'])) : ?>
                    <div id="insufficient-quantity-alert" class="alert alert-danger" role="alert">
                        Insufficient quantity available.
                    </div>
                <?php endif; ?>
            <div class="col-md-4">
                <img src="<?php echo getImageURL($article['image_path']); ?>" alt="Article Image" class="img-fluid">
            </div>
            <div class="col-md-8">
                <h2><?php echo $article['designation']; ?></h2>
                <p>Type: <?php echo $article['type']; ?></p>
                <p>Total Quantity: <?php echo ($article['QteRegion'] + $article['QteME']); ?></p>
                <form id="commandeForm" method="post">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location:</label>
                        <select id="location" name="location" class="form-select" required>
                            <option value="">Select Location</option>
                            <?php foreach ($locations as $location) { ?>
                                <option value="<?php echo $location['idL']; ?>"><?php echo $location['locationName']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
  $(document).ready(function() {
    $('#commandeForm').submit(function(event) {
      if ($('#quantity').val() <= 0) {
        event.preventDefault();
        showAlert('Please enter a valid quantity.', 'danger');
      }
    });

    function showAlert(message, type) {
      var alertDiv = $('<div class="alert alert-' + type + '"></div>');
      alertDiv.text(message);
      $('.row').prepend(alertDiv);
      setTimeout(function() {
        alertDiv.remove();
      }, 3000);
    };

    $(document).ready(function() {
        setTimeout(function() {
            $('#error-alert').fadeOut('slow');
            $('#insufficient-quantity-alert').fadeOut('slow');
        }, 3000);
    }); 

  });
</script>
</body>

</html>