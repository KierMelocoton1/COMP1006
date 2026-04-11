<?php
/**
 * User Profile Management
 * Allows users to update their details or delete their account.
 */
require_once '../config/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = "";

// Fetch current user data
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $new_password = $_POST['new_password'];

        if (empty($username) || empty($email)) {
            $errors[] = "Username and Email are required.";
        }

        if (empty($errors)) {
            $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
            $params = [$username, $email, $user_id];
            
            if (!empty($new_password)) {
                $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
                $params = [$username, $email, password_hash($new_password, PASSWORD_BCRYPT), $user_id];
            }

            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($params)) {
                $_SESSION['username'] = $username;
                $success = "Profile updated successfully.";
                $user['username'] = $username;
                $user['email'] = $email;
            } else {
                $errors[] = "Failed to update profile.";
            }
        }
    } elseif (isset($_POST['delete_account'])) {
        // Cascading delete will remove their player records if DB is configured with ON DELETE CASCADE
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$user_id])) {
            session_destroy();
            header("Location: register.php?deleted=1");
            exit;
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-secondary text-white"><h4>My Profile</h4></div>
            <div class="card-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if ($errors): ?>
                    <div class="alert alert-danger"><?php foreach($errors as $e) echo "<p class='mb-0'>$e</p>"; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="new_password" class="form-control">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                        <hr>
                        <button type="submit" name="delete_account" class="btn btn-danger" onclick="return confirm('WARNING: Are you sure you want to delete your account? This action cannot be undone.')">Delete My Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
