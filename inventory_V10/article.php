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
    <title>Articles List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc; 
            color: #333;
        }

        .content-area {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-control,
        .form-select {
            border-radius: 20px;
        }

        .btn-primary {
            background-color: #1a83ec; 
            border-color: #1a83ec;
            color: #fff;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #1a83ec;
            border-color: #1a83ec;
            color: #fff;
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            margin-bottom: 1rem;
            background-color: #fff;
        }

        .table thead th {
            background-color: #1a83ec; 
            color: #fff;
            font-weight: bold;
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            text-align: center;
        }

        .table td,
        .table th {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            text-align: left;
        }

        .table tbody tr:hover {
            background-color: #f3f4f6;
        }

        .action-icons {
            font-size: 40px; 
            display: flex; 
            align-items: center; 
        }

        .action-icons a {
            color: #333;
            margin-right: 10px;
        }

        .action-icons a:hover {
            color: #1a83ec; 
        }

        
        .btn-primary {
            border-radius: 25px;
            padding: 12px 24px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-white {
            background-color: #fff;
            border-color: #fff;
            color: #1a83ec; 
            font-weight: bold;
        }

        .btn-white:hover {
            background-color: #f3f4f6;
            border-color: #f3f4f6;
            color: #1a83ec; 
        }

        .form-control {
            transition: all 0.3s ease;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            border-color: #1a83ec; 
            box-shadow: 0px 2px 6px rgba(76, 175, 80, 0.5);
        }

        
        .btn-consulter {
            color: #fff !important; 
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-5">
        <?php include "nav.php"; ?>
        <div class="row">
            <main class="col-lg-10 col-md-9 col-sm-12 content-area mx-auto">
                <div class="container mt-3">
                    <!-- Search form -->
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <input type="text" name="designation" class="form-control" placeholder="Search by designation" value="<?php echo isset($_GET['designation']) ? $_GET['designation'] : ''; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <select name="type" class="form-select">
                                        <option value="">Select Type</option>
                                        <?php
                                        $sqlType = "SELECT * FROM typeart ORDER BY type";
                                        $stmtType = $conn->prepare($sqlType);
                                        $stmtType->execute();
                                        $types = $stmtType->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($types as $type) {
                                            echo "<option value=\"{$type['idT']}\"" . (isset($_GET['type']) && $_GET['type'] == $type['idT'] ? ' selected' : '') . ">{$type['type']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="container mt-4">
                    <?php
                    $typeFilter = "";
                    $designationFilter = "";

                    if (isset($_GET['type']) && !empty($_GET['type'])) {
                        $typeFilter = " AND articles.type_id = :typeId";
                    }

                    if (isset($_GET['designation']) && !empty($_GET['designation'])) {
                        $designationFilter = " AND articles.designation LIKE :designation";
                    }

                    $sql = "SELECT articles.id, articles.designation, typeart.type, articles.QteRegion, articles.QteME, articles.image_path
                        FROM articles
                        INNER JOIN typeart ON articles.type_id = typeart.idT
                        WHERE 1=1" . $typeFilter . $designationFilter . "
                        ORDER BY articles.designation";
                    $stmt = $conn->prepare($sql);

                    if (!empty($typeFilter)) {
                        $stmt->bindParam(':typeId', $_GET['type'], PDO::PARAM_INT);
                    }

                    if (!empty($designationFilter)) {
                        $stmt->bindValue(':designation', '%' . $_GET['designation'] . '%', PDO::PARAM_STR);
                    }

                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                    ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <!-- <th scope="col">#</th> -->
                                        <th scope="col">Image</th>
                                        <th scope="col">Designation</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Qte Total</th>
                                        <th scope="col">Qte Region</th>
                                        <th scope="col">Qte ME</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($resultat as $row) {
                                        $qteTotal = $row['QteRegion'] + $row['QteME'];
                                    ?>
                                        <tr>
                                            <!-- <th scope="row"><?php echo $row['id']; ?></th> -->
                                            <td><img src="<?php echo $row['image_path']; ?>" alt="Article Image" class="img-fluid" style="max-width: 100px;"></td>
                                            <td><?php echo $row['designation']; ?></td>
                                            <td><?php echo $row['type']; ?></td>
                                            <td><?php echo $qteTotal; ?></td>
                                            <td><?php echo $row['QteRegion']; ?></td>
                                            <td><?php echo $row['QteME']; ?></td>
                                            <td>
                                                <div class="action-icons">
                                                    <a href="supprimer_Article.php?id=<?php echo $row['id']; ?>" title="Supprimer"><i class='bx bx-trash text-danger'></i></a>
                                                    <a href="modify_art.php?id=<?php echo $row['id']; ?>" title="Modifier"><i class='bx bxs-edit text-primary'></i></a>
                                                    <a href="consulter_Article.php?id=<?php echo $row['id']; ?>" title="Consulter" class="btn btn-sm btn-primary btn-consulter">Consulter</a>
                                                </div>
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
                        echo "<p>No articles found in the database.</p>";
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS (Optional, if you need dropdowns, modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
