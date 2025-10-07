<?php
// Creator: ghost1473

session_start();
class AuthController extends Controller {
    public function login($error = null) {
        require_once '../app/views/auth/login.php';
    }
    public function register($error = null) {
        require_once '../app/views/auth/register.php';
    }


    public function handleLogin() {
        
        session_start();
        $userModel = $this->model('User');
        $failedLoginModel = $this->model('FailedLogin');
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $maxAttempts = 5;
        $attempts = $failedLoginModel->getAttempts($username, $ip);
        if ($attempts >= $maxAttempts) {
            $error = 'Account locked due to too many failed attempts. Try again later.';
            require '../app/views/auth/login.php';
            return;
        }
        $user = $userModel->findByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $failedLoginModel->reset($username, $ip);
            $this->model('AuditLog')->log($user['id'], 'login_success', '');
            header('Location: index.php?url=dashboard/index');
        } else {
            $failedLoginModel->record($username, $ip);
            $this->model('AuditLog')->log($user ? $user['id'] : null, 'login_failed', '');
            $remaining = $maxAttempts - $attempts - 1;
            $error = $remaining > 0 ? "Invalid credentials! Attempts left: $remaining" : "Account locked due to too many failed attempts.";
            require '../app/views/auth/login.php';
        }
    }
    public function handleRegister() {
        $userModel = $this->model('User');
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ??'';

        $role = $_POST['role'] ?? 'customer';
        if ($userModel->findByUsername($username)) {
            $error = 'Username already exists!';
            require '../app/views/auth/register.php';
            return;
        }
        if ($userModel->create($username, $password, $role, $email, $phone)) {
            // Log registration event
            $user = $userModel->findByUsername($username);
            $this->model('AuditLog')->log($user ? $user['id'] : null, 'registration', json_encode(['username'=>$username, 'role'=>$role]));
            $success = 'Registration successful! Please login.';
            require '../app/views/auth/register.php';
        } else {
            $error = 'Registration failed!';
            require '../app/views/auth/register.php';
        }
    }

    //logout
    public function logout() {
        session_start();
        session_destroy();
        header('Location: ../public/index.php');
    }

    // Show forgot password form
    public function forgotPassword() {
        require_once '../app/views/auth/forgotPassword.php';
    }

    // Handle forgot password: verify username + phone, then show reset view
    public function handleForgotPassword() {
        $userModel = $this->model('User');
        $username = $_POST['username'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $user = $userModel->findByUsernameAndPhone($username, $phone);
        if ($user) {
            session_start();
            $_SESSION['reset_user'] = $user['username'];
            $username = $user['username'];
            $success = 'Identity verified. Please set a new password.';
            require '../app/views/auth/resetPassword.php';
        } else {
            $error = 'No matching user found.';
            require '../app/views/auth/forgotPassword.php';
        }
    }


    // Handle reset password
    public function handleResetPassword() {
        $userModel = $this->model('User');
        $username = $_POST['username'] ?? '';
        $new = $_POST['new_password'] ?? '';
        if (strlen($new) < 6) {
            $error = 'Password must be at least 6 characters.';
            require '../app/views/auth/resetPassword.php';
            return;
        }
        $user = $userModel->findByUsername($username);
        if (!$user) {
            $error = 'User not found.';
            require '../app/views/auth/resetPassword.php';
            return;
        }
        $userModel->updatePassword((int)$user['id'], $new);
        session_start();
        unset($_SESSION['reset_user']);
        $success = 'Password reset successful. You can now login.';
        require '../app/views/auth/login.php';
    }
}
