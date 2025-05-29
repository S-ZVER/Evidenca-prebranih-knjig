<?php
require_once 'config.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$genre = isset($_GET['genre']) ? (int)$_GET['genre'] : '';

$query = "SELECT b.*, 
          AVG(ubs.rating) as avg_rating,
          COUNT(ubs.user_id) as total_readers,
          a.first_name as author_first_name,
          a.last_name as author_last_name,
          g.name as genre_name
          FROM books b
          LEFT JOIN users_books_status ubs ON b.books_id = ubs.books_id AND ubs.status = 'read'
          JOIN authors a ON b.authors_id = a.authors_id
          JOIN genres g ON b.genre_id = g.genre_id";

if ($search) {
    $query .= " WHERE b.title LIKE '%$search%' OR CONCAT(a.first_name, ' ', a.last_name) LIKE '%$search%'";
}

if ($genre) {
    $query .= $search ? " AND" : " WHERE";
    $query .= " g.genre_id = $genre";
}

$query .= " GROUP BY b.books_id ORDER BY b.title";

$result = mysqli_query($conn, $query);
$genres_result = mysqli_query($conn, "SELECT * FROM genres ORDER BY name");
?>

<?php include 'header.php'; ?>

<h2>Iskanje knjig</h2>

<form method="GET" action="" class="search-form">
    <input type="text" name="search" placeholder="Išči po naslovu ali avtorju" value="<?php echo htmlspecialchars($search); ?>">
    
    <select name="genre">
        <option value="">Vse zvrsti</option>
        <?php while ($genre_row = mysqli_fetch_assoc($genres_result)): ?>
            <option value="<?php echo (int)$genre_row['genre_id']; ?>" 
                    <?php echo ($genre == $genre_row['genre_id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($genre_row['name']); ?>
            </option>
        <?php endwhile; ?>
    </select>
    
    <input type="submit" value="Išči">
</form>

<table class="books-table">
    <tr>
        <th>Naslov</th>
        <th>Avtor</th>
        <th>Jezik</th>
        <th>Leto</th>
        <th>Zvrst</th>
        <th>Ocena</th>
        <th>Bralci</th>
        <th>Podrobnosti</th>
    </tr>
    <?php while ($book = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($book['title']); ?></td>
            <td><?php echo htmlspecialchars($book['author_first_name'] . ' ' . $book['author_last_name']); ?></td>
            <td><?php echo htmlspecialchars($book['language']); ?></td>
            <td><?php echo (int)$book['publication_year']; ?></td>
            <td><?php echo htmlspecialchars($book['genre_name']); ?></td>
            <td><?php echo $book['avg_rating'] ? number_format($book['avg_rating'], 1) : '-'; ?></td>
            <td><?php echo (int)$book['total_readers']; ?></td>
            <td><a href="book_details.php?id=<?php echo (int)$book['books_id']; ?>">Poglej</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include 'footer.php'; ?> 