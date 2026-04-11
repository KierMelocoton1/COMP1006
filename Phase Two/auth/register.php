<?php
/**
 * User Registration
 * Integrates reCAPTCHA and secure password hashing.
 */
require_once '../config/db.php';
require_once '../includes/header.php';

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // reCAPTCHA Validation using cURL
    $secret_key = 'YOUR_SECRET_KEY';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['secret' => $secret_key, 'response' => $recaptcha_response]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_data = json_decode($response);

    if (!$response_data->success) {
        $errors[] = "reCAPTCHA verification failed.";
    }

    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Username or Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $success = "Registration successful! <a href='login.php'>Login here</a>";
            } else {
                $errors[] = "An error occurred during registration.";
            }
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white"><h4>Register</h4></div>
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
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
