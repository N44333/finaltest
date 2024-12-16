<?php
// Initialization
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 5;
$from_record_num = ($records_per_page * $page) - $records_per_page;

// Include database and object files
include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';

// Instantiate database and objects
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$category = new Category($db);

// HTML header
$page_title = "Read Products";
include_once "header.php";

// Database operations
$stmt = $product->readAll($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// Display products if available
if ($num > 0) {
    echo "<div class='right-button-margin'>";
    echo "<a href='create-product.php' class='btn btn-default pull-right'>Create Product</a>";
    echo "</div>";

    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr>";
    echo "<th>Product</th>";
    echo "<th>Price</th>";
    echo "<th>Description</th>";
    echo "<th>Category</th>";
    echo "<th>Brand</th>";
    echo "<th>Actions</th>";
    echo "</tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        echo "<tr>";
        echo "<td>{$name}</td>";
        echo "<td>{$price}</td>";
        echo "<td>{$description}</td>";
        echo "<td>";
        $category->id = $category_id;
        $category->readName();
        echo $category->name;
        echo "</td>";
        echo "<td>{$brand}</td>";
        echo "<td>";
        echo "<a href='read-product.php?id={$id}' class='btn btn-primary left-margin'>Read</a>";
        echo "<a href='update-product.php?id={$id}' class='btn btn-default left-margin'>Edit</a>";
        echo "<a href='delete-product.php?id={$id}' class='btn btn-danger delete-object'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Paging buttons
    $page_url = "index.php?";
    $total_rows = $product->countAll();
    include_once 'paging.php';
} else {
    echo "<div class='alert alert-info'>No products found.</div>";
}

// Set page footer
include_once "footer.php";
?>