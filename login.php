<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $result = $conn->query("SELECT * FROM admin WHERE username='$username' AND password='$password'");

    if ($result->num_rows == 1) {
        $_SESSION['admin'] = $username;
        header("Location: index.php");
    } else {
        $error = "Invalid Login!";
    }
}
?>

<h2>Admin Login</h2>
<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
