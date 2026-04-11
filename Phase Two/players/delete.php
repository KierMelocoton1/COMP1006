<?php
/**
 * Delete Player Record
 */
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if ($id) {
    // Fetch to get image path for deletion
    $stmt = $pdo->prepare("SELECT image_path FROM players WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $player = $stmt->fetch();

    if ($player) {
        // Delete image file
        if ($player['image_path'] && file_exists('../uploads/' . $player['image_path'])) {
            unlink('../uploads/' . $player['image_path']);
        }
        // Delete record
        $stmt = $pdo->prepare("DELETE FROM players WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
    }
}

header("Location: index.php");
exit;
?>
