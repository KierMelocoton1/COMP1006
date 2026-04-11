<?php
/**
 * Update Player Record
 */
require_once '../config/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// Fetch player data
$stmt = $pdo->prepare("SELECT * FROM players WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$player = $stmt->fetch();

if (!$player) {
    header("Location: index.php");
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

    if (empty($first_name) || empty($last_name) || empty($position) || empty($phone) || empty($email) || empty($team_name)) {
        $errors[] = "All fields are required.";
    }

    $image_path = $player['image_path'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed)) {
            // Remove old image
            if ($image_path && file_exists('../uploads/' . $image_path)) {
                unlink('../uploads/' . $image_path);
            }
            $new_name = uniqid('player_', true) . '.' . $file_ext;
            move_uploaded_file($file_tmp, '../uploads/' . $new_name);
            $image_path = $new_name;
        } else {
            $errors[] = "Invalid image type.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE players SET first_name = ?, last_name = ?, position = ?, phone = ?, email = ?, team_name = ?, image_path = ? WHERE id = ? AND user_id = ?");
        if ($stmt->execute([$first_name, $last_name, $position, $phone, $email, $team_name, $image_path, $id, $_SESSION['user_id']])) {
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Failed to update player.";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white"><h4>Edit Player Details</h4></div>
            <div class="card-body">
                <?php if ($errors): ?>
                    <div class="alert alert-danger"><?php foreach($errors as $e) echo "<p class='mb-0'>$e</p>"; ?></div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($player['first_name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($player['last_name']); ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" name="position" class="form-control" value="<?php echo htmlspecialchars($player['position']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($player['phone']); ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($player['email']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Team Name</label>
                            <input type="text" name="team_name" class="form-control" value="<?php echo htmlspecialchars($player['team_name']); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Player Image (leave blank to keep current)</label>
                        <?php if ($player['image_path']): ?>
                            <div class="mb-2">
                                <img src="../uploads/<?php echo $player['image_path']; ?>" width="100" class="img-thumbnail">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Player</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
