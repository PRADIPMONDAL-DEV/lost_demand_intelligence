
<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

session_start();

if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Lost Demand Intelligence</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#2563eb,#1e40af);
    padding:20px;
}

.login-card{
    width:100%;
    max-width:420px;
    background:#ffffff;
    border-radius:20px;
    padding:35px;
    box-shadow:0 15px 35px rgba(0,0,0,0.15);
}

.logo{
    text-align:center;
    margin-bottom:30px;
}

.logo i{
    font-size:55px;
    color:#2563eb;
    margin-bottom:10px;
}

.logo h2{
    color:#2563eb;
    margin-bottom:5px;
}

.logo p{
    color:#666;
    font-size:14px;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#333;
}

.input-box{
    display:flex;
    align-items:center;
    border:1px solid #ddd;
    border-radius:10px;
    overflow:hidden;
}

.input-box i{
    width:50px;
    text-align:center;
    color:#2563eb;
    background:#f8fafc;
}

.input-box input{
    flex:1;
    border:none;
    outline:none;
    padding:14px;
    font-size:15px;
}

.options{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
    font-size:14px;
}

.options a{
    text-decoration:none;
    color:#2563eb;
    font-weight:600;
}

.login-btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    background:#2563eb;
    color:white;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.login-btn:hover{
    background:#1d4ed8;
}

.login-btn i{
    margin-right:8px;
}

.access{
    text-align:center;
    margin-top:20px;
    color:#777;
    font-size:14px;
}

.back-home{
    text-align:center;
    margin-top:20px;
}

.back-home a{
    text-decoration:none;
    color:#2563eb;
    font-weight:600;
}

.back-home i{
    margin-right:5px;
}

@media(max-width:480px){

    .login-card{
        padding:25px;
    }

    .options{
        flex-direction:column;
        gap:10px;
        align-items:flex-start;
    }

}
</style>
</head>

<body>

<?php if(isset($_GET['error'])) { ?>
<script>
window.onload = function(){
    alert("Please check your email or password.");
}
</script>
<?php } ?>

<div class="login-card">

    <div class="logo">
        <i class="fas fa-chart-line"></i>
        <h2>LostDemand AI</h2>
        <p>Sign in to your account</p>
    </div>

    <form action="loginProcess.php" method="POST">

        <div class="form-group">
            <label>Email Address</label>

            <div class="input-box">
                <i class="fas fa-envelope"></i>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required>
            </div>
        </div>

        <div class="form-group">
            <label>Password</label>

            <div class="input-box">
                <i class="fas fa-lock"></i>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required>
            </div>
        </div>

        <div class="options">

            <label>
                <input type="checkbox">
                Remember Me
            </label>

            <a href="forgot-password.php">
                Forgot Password?
            </a>

        </div>

        <button type="submit" class="login-btn">
            <i class="fas fa-sign-in-alt"></i>
            Login
        </button>

    </form>

    <div class="access">
        Employee & Admin Access
    </div>

    <div class="back-home">
        <a href="index.php">
            <i class="fas fa-arrow-left"></i>
            Back to Home
        </a>
    </div>

</div>

</body>
</html>

