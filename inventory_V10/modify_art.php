<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

if(isset($_GET['id'])){
    $id=$_GET['id'];

    $sql = 'SELECT * FROM `articles` WHERE id = :id LIMIT 1';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $article = $stmt->fetch(PDO::FETCH_ASSOC); 
    } else {
        header("Location: article.php?error=no_article_found");
        exit();
    }
} else {
    header("Location: article.php?error=id_not_set");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $designation = $_POST['designation'];
    $type_id = $_POST['type_id'];

    // Check if a new image was uploaded
    if(isset($_FILES["product_image"]) && $_FILES["product_image"]["size"] > 0){
        // Handle uploaded image
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
            // Update the image path in the database
            $sql_update_image = 'UPDATE `articles` SET image_path = :image_path WHERE id = :id';
            $stmt_update_image = $conn->prepare($sql_update_image);
            $stmt_update_image->bindParam(':image_path', $image_path);
            $stmt_update_image->bindParam(':id', $id);
            $stmt_update_image->execute();
        } else {
            echo "Error uploading image.";
            exit();
        }
    }

    // Update the article details
    $sql_update_article = 'UPDATE `articles` SET designation = :designation, type_id = :type_id WHERE id = :id';
    $stmt_update_article = $conn->prepare($sql_update_article);
    $stmt_update_article->bindParam(':designation', $designation);
    $stmt_update_article->bindParam(':type_id', $type_id);
    $stmt_update_article->bindParam(':id', $id);

    if ($stmt_update_article->execute()) {
        echo "Article updated successfully.";
        // Redirect the user to another page
        header("Location: article.php");
        exit(); // Make sure to exit after redirection
    } else {
        echo "Error updating Article: " . $stmt_update_article->errorInfo()[2];
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Article</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border-bottom: none;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .card-body {
            padding: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 20px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .img-preview-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        .img-preview {
            max-width: 200px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Modify Article</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="designation">Designation:</label>
                                <input type="text" name="designation" value="<?php echo $article['designation']; ?>" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="type_id">Type:</label>
                                <select name="type_id" class="form-control">
                                    <?php
                                    $sql_types = 'SELECT idT, type FROM typeart';
                                    $stmt_types = $conn->prepare($sql_types);
                                    $stmt_types->execute();
                                    $types = $stmt_types->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    foreach ($types as $type) {
                                        $selected = ($type['idT'] == $article['type_id']) ? 'selected' : '';
                                        echo '<option value="' . $type['idT'] . '" ' . $selected . '>' . $type['type'] . '</option>';
                                    }
                                    
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product_image">Image:</label>
                                <input type="file" name="product_image" class="form-control-file">
                            </div>
                            <div class="form-group img-preview-container">
                                <img src="<?php echo $article['image_path']; ?>" alt="Article Image" class="img-preview">
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
