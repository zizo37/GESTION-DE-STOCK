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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa; /* Light gray background */
      color: #333;
    }

    .container {
      margin-top: 50px;
    }

    .table {
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      width: 100%;
      background-color: #fff;
    }

    .table thead th {
      background-color: #007bff; /* Blue */
      color: #fff;
      font-weight: bold;
      vertical-align: middle;
      border: none;
      text-align: center;
      padding: 1rem;
    }

    .table td,
    .table th {
      vertical-align: middle;
      border-top: 1px solid #dee2e6;
      text-align: left;
      padding: 1rem;
    }

    .table tbody tr:nth-child(even) {
      background-color: #f3f4f6; /* Light gray for even rows */
    }

    .table tbody tr:nth-child(odd) {
      background-color: #fff; /* White for odd rows */
    }

    .table tbody tr:hover {
      background-color: #e7ebef; /* Lighter gray on hover */
      transition: background-color 0.3s ease;
    }

    .btn {
      font-weight: bold;
      font-size: 0.9rem;
      border-radius: 5px;
      transition: all 0.3s ease;
      border: none; /* Removed border for cleaner look */
    }

    .btn-danger:hover,
    .btn-primary:hover {
      filter: brightness(90%); /* Less dimming on hover */
      background-color: #d9534f; /* Red */
      /* OR */
      background-color: #007bff; /* Blue */  /* Choose your preferred hover color */
    }

    .btn-danger {
      background-color: #dc3545; /* Darker red for buttons */
    }

    .btn-primary {
      background-color: #007bff; /* Darker blue for buttons */
    }

    /* Responsive Design (Adjust as needed) */
    @media (min-width: 768px) {
      .container {
        display: flex;
        flex-direction: row;
      }

      .col-md-2 {
        flex: 0 0 20%; /* Fixed width for navigation on larger screens */
        padding: 1rem;
      }

      .col-md-10 {
        flex: 1 1 auto; /* Remaining space for content */
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <?php include "nav.php"; ?>  <div class="col-md-10">  <div class="px-md-5">
        <h2>Liste des marchés</h2>
        <?php
          $sql = 'SELECT * FROM `bc_marche`';
          $stmt = $conn->prepare($sql);
          $stmt->execute();

          if ($stmt->rowCount() > 0) {
        ?>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom</th>
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
              <td><?php echo $row['idMarche']; ?></td>
              <td><?php echo $row['NomMarche']; ?></td>
              <td>
                <a href="supprimer_marche.php?id=<?php echo $row['idMarche'] ?>" class="btn btn-danger btn-sm">
                  <i class="fas fa-trash"></i> Supprimer
                </a>
              </td>
              <td>
                <a href="modifier__marche.php?id=<?php echo $row['idMarche'] ?>" class="btn btn-primary btn-sm">
                  <i class="fas fa-edit"></i> Modifier
                </a>
              </td>
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
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
