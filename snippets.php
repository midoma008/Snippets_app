<?php
session_start();
require_once "config/db.php";
require_once "includes/header.php";
require_once "includes/navbar.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM snippets WHERE id = ? AND user_id = ?");
        $stmt->execute([$_POST['snippet_id'], $user_id]);
    } else {
        $title = $_POST['title'];
        $language = $_POST['language'];
        $code = $_POST['code'];

        if (isset($_POST['snippet_id'])) {
            $stmt = $conn->prepare("UPDATE snippets SET title = ?, language = ?, code = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $language, $code, $_POST['snippet_id'], $user_id]);
        } else {
            $stmt = $conn->prepare("INSERT INTO snippets (user_id, title, language, code) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $title, $language, $code]);
        }
        // Redirect to the same page to prevent resubmission
        header("Location: snippets.php");
        exit();
    }
}

$stmt = $conn->prepare("SELECT * FROM snippets WHERE user_id = ?");
$stmt->execute([$user_id]);
$snippets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <h1 class="text-center mb-4">Manage Snippets</h1>
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="language" class="form-label">Language</label>
            <select name="language" class="form-control" required>
            <option value="PHP" data-icon="fa-brands fa-php">PHP</option>
        <option value="JavaScript" data-icon="fa-brands fa-js">JavaScript</option>
        <option value="Python" data-icon="fa-brands fa-python">Python</option>
        <option value="Java" data-icon="fa-brands fa-java">Java</option>
          <option value="C++" data-icon="fa-brands fa-c">C++</option>
          <option value="Ruby" data-icon="fa-brands fa-ruby">Ruby</option>
        <option value="Go" data-icon="fa-brands fa-golang">Go</option>
        <option value="C#" data-icon="fa-solid fa-hashtag">C#</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <textarea name="code" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-custom">Save Snippet</button>
    </form>

    <h2 class="mb-4">Your Snippets</h2>
    <?php foreach ($snippets as $snippet): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($snippet['title']) ?> (<?= htmlspecialchars($snippet['language']) ?>)</h5>
                <pre><?= htmlspecialchars($snippet['code']) ?></pre>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="snippet_id" value="<?= $snippet['id'] ?>">
                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
