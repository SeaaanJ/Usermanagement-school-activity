<?php
require_once 'components/auth.php';
$auth    = new Auth();
$auth->requireLogin();

$db      = (new Database())->connect();
$me      = $auth->user();
$isAdmin = $_SESSION['role'] === 'admin';
$error   = '';
$success = '';

$stmt = $db->prepare("SELECT * FROM usermanagement WHERE id = :id");
$stmt->execute([':id' => $me['id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $db->prepare("
            UPDATE usermanagement SET
                firstname      = :firstname,
                lastname       = :lastname,
                gender         = :gender,
                nationality    = :nationality,
                contact_number = :contact_number
            WHERE id = :id
        ");
        $stmt->execute([
            ':firstname'      => trim($_POST['firstname']      ?? ''),
            ':lastname'       => trim($_POST['lastname']       ?? ''),
            ':gender'         => trim($_POST['gender']         ?? ''),
            ':nationality'    => trim($_POST['nationality']    ?? ''),
            ':contact_number' => trim($_POST['contact_number'] ?? ''),
            ':id'             => $me['id'],
        ]);
        $_SESSION['firstname'] = trim($_POST['firstname'] ?? '');
        $_SESSION['lastname']  = trim($_POST['lastname']  ?? '');
        $success = 'Profile updated successfully.';

        $stmt = $db->prepare("SELECT * FROM usermanagement WHERE id = :id");
        $stmt->execute([':id' => $me['id']]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style/style.css">
</head>
<body class="dashboard-body">

<nav class="dash-navbar">
    <span class="brand">Sean</span>
    <div class="nav-right">
        <span class="nav-user"><?= $isAdmin ? 'Admin' : 'User' ?>: <?= htmlspecialchars($me['username']) ?></span>
        <?php if ($isAdmin): ?>
            <a href="admin_users.php" class="nav-btn nav-btn-accent">← Dashboard</a>
        <?php endif; ?>
        <a href="logout.php" class="nav-btn">Logout</a>
    </div>
</nav>

<?php if ($isAdmin): ?>
<div class="sidebar">
    <div class="sidebar-label">Menu</div>
    <a href="admin_users.php">• Dashboard</a>
    <a href="admin_users.php?role=user">• Users</a>
    <a href="profile.php" class="active">• Profile</a>
</div>
<?php endif; ?>

<div class="main-content <?= $isAdmin ? 'with-sidebar' : '' ?> py-4">
    <div style="max-width:640px; margin: 0 auto;">

        <h5 class="page-title">
            <?= $isAdmin ? 'Admin Profile' : 'USER VIEW — My Account (Own Data Only)' ?>
        </h5>

        <?php if ($error): ?>
            <div class="alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Profile Card -->
        <div class="dash-card">
            <h6>Profile Card</h6>
            <div class="profile-info">
                <p><strong>Name</strong> <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></p>
                <p><strong>Username</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>Email</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Role</strong>
                    <span class="role-badge <?= $user['role'] ?>"><?= $user['role'] ?></span>
                    <span style="font-size:.75rem; color:#aaa;">(read-only)</span>
                </p>
            </div>
        </div>

        <!-- Edit Details -->
        <div class="dash-card">
            <h6>Edit My Details Form</h6>
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="dash-label">First Name</label>
                        <input type="text" name="firstname" class="dash-input"
                            value="<?= htmlspecialchars($user['firstname']) ?>" required/>
                    </div>
                    <div class="col-6">
                        <label class="dash-label">Last Name</label>
                        <input type="text" name="lastname" class="dash-input"
                            value="<?= htmlspecialchars($user['lastname']) ?>" required/>
                    </div>
                    <div class="col-6">
                        <label class="dash-label">Gender</label>
                        <select name="gender" class="dash-select">
                            <option value="male"   <?= $user['gender'] === 'male'   ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= $user['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                            <option value="other"  <?= $user['gender'] === 'other'  ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="dash-label">Nationality</label>
                        <input type="text" name="nationality" class="dash-input"
                            value="<?= htmlspecialchars($user['nationality']) ?>" required/>
                    </div>
                    <div class="col-12">
                        <label class="dash-label">Contact Number</label>
                        <input type="text" name="contact_number" class="dash-input"
                            value="<?= htmlspecialchars($user['contact_number']) ?>" required/>
                    </div>
                </div>
                <div style="margin-top:16px;">
                    <button type="submit" class="dash-btn dash-btn-dark">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="dash-card">
            <h6>Change Password</h6>
            <a href="change_password.php" class="dash-btn dash-btn-outline">Go to Change Password</a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>