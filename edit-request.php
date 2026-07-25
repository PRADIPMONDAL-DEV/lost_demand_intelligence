
<?php
include_once 'session.php';
include_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0)
{
    header("Location: view-requests.php");
    exit();
}

/* FETCH RECORD */

$stmt = $conn->prepare("SELECT * FROM lost_demand WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0)
{
    die("Record not found.");
}

$row = $result->fetch_assoc();

/* UPDATE RECORD */

if(isset($_POST['update']))
{
    $product_name    = trim($_POST['product_name']);
    $category        = trim($_POST['category']);
    $brand           = trim($_POST['brand']);
    $size            = trim($_POST['size']);
    $color           = trim($_POST['color']);
    $quantity        = (int)$_POST['quantity'];
    $estimated_price = (float)$_POST['estimated_price'];
    $customer_left   = $_POST['customer_left'];
    $notes           = trim($_POST['notes']);

    $update = $conn->prepare("
        UPDATE lost_demand
        SET
            product_name=?,
            category=?,
            brand=?,
            size=?,
            color=?,
            quantity=?,
            estimated_price=?,
            customer_left=?,
            notes=?
        WHERE id=?
    ");

    $update->bind_param(
        "sssssidssi",
        $product_name,
        $category,
        $brand,
        $size,
        $color,
        $quantity,
        $estimated_price,
        $customer_left,
        $notes,
        $id
    );

    if($update->execute())
    {
        header("Location: view-requests.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit Demand Request</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f1f5f9;
}

.card-box{
    max-width:900px;
    margin:40px auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}
</style>

</head>

<body>

<div class="container">

<div class="card-box">

<h2 class="mb-4">
Edit Demand Request
</h2>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">
<label>Product Name</label>
<input
type="text"
name="product_name"
class="form-control"
value="<?= htmlspecialchars($row['product_name']) ?>"
required>
</div>

<div class="col-md-6 mb-3">
<label>Category</label>

<select name="category" class="form-select">

<option value="T-Shirts" <?= ($row['category']=='T-Shirts')?'selected':'' ?>>T-Shirts</option>

<option value="Shirts" <?= ($row['category']=='Shirts')?'selected':'' ?>>Shirts</option>

<option value="Pants" <?= ($row['category']=='Pants')?'selected':'' ?>>Pants</option>

<option value="Jeans" <?= ($row['category']=='Jeans')?'selected':'' ?>>Jeans</option>

<option value="Shoes" <?= ($row['category']=='Shoes')?'selected':'' ?>>Shoes</option>

<option value="Chocolates" <?= ($row['category']=='Chocolates')?'selected':'' ?>>Chocolates</option>

<option value="Soft Drinks" <?= ($row['category']=='Soft Drinks')?'selected':'' ?>>Soft Drinks</option>

<option value="Mobile Phones" <?= ($row['category']=='Mobile Phones')?'selected':'' ?>>Mobile Phones</option>

<option value="Books" <?= ($row['category']=='Books')?'selected':'' ?>>Books</option>

<option value="Cosmetics" <?= ($row['category']=='Cosmetics')?'selected':'' ?>>Cosmetics</option>

<option value="Others" <?= ($row['category']=='Others')?'selected':'' ?>>Others</option>

</select>
</div>

</div>

<div class="row">

<div class="col-md-6 mb-3">
<label>Brand</label>
<input
type="text"
name="brand"
class="form-control"
value="<?= htmlspecialchars($row['brand']) ?>">
</div>

<div class="col-md-3 mb-3">
<label>Size</label>
<input
type="text"
name="size"
class="form-control"
value="<?= htmlspecialchars($row['size']) ?>">
</div>

<div class="col-md-3 mb-3">
<label>Color</label>
<input
type="text"
name="color"
class="form-control"
value="<?= htmlspecialchars($row['color']) ?>">
</div>

</div>

<div class="row">

<div class="col-md-4 mb-3">
<label>Quantity</label>
<input
type="number"
name="quantity"
class="form-control"
value="<?= $row['quantity'] ?>">
</div>

<div class="col-md-4 mb-3">
<label>Estimated Price</label>
<input
type="number"
step="0.01"
name="estimated_price"
class="form-control"
value="<?= $row['estimated_price'] ?>">
</div>

<div class="col-md-4 mb-3">
<label>Customer Left?</label>

<select
name="customer_left"
class="form-select">

<option value="Yes"
<?= ($row['customer_left']=='Yes')?'selected':'' ?>>
Yes
</option>

<option value="No"
<?= ($row['customer_left']=='No')?'selected':'' ?>>
No
</option>

</select>

</div>

</div>

<div class="mb-3">
<label>Notes</label>

<textarea
name="notes"
rows="5"
class="form-control"><?= htmlspecialchars($row['notes']) ?></textarea>

</div>

<button
type="submit"
name="update"
class="btn btn-primary">
Update Record
</button>

<a
href="view-requests.php"
class="btn btn-secondary">
Cancel
</a>

</form>

</div>

</div>

</body>
</html>
