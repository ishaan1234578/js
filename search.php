<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$results = [];
$search_query = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = $_POST['search_query'] ?? '';
    $filter = $_POST['filter_type'] ?? 'name';

    if ($filter == 'name') {
        $stmt = $conn->prepare("SELECT * FROM drugs WHERE drug_name LIKE ?");
    } elseif ($filter == 'category') {
        $stmt = $conn->prepare("SELECT * FROM drugs WHERE category LIKE ?");
    } else {
        $stmt = $conn->prepare("SELECT * FROM drugs WHERE supplier_id IN (SELECT id FROM suppliers WHERE supplier_name LIKE ?)");
    }

    $search_param = "%$search_query%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search - Drug Inventory System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="nav-brand"><h1>💊 Drug Inventory System</h1></div>
            <ul class="nav-links">
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="drugs.php">Drugs</a></li>
                <li><a href="search.php" class="active">Search</a></li>
                <li><a href="suppliers.php">Suppliers</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </nav>

        <div class="content">
            <div class="header"><h2>Search Drugs</h2></div>
            <div class="form-container">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Search Query *</label>
                            <input type="text" name="search_query" value="<?php echo htmlspecialchars($search_query); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Search By *</label>
                            <select name="filter_type">
                                <option value="name">Drug Name</option>
                                <option value="category">Category</option>
                                <option value="supplier">Supplier</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="search.php" class="btn btn-secondary">Clear</a>
                    </div>
                </form>
            </div>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <div class="section">
                <h3>Results for "<?php echo htmlspecialchars($search_query); ?>"</h3>
                <?php if ($results && $results->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Drug Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($drug = $results->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($drug['drug_name']); ?></td>
                            <td><?php echo htmlspecialchars($drug['category']); ?></td>
                            <td><?php echo $drug['quantity']; ?></td>
                            <td>Rs. <?php echo number_format($drug['price'], 2); ?></td>
                            <td>
                                <a href="edit_drug.php?id=<?php echo $drug['id']; ?>" class="btn-small btn-edit">Edit</a>
                                <a href="delete_drug.php?id=<?php echo $drug['id']; ?>" class="btn-small btn-delete" onclick="return confirm('Sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="no-results">No drugs found matching your search.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
