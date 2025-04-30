
<?php
//NEEDS SOME SWEET STYLE

// START SESSION IF NOT ALREADY STARTED
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// FUNCTION TO HANDLE COMMENT SUBMISSION
function processCommentSubmission(mysqli $conn, int $projectId){
    // VERIFIES METHOD AND CHECKS IF COMMENT IS SET
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
        $commentText = trim($_POST['comment']);
        if ($commentText === '') {
            flashMessage('ERROR: Comment cannot be empty.');
        } else {
            // GRABS USER ID FROM SESSION
            $userId = $_SESSION['user_id'] ?? 0;
            $writeCommentQuery = "INSERT INTO project_comments (project_id, user_id, comment) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $writeCommentQuery);
                        mysqli_stmt_bind_param($stmt, 'iis', $projectId, $userId, $commentText);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            flashMessage('Comment posted!');
            header('Location: project.php?id=' . $projectId );
        }
        header('Location: project.php?id=' . $projectId );
        exit();
    }
}

// FUNCTION TO DISPLAY COMMENTS SECTION
function commentsSection(mysqli $conn, int $projectId) {
    // READ COMMENTS FROM DATABASE
    $readCommentQuery = "SELECT project_comments.comment, project_comments.created_at, users.name FROM project_comments 
         JOIN users  ON project_comments.user_id = users.id 
         WHERE project_comments.project_id = ? 
         ORDER BY project_comments.created_at ASC";
    $stmt = mysqli_prepare($conn, $readCommentQuery);
    mysqli_stmt_bind_param($stmt, 'i', $projectId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    echo '<div id="comments" class="comments-section mt-5">';
    echo '<h4>Comments</h4>';

    //PULL COMMENTS FROM DATABASE
    while ($commentsList = mysqli_fetch_assoc($result)) {
        echo '<div class="card mb-2"><div class="card-body p-2">';
        echo '<small class="text-muted">' . htmlspecialchars($commentsList['name'])
        . ' - ' . $commentsList['created_at']
            
             . '</small>';
        echo '<p class="mb-0">' . nl2br(htmlspecialchars($commentsList['comment'])) . '</p>';
        echo '</div></div>';
    }

    // CHECKS FOR USER SESSION TO ALLOW COMMENTING
    if (isset($_SESSION['user_id'])) {
        echo '<form method="POST" action="">';
        echo '<div class="form-group mb-2">';
        echo '<label for="comment">Leave a Comment:</label>';
        echo '<textarea id="comment" name="comment" class="form-control" rows="3" required></textarea>';
        echo '</div>';
        echo '<button type="submit" class="btn btn-success">Post Comment</button>';
        echo '</form>';
    } else {
        echo '<p><a href="login.php">Log in</a> to post a comment.</p>';
    }


    echo '</div>';
    mysqli_stmt_close($stmt);
}