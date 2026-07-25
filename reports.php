
<?php
include_once 'session.php';
include_once 'db.php';

/* FILTER */

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$where = "";

if(!empty($from) && !empty($to))
{
    $where = "
    WHERE DATE(request_date)
    BETWEEN '$from'
    AND '$to'
    ";
}

/* EXPORT CSV */

if(isset($_GET['export']))
{
    header("Content-Type:text/csv");
    header("Content-Disposition:attachment; filename=lost_demand_report.csv");

    $output = fopen("php://output","w");

    fputcsv($output,[
        "ID",
        "Product",
        "Category",
        "Brand",
        "Quantity",
        "Price",
        "Customer Left",
        "Date"
    ]);

    $exportData = $conn->query("
    SELECT *
    FROM lost_demand
    $where
    ORDER BY request_date DESC
    ");

    while($row = $exportData->fetch_assoc())
    {
        fputcsv($output,[
            $row['id'],
            $row['product_name'],
            $row['category'],
            $row['brand'],
            $row['quantity'],
            $row['estimated_price'],
            $row['customer_left'],
            $row['request_date']
        ]);
    }

    fclose($output);
    exit();
}

/* KPI */

$summary = $conn->query("
SELECT
COUNT(*) total_requests,
IFNULL(SUM(quantity),0) total_qty,
IFNULL(
SUM(
CASE
WHEN customer_left='Yes'
THEN quantity * estimated_price
ELSE 0
END
),0
) total_loss
FROM lost_demand
$where
")->fetch_assoc();

$totalRequests = $summary['total_requests'];
$totalQty = $summary['total_qty'];
$totalLoss = $summary['total_loss'];

/* REPORT */

$reportData = $conn->query("
SELECT *
FROM lost_demand
$where
ORDER BY request_date DESC
");

?>


<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>Reports</title>

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

/* CARD */

.card{
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,.08);
margin-bottom:20px;
}

/* FILTER */

.filter-grid{
display:grid;
grid-template-columns:1fr 1fr auto auto auto;
gap:15px;
align-items:end;
}

label{
display:block;
margin-bottom:8px;
font-weight:600;
}

input[type=date]{
width:100%;
padding:12px;
border:1px solid #d1d5db;
border-radius:8px;
}

/* KPI */

.kpi-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:20px;
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

.blue{
color:#2563eb;
}

.red{
color:#ef4444;
}

/* BUTTONS */

.btn{
padding:12px 18px;
border:none;
border-radius:8px;
cursor:pointer;
text-decoration:none;
display:inline-block;
color:white;
}

.btn-primary{
background:#2563eb;
}

.btn-success{
background:#10b981;
}

.btn-dark{
background:#111827;
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

.badge-danger{
background:#ef4444;
}

.badge-success{
background:#10b981;
}

.no-record{
text-align:center;
padding:30px;
color:#666;
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

.filter-grid{
grid-template-columns:1fr;
}

}

/* PRINT */

@media print{

.sidebar,
.filter-box,
.action-btn{
display:none;
}

.main{
margin:0;
padding:0;
}

.card{
box-shadow:none;
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

<a href="view-requests.php">
<i class="fas fa-list"></i> View Requests
</a>

<a href="analytics.php">
<i class="fas fa-chart-bar"></i> Analytics
</a>

<a href="recommendations.php">
<i class="fas fa-lightbulb"></i> Recommendations
</a>

<a href="reports.php" class="active">
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
<i class="fas fa-file-alt"></i>
Demand Reports
</h2>

<p>
Generate reports, export data and monitor business performance.
</p>

</div>

<!-- FILTER -->

<div class="card filter-box">

<form method="GET">

<div class="filter-grid">

<div>
<label>From Date</label>

<input
type="date"
name="from"
value="<?= htmlspecialchars($from) ?>">
</div>

<div>
<label>To Date</label>

<input
type="date"
name="to"
value="<?= htmlspecialchars($to) ?>">
</div>

<button class="btn btn-primary action-btn">
<i class="fas fa-filter"></i>
Filter
</button>

<a
href="?from=<?= $from ?>&to=<?= $to ?>&export=1"
class="btn btn-success action-btn">

<i class="fas fa-download"></i>
CSV

</a>

<button
type="button"
onclick="window.print()"
class="btn btn-dark action-btn">

<i class="fas fa-print"></i>
Print

</button>

</div>

</form>

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

<div class="kpi blue">
<?= number_format($totalQty) ?>
</div>

</div>

<div class="kpi-card">

<small>Revenue Lost</small>

<div class="kpi red">
₹<?= number_format($totalLoss,2) ?>
</div>

</div>

</div>

<!-- REPORT TABLE -->

<div class="card">

<h3 style="margin-bottom:20px;">
Detailed Report
</h3>

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
</tr>

</thead>

<tbody>

<?php
if($reportData->num_rows > 0)
{
while($row = $reportData->fetch_assoc())
{
?>

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

<span class="badge badge-danger">
Lost
</span>

<?php } else { ?>

<span class="badge badge-success">
Retained
</span>

<?php } ?>

</td>

<td>
<?= date('d M Y',strtotime($row['request_date'])) ?>
</td>

</tr>

<?php
}
}
else
{
?>

<tr>
<td colspan="8" class="no-record">
No Records Found
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



