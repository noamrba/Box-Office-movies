<?php
// Start connection
$conn = mysqli_connect("localhost", "root", "", "cyberx");
if (!$conn) {
    echo mysqli_connect_error();
    exit;
}

//operation
$movie_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$query = "SELECT * FROM `movieinfo` WHERE `id` = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param($stmt, "i", $movie_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $id, $rank, $name, $revenue, $date, $image, $summary);

if (mysqli_stmt_fetch($stmt)) {
    $movie_row = array(
        'id' => $id,
        'rank' => $rank,
        'name' => $name,
        'revenue' => $revenue,
        'date' => $date,
        'image' => $image,
        'summary' => $summary
    );
} else {
    echo "<p>Movie not found.</p>";
    exit;
}

mysqli_stmt_close($stmt);

$query_c = "SELECT * FROM `comments` WHERE `movie_id`= ?";
$stmt_c = mysqli_prepare($conn, $query_c);
mysqli_stmt_bind_param($stmt_c, "i", $movie_id);
mysqli_stmt_execute($stmt_c);
mysqli_stmt_bind_result($stmt_c, $movie_id, $comment_id, $comment, $username);

$comments = array();
while (mysqli_stmt_fetch($stmt_c)) {
    $comments[] = array(
        'movie_id' => $movie_id,
        'comment_id' => $comment_id,
        'comment' => $comment,
        'username' => $username
    );
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $query_cm = "INSERT INTO `comments` (movie_id, comment, username) VALUES ($movie_id, '$comment', '$username')";

    if (mysqli_query($conn, $query_cm)) {
        header("Location: movie_details.php?id=$movie_id");
        exit();
    } else {
        echo "<p>Error inserting comment: " . mysqli_error($conn) . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($movie_row) ? $movie_row['name'] : 'Movie Details' ?></title> 
    <link rel="stylesheet" href="movie_details.css">
</head>
<body>
<h2>Box Office</h2>
<?php if (isset($movie_row)){ ?>
    <div class="movie">
        <img src="<?= $movie_row['image'] ?>" alt="Movie Image"> 
        <div class="movie-info">
            <h3><?= $movie_row['name'] ?></h3> 
            <p><?= $movie_row['summary'] ?></p> 
            <p>Rank: <?= $movie_row['rank'] ?></p> 
            <p>Revenue: <?= $movie_row['revenue'] ?></p> 
            <p>Release Date: <?= $movie_row['date'] ?></p> 
            <div class="comments">
                <?php foreach ($comments as $row) { ?>
                    <p class="un"><?= $row['username'] ?></p> 
                    <p class="cm"><?= $row['comment'] ?></p> 
                <?php } ?>
                <input type="button" value="Add Comment" id="toggleButton" onclick="showCommentForm()">
                <form action="movie_details.php?id=<?=$movie_id?>" method="POST" id="commentForm" style="display: none;">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    
                    <label for="comment">Comment:</label>
                    <textarea id="comment" name="comment" required></textarea>
                    
                    <input type="submit" value="Submit Comment" name="submit">
                </form>                        
            </div>
        </div>
    </div>
<?php } else { ?>
    <p>No movie details available.</p>
<?php } ?>
<script src="movie_details.js"></script>
</body>
</html>
