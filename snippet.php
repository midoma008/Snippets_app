<?php
session_start();
include 'config.php';

// Check if the snippet ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $snippet_id = $_GET['id'];

    // Prepare and execute the query to fetch the snippet details
    $stmt = $conn->prepare("SELECT snippets.*, users.name FROM snippets JOIN users ON snippets.user_id = users.id WHERE snippets.id = ?");
    $stmt->execute([$snippet_id]);
    $snippet = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the snippet exists
    if (!$snippet) {
        die("Snippet not found.");
    }
} else {
    die("Invalid snippet ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($snippet['title']) ?> - SnippetShare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="index.php">SnippetShare</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="snippets.php">Manage Snippets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

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