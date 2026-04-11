<?php
/**
 * Create New Player
 * Handles form validation and image file upload.
 */
require_once '../config/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $position = trim($_POST['position']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $team_name = trim($_POST['team_name']);
    $user_id = $_SESSION['user_id'];

    // Validation
    if (empty($first_name) || empty($last_name) || empty($position) || empty($phone) || empty($email) || empty($team_name)) {
        $errors[] = "All text fields are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Image Upload
    $image_path = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_ext, $allowed_exts)) {
            $errors[] = "Invalid image type. Allowed: JPG, JPEG, PNG, GIF.";
        } else {
            $new_name = uniqid('player_', true) . '.' . $file_ext;
            if (move_uploaded_file($file_tmp, '../uploads/' . $new_name)) {
                $image_path = $new_name;
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO players (first_name, last_name, position, phone, email, team_name, image_path, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$first_name, $last_name, $position, $phone, $email, $team_name, $image_path, $user_id])) {
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Database error. Please try again.";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white"><h4>Add New Player</h4></div>
            <div class="card-body">
                <?php if ($errors): ?>
                    <div class="alert alert-danger"><?php foreach($errors as $e) echo "<p class='mb-0'>$e</p>"; ?></div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" name="position" class="form-control" placeholder="e.g. Forward, Guard" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Team Name</label>
                            <input type="text" name="team_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Player Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">Save Player</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
