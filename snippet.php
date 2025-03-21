<?php
session_start();
require_once "config/db.php";
require_once "includes/header.php";
require_once "includes/navbar.php";

// Handle form submission for adding a new snippet
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $language = $_POST['language'];
    $code = $_POST['code'];

    // Prepare and execute the insert query
    $stmt = $conn->prepare("INSERT INTO snippets (title, language, code, user_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $language, $code, $_SESSION['user_id']]);

    // Redirect to the same page to prevent resubmission
    header("Location: snippet.php?id=" . $conn->lastInsertId());
    exit();
}

// Check if the snippet ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $snippet_id = $_GET['id'];

    // Prepare and execute the query to fetch the snippet details
    $stmt = $conn->prepare("SELECT snippets.*, users.name FROM snippets JOIN users ON snippets.user_id = users.id WHERE snippets.id = ?");
    $stmt->execute([$snippet_id]);
    $snippet = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the snippet exists
    if (!$snippet) {
        header("Location: error.php?error=Snippet not found");
        exit();
    }

} else {
    die("Invalid snippet ID.");
}
?>

<!-- Snippet Details -->
<div class="container my-5">
    <h1 class="text-center mb-4"><?= htmlspecialchars($snippet['title']) ?></h1>
    <p><strong>Language:</strong> <?= htmlspecialchars($snippet['language']) ?></p>
    <pre class="snippet-code"><?= htmlspecialchars($snippet['code']) ?></pre>
    <p class="text-muted">By: <?= htmlspecialchars($snippet['name']) ?></p>
    <a href="index.php" class="btn btn-primary">Back to Snippets</a>
</div>
</body>
</html>
