<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$drugs = $conn->query("SELECT d.*, s.supplier_name FROM drugs d LEFT JOIN suppliers s ON d.supplier_id = s.id ORDER BY d.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Drugs - Drug Inventory System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="nav-brand"><h1>💊 Drug Inventory System</h1></div>
            <ul class="nav-links">
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="drugs.php" class="active">Drugs</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="suppliers.php">Suppliers</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </nav>

        <div class="content">
            <div class="header">
                <h2>Drug Inventory</h2>
            </div>
            <div class="action-buttons">
                <a href="add_drug.php" class="btn btn-primary">+ Add New Drug</a>
            </div>
            <div class="section">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Drug Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($drug = $drugs->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($drug['drug_name']); ?></td>
                            <td><?php echo htmlspecialchars($drug['category']); ?></td>
                            <td><?php echo $drug['quantity']; ?></td>
                            <td>Rs. <?php echo number_format($drug['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($drug['supplier_name'] ?? 'N/A'); ?></td>
                            <td><span class="status <?php echo $drug['quantity'] < 10 ? 'low' : 'ok'; ?>"><?php echo $drug['quantity'] < 10 ? 'Low Stock' : 'In Stock'; ?></span></td>
                            <td>
                                <a href="edit_drug.php?id=<?php echo $drug['id']; ?>" class="btn-small btn-edit">Edit</a>
                                <a href="delete_drug.php?id=<?php echo $drug['id']; ?>" class="btn-small btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
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
