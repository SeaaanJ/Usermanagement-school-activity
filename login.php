<?php
require_once 'components/auth.php';

$auth    = new Auth();
$error   = $_GET['error']      ?? '';
$success = isset($_GET['registered']) ? 'Account created! You can now login.' : '';

if ($auth->isLoggedIn()) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_users.php");
    } else {
        header("Location: profile.php");
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->login(
        trim($_POST['login']    ?? ''),
        trim($_POST['password'] ?? '')
    );

    if ($result['success']) {
        if ($_SESSION['role'] === 'admin') {
            header("Location: admin_users.php");
        } else {
            header("Location: profile.php");
        }
        exit;
    } else {
        header("Location: login.php?error=" . urlencode($result['message']));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Style/style.css">
</head>
<body>

<div class="card">
    <div class="header">
        <div class="dot-logo"><span></span></div>
        <h1>Welcome back</h1>
        <p class="sub">Sign in to your account</p>
    </div>

    <?php if ($error): ?>
        <div class="alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert-success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="field">
            <label for="login">Email or Username</label>
            <input type="text" id="login" name="login"
                placeholder="you@example.com"
                value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"
                autocomplete="username" required/>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <div class="pw-wrap">
                <input type="password" id="password" name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password" required/>
                <button type="button" class="eye-btn" onclick="togglePw()">
                    <span id="eye">👁</span>
                </button>
            </div>
        </div>
        <div class="row-opts">
            <label class="check-label">
                <input type="checkbox" name="remember"/> Remember me
            </label>
            <a href="#" class="link">Forgot password?</a>
        </div>
        <button type="submit" class="btn">Sign In</button>
    </form>

    <div class="divider">or</div>
    <p class="register">Don't have an account? <a href="register.php">Create one</a></p>
</div>

<script>
    function togglePw() {
        const inp = document.getElementById('password');
        const eye = document.getElementById('eye');
        inp.type = inp.type === 'password' ? 'text' : 'password';
        eye.textContent = inp.type === 'password' ? '👁' : '🙈';
    }
</script>
</body>
</html>