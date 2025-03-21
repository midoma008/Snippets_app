<?php
session_start();
require_once "config/db.php";
require_once "includes/header.php";
require_once "includes/navbar.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $password]);

    header("Location: login.php");
    exit();
}
?>


<body>
    <div class="container my-5">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body">
                <h1 class="card-title text-center mb-4">Register</h1>
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-custom w-100">Register</button>
                </form>
                <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>