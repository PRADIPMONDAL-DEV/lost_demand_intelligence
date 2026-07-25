<?php
include_once 'db.php';

$email = $_GET['email'] ?? '';

if(isset($_POST['reset']))
{
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "UPDATE users SET password=? WHERE email=?"
    );

    $stmt->bind_param("ss",$newPassword,$email);

    if($stmt->execute())
    {
        header("Location: login.php?reset=success");
        exit();
    }
}
?>

<form method="POST">

    <input
        type="password"
        name="password"
        placeholder="New Password"
        required>

    <button type="submit" name="reset">
        Reset Password
    </button>

</form>