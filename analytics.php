
<?php
include_once 'session.php';
include_once 'db.php';
/* KPI */

$totalRequests = $conn->query("
SELECT COUNT(*) total
FROM lost_demand
")->fetch_assoc()['total'];

$totalRevenueLost = $conn->query("
SELECT IFNULL(
SUM(quantity * estimated_price),0
) total
FROM lost_demand
WHERE customer_left='Yes'
")->fetch_assoc()['total'];

$totalLostCustomers = $conn->query("
SELECT COUNT(*) total
FROM lost_demand
WHERE customer_left='Yes'
")->fetch_assoc()['total'];

$totalQty = $conn->query("
SELECT IFNULL(SUM(quantity),0) total
FROM lost_demand
")->fetch_assoc()['total'];

/* TOP PRODUCTS */

$productLabels = [];
$productData = [];

$result = $conn->query("
SELECT
product_name,
SUM(quantity) qty
FROM lost_demand
GROUP BY product_name
ORDER BY qty DESC
LIMIT 5
");

while($row = $result->fetch_assoc())
{
    $productLabels[] = $row['product_name'];
    $productData[] = $row['qty'];
}

/* CATEGORY */

$categoryLabels = [];
$categoryData = [];

$result = $conn->query("
SELECT
category,
COUNT(*) total
FROM lost_demand
WHERE category IS NOT NULL
AND category <> ''
GROUP BY category
");

while($row = $result->fetch_assoc())
{
    $categoryLabels[] = $row['category'];
    $categoryData[] = $row['total'];
}

/* CUSTOMER LOSS */

$lossLabels = [];
$lossData = [];

$result = $conn->query("
SELECT
customer_left,
COUNT(*) total
FROM lost_demand
GROUP BY customer_left
");

while($row = $result->fetch_assoc())
{
    $lossLabels[] = $row['customer_left'];
    $lossData[] = $row['total'];
}

/* MONTHLY TREND */

$months = [];
$monthlyDemand = [];

$result = $conn->query("
SELECT
DATE_FORMAT(request_date,'%b %Y') month,
SUM(quantity) total
FROM lost_demand
GROUP BY DATE_FORMAT(request_date,'%Y-%m')
ORDER BY request_date
");

while($row = $result->fetch_assoc())
{
    $months[] = $row['month'];
    $monthlyDemand[] = $row['total'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>Analytics</title>

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
overflow-y:auto;
}

.logo{
padding:25px;
text-align:center;
font-size:22px;
font-weight:bold;
color:#fff;
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

/* KPI CARDS */

.kpi-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:25px;
}

.kpi-card{
background:#fff;
padding:20px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,.08);
}

.kpi-card small{
color:#666;
display:block;
margin-bottom:10px;
}

.kpi{
font-size:32px;
font-weight:bold;
}

.blue{
color:#2563eb;
}

.red{
color:#ef4444;
}

.green{
color:#10b981;
}

/* CHART GRID */

.chart-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(450px,1fr));
gap:20px;
}

.chart-card{
background:#fff;
padding:20px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,.08);
}

.chart-card h3{
margin-bottom:15px;
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

.chart-grid{
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
<i class="fas fa-home"></i>
Dashboard
</a>

<a href="record-demand.php">
<i class="fas fa-plus-circle"></i>
Record Demand
</a>

<a href="view-requests.php">
<i class="fas fa-list"></i>
View Requests
</a>

<a href="analytics.php" class="active">
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

<!-- MAIN CONTENT -->

<div class="main">

<div class="header">

<h2>
<i class="fas fa-chart-bar"></i>
Demand Analytics
</h2>

<p>
Analyze lost demand patterns and customer behavior.
</p>

</div>

<!-- KPI SECTION -->

<div class="kpi-grid">

<div class="kpi-card">
<small>Total Requests</small>
<div class="kpi">
<?= number_format($totalRequests) ?>
</div>
</div>

<div class="kpi-card">
<small>Total Quantity</small>
<div class="kpi blue">
<?= number_format($totalQty) ?>
</div>
</div>

<div class="kpi-card">
<small>Lost Customers</small>
<div class="kpi red">
<?= number_format($totalLostCustomers) ?>
</div>
</div>

<div class="kpi-card">
<small>Revenue Lost</small>
<div class="kpi green">
₹<?= number_format($totalRevenueLost) ?>
</div>
</div>

</div>

<!-- CHARTS -->

<div class="chart-grid">

<div class="chart-card">
<h3>Top Products</h3>
<canvas id="productChart"></canvas>
</div>

<div class="chart-card">
<h3>Demand By Category</h3>
<canvas id="categoryChart"></canvas>
</div>

<div class="chart-card">
<h3>Customer Loss Analysis</h3>
<canvas id="lossChart"></canvas>
</div>

<div class="chart-card">
<h3>Monthly Demand Trend</h3>
<canvas id="trendChart"></canvas>
</div>

</div>

</div>

<script>

/* TOP PRODUCTS */

new Chart(
document.getElementById('productChart'),
{
type:'bar',
data:{
labels:<?= json_encode($productLabels) ?>,
datasets:[{
label:'Quantity',
data:<?= json_encode($productData) ?>,
backgroundColor:'#2563eb'
}]
},
options:{
responsive:true
}
});

/* CATEGORY */

new Chart(
document.getElementById('categoryChart'),
{
type:'pie',
data:{
labels:<?= json_encode($categoryLabels) ?>,
datasets:[{
data:<?= json_encode($categoryData) ?>
}]
},
options:{
responsive:true
}
});

/* CUSTOMER LOSS */

new Chart(
document.getElementById('lossChart'),
{
type:'doughnut',
data:{
labels:<?= json_encode($lossLabels) ?>,
datasets:[{
data:<?= json_encode($lossData) ?>
}]
},
options:{
responsive:true
}
});

/* MONTHLY TREND */

new Chart(
document.getElementById('trendChart'),
{
type:'line',
data:{
labels:<?= json_encode($months) ?>,
datasets:[{
label:'Demand',
data:<?= json_encode($monthlyDemand) ?>,
borderColor:'#2563eb',
backgroundColor:'#2563eb',
borderWidth:3,
tension:0.4,
fill:false
}]
},
options:{
responsive:true
}
});

</script>

</body>
</html>



