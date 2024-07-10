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
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <?php
        $sql = 'SELECT * FROM `typeart`';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
        ?>
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Supprimer</th>
                        <th>Modifier</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($resultat as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['idT']; ?></td>
                            <td><?php echo $row['type']; ?></td>
                            <td><a href="supprimer_typeArticle.php?id=<?php echo $row['idT'] ?>" class="btn btn-danger btn-sm">Supprimer</a></td>
                            <td><a href="modifier_typeArticle.php?id=<?php echo $row['idT'] ?>" class="btn btn-primary btn-sm">Modifier</a></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
        } else {
            echo "Aucun article trouvé dans la base de données.";
        }
        ?>
    </div>
</body>
</html>