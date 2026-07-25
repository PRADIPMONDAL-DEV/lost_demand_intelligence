<?php
include_once 'session.php';
include_once 'db.php';
/* DELETE RECORD */

if(isset($_GET['delete']))
{
    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare("
        DELETE FROM lost_demand
        WHERE id=?
    ");

    $stmt->bind_param("i",$id);
    $stmt->execute();

    header("Location:view-requests.php");
    exit();
}

/* SEARCH */

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "
SELECT *
FROM lost_demand
WHERE 1=1
";

if(!empty($search))
{
    $search = $conn->real_escape_string($search);

    $sql .= "
    AND (
        product_name LIKE '%$search%'
        OR brand LIKE '%$search%'
        OR category LIKE '%$search%'
    )
    ";
}

if(!empty($category))
{
    $category = $conn->real_escape_string($category);

    $sql .= "
    AND category='$category'
    ";
}

$sql .= "
ORDER BY request_date DESC
";

$result = $conn->query($sql);

/* KPI */

$totalRequests = $conn->query("
SELECT COUNT(*) total
FROM lost_demand
")->fetch_assoc()['total'];

$totalQty = $conn->query("
SELECT IFNULL(SUM(quantity),0) total
FROM lost_demand
")->fetch_assoc()['total'];

$totalLoss = $conn->query("
SELECT COUNT(*) total
FROM lost_demand
WHERE customer_left='Yes'
")->fetch_assoc()['total'];

/* CATEGORY */

$categories = $conn->query("
SELECT DISTINCT category
FROM lost_demand
WHERE category IS NOT NULL
AND category <> ''
");
?>


<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>View Requests</title>

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
font-size:22px;
font-weight:bold;
color:white;
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

/* MAIN */

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

/* KPI */

.kpi-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:25px;
}

.kpi-card{
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,.08);
}

.kpi{
font-size:30px;
font-weight:bold;
margin-top:10px;
}

/* CARD */

.card{
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,.08);
}

/* TOP BAR */

.top-bar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
gap:15px;
flex-wrap:wrap;
}

/* BUTTONS */

.btn{
padding:10px 18px;
border:none;
border-radius:8px;
text-decoration:none;
cursor:pointer;
display:inline-block;
}

.btn-primary{
background:#2563eb;
color:white;
}

.btn-success{
background:#10b981;
color:white;
}

.btn-secondary{
background:#6b7280;
color:white;
}

.btn-warning{
background:#f59e0b;
color:white;
padding:6px 10px;
}

.btn-danger{
background:#ef4444;
color:white;
padding:6px 10px;
}

/* FILTER */

.filter-form{
display:grid;
grid-template-columns:2fr 1fr auto auto;
gap:15px;
margin-bottom:20px;
}

.filter-form input,
.filter-form select{
padding:12px;
border:1px solid #d1d5db;
border-radius:8px;
}

/* TABLE */

.table-wrapper{
overflow-x:auto;
}

table{
width:100%;
border-collapse:collapse;
min-width:900px;
}

table th{
background:#0f172a;
color:white;
padding:12px;
text-align:left;
}

table td{
padding:12px;
border-bottom:1px solid #e5e7eb;
}

.badge{
padding:6px 12px;
border-radius:20px;
font-size:12px;
font-weight:600;
color:white;
}

.badge-lost{
background:#ef4444;
}

.badge-retained{
background:#10b981;
}

/* MOBILE */

@media(max-width:900px){

.sidebar{
position:relative;
width:100%;
height:auto;
}

.main{
margin-left:0;
}

.filter-form{
grid-template-columns:1fr;
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
<i class="fas fa-home"></i> Dashboard
</a>

<a href="record-demand.php">
<i class="fas fa-plus-circle"></i> Record Demand
</a>

<a class="active" href="view-requests.php">
<i class="fas fa-list"></i> View Requests
</a>

<a href="analytics.php">
<i class="fas fa-chart-bar"></i> Analytics
</a>

<a href="recommendations.php">
<i class="fas fa-lightbulb"></i> Recommendations
</a>

<a href="reports.php">
<i class="fas fa-file-alt"></i> Reports
</a>

<a href="logout.php">
<i class="fas fa-sign-out-alt"></i> Logout
</a>

</div>

<!-- MAIN -->

<div class="main">

<div class="header">

<h2>
<i class="fas fa-list"></i>
View Demand Requests
</h2>

<p>
Search, manage and analyze customer demand records.
</p>

</div>

<!-- KPI -->

<div class="kpi-grid">

<div class="kpi-card">
<small>Total Requests</small>
<div class="kpi">
<?= number_format($totalRequests) ?>
</div>
</div>

<div class="kpi-card">
<small>Total Quantity</small>
<div class="kpi">
<?= number_format($totalQty) ?>
</div>
</div>

<div class="kpi-card">
<small>Lost Customers</small>
<div class="kpi" style="color:#ef4444;">
<?= number_format($totalLoss) ?>
</div>
</div>

</div>

<!-- CARD -->

<div class="card">

<div class="top-bar">

<h3>Demand Records</h3>

<a href="record-demand.php" class="btn btn-primary">
<i class="fas fa-plus"></i>
New Record
</a>

</div>

<!-- FILTER -->

<form method="GET" class="filter-form">

<input
type="text"
name="search"
placeholder="Search Product..."
value="<?= htmlspecialchars($search) ?>">

<select
name="category"
onchange="this.form.submit()">

<option value="">
All Categories
</option>

<?php while($cat = $categories->fetch_assoc()) { ?>

<option
value="<?= htmlspecialchars($cat['category']) ?>"
<?= ($category == $cat['category']) ? 'selected' : '' ?>>

<?= htmlspecialchars($cat['category']) ?>

</option>

<?php } ?>

</select>

<button type="submit" class="btn btn-success">
<i class="fas fa-search"></i>
Search
</button>

<a href="view-requests.php" class="btn btn-secondary">
Reset
</a>

</form>

<!-- TABLE -->

<div class="table-wrapper">

<table>

<thead>

<tr>
<th>ID</th>
<th>Product</th>
<th>Category</th>
<th>Brand</th>
<th>Qty</th>
<th>Price</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>

<td><?= $row['id'] ?></td>

<td><?= htmlspecialchars($row['product_name']) ?></td>

<td><?= htmlspecialchars($row['category']) ?></td>

<td><?= htmlspecialchars($row['brand']) ?></td>

<td><?= $row['quantity'] ?></td>

<td>
₹<?= number_format($row['estimated_price']) ?>
</td>

<td>

<?php if($row['customer_left']=="Yes"){ ?>

<span class="badge badge-lost">
Lost
</span>

<?php } else { ?>

<span class="badge badge-retained">
Retained
</span>

<?php } ?>

</td>

<td>
<?= date('d M Y',strtotime($row['request_date'])) ?>
</td>

<td>

<a
href="edit-request.php?id=<?= $row['id'] ?>"
class="btn btn-warning">

<i class="fas fa-edit"></i>

</a>

<a
href="?delete=<?= $row['id'] ?>"
class="btn btn-danger"
onclick="return confirm('Delete record?')">

<i class="fas fa-trash"></i>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</body>
</html>


