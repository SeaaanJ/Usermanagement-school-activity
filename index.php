<?php
require_once 'components/auth.php';
$auth = new Auth();

if ($auth->isLoggedIn()) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? 'admin_users.php' : 'profile.php'));
} else {
    header("Location: login.php");
}
exit;
?>