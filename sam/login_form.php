<?php
require_once 'config_session.inc.php';
$redirect_to = $_GET['redirect'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
<h1>Login</h1>
<form action="login.inc.php" method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="pwd" placeholder="Password" required><br>
    <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($redirect_to) ?>">
    <button>Login</button>
</form>
<p>Don't have an account? <a href="signup_form.php">Sign up</a></p>

<?php
// show login errors
if(isset($_SESSION['error_login'])){
    foreach($_SESSION['error_login'] as $err){
        echo "<p style='color:red;'>$err</p>";
    }
    unset($_SESSION['error_login']);
}
?>
</body>
</html>
