<?php
session_start();
include 'config.php';

// Fetch recent snippets
$stmt = $conn->query("SELECT snippets.*, users.name FROM snippets JOIN users ON snippets.user_id = users.id ORDER BY created_at DESC LIMIT 10");
$snippets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Snippet Sharing</title>
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

    <!-- Main Content -->
    <div class="container my-5">
        <h1 class="text-center mb-4">Recent Snippets</h1>
        <form action="index.php" method="GET" class="mb-4">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Search by title or language">
                <button type="submit" class="btn btn-custom">Search</button>
            </div>
            <div class="input-group">
                <select name="language" class="form-control">
                    <option value="">All Languages</option>
                    <option value="PHP">PHP</option>
                    <option value="JavaScript">JavaScript</option>
                    <option value="Python">Python</option>
                    <option value="Java">Java</option>
                    <option value="C++">C++</option>
                    <option value="Ruby">Ruby</option>
                    <option value="Go">Go</option>
                    <option value="C#">C#</option>
                </select>
                <button type="submit" class="btn btn-custom">Filter</button>
            </div>
        </form>

        <?php
        if (!empty($_GET['search']) || !empty($_GET['language'])) {
            $search = $_GET['search'] ?? '';
            $language = $_GET['language'] ?? '';
            $query = "SELECT snippets.*, users.name FROM snippets JOIN users ON snippets.user_id = users.id WHERE (title LIKE ? OR language LIKE ?)";
            $params = ["%$search%", "%$search%"];
            
            if (!empty($language)) {
                $query .= " AND language = ?";
                $params[] = $language;
            }
            
            $query .= " ORDER BY created_at DESC";
            $stmt = $conn->prepare($query);
            $stmt->execute($params);
            $snippets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        ?>

        <?php if (!empty($snippets)): ?>
            <?php foreach ($snippets as $snippet): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($snippet['title']) ?> (<?= htmlspecialchars($snippet['language']) ?>)</h5>
                        <pre class="snippet-preview"><?= htmlspecialchars($snippet['code']) ?></pre>
                        <p class="text-muted">By: <?= htmlspecialchars($snippet['name']) ?></p>
                        <a href="snippet.php?id=<?= $snippet['id'] ?>" class="btn btn-link read-more">Read more</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No snippets found.</p>
        <?php endif; ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.snippet-preview').forEach(function(pre) {
                const lines = pre.textContent.split('\n').length;
                if (lines <3) {
                    pre.nextElementSibling.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>