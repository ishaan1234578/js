<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$active_tab = $_GET['tab'] ?? 'suppliers';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_supplier'])) {
    $supplier_name = $_POST['supplier_name'] ?? '';
    $contact_person = $_POST['contact_person'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';

    $stmt = $conn->prepare("INSERT INTO suppliers (supplier_name, contact_person, phone, email, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $supplier_name, $contact_person, $phone, $email, $address);

    if ($stmt->execute()) {
        $message = "Supplier added successfully!";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin'])) {
    $username = $_POST['username'] ?? '';
    $password = md5($_POST['password'] ?? '');

    $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $message = "Admin added successfully!";
    }
}

$suppliers = $conn->query("SELECT * FROM suppliers");
$admins = $conn->query("SELECT id, username FROM admin");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Drug Inventory System</title>
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
                <li><a href="suppliers.php">Suppliers</a></li>
                <li><a href="admin.php" class="active">Admin</a></li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </nav>

        <div class="content">
            <div class="header"><h2>Admin Panel</h2></div>
            <?php if ($message): ?><div class="message success"><?php echo $message; ?></div><?php endif; ?>

            <div class="tabs">
                <button class="tab-button <?php echo $active_tab == 'suppliers' ? 'active' : ''; ?>" onclick="switchTab('suppliers')">Manage Suppliers</button>
                <button class="tab-button <?php echo $active_tab == 'admins' ? 'active' : ''; ?>" onclick="switchTab('admins')">Manage Admins</button>
            </div>

            <div id="suppliers" class="tab-content" style="display: <?php echo $active_tab == 'suppliers' ? 'block' : 'none'; ?>">
                <div class="form-container">
                    <h3>Add New Supplier</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Supplier Name *</label>
                            <input type="text" name="supplier_name" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Contact Person *</label>
                                <input type="text" name="contact_person" required>
                            </div>
                            <div class="form-group">
                                <label>Phone *</label>
                                <input type="tel" name="phone" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Address *</label>
                            <textarea name="address" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="add_supplier" class="btn btn-primary">Add Supplier</button>
                    </form>
                </div>

                <div class="section">
                    <h3>Current Suppliers</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Supplier Name</th>
                                <th>Contact Person</th>
                                <th>Phone</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $suppliers = $conn->query("SELECT * FROM suppliers"); while ($supplier = $suppliers->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($supplier['supplier_name']); ?></td>
                                <td><?php echo htmlspecialchars($supplier['contact_person']); ?></td>
                                <td><?php echo htmlspecialchars($supplier['phone']); ?></td>
                                <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="admins" class="tab-content" style="display: <?php echo $active_tab == 'admins' ? 'block' : 'none'; ?>">
                <div class="form-container">
                    <h3>Add New Admin</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Username *</label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="form-group">
                            <label>Password *</label>
                            <input type="password" name="password" required>
                        </div>
                        <button type="submit" name="add_admin" class="btn btn-primary">Add Admin</button>
                    </form>
                </div>

                <div class="section">
                    <h3>Current Admins</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Admin ID</th>
                                <th>Username</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $admins = $conn->query("SELECT id, username FROM admin"); while ($admin = $admins->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $admin['id']; ?></td>
                                <td><?php echo htmlspecialchars($admin['username']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
