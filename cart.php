<?php 
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
include(ROOT_DIR."app/config/DatabaseConnect.php");

// Initialize database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();

if (!$conn) {
    echo "Error: Unable to connect to database.";
    exit;
}

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "Error: User not logged in.";
    exit;
}

$carts = [];
$userId = $_SESSION["user_id"];
$subtotal = 0;

try {
    // Use prepared statements to fetch cart data
    $sql = "SELECT carts.id, products.product_name, carts.quantity, carts.unit_price, carts.total_price 
            FROM carts 
            LEFT JOIN products ON products.id = carts.product_id 
            WHERE carts.user_id = :user_id AND carts.status = 0";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $carts = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $db = null;
}

require_once(ROOT_DIR."includes/header.php");

// Display success or error messages
if (isset($_SESSION["error"])) {
    $messageErr = $_SESSION["error"];
    unset($_SESSION["error"]);
}

if (isset($_SESSION["success"])) {
    $messageSucc = $_SESSION["success"];
    unset($_SESSION["success"]);
}
?>
