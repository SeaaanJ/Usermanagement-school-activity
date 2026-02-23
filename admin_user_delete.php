<?php
require_once 'components/auth.php';
$auth = new Auth();
$auth->requireAdmin();

$db  = (new Database())->connect();
$me  = $auth->user();
$id  = (int)($_GET['id'] ?? 0);

if (!$id) { header("Location: admin_users.php"); exit; }

if ($id === (int)$me['id']) {
    header("Location: admin_users.php?error=" . urlencode('You cannot delete your own account.'));
    exit;
}

try {
    $stmt = $db->prepare("DELETE FROM usermanagement WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: admin_users.php?success=" . urlencode('User deleted successfully.'));
    exit;
} catch (PDOException $e) {
    header("Location: admin_users.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>