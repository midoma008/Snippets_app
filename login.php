<?php
session_start();
require_once "config/db.php";
require_once "includes/header.php";
require_once "includes/navbar.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Invalid credentials!</div>";
    }
}
?>


<body>
    <div class="container my-5">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body">
                <h1 class="card-title text-center mb-4">Login</h1>
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-custom w-100">Login</button>
                </form>
                <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </div>
    </div>
</body>
</html>