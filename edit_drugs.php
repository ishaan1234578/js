<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? '';
$drug = $conn->query("SELECT * FROM drugs WHERE id = $id")->fetch_assoc();

if (!$drug) {
    header("Location: drugs.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $drug_name = $_POST['drug_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $supplier_id = $_POST['supplier_id'];
    $expiry_date = $_POST['expiry_date'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE drugs SET drug_name=?, category=?, quantity=?, price=?, supplier_id=?, expiry_date=?, description=? WHERE id=?");
    $stmt->bind_param("ssissssi", $drug_name, $category, $quantity, $price, $supplier_id, $expiry_date, $description, $id);

    if ($stmt->execute()) {
        header("Location: drugs.php");
        exit();
    }
}

$suppliers = $conn->query("SELECT * FROM suppliers");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Drug - Drug Inventory System</title>
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
                <li><a href="admin.php">Admin</a></li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </nav>

        <div class="content">
            <div class="header"><h2>Edit Drug</h2></div>
            <div class="form-container">
                <form method="POST">
                    <div class="form-group">
                        <label>Drug Name *</label>
                        <input type="text" name="drug_name" value="<?php echo $drug['drug_name']; ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Category *</label>
                            <select name="category" required>
                                <option value="Antibiotic" <?php echo $drug['category'] == 'Antibiotic' ? 'selected' : ''; ?>>Antibiotic</option>
                                <option value="Painkiller" <?php echo $drug['category'] == 'Painkiller' ? 'selected' : ''; ?>>Painkiller</option>
                                <option value="Vitamin" <?php echo $drug['category'] == 'Vitamin' ? 'selected' : ''; ?>>Vitamin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantity *</label>
                            <input type="number" name="quantity" value="<?php echo $drug['quantity']; ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Price (Rs) *</label>
                            <input type="number" name="price" value="<?php echo $drug['price']; ?>" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Supplier *</label>
                            <select name="supplier_id" required>
                                <?php $suppliers = $conn->query("SELECT * FROM suppliers"); while ($s = $suppliers->fetch_assoc()): ?>
                                <option value="<?php echo $s['id']; ?>" <?php echo $drug['supplier_id'] == $s['id'] ? 'selected' : ''; ?>><?php echo $s['supplier_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Expiry Date *</label>
                        <input type="date" name="expiry_date" value="<?php echo $drug['expiry_date']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4"><?php echo $drug['description']; ?></textarea>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">Update Drug</button>
                        <a href="drugs.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
