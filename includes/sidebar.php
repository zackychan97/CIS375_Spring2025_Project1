

<div class="col-md-3">
    <div class="list-group">
        <a href="dashboard.php" class="list-group-item list-group-item-action active">Dashboard</a>
        <a href="projects.php" class="list-group-item list-group-item-action">Browse Projects</a>
        <?php if ($role === 'admin' || $role ==='professor'): ?>
            <a href="add_project.php" class="list-group-item list-group-item-action">Add Project</a>
        <?php endif; ?>
        <a href="edit_profile.php" class="list-group-item list-group-item-action">Edit Profile</a>
        
        <?php if ($role === 'admin'): ?>
            <a href="manage_users.php" class="list-group-item list-group-item-action text-info">Admin: Manage Users</a>
            <a href="manage_projects.php" class="list-group-item list-group-item-action text-info">Admin: Manage Projects</a>
            <a href="manage_messages.php" class="list-group-item list-group-item-action text-info">Admin: Manage Messages</a>
        <?php endif; ?>
        <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
    </div>
</div>