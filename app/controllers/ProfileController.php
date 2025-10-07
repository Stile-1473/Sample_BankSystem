<?php
// Creator: ghost1473

class ProfileController extends Controller {

    public function editProfile() {
        session_start();
        if (!isset($_SESSION['user'])) header('Location: index.php?url=auth/login');
        $userModel = $this->model('User');
        $user = $userModel->findByUsername($_SESSION['user']['username']);
        require '../app/views/profile/editProfile.php';
    }

    public function updateProfile() {
        session_start();
        if (!isset($_SESSION['user'])) header('Location: index.php?url=auth/login');
        $userModel = $this->model('User');
        $user = $_SESSION['user'];
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address!';
            require '../app/views/profile/editProfile.php';
            return;
        }
        if (!preg_match('/^[0-9\-\+]{9,15}$/', $phone)) {
            $error = 'Invalid phone number!';
            require '../app/views/profile/editProfile.php';
            return;
        }
        $userModel->updateProfile($user['id'], $email, $phone);
        $success = 'Profile updated!';
        $audit =$this -> model('AuditLog')->log($user['id'],"Profile update","New email : " .$email. " ...New phone number :" . $phone);
        $user = $userModel->findByUsername($user['username']);
        require '../app/views/profile/editProfile.php';
    }
    public function changePassword() {
        session_start();
        if (!isset($_SESSION['user'])) header('Location: index.php?url=auth/login');
        require_once '../app/views/profile/changePassword.php';
    }
    public function updatePassword() {
        session_start();
        if (!isset($_SESSION['user'])) header('Location: index.php?url=auth/login');
        $userModel = $this->model('User');
        $user = $_SESSION['user'];
        $old = $_POST['old_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        if (!$userModel->findByUsername($user['username']) || !password_verify($old, $user['password'])) {
            echo "<script>showPopup('Old password incorrect!'); setTimeout(()=>{window.location='index.php?url=profile/changePassword'}, 1500);</script>";
            return;
        }
        if ($new !== $confirm) {
            echo "<script>showPopup('Passwords do not match!'); setTimeout(()=>{window.location='index.php?url=profile/changePassword'}, 1500);</script>";
            return;
        }
        $stmt = (new Database())->getConnection()->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([password_hash($new, PASSWORD_DEFAULT), $user['id']]);
        echo "<script>showPopup('Password changed!','success'); setTimeout(()=>{window.location='index.php?url=dashboard/index'}, 1500);</script>";
    }

    
}
