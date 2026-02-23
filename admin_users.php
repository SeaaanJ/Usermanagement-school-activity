<?php
require_once 'components/auth.php';
$auth = new Auth();
$auth->requireAdmin();

$db = (new Database())->connect();
$me = $auth->user();

$search = trim($_GET['search'] ?? '');
$role   = trim($_GET['role']   ?? '');

$query  = "SELECT id, username, email, role, firstname, lastname, gender, nationality, contact_number, created_at FROM usermanagement WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (username LIKE :search OR email LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}
if ($role) {
    $query .= " AND role = :role";
    $params[':role'] = $role;
}
$query .= " ORDER BY id";

$stmt = $db->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style/style.css">
</head>
<body class="dashboard-body">

<!-- Navbar -->
<nav class="dash-navbar">
    <span class="brand">Sean</span>
    <div class="nav-right">
        <span class="nav-user">Admin: <?= htmlspecialchars($me['username']) ?></span>
        <a href="logout.php" class="nav-btn">Logout</a>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-label">Menu</div>
    <a href="admin_users.php" <?= !isset($_GET['role']) && !isset($_GET['search']) ? 'class="active"' : '' ?>>• Dashboard</a>
    <a href="admin_users.php?role=user" <?= ($_GET['role'] ?? '') === 'user' ? 'class="active"' : '' ?>>• Users</a>
    <a href="profile.php">• Profile</a>
</div>

<!-- Main Content -->
<div class="main-content with-sidebar">
    <h5 class="page-title">ADMIN VIEW — Users Management Dashboard</h5>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert-success">✅ <?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert-error">⚠️ <?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- Filter Bar -->
    <form method="GET" action="" class="filter-bar mb-3">
        <div>
            <label>Search</label>
            <input type="text" name="search" placeholder="username / email"
                value="<?= htmlspecialchars($search) ?>"/>
        </div>
        <div>
            <label>Role</label>
            <select name="role">
                <option value="">All</option>
                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="user"  <?= $role === 'user'  ? 'selected' : '' ?>>User</option>
            </select>
        </div>
        <button type="submit" class="dash-btn dash-btn-dark">Search</button>
        <a href="admin_users.php" class="dash-btn dash-btn-outline">Clear</a>
    </form>

    <!-- Add User -->
    <div class="mb-3">
        <a href="admin_user_create.php" class="dash-btn dash-btn-dark">+ Add User</a>
    </div>

    <!-- Table -->
    <div class="dash-card" style="padding:0; overflow:hidden;">
        <table class="dash-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><span class="role-badge <?= $user['role'] ?>"><?= $user['role'] ?></span></td>
                    <td><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></td>
                    <td style="display:flex; gap:6px;">
                        <a href="admin_user_edit.php?id=<?= $user['id'] ?>" class="dash-btn dash-btn-warning">Edit</a>
                        <?php if ($user['id'] != $me['id']): ?>
                            <a href="admin_user_delete.php?id=<?= $user['id'] ?>"
                               class="dash-btn dash-btn-danger"
                               onclick="return confirm('Delete this user?')">Delete</a>
                        <?php else: ?>
                            <span class="dash-btn dash-btn-outline" style="opacity:.5; cursor:default;">You</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?>
                    <tr><td colspan="6" style="text-align:center; padding:20px; color:#aaa;">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>