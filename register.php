<?php
require_once 'components/auth.php';

$error   = $_GET['error']      ?? '';
$success = isset($_GET['registered']) ? 'Account created! You can now login.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $register = new Register();
    $result   = $register->register([
        'firstname'      => trim($_POST['firstname']      ?? ''),
        'lastname'       => trim($_POST['lastname']       ?? ''),
        'username'       => trim($_POST['username']       ?? ''),
        'email'          => trim($_POST['email']          ?? ''),
        'gender'         => trim($_POST['gender']         ?? ''),
        'nationality'    => trim($_POST['nationality']    ?? ''),
        'contact_number' => trim($_POST['contact_number'] ?? ''),
        'password'       => trim($_POST['password']       ?? ''),
        'confirm'        => trim($_POST['confirm']        ?? ''),
    ]);

    if ($result['success']) {
        header("Location: login.php?registered=1");
        exit;
    } else {
        header("Location: register.php?error=" . urlencode($result['message']));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="Style/style.css">
</head>
<body>

<div class="register-wrapper">
    <div class="register-card">

        <div class="brand text-center mb-4">
            <div class="brand-icon mx-auto mb-3">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h2>Create Account</h2>
            <p class="text-muted small mt-1">Fill in the details below to get started</p>
        </div>

        <?php if ($error): ?>
            <div class="alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label" for="firstname">First Name</label>
                    <div class="input-wrap">
                        <input type="text" id="firstname" name="firstname"
                            class="custom-input" placeholder="Maria" required/>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label" for="lastname">Last Name</label>
                    <div class="input-wrap">
                        <input type="text" id="lastname" name="lastname"
                            class="custom-input" placeholder="Cruz" required/>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <div class="input-wrap">
                    <input type="text" id="username" name="username"
                        class="custom-input" placeholder="mcruz_admin" required/>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-wrap">
                    <input type="email" id="email" name="email"
                        class="custom-input" placeholder="you@example.com" required/>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label" for="gender">Gender</label>
                    <div class="input-wrap">
                        <select id="gender" name="gender" class="custom-input custom-select" required>
                            <option value="" disabled selected>Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label" for="nationality">Nationality</label>
                    <div class="input-wrap">
                        <input type="text" id="nationality" name="nationality"
                            class="custom-input" placeholder="Filipino" required/>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="contact">Contact Number</label>
                <div class="input-wrap">
                    <input type="text" id="contact" name="contact_number"
                        class="custom-input" placeholder="09171234501" required/>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrap">
                    <input type="password" id="password" name="password"
                        class="custom-input" placeholder="Min. 8 characters" required/>
                    <span class="eye-toggle" onclick="togglePw('password','eye1')">
                        <i class="bi bi-eye" id="eye1"></i>
                    </span>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label" for="confirm">Confirm Password</label>
                <div class="input-wrap">
                    <input type="password" id="confirm" name="confirm"
                        class="custom-input" placeholder="Repeat your password" required/>
                    <span class="eye-toggle" onclick="togglePw('confirm','eye2')">
                        <i class="bi bi-eye" id="eye2"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn-register w-100">
                <i class="bi bi-person-check-fill me-2"></i>Create Account
            </button>
        </form>

        <div class="divider my-3">or</div>
        <p class="text-center small text-muted mb-0">
            Already have an account? <a href="login.php" class="login-link">Sign in</a>
        </p>
    </div>
    <p class="foot-note">&copy; <?= date('Y') ?> MyApp. All rights reserved.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePw(inputId, eyeId) {
        const inp = document.getElementById(inputId);
        const eye = document.getElementById(eyeId);
        inp.type = inp.type === 'password' ? 'text' : 'password';
        eye.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    }
</script>
</body>
</html>