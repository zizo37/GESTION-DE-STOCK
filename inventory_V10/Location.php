<?php
include "connection.php"; 
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>

    <div class="container mt-5 py-3 ">
      
        <?php
            include "nav.php";
        ?>
        <?php
        
        $sql = 'SELECT * FROM `location`';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
        ?>
        <div class="px-md-5">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th colspan="2">Gérer Locations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($resultat as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['idL']; ?></td>
                            <td><?php echo $row['locationName']; ?></td>
                            <td>
                                <a href="supprimer_Location.php?id=<?php echo $row['idL'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                <a href="modifier_Location.php?id=<?php echo $row['idL'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                            </td>
                        </tr>
                    <?php 
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        } else {
            echo "Aucun article trouvé dans la base de données.";
        }
    ?>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
