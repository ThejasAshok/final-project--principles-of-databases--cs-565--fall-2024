<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Final Project | CS 565 | Passwords Assignment</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <header>
      <h1>CRUD Operations via a Web Interface</h1>
    </header>
    <nav>
      <ul>
        <li><a href="#search-form">Search</a></li>
        <li><a href="#update-form">Update</a></li>
        <li><a href="#insert-form">Insert</a></li>
        <li><a href="#delete-form">Delete</a></li>
      </ul>
    </nav>
    <form id="clear-results" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <!-- Reset button to clear the results -->
      <button type="submit">Clear Results</button>
    </form>
<?php
require_once "includes/html/search-form.html";
require_once "includes/html/update-form.html";
require_once "includes/html/insert-form.html";
require_once "includes/html/delete-form.html";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db.php';
    $submitted = $_POST['submitted'] ?? null;

    switch ($submitted) {
        case '1': // Search operation
            $searchTerm = $_POST['search'] ?? '';
            $results = searchEntries($searchTerm);
            echo "<div id='results'>";
            if (!empty($results)) {
                foreach ($results as $result) {
                    echo "<p>" . htmlspecialchars(print_r($result, true)) . "</p>";
                }
            } else {
                echo "<p>No results found.</p>";
            }
            echo "</div>";
            break;
        case '2': // Update operation
            $currentAttr = $_POST['current-attribute'] ?? '';
            $newAttr = $_POST['new-attribute'] ?? '';
            $queryAttr = $_POST['query-attribute'] ?? '';
            $pattern = $_POST['pattern'] ?? '';
            if (updateEntry($currentAttr, $newAttr, $queryAttr, $pattern)) {
                echo "<p>Update successful.</p>";
            } else {
                echo "<p>Update failed.</p>";
            }
            break;
        case '3': // Insert operation
            $userId = $_POST['user-id'] ?? '';
            $websiteName = $_POST['website-name'] ?? '';
            $url = $_POST['url'] ?? '';
            $email = $_POST['email'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $comment = $_POST['comment'] ?? '';
            if (insertEntry($userId, $websiteName, $url, $email, $username, $password, $comment)) {
                echo "<p>Insert successful.</p>";
            } else {
                echo "<p>Insert failed.</p>";
            }
            break;
        case '4': // Delete operation
            $currentAttr = $_POST['current-attribute'] ?? '';
            $pattern = $_POST['pattern'] ?? '';
            if (deleteEntry($currentAttr, $pattern)) {
                echo "<p>Delete successful.</p>";
            } else {
                echo "<p>Delete failed.</p>";
            }
            break;
    }
}
?>
  </body>
</html>
