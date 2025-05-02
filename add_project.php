<?php 
include "includes/header.php"; 
?>

<div class="container mt-5">
    <h2>Add New Project</h2>

    <!-- BASIC FORM FOR PROJECT ADD -->
    <form action="add_project_process.php" method="POST" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="title">Project Title</label>
            <input type="text" class="form-control" name="title" id="title" required>
        </div>

        <div class="form-group mb-3">
  <label for="thumbnail">Thumbnail Image</label>
  <input type="file" class="form-control" name="thumbnail" id="thumbnail" accept="image/*">
  <?php if (!empty($project['thumbnail'])): ?>
    <small class="text-light">
      Current: <?= htmlspecialchars($project['thumbnail']) ?>
    </small>
  <?php endif; ?>
</div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="college">College</label>
            <select name="college" id="college" class="form-control" >
                <option value="">-- Select Title --</option>
                <option value="College of Arts & Sciences">College of Arts & Sciences</option>
                <option value="College of Business & Information Systems">College of Business & Information Systems</option>
                <option value="The Beacom College of Computer & Cyber Sciences">The Beacom College of Computer & Cyber Sciences</option>
                <option value="College of Education and Human Performance">College of Education and Human Performance</option>
            </select>
        </div>
    <!-- EASIER TO COMPARE EMAIL VS. NAME -->
        <div class="form-group mb-3">
            <label for="faculty_email">Faculty Lead Email</label>
            <input type="email" class="form-control" name="faculty_email" id="faculty_email" required>
        </div>

        <button type="submit" class="btn btn-secondary">Add Project</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>
