<?php
session_start();
require_once "config/db.php";
require_once "includes/header.php";
require_once "includes/navbar.php";


// Fetch recent snippets
$stmt = $conn->query("SELECT snippets.*, users.name FROM snippets JOIN users ON snippets.user_id = users.id ORDER BY created_at DESC LIMIT 10");
$snippets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<body>
    

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
                <option value="PHP" data-icon="fa-brands fa-php">PHP</option>
        <option value="JavaScript" data-icon="fa-brands fa-js">JavaScript</option>
        <option value="Python" data-icon="fa-brands fa-python">Python</option>
        <option value="Java" data-icon="fa-brands fa-java">Java</option>
          <option value="C++" data-icon="fa-brands fa-c">C++</option>
          <option value="Ruby" data-icon="fa-brands fa-ruby">Ruby</option>
        <option value="Go" data-icon="fa-brands fa-golang">Go</option>
        <option value="C#" data-icon="fa-solid fa-hashtag">C#</option>
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