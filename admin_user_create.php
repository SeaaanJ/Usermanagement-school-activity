<?php
require_once 'components/auth.php';
$auth = new Auth();
$auth->requireAdmin();

$db    = (new Database())->connect();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'firstname'      => trim($_POST['firstname']      ?? ''),
        'lastname'       => trim($_POST['lastname']       ?? ''),
        'username'       => trim($_POST['username']       ?? ''),
        'email'          => trim($_POST['email']          ?? ''),
        'gender'         => trim($_POST['gender']         ?? ''),
        'nationality'    => trim($_POST['nationality']    ?? ''),
        'contact_number' => trim($_POST['contact_number'] ?? ''),
        'role'           => trim($_POST['role']           ?? 'user'),
        'password'       => trim($_POST['password']       ?? ''),
    ];

    if (in_array('', $fields)) {
        $error = 'All fields are required.';
    } else {
        try {
            $check = $db->prepare("SELECT id FROM usermanagement WHERE username = :u OR email = :e LIMIT 1");
            $check->execute([':u' => $fields['username'], ':e' => $fields['email']]);
            if ($check->fetch()) {
                $error = 'Username or email already taken.';
            } else {
                $stmt = $db->prepare("
                    INSERT INTO usermanagement (firstname, lastname, username, email, gender, nationality, contact_number, role, password_hash)
                    VALUES (:firstname, :lastname, :username, :email, :gender, :nationality, :contact_number, :role, :password_hash)
                ");
                $stmt->execute([
                    ':firstname'      => $fields['firstname'],
                    ':lastname'       => $fields['lastname'],
                    ':username'       => $fields['username'],
                    ':email'          => $fields['email'],
                    ':gender'         => $fields['gender'],
                    ':nationality'    => $fields['nationality'],
                    ':contact_number' => $fields['contact_number'],
                    ':role'           => $fields['role'],
                    ':password_hash'  => password_hash($fields['password'], PASSWORD_DEFAULT),
                ]);
                header("Location: admin_users.php?success=" . urlencode('User created successfully.'));
                exit;
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
    <title>Create User</title>
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


    <?php if ($error): ?>
        <div class="alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="main-content with-sidebar">
    <div style="max-width:580px; margin: 0 auto;">

        <h5 class="page-title">Add New User</h5>

        <?php if ($error): ?>
            <div class="alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="dash-card">
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="dash-label">First Name</label>
                        <input type="text" name="firstname" class="dash-input" required/>
                    </div>
                    <div class="col-6">
                        <label class="dash-label">Last Name</label>
                        <input type="text" name="lastname" class="dash-input" required/>
                    </div>
                    <div class="col-6">
                        <label class="dash-label">Username</label>
                        <input type="text" name="username" class="dash-input" required/>
                    </div>
                    <div class="col-6">
                        <label class="dash-label">Email</label>
                        <input type="email" name="email" class="dash-input" required/>
                    </div>
                    <div class="col-6">
                        <label class="dash-label">Gender</label>
                        <select name="gender" class="dash-select" required>
                            <option value="" disabled selected>Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="dash-label">Nationality</label>
                        <input type="text" name="nationality" class="dash-input" required/>
                    </div>
                    <div class="col-6">
                        <label class="dash-label">Contact Number</label>
                        <input type="text" name="contact_number" class="dash-input" required/>
                    </div>
                    <div class="col-6">
                        <label class="dash-label">Role</label>
                        <select name="role" class="dash-select">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="dash-label">Password</label>
                        <input type="password" name="password" class="dash-input" required/>
                    </div>
                </div>
                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit" class="dash-btn dash-btn-dark">Create User</button>
                    <a href="admin_users.php" class="dash-btn dash-btn-outline">Cancel</a>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>