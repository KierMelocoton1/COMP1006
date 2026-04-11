<?php
/**
 * Players List (Dashboard)
 * Displays records using jQuery DataTables with server-side processing.
 */
require_once '../config/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<div class="card shadow">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Player Management</h4>
        <a href="create.php" class="btn btn-light btn-sm">+ Add New Player</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="playersTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Position</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Team</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#playersTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "fetch.php",
            "type": "POST"
        },
        "columns": [
            { 
                "data": "image_path",
                "orderable": false,
                "render": function(data) {
                    if(data) return '<img src="../uploads/'+data+'" width="50" height="50" class="img-thumbnail rounded-circle" alt="Player">';
                    return '<div class="bg-secondary text-white text-center rounded-circle" style="width:50px; height:50px; line-height:50px;">N/A</div>';
                }
            },
            { "data": "first_name" },
            { "data": "last_name" },
            { "data": "position" },
            { "data": "phone" },
            { "data": "email" },
            { "data": "team_name" },
            { 
                "data": "id",
                "orderable": false,
                "render": function(data) {
                    return '<div class="btn-group">' +
                           '<a href="update.php?id=' + data + '" class="btn btn-sm btn-outline-primary">Edit</a>' +
                           '<a href="delete.php?id=' + data + '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Delete this player?\')">Delete</a>' +
                           '</div>';
                }
            }
        ],
        "order": [[1, "asc"]] // Sort by First Name initially
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
