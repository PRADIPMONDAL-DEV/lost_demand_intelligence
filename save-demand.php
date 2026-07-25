<?php
include_once 'session.php';
include_once 'db.php';

/*
|--------------------------------------------------------------------------
| Collect Form Data
|--------------------------------------------------------------------------
*/

$product_name   = mysqli_real_escape_string($conn, $_POST['product_name']);
$category       = mysqli_real_escape_string($conn, $_POST['category']);
$brand          = mysqli_real_escape_string($conn, $_POST['brand']);
$size           = mysqli_real_escape_string($conn, $_POST['size']);
$color          = mysqli_real_escape_string($conn, $_POST['color']);
$quantity       = (int)$_POST['quantity'];
$estimated_price= (float)$_POST['estimated_price'];
$customer_left  = mysqli_real_escape_string($conn, $_POST['customer_left']);
$notes          = mysqli_real_escape_string($conn, $_POST['notes']);

/*
|--------------------------------------------------------------------------
| User ID
|--------------------------------------------------------------------------
| If login system is ready
| Otherwise store NULL
*/

$user_id = NULL;

if(isset($_SESSION['user_id']))
{
    $user_id = $_SESSION['user_id'];
}

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

if(empty($product_name))
{
    $_SESSION['error'] = "Product Name is required.";
    header("Location: record-demand.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Insert Query
|--------------------------------------------------------------------------
*/

$sql = "
INSERT INTO lost_demand
(
    product_name,
    category,
    brand,
    size,
    color,
    quantity,
    estimated_price,
    customer_left,
    notes,
    user_id
)
VALUES
(
    '$product_name',
    '$category',
    '$brand',
    '$size',
    '$color',
    '$quantity',
    '$estimated_price',
    '$customer_left',
    '$notes',
    ".($user_id === NULL ? "NULL" : "'$user_id'")."
)
";

$result = mysqli_query($conn, $sql);

/*
|--------------------------------------------------------------------------
| Response
|--------------------------------------------------------------------------
*/

if($result)
{
    $_SESSION['success'] =
        "Lost demand request recorded successfully.";

    header("Location: record-demand.php");
    exit();
}
else
{
    $_SESSION['error'] =
        "Something went wrong. Please try again.";

    header("Location: record-demand.php");
    exit();
}

?>