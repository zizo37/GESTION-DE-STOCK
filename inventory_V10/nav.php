<?php
$sql_count = "SELECT COUNT(*) AS count FROM notification";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->execute();
$notification_count = $stmt_count->fetchColumn();

$notif = '<span class="notification-badge">' . $notification_count . '</span>';
?>

<div class="sidebar">
    <div class="sidebar-header">
        <a class="sidebar-brand" href="#">STOCK</a>
    </div>
    <div class="sidebar-menu">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="dashboard">
                    <i class="bx bx-grid-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="article">
                    <i class="bx bx-list-ul"></i>
                    <span>Article</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="amortissement.php">
                    <i class="bx bx-calculator"></i>
                    <span>Amortissement</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="filter_marche.php">
                    <i class="bx bx-plus-circle"></i>
                    <span>Ajouter du stock pour les Marches</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Stockage.php">
                    <i class="bx bx-history"></i>
                    <span>Historique Stock</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_Benificiare.php">
                    <i class="bx bx-user-plus"></i>
                    <span>Ajouter bénéficiaire</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="AjouterArticle.php">
                    <i class="bx bx-plus-circle"></i>
                    <span>Ajouter Article</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_Marche.php">
                    <i class="bx bx-plus-circle"></i>
                    <span>Ajouter marche</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_Location.php">
                    <i class="bx bx-plus-circle"></i>
                    <span>Ajouter nouveau Dépôt</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="benificiaire.php">
                    <i class="bx bx-user"></i>
                    <span>Benificiaires</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Location.php">
                    <i class="bx bx-building"></i>
                    <span>Dépôt</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="marche.php">
                    <i class="bx bx-shopping-bag"></i>
                    <span>Marche</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="notification.php">
                    <i class="bx bx-bell"></i>
                    <span>Notification</span>
                    <?php echo $notif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Art_Benif.php">
                    <i class="bx bx-user-check"></i>
                    <span>Consommation d'un bénéficiaire</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Qte_One_Stock.php">
                    <i class="bx bx-box"></i>
                    <span>Quantités dans le stock</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="deconnection.php">
                    <i class="bx bx-power-off"></i>
                    <span>Déconnection</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Add CSS styles -->
<style>
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 250px;
        background-color: #343a40;
        padding: 20px;
        color: rgba(255, 255, 255, 0.8);
        transition: width 0.3s ease;
        font-family: 'Arial', sans-serif;
        z-index: 999;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .sidebar-header {
        margin-bottom: 20px;
    }

    .sidebar-brand {
        color: #fff;
        font-weight: bold;
        text-decoration: none;
        font-size: 1.5rem;
    }

    .sidebar-menu {
        padding-left: 0;
    }

    .nav-link {
        display: flex;
        align-items: center;
        color: rgba(255, 255, 255, 0.8);
        padding: 10px 15px;
        border-radius: 5px;
        transition: color 0.3s ease, background-color 0.3s ease;
        font-size: 1rem;
    }

    .nav-link i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .nav-link:hover,
    .nav-link.active {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .notification-badge {
        background-color: #ffc107;
        color: #212529;
        padding: 2px 6px;
        border-radius: 50%;
        font-size: 12px;
        margin-left: auto;
    }
</style>