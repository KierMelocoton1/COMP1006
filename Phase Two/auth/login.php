<?php
/**
 * User Login
 */
require_once '../config/db.php';
require_once '../includes/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../players/index.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
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

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../players/index.php");
            exit;
        } else {
            $errors[] = "Invalid login credentials.";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow mt-4">
            <div class="card-header bg-dark text-white text-center"><h4>Login</h4></div>
            <div class="card-body">
                <?php if ($errors): ?>
                    <div class="alert alert-danger"><?php foreach($errors as $e) echo "<p class='mb-0'>$e</p>"; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username or Email</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Login</button>
                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="register.php">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
