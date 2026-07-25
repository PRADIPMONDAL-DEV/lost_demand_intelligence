
<?php
include_once 'session.php';
include_once 'db.php';

/* MOST LOST PRODUCT */

$mostLostProduct = "N/A";
$lostCount = 0;

$result = $conn->query("
SELECT product_name,
COUNT(*) total
FROM lost_demand
WHERE customer_left='Yes'
GROUP BY product_name
ORDER BY total DESC
LIMIT 1
");

if($row = $result->fetch_assoc()){
    $mostLostProduct = $row['product_name'];
    $lostCount = $row['total'];
}

/* TOP PRODUCT */

$topProduct = "N/A";
$topQty = 0;

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
    $topQty = $row['qty'];
}

/* TOP CATEGORY */

$topCategory = "N/A";

$result = $conn->query("
SELECT category,
COUNT(*) total
FROM lost_demand
WHERE category IS NOT NULL
AND category <> ''
GROUP BY category
ORDER BY total DESC
LIMIT 1
");

if($row = $result->fetch_assoc()){
    $topCategory = $row['category'];
}

/* TOP BRAND */

$topBrand = "N/A";

$result = $conn->query("
SELECT brand,
COUNT(*) total
FROM lost_demand
WHERE brand IS NOT NULL
AND brand <> ''
GROUP BY brand
ORDER BY total DESC
LIMIT 1
");

if($row = $result->fetch_assoc()){
    $topBrand = $row['brand'];
}

/* REVENUE LOSS PRODUCT */

$revenueLossProduct = "N/A";
$revenueLoss = 0;

$result = $conn->query("
SELECT product_name,
SUM(quantity * estimated_price) total_loss
FROM lost_demand
WHERE customer_left='Yes'
GROUP BY product_name
ORDER BY total_loss DESC
LIMIT 1
");

if($row = $result->fetch_assoc()){
    $revenueLossProduct = $row['product_name'];
    $revenueLoss = $row['total_loss'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>Recommendations</title>

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

/* GRID */

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(350px,1fr));
gap:20px;
margin-bottom:20px;
}

/* CARD */

.card{
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,.08);
}

.alert-card{
border-left:6px solid #ef4444;
}

.success-card{
border-left:6px solid #10b981;
}

.primary-card{
border-left:6px solid #2563eb;
}

.warning-card{
border-left:6px solid #f59e0b;
}

.big-number{
font-size:32px;
font-weight:bold;
margin:10px 0;
}

.text-danger{
color:#ef4444;
}

.text-success{
color:#10b981;
}

.card h3{
margin:10px 0;
}

.card p{
color:#666;
}

/* INSIGHT */

.insight-list{
margin-top:15px;
padding-left:20px;
}

.insight-list li{
margin-bottom:12px;
line-height:1.7;
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

.grid{
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

<a href="analytics.php">
<i class="fas fa-chart-bar"></i>
Analytics
</a>

<a href="recommendations.php" class="active">
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
<i class="fas fa-lightbulb"></i>
AI Recommendations
</h2>

<p>
Smart inventory recommendations based on lost demand analysis.
</p>

</div>

<!-- FIRST ROW -->

<div class="grid">

<div class="card alert-card">

<h4>⚠ Most Lost Product</h4>

<h3>
<?= htmlspecialchars($mostLostProduct) ?>
</h3>

<div class="big-number text-danger">
<?= $lostCount ?>
</div>

<p>
customers left without purchasing.
</p>

</div>

<div class="card success-card">

<h4>🔥 Most Requested Product</h4>

<h3>
<?= htmlspecialchars($topProduct) ?>
</h3>

<div class="big-number text-success">
<?= $topQty ?>
</div>

<p>
units requested by customers.
</p>

</div>

</div>

<!-- SECOND ROW -->

<div class="grid">

<div class="card primary-card">

<h4>📦 Top Category</h4>

<h3>
<?= htmlspecialchars($topCategory) ?>
</h3>

<p>
Highest demand category.
</p>

</div>

<div class="card warning-card">

<h4>🏷 Top Brand</h4>

<h3>
<?= htmlspecialchars($topBrand) ?>
</h3>

<p>
Most requested brand.
</p>

</div>

</div>

<!-- REVENUE LOSS -->

<div class="card alert-card" style="margin-bottom:20px;">

<h3>💰 Revenue Loss Alert</h3>

<h2>
<?= htmlspecialchars($revenueLossProduct) ?>
</h2>

<div class="big-number text-danger">
₹<?= number_format($revenueLoss,2) ?>
</div>

<p>
Potential revenue lost due to unavailable stock.
</p>

</div>

<!-- AI INSIGHTS -->

<div class="card">

<h3>🤖 AI Insights & Recommended Actions</h3>

<ul class="insight-list">

<li>
Increase inventory for
<strong><?= htmlspecialchars($topProduct) ?></strong>.
</li>

<li>
Prioritize procurement of
<strong><?= htmlspecialchars($topBrand) ?></strong>.
</li>

<li>
Allocate additional stock budget to
<strong><?= htmlspecialchars($topCategory) ?></strong>.
</li>

<li>
Create reorder alerts for
<strong><?= htmlspecialchars($mostLostProduct) ?></strong>.
</li>

<li>
Monitor products causing the highest revenue loss.
</li>

<li>
Review demand trends weekly for better forecasting.
</li>

<li>
Maintain safety stock levels for fast-moving products.
</li>

</ul>

</div>

</div>

</body>
</html>

