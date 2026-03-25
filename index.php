<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$total_drugs = $conn->query("SELECT COUNT(*) as count FROM drugs")->fetch_assoc()['count'] ?? 0;
$total_suppliers = $conn->query("SELECT COUNT(*) as count FROM suppliers")->fetch_assoc()['count'] ?? 0;
$low_stock = $conn->query("SELECT COUNT(*) as count FROM drugs WHERE quantity < 10")->fetch_assoc()['count'] ?? 0;
$total_value = $conn->query("SELECT SUM(quantity * price) as value FROM drugs")->fetch_assoc()['value'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Drug Inventory System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="nav-brand"><h1>💊 Drug Inventory System</h1></div>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Dashboard</a></li>
                <li><a href="drugs.php">Drugs</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="suppliers.php">Suppliers</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </nav>

        <div class="content">
            <div class="header">
                <h2>Dashboard</h2>
                <p>Welcome to Drug Inventory Management System</p>
            </div>

            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Drugs</h3>
                    <p class="stat-number"><?php echo $total_drugs; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Suppliers</h3>
                    <p class="stat-number"><?php echo $total_suppliers; ?></p>
                </div>
                <div class="stat-card warning">
                    <h3>Low Stock</h3>
                    <p class="stat-number"><?php echo $low_stock; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Inventory Value</h3>
                    <p class="stat-number">Rs. <?php echo number_format($total_value, 2); ?></p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="add_drug.php" class="btn btn-primary">+ Add New Drug</a>
                <a href="drugs.php" class="btn btn-secondary">View All Drugs</a>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
