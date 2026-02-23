<?php
require_once 'components/auth.php';
$auth = new Auth();
$auth->requireAdmin();

$db    = (new Database())->connect();
$error = '';
$id    = (int)($_GET['id'] ?? 0);

if (!$id) { header("Location: admin_users.php"); exit; }

$stmt = $db->prepare("SELECT * FROM usermanagement WHERE id = :id");
$stmt->execute([':id' => $id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: admin_users.php?error=" . urlencode('User not found.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $db->prepare("
            UPDATE usermanagement SET
                firstname      = :firstname,
                lastname       = :lastname,
                email          = :email,
                gender         = :gender,
                nationality    = :nationality,
                contact_number = :contact_number,
                role           = :role
            WHERE id = :id
        ");
        $stmt->execute([
            ':firstname'      => trim($_POST['firstname']      ?? ''),
            ':lastname'       => trim($_POST['lastname']       ?? ''),
            ':email'          => trim($_POST['email']          ?? ''),
            ':gender'         => trim($_POST['gender']         ?? ''),
            ':nationality'    => trim($_POST['nationality']    ?? ''),
            ':contact_number' => trim($_POST['contact_number'] ?? ''),
            ':role'           => trim($_POST['role']           ?? 'user'),
            ':id'             => $id,
        ]);
        header("Location: admin_users.php?success=" . urlencode('User updated successfully.'));
        exit;
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
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style/style.css">
</head>
<body class="dashboard-body">

<nav class="dash-navbar">
    <span class="brand">Sean</span>
    <div class="nav-right">
        <a href="admin_users.php" class="nav-btn">← Back</a>
        <a href="logout.php" class="nav-btn">Logout</a>
    </div>
</nav>

<div class="sidebar">
    <div class="sidebar-label">Menu</div>
    <a href="admin_users.php">• Dashboard</a>
    <a href="admin_users.php?role=user">• Users</a>
    <a href="profile.php">• Profile</a>
</div>

<div class="main-content with-sidebar">
    <h5 class="page-title">Edit User — <?= htmlspecialchars($user['username']) ?></h5>

    <?php if ($error): ?>
        <div class="alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="dash-card" style="max-width:580px;">
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
                <div class="col-12">
                    <label class="dash-label">Email</label>
                    <input type="email" name="email" class="dash-input"
                        value="<?= htmlspecialchars($user['email']) ?>" required/>
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
                <div class="col-6">
                    <label class="dash-label">Contact Number</label>
                    <input type="text" name="contact_number" class="dash-input"
                        value="<?= htmlspecialchars($user['contact_number']) ?>" required/>
                </div>
                <div class="col-6">
                    <label class="dash-label">Role</label>
                    <select name="role" class="dash-select">
                        <option value="user"  <?= $user['role'] === 'user'  ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:10px; margin-top:20px;">
                <button type="submit" class="dash-btn dash-btn-dark">Save Changes</button>
                <a href="admin_users.php" class="dash-btn dash-btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>