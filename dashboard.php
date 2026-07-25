<?php
include_once 'session.php';
include_once 'db.php';

/* KPIs */

$totalRequests = $conn->query("
SELECT COUNT(*) total
FROM lost_demand
")->fetch_assoc()['total'];

$totalQty = $conn->query("
SELECT IFNULL(SUM(quantity),0) total
FROM lost_demand
")->fetch_assoc()['total'];

$lostRevenue = $conn->query("
SELECT IFNULL(SUM(quantity*estimated_price),0) total
FROM lost_demand
WHERE customer_left='Yes'
")->fetch_assoc()['total'];

$lostCustomers = $conn->query("
SELECT COUNT(*) total
FROM lost_demand
WHERE customer_left='Yes'
")->fetch_assoc()['total'];

/* TOP PRODUCT */

$topProduct = "N/A";

$result = $conn->query("
SELECT product_name,
SUM(quantity) qty
FROM lost_demand
GROUP BY product_name
ORDER BY qty DESC
LIMIT 1
");

if($row = $result->fetch_assoc()){
    $topProduct = $row['product_name'];
}

/* CHART */

$months = [];
$values = [];

$result = $conn->query("
SELECT
DATE_FORMAT(request_date,'%b') month,
SUM(quantity) qty
FROM lost_demand
GROUP BY DATE_FORMAT(request_date,'%Y-%m')
ORDER BY request_date
");

while($row = $result->fetch_assoc()){
    $months[] = $row['month'];
    $values[] = $row['qty'];
}

/* RECENT REQUESTS */

$recent = $conn->query("
SELECT *
FROM lost_demand
ORDER BY request_date DESC
LIMIT 10
");
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>Dashboard</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
padding-top:20px;
}

.logo{
text-align:center;
color:#fff;
font-size:22px;
font-weight:bold;
margin-bottom:30px;
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
color:#fff;
border-left:4px solid #3b82f6;
}

/* MAIN */

.main{
margin-left:250px;
padding:25px;
}

/* HEADER */

.header{
background:linear-gradient(135deg,#2563eb,#1d4ed8);
padding:25px;
border-radius:15px;
color:white;
margin-bottom:25px;
}

/* KPI */

.kpi-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:25px;
}

.card{
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,.08);
}

.card-top{
display:flex;
justify-content:space-between;
align-items:center;
}

.card h2{
margin-top:10px;
font-size:30px;
}

.icon{
width:50px;
height:50px;
display:flex;
justify-content:center;
align-items:center;
border-radius:12px;
color:white;
font-size:20px;
}

.blue{background:#2563eb;}
.green{background:#10b981;}
.red{background:#ef4444;}
.orange{background:#f59e0b;}

/* CHART SECTION */

.grid-2{
display:grid;
grid-template-columns:2fr 1fr;
gap:20px;
margin-bottom:25px;
}

/* TABLE */

.table-card{
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,.08);
overflow-x:auto;
}

table{
width:100%;
border-collapse:collapse;
}

table th{
background:#0f172a;
color:white;
padding:12px;
text-align:left;
}

table td{
padding:12px;
border-bottom:1px solid #eee;
}

.badge{
padding:6px 12px;
border-radius:20px;
font-size:13px;
color:white;
}

.badge-danger{
background:#ef4444;
}

.badge-success{
background:#10b981;
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

.grid-2{
grid-template-columns:1fr;
}

}

</style>
</head>

<body>

<div class="sidebar">

<div class="logo">
<i class="fas fa-chart-line"></i>
Lost Demand
</div>

<a href="dashboard.php" class="active">
<i class="fas fa-home"></i> Dashboard
</a>

<a href="record-demand.php">
<i class="fas fa-plus-circle"></i> Record Demand
</a>

<a href="view-requests.php">
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

<div class="main">

<div class="header">
<h2>Lost Demand Intelligence Dashboard</h2>
<p>
Track customer demand, revenue loss and inventory opportunities.
</p>
</div>

<div class="kpi-grid">

<div class="card">
<div class="card-top">
<div>Total Requests</div>
<div class="icon blue">
<i class="fas fa-file-alt"></i>
</div>
</div>
<h2><?= number_format($totalRequests) ?></h2>
</div>

<div class="card">
<div class="card-top">
<div>Total Quantity</div>
<div class="icon green">
<i class="fas fa-box"></i>
</div>
</div>
<h2><?= number_format($totalQty) ?></h2>
</div>

<div class="card">
<div class="card-top">
<div>Revenue Lost</div>
<div class="icon red">
<i class="fas fa-money-bill-wave"></i>
</div>
</div>
<h2>₹<?= number_format($lostRevenue,0) ?></h2>
</div>

<div class="card">
<div class="card-top">
<div>Lost Customers</div>
<div class="icon orange">
<i class="fas fa-users"></i>
</div>
</div>
<h2><?= number_format($lostCustomers) ?></h2>
</div>

</div>

<div class="grid-2">

<div class="card">
<h3>Demand Trend</h3>
<br>
<canvas id="trendChart"></canvas>
</div>

<div class="card">
<h3>Top Product</h3>
<br>
<h2 style="color:#2563eb;">
<?= htmlspecialchars($topProduct) ?>
</h2>
<p>
Most requested product based on customer demand.
</p>
</div>

</div>

<div class="table-card">

<h3>Recent Demand Requests</h3>

<br>

<table>

<thead>
<tr>
<th>Product</th>
<th>Category</th>
<th>Brand</th>
<th>Qty</th>
<th>Price</th>
<th>Status</th>
</tr>
</thead>

<tbody>

<?php while($row = $recent->fetch_assoc()) { ?>

<tr>

<td><?= htmlspecialchars($row['product_name']) ?></td>

<td><?= htmlspecialchars($row['category']) ?></td>

<td><?= htmlspecialchars($row['brand']) ?></td>

<td><?= $row['quantity'] ?></td>

<td>₹<?= number_format($row['estimated_price']) ?></td>

<td>

<?php if($row['customer_left']=="Yes"){ ?>

<span class="badge badge-danger">
Lost
</span>

<?php } else { ?>

<span class="badge badge-success">
Retained
</span>

<?php } ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<script>

new Chart(
document.getElementById('trendChart'),
{
type:'line',

data:{
labels:<?= json_encode($months) ?>,

datasets:[{
label:'Demand',
data:<?= json_encode($values) ?>,
borderColor:'#2563eb',
backgroundColor:'#2563eb',
borderWidth:3,
tension:.4
}]
}
});

</script>

</body>
</html>


