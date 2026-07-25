<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lost Demand Intelligence System</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Segoe UI, sans-serif;
    background:#f8fafc;
    color:#333;
    line-height:1.6;
}

.container{
    width:90%;
    max-width:1200px;
    margin:auto;
}

/* Navbar */

.navbar{
    background:#fff;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
    position:sticky;
    top:0;
    z-index:100;
}

.nav-container{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 0;
}

.logo{
    font-size:24px;
    font-weight:bold;
    color:#2563eb;
    text-decoration:none;
}

.nav-links{
    display:flex;
    gap:20px;
    align-items:center;
}

.nav-links a{
    text-decoration:none;
    color:#333;
    font-weight:500;
}

.login-btn{
    background:#2563eb;
    color:#fff !important;
    padding:10px 18px;
    border-radius:6px;
}

/* Hero */

.hero{
    background:linear-gradient(135deg,#2563eb,#1e40af);
    color:white;
    padding:80px 0;
}

.hero-content{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:40px;
}

.hero-text{
    flex:1;
}

.hero-text h1{
    font-size:48px;
    margin-bottom:20px;
}

.hero-text p{
    margin-bottom:25px;
}

.hero-image{
    flex:1;
    text-align:center;
}

.hero-image img{
    max-width:100%;
    height:auto;
}

.btn{
    display:inline-block;
    text-decoration:none;
    padding:12px 25px;
    border-radius:30px;
    font-weight:600;
}

.btn-white{
    background:white;
    color:#2563eb;
}

.btn-outline{
    border:2px solid white;
    color:white;
    margin-left:10px;
}

/* Statistics */

.stats{
    background:white;
    padding:60px 0;
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    text-align:center;
}

.stats-grid h2{
    color:#2563eb;
    font-size:36px;
}

/* Features */

.features{
    padding:80px 0;
}

.section-title{
    text-align:center;
    margin-bottom:50px;
}

.feature-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:25px;
}

.feature-card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
    text-align:center;
    transition:.3s;
}

.feature-card:hover{
    transform:translateY(-5px);
}

.feature-card i{
    font-size:40px;
    color:#2563eb;
    margin-bottom:15px;
}

/* How It Works */

.how-it-works{
    background:#eef4ff;
    padding:80px 0;
}

.steps{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.step{
    text-align:center;
}

.step-number{
    width:60px;
    height:60px;
    background:#2563eb;
    color:white;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    margin:auto;
    font-weight:bold;
    font-size:24px;
}

/* Dashboard */

.dashboard{
    padding:80px 0;
}

.dashboard-box{
    background:white;
    border-radius:15px;
    padding:40px;
    text-align:center;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

.dashboard-box img{
    max-width:100%;
    margin-top:20px;
}

/* CTA */

.cta{
    background:#2563eb;
    color:white;
    text-align:center;
    padding:80px 20px;
}

.cta .btn{
    background:white;
    color:#2563eb;
    margin-top:20px;
}

/* Footer */

footer{
    background:#1e293b;
    color:white;
    text-align:center;
    padding:20px;
}

/* Mobile Responsive */

@media(max-width:768px){

    .hero-content{
        flex-direction:column;
        text-align:center;
    }

    .hero-text h1{
        font-size:32px;
    }

    .stats-grid,
    .feature-grid,
    .steps{
        grid-template-columns:1fr;
    }

    .nav-container{
        flex-direction:column;
        gap:15px;
    }

    .nav-links{
        flex-wrap:wrap;
        justify-content:center;
    }

    .btn-outline{
        margin-left:0;
        margin-top:10px;
        display:inline-block;
    }
}

</style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="container nav-container">

        <a href="index.php" class="logo">
            LostDemand AI
        </a>

        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#works">How It Works</a>
            <a href="#analytics">Analytics</a>
            <a href="login.php" class="login-btn">Login</a>
        </div>

    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container hero-content">

        <div class="hero-text">
            <h1>Stop Guessing What Customers Want</h1>

            <p>
                Track products customers ask for but you don't have in stock.
                Convert missed opportunities into valuable business insights.
            </p>

            <a href="#" class="btn btn-white">Request Demo</a>
            <a href="login.php" class="btn btn-outline">Login</a>
        </div>

        <div class="hero-image">
            <img src="assets/images/logo.png" alt="Logo">
        </div>

    </div>
</section>

<!-- Statistics -->
<section class="stats">
    <div class="container">

        <div class="stats-grid">

            <div>
                <h2>₹5L+</h2>
                <p>Revenue Loss Tracked</p>
            </div>

            <div>
                <h2>10K+</h2>
                <p>Customer Requests Recorded</p>
            </div>

            <div>
                <h2>500+</h2>
                <p>Business Insights Generated</p>
            </div>

        </div>

    </div>
</section>

<!-- Features -->
<section class="features" id="features">

    <div class="container">

        <div class="section-title">
            <h2>Powerful Features</h2>
            <p>Transform hidden demand into actionable intelligence.</p>
        </div>

        <div class="feature-grid">

            <div class="feature-card">
                <i class="fas fa-clipboard-list"></i>
                <h3>Lost Demand Tracking</h3>
                <p>Record unavailable products instantly.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>Revenue Analysis</h3>
                <p>Calculate estimated revenue losses.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-lightbulb"></i>
                <h3>Stock Suggestions</h3>
                <p>Know exactly what products to stock.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-brain"></i>
                <h3>Demand Intelligence</h3>
                <p>Discover trends and buying patterns.</p>
            </div>

        </div>

    </div>

</section>

<!-- How It Works -->
<section class="how-it-works" id="works">

    <div class="container">

        <div class="section-title">
            <h2>How It Works</h2>
        </div>

        <div class="steps">

            <div class="step">
                <div class="step-number">1</div>
                <h3>Customer Request</h3>
                <p>Customer asks for unavailable product.</p>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <h3>Record Demand</h3>
                <p>Employee enters request details.</p>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <h3>Analyze Trends</h3>
                <p>System identifies demand patterns.</p>
            </div>

            <div class="step">
                <div class="step-number">4</div>
                <h3>Take Action</h3>
                <p>Owner gets recommendations.</p>
            </div>

        </div>

    </div>

</section>

<!-- Dashboard Preview -->
<section class="dashboard" id="analytics">

    <div class="container">

        <div class="dashboard-box">

            <h2>Business Intelligence Dashboard</h2>

            <p>
                Monitor lost sales, product demand,
                revenue leakage and stock opportunities.
            </p>

            <img src="assets/images/dashboard.png" alt="Dashboard">

        </div>

    </div>

</section>

<!-- CTA -->
<section class="cta">

    <h2>Ready to Uncover Hidden Customer Demand?</h2>

    <p>
        Start tracking missed sales opportunities today.
    </p>

    <a href="login.php" class="btn">
        Start Tracking
    </a>

</section>

<footer>
    © 2026 Lost Demand Intelligence System. All Rights Reserved.
</footer>

</body>
</html>