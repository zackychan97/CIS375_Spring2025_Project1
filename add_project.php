<?php 
include "includes/header.php"; 
?>

<div class="container mt-5">
    <h2>Add New Project</h2>

    <!-- BASIC FORM FOR PROJECT ADD -->
    <form action="add_project_process.php" method="POST">
        <div class="form-group mb-3">
            <label for="title">Project Title</label>
            <input type="text" class="form-control" name="title" id="title" required>
        </div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="college">College</label>
            <select name="college" id="college" class="form-control" >
                <option value="">-- Select Title --</option>
                <option value="artsScience">College of Arts & Sciences</option>
                <option value="businessIS">College of Business & Information Systems</option>
                <option value="beacom">The Beacom College of Computer & Cyber Sciences</option>
                <option value="educHP">College of Education and Human Performance</option>
            </select>
        </div>
    <!-- EASIER TO COMPARE EMAIL VS. NAME -->
        <div class="form-group mb-3">
            <label for="faculty_email">Faculty Lead Email</label>
            <input type="email" class="form-control" name="faculty_email" id="faculty_email" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Project</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>
