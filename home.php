<?php
// Start connection
$conn = mysqli_connect("localhost", "root", "", "cyberx");
if (!$conn) {
    echo mysqli_connect_error();
    exit;
}

//operation
$query = "SELECT * FROM `movies`";
$search = "";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $query .= " WHERE `name` LIKE '%$search%'";
}

$result = mysqli_query($conn, $query);

$results = array();
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Movies</title>
</head>
<body>
    <h2>Box Office</h2>
    <form method="GET">
        <input type="text" name="search" placeholder="Enter name to search with" value="<?= $search ?>">
        <input type="submit" value="Search">
    </form>
    <?php if (isset($_GET['search'])) { ?>
        <p>Search Results for: <?= $search ?></p>
    <?php } ?>
    <div class="movies">
        <?php foreach ($results as $row) { ?>
            <div class="movie" id="<?= $row['id'] ?>"> 
                <img src="<?= $row['image'] ?>" alt="movie Image">
                <div class="movie-info">
                    <h3><?= $row['name'] ?></h3>
                    <p>Rank: <?= $row['rank'] ?></p>
                    <p>Revenue: <?= $row['revenue'] ?></p>
                    <p>Release Date: <?= $row['release_date'] ?></p>
                </div>
            </div>
        <?php } ?>
    </div>
    <script src="home.js"></script>
</body>
</html>
