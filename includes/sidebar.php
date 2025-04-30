<div class="dashboard-sidebar glass">
    <ul class="dashboard-menu">
        <li class="dashboard-menu-item">
            <a href="dashboard.php" class="dashboard-menu-link active">Dashboard</a>
        </li>
        <?php if ($role === 'admin' || $role ==='professor'): ?>
            <li class="dashboard-menu-item">
                <a href="add_project.php" class="dashboard-menu-link">Add Project</a>
            </li>
        <?php endif; ?>
        <li class="dashboard-menu-item">
            <a href="edit_profile.php" class="dashboard-menu-link">Edit Profile</a>
        </li>
        
        <?php if ($role === 'admin'): ?>
            <li class="dashboard-menu-item">
                <a href="manage_users.php" class="dashboard-menu-link">Admin: Manage Users</a>
            </li>
            <li class="dashboard-menu-item">
                <a href="manage_projects.php" class="dashboard-menu-link">Admin: Manage Projects</a>
            </li>
            <li class="dashboard-menu-item">
                <a href="manage_messages.php" class="dashboard-menu-link">Admin: Manage Messages</a>
            </li>
        <?php endif; ?>
        <li class="dashboard-menu-item">
            <a href="logout.php" class="dashboard-menu-link logout">Logout</a>
        </li>
    </ul>
</div>