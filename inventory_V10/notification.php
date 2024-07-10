<?php
include "connection.php";
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
    header("Location: login.php?erreur=1");
    exit();
}

$sql = "SELECT * FROM notification";
$stmt = $conn->prepare($sql);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$modifiedNotifications = array();

foreach ($notifications as $notification) {
    $location_id = $notification['location_id'];
    $articleId = $notification['article_id'];
    $beneficiary_matricule = $notification['beneficiary_matricule'];

    $sql_location = "SELECT locationName FROM location WHERE idL = :location_id";
    $stmt_location = $conn->prepare($sql_location);
    $stmt_location->bindParam(':location_id', $location_id);
    $stmt_location->execute();
    $location_data = $stmt_location->fetch(PDO::FETCH_ASSOC);

    $locationName = ($location_data === false) ? 'N/A' : $location_data['locationName'];

    $sql_designation = "SELECT designation FROM articles WHERE id = :article_id";
    $stmt_designation = $conn->prepare($sql_designation);
    $stmt_designation->bindParam(':article_id', $articleId);
    $stmt_designation->execute();
    $designation_data = $stmt_designation->fetch(PDO::FETCH_ASSOC);

    $designation = ($designation_data === false) ? 'N/A' : $designation_data['designation'];

    $sql_beneficiaire = "SELECT nom, prenom FROM beneficiaire WHERE matriculeU = :matriculeU";
    $stmt_beneficiaire = $conn->prepare($sql_beneficiaire);
    $stmt_beneficiaire->bindParam(':matriculeU', $beneficiary_matricule);
    $stmt_beneficiaire->execute();
    $beneficiaire_data = $stmt_beneficiaire->fetch(PDO::FETCH_ASSOC);

    $nom = ($beneficiaire_data === false) ? 'N/A' : $beneficiaire_data['nom'];
    $prenom = ($beneficiaire_data === false) ? 'N/A' : $beneficiaire_data['prenom'];

    $modifiedNotification = array(
        'id' => $notification['id'],
        'article_id' => $notification['article_id'],
        'beneficiary_matricule' => $notification['beneficiary_matricule'],
        'quantity' => $notification['quantity'],
        'location_id' => $notification['location_id'],
        'created_at' => $notification['created_at'],
        'locationName' => $locationName,
        'designation' => $designation,
        'nom' => $nom,
        'prenom' => $prenom
    );

    $modifiedNotifications[] = $modifiedNotification;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .table-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php
        include "nav.php";
    ?>
    
    <div class="container py-5">
        
    <?php
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success">Notification deleted successfully.</div>';
        }

        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger">Error occurred while deleting the notification.</div>';
        }
    ?>
        <h2>Notifications</h2>
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Matricule de benificiaire </th>
                        <th>Designation</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Quantity</th>
                        <th>Location Name</th>
                        <th>Created At</th>
                        <th>Paramètre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modifiedNotifications as $notification) : ?>
                        <tr>
                            <td><?php echo $notification['id']; ?></td>
                            <td><?php echo $notification['beneficiary_matricule']; ?></td>
                            <td><?php echo isset($notification['designation']) ? $notification['designation'] : 'N/A'; ?></td>
                            <td><?php echo isset($notification['nom']) ? $notification['nom'] : 'N/A'; ?></td>
                            <td><?php echo isset($notification['prenom']) ? $notification['prenom'] : 'N/A'; ?></td>
                            <td><?php echo $notification['quantity']; ?></td>
                            <td><?php echo isset($notification['locationName']) ? $notification['locationName'] : 'N/A'; ?></td>
                            <td><?php echo $notification['created_at']; ?></td>
                            <td>
                                <a href="delete_not.php?id=<?php echo $notification['id']; ?>" class="btn btn-danger">supprimer</a>
                            </td>
                            <td>
                                <!-- <a href="approve.php?id=<?php echo $notification['id']; ?>" class="btn btn-primary">approuver</a> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>