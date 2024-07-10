<?php
include "connection.php"; // Assuming this file contains your database connection
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

// Query to fetch articles for the select dropdown
$sql_types = 'SELECT idT, type FROM typeart';
$stmt_types = $conn->prepare($sql_types);
$stmt_types->execute();
$types = $stmt_types->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission here
    $designation = $_POST["designation"];
    $type_id = $_POST["type_id"];

    // Handle uploaded image
    $image_path = ''; // Variable to store the image path or filename

    if (isset($_FILES["product_image"])) {
        $image_file = $_FILES["product_image"];
        $image_name = $image_file["name"];
        $image_tmp_name = $image_file["tmp_name"];
        $image_directory = "images/"; // Define the directory to store the uploaded images

        // Generate a unique filename for the image
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_path = $image_directory . uniqid() . "." . $image_extension;

        // Move the uploaded image to the desired directory
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            // Image uploaded successfully
        } else {
            echo "Error uploading image.";
            exit();
        }
    }

    $sql_insert = "INSERT INTO articles (designation, type_id, image_path) VALUES (:designation, :type_id, :image_path)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':designation', $designation);
    $stmt_insert->bindParam(':type_id', $type_id);
    $stmt_insert->bindParam(':image_path', $image_path);

    if ($stmt_insert->execute()) {
        echo "Article inserted successfully.";
        // Redirect the user to another page
        header("Location: article.php");
        exit(); // Make sure to exit after redirection
    } else {
        echo "Error inserting Article: " . $stmt_insert->errorInfo()[2];
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Article</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5 py-2">
        <?php include "nav.php"; ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertPlaceholder" style="display:none;">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Error!</strong> Please fix the following issues:
            <ul id="errorMessages"></ul>
        </div>
        <form id="addArticleForm" method="post" class="mx-4 mt-2" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="designation" class="form-label">Désignation:</label>
                <input type="text" name="designation" id="designation" class="form-control" placeholder="Désignation du Article">
            </div>
            <div class="mb-3">
                <label for="type_id" class="form-label">Type:</label>
                <select name="type_id" id="type_id" class="form-select">
                    <option value="" disabled selected>Select Type</option>
                    <?php foreach ($types as $type) : ?>
                        <option value="<?php echo $type['idT']; ?>"><?php echo $type['type']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="product_image" class="form-label">Image:</label>
                <input type="file" name="product_image" id="product_image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter Article</button>
        </form>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
    $(document).ready(function() {
        // Add your JavaScript code here (if any)
    });
</script>
</body>
</html>