<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/pdo.php';

class Auth {

    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // LOGIN
    public function login(string $login, string $password): array {

        if (empty($login) || empty($password)) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        try {
            $stmt = $this->db->prepare("
                SELECT id, firstname, lastname, username, email, password_hash, role
                FROM usermanagement
                WHERE username = :username OR email = :email
                LIMIT 1
            ");
            $stmt->execute([
                ':username' => $login,
                ':email'    => $login,
            ]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {

                session_regenerate_id(true);

                $_SESSION['user_id']   = $user['id'];
                $_SESSION['name']      = $user['firstname'] . ' ' . $user['lastname'];
                $_SESSION['firstname'] = $user['firstname']; // ✅ added
                $_SESSION['lastname']  = $user['lastname'];  // ✅ added
                $_SESSION['username']  = $user['username'];
                $_SESSION['email']     = $user['email'];
                $_SESSION['role']      = $user['role'];      // ✅ added — THIS was missing
                $_SESSION['logged_in'] = true;

                return ['success' => true, 'message' => 'Login successful.'];

            } else {
                return ['success' => false, 'message' => 'Invalid username/email or password.'];
            }

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // CHECK IF LOGGED IN
    public function isLoggedIn(): bool {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    // REQUIRE LOGIN
    public function requireLogin(): void {
        if (!$this->isLoggedIn()) {
            header("Location: login.php");
            exit;
        }
    }

    // REQUIRE ADMIN
    public function requireAdmin(): void {
        $this->requireLogin();
        if ($_SESSION['role'] !== 'admin') {
            header("Location: profile.php");
            exit;
        }
    }

    // LOGOUT
    public function logout(): void {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }

    // GET CURRENT USER
    public function user(): array {
        return [
            'id'        => $_SESSION['user_id']  ?? null,
            'name'      => $_SESSION['name']      ?? null,
            'firstname' => $_SESSION['firstname'] ?? null, // ✅ added
            'lastname'  => $_SESSION['lastname']  ?? null, // ✅ added
            'username'  => $_SESSION['username']  ?? null,
            'email'     => $_SESSION['email']     ?? null,
            'role'      => $_SESSION['role']      ?? null, // ✅ added
        ];
    }
}
    



class Register {

    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function register(array $data): array {

        if (
            empty($data['firstname'])      ||
            empty($data['lastname'])       ||
            empty($data['username'])       ||
            empty($data['email'])          ||
            empty($data['gender'])         ||
            empty($data['nationality'])    ||
            empty($data['contact_number']) ||
            empty($data['password'])       ||
            empty($data['confirm'])
        ) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email address.'];
        }

        if (strlen($data['password']) < 8) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters.'];
        }

        if ($data['password'] !== $data['confirm']) {
            return ['success' => false, 'message' => 'Passwords do not match.'];
        }

        try {
            $check = $this->db->prepare("
                SELECT id FROM user
                WHERE username = :username OR email = :email 
                LIMIT 1
            ");
            $check->execute([
                ':username' => $data['username'],
                ':email'    => $data['email'],
            ]);

            if ($check->fetch()) {
                return ['success' => false, 'message' => 'Username or email is already taken.'];
            }

            $stmt = $this->db->prepare("
                INSERT INTO usermanagement 
                    (username, email, password_hash, role, firstname, lastname, gender, nationality, contact_number)
                VALUES 
                    (:username, :email, :password, 'user', :firstname, :lastname, :gender, :nationality, :contact_number)
            ");

            $stmt->execute([
                ':username'       => $data['username'],
                ':email'          => $data['email'],
                ':password'  => password_hash($data['password'], PASSWORD_DEFAULT),
                ':firstname'      => $data['firstname'],
                ':lastname'       => $data['lastname'],
                ':gender'         => $data['gender'],
                ':nationality'    => $data['nationality'],
                ':contact_number' => $data['contact_number'],
            ]);

            return ['success' => true, 'message' => 'Registration successful.'];

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}



?>