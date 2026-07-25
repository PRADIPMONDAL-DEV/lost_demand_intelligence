
<?php
include_once 'session.php';
include_once 'db.php';

$message = "";

if(isset($_POST['save_demand']))
{
    $product_name = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $brand = trim($_POST['brand']);
    $size = trim($_POST['size']);
    $color = trim($_POST['color']);
    $quantity = (int)$_POST['quantity'];
    $estimated_price = (float)$_POST['estimated_price'];
    $customer_left = $_POST['customer_left'];
    $notes = trim($_POST['notes']);

    $user_id = $_SESSION['user_id'] ?? NULL;

    $stmt = $conn->prepare("
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
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
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
        $user_id
    );

if($stmt->execute()){
    $message = "<div class='message success'>
    Demand recorded successfully.
    </div>";
}else{
    $message = "<div class='message error'>
    Error saving record.
    </div>";
}
$stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>Record Demand</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
background:#f4f6f9;
}

/* SIDEBAR */

.sidebar{
position:fixed;
left:0;
top:0;
width:250px;
height:100vh;
background:#0f172a;
overflow-y:auto;
}

.logo{
padding:25px;
text-align:center;
color:white;
font-size:22px;
font-weight:bold;
}

.sidebar a{
display:block;
padding:15px 25px;
color:#cbd5e1;
text-decoration:none;
transition:.3s;
}

.sidebar a:hover,
.sidebar a.active{
background:#1e293b;
color:white;
border-left:4px solid #2563eb;
}

/* CONTENT */

.main{
margin-left:250px;
padding:25px;
}

/* HEADER */

.header{
background:linear-gradient(135deg,#2563eb,#1d4ed8);
color:white;
padding:25px;
border-radius:15px;
margin-bottom:25px;
}

/* MESSAGE */

.message{
padding:15px;
border-radius:10px;
margin-bottom:20px;
}

.success{
background:#dcfce7;
color:#166534;
border:1px solid #86efac;
}

.error{
background:#fee2e2;
color:#991b1b;
border:1px solid #fca5a5;
}

/* FORM */

.form-card{
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,.08);
}

.form-grid{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:20px;
}

.form-grid-3{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
}

.form-group{
margin-bottom:20px;
}

label{
display:block;
margin-bottom:8px;
font-weight:600;
}

input,
select,
textarea{
width:100%;
padding:12px;
border:1px solid #d1d5db;
border-radius:10px;
outline:none;
}

input:focus,
select:focus,
textarea:focus{
border-color:#2563eb;
}

/* BUTTONS */

.btn-group{
display:flex;
gap:10px;
margin-top:10px;
}

.btn{
padding:12px 20px;
border:none;
border-radius:10px;
text-decoration:none;
cursor:pointer;
font-size:15px;
}

.btn-save{
background:#2563eb;
color:white;
}

.btn-save:hover{
background:#1d4ed8;
}

.btn-back{
background:#6b7280;
color:white;
}

.btn-back:hover{
background:#4b5563;
}

/* RESPONSIVE */

@media(max-width:900px){

.sidebar{
position:relative;
width:100%;
height:auto;
}

.main{
margin-left:0;
}

.form-grid,
.form-grid-3{
grid-template-columns:1fr;
}

.btn-group{
flex-direction:column;
}

}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<div class="logo">
<i class="fas fa-chart-line"></i>
Lost Demand
</div>

<a href="dashboard.php">
<i class="fas fa-home"></i>
Dashboard
</a>

<a href="record-demand.php" class="active">
<i class="fas fa-plus-circle"></i>
Record Demand
</a>

<a href="view-requests.php">
<i class="fas fa-list"></i>
View Requests
</a>

<a href="analytics.php">
<i class="fas fa-chart-bar"></i>
Analytics
</a>

<a href="recommendations.php">
<i class="fas fa-lightbulb"></i>
Recommendations
</a>

<a href="reports.php">
<i class="fas fa-file-alt"></i>
Reports
</a>

<a href="logout.php">
<i class="fas fa-sign-out-alt"></i>
Logout
</a>

</div>

<!-- MAIN -->

<div class="main">

<div class="header">

<h2>
<i class="fas fa-plus-circle"></i>
Record Lost Demand
</h2>

<p>
Capture customer requests that could not be fulfilled.
</p>

</div>

<?php if(!empty($message)) echo $message; ?>

<div class="form-card">

<form method="POST">

<div class="form-grid">

<div class="form-group">
<label>Product Name *</label>

<input
type="text"
name="product_name"
required>
</div>

<div class="form-group">
<label>Category *</label>

<select name="category" required>

<option value="">Select Category</option>

<option value="T-Shirts">T-Shirts</option>
<option value="Shirts">Shirts</option>
<option value="Pants">Pants</option>
<option value="Jeans">Jeans</option>
<option value="Shoes">Shoes</option>
<option value="Sandals">Sandals</option>

<option value="Chocolates">Chocolates</option>
<option value="Biscuits">Biscuits</option>
<option value="Soft Drinks">Soft Drinks</option>
<option value="Snacks">Snacks</option>

<option value="Mobile Phones">Mobile Phones</option>
<option value="Headphones">Headphones</option>
<option value="Chargers">Chargers</option>

<option value="Books">Books</option>
<option value="Stationery">Stationery</option>

<option value="Cosmetics">Cosmetics</option>
<option value="Perfumes">Perfumes</option>

<option value="Toys">Toys</option>
<option value="Others">Others</option>

</select>
</div>

</div>

<div class="form-grid-3">

<div class="form-group">
<label>Brand</label>
<input type="text" name="brand">
</div>

<div class="form-group">
<label>Size</label>
<input type="text" name="size">
</div>

<div class="form-group">
<label>Color</label>
<input type="text" name="color">
</div>

</div>

<div class="form-grid-3">

<div class="form-group">
<label>Quantity</label>

<input
type="number"
name="quantity"
value="1"
min="1">
</div>

<div class="form-group">
<label>Estimated Price (₹)</label>

<input
type="number"
step="0.01"
name="estimated_price">
</div>

<div class="form-group">
<label>Customer Left?</label>

<select name="customer_left">
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</div>

</div>

<div class="form-group">

<label>Notes</label>

<textarea
name="notes"
rows="5"></textarea>

</div>

<div class="btn-group">

<button
type="submit"
name="save_demand"
class="btn btn-save">

<i class="fas fa-save"></i>
Save Demand

</button>

<a
href="dashboard.php"
class="btn btn-back">

<i class="fas fa-arrow-left"></i>
Back

</a>

</div>

</form>

</div>

</div>

</body>
</html>


