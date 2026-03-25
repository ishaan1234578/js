<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$suppliers = $conn->query("SELECT s.*, COUNT(d.id) as drug_count FROM suppliers s LEFT JOIN drugs d ON s.id = d.supplier_id GROUP BY s.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Suppliers - Drug Inventory System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="nav-brand"><h1>💊 Drug Inventory System</h1></div>
            <ul class="nav-links">
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="drugs.php">Drugs</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="suppliers.php" class="active">Suppliers</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </nav>

        <div class="content">
            <div class="header">
                <h2>Suppliers Management</h2>
            </div>
            <div class="action-buttons">
                <a href="admin.php?tab=suppliers" class="btn btn-primary">+ Add New Supplier</a>
            </div>
            <div class="section">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Drugs Supplied</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($supplier = $suppliers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($supplier['supplier_name']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['contact_person']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['phone']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                            <td><span class="badge"><?php echo $supplier['drug_count']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
