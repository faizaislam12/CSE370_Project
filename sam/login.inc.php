<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once 'dbh.inc.php'; // database connection
    require_once 'login_model.inc.php';
    require_once 'login_control.inc.php';

    $username = $_POST["username"];
    $pwd = $_POST["pwd"];
    $redirectTo = $_POST['redirect_to'] ?? '';

    $error = [];

    if (empty($username) || empty($pwd)) {
        $error[] = "Fill in all fields!";
    }

    $result = get_user($pdo, $username);

    if (!$result || !password_verify($pwd, $result['pwd'])) {
        $error[] = "Incorrect login info!";
    }

    if ($error) {
        $_SESSION['error_login'] = $error;
        header("Location: login_form.php" . ($redirectTo ? "?redirect=" . urlencode($redirectTo) : ""));
        exit();
    }

    // Successful login
    $_SESSION['user_id'] = $result['id'];
    $_SESSION['user_username'] = htmlspecialchars($result['username']);

    // Redirect
    if (!empty($redirectTo)) {
        header("Location: " . $redirectTo);
        exit();
    } else if (str_contains($result['email'], 'admin')) {
        header("Location: /sam/admin_dashboard.php");
        exit();
    } else {
        header("Location: /sam/passenger_dashboard.php");
        exit();
    }
} else {
    header("Location: login_form.php");
    exit();
}
