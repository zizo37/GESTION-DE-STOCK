<?php
include "../connection.php";
session_start();
?>

<?php
if (!isset($_SESSION["loggedIn_User"]) || $_SESSION["loggedIn_User"] !== true) {
 header("Location: loginUser.php?erreur=1");
 exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Articles List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Boxicons CSS -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    .card {
      height: 500px; /* Increase the height of the card */
    }

    .card-img-top {
      height: 300px; /* Increase the height of the image */
      object-fit: cover;
    }
  </style>
</head>

<body>
  <?php include "navU.php"; ?>
  <div class="container-fluid mt-5">
    <!-- Add your navigation code here -->
    <div class="row">
      <main class="col-lg-12">
        <div class="container mt-3">
          <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php
            function getImageURL($imagePath)
            {
              return '../' . $imagePath;
            }

            $sql = "SELECT articles.id, articles.designation, typeart.type, articles.QteRegion, articles.QteME, articles.image_path
            FROM articles
                    INNER JOIN typeart ON articles.type_id = typeart.idT
                    ORDER BY articles.designation";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($articles as $article) {
            ?>
              <div class="col">
                <div class="card h-100">
                  <img src="<?php echo getImageURL($article['image_path']); ?>" class="card-img-top" alt="Article Image">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo $article['designation']; ?></h5>
                    <p class="card-text">Type: <?php echo $article['type']; ?></p>
                    <p class="card-text">Qte Total: <?php echo ($article['QteRegion'] + $article['QteME']); ?></p>
                    <a href="commande.php?article_id=<?php echo $article['id']; ?>&id=<?php echo $_SESSION["id"]; ?>" class="btn btn-primary">Commande</a>
                  </div>
                </div>
              </div>
            <?php
            }
            ?>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS (Optional, if you need dropdowns, modals, etc.) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
