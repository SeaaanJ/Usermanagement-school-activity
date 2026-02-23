<?php
require_once 'components/auth.php';
$auth = new Auth();
$auth->requireLogin();

$db      = (new Database())->connect();
$me      = $auth->user();
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = trim($_POST['current_password']  ?? '');
    $new     = trim($_POST['new_password']      ?? '');
    $confirm = trim($_POST['confirm_password']  ?? '');

    if (empty($current) || empty($new) || empty($confirm)) {
        $error = 'All fields are required.';
    } elseif (strlen($new) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } else {
        try {
            $stmt = $db->prepare("SELECT password_hash FROM usermanagement WHERE id = :id");
            $stmt->execute([':id' => $me['id']]);
            $user = $stmt->fetch();

            if (!password_verify($current, $user['password_hash'])) {
                $error = 'Current password is incorrect.';
            } else {
                $stmt = $db->prepare("UPDATE usermanagement SET password_hash = :hash WHERE id = :id");
                $stmt->execute([
                    ':hash' => password_hash($new, PASSWORD_DEFAULT),
                    ':id'   => $me['id'],
                ]);
                $success = 'Password changed successfully.';
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="Style/style.css">
</head>
<body>

<!-- Navbar -->
<!-- Navbar -->
<nav class="dash-navbar">
    <div class="nav-left">
        <span class="brand">Sean</span>
    </div>

    <div class="nav-right">
        <span class="nav-user">
            Admin: <?= htmlspecialchars($me['username']) ?>
        </span>

        <a href="profile.php" class="nav-btn">Profile</a>
        <a href="logout.php" class="nav-btn logout">Logout</a>
    </div>
</nav>


<div class="main-content container py-4" style="max-width:480px;">
    <h5 class="fw-bold mb-4">Change Password</h5>

    <?php if ($error): ?>
        <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success py-2"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-semibold">Current Password</label>
                <input type="password" name="current_password" class="form-control" required/>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">New Password</label>
                <input type="password" name="new_password" class="form-control" required/>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required/>
            </div>
            <button type="submit" class="btn btn-dark w-100">Update Password</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>