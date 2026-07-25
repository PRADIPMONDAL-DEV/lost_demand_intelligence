<?php
include_once 'db.php';

$message = "";

if(isset($_POST['submit']))
{
    $email = trim($_POST['email']);

    $result = $conn->query("SELECT id FROM users WHERE email='$email'");

    if($result->num_rows > 0)
    {
        header("Location: reset-password.php?email=".$email);
        exit();
    }
    else
    {
        $message = "<div class='alert alert-danger'>Email not found.</div>";
    }
}
?>

<form method="POST">
    <?= $message ?>
    <input type="email" name="email" placeholder="Enter Email" required>
    <button type="submit" name="submit">Continue</button>
</form>