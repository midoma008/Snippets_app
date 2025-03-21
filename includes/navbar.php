<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom" role="navigation" aria-label="Main Navigation">
    <div class="container">
        <a class="navbar-brand" href="index.php">SnippetShare</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item" role="menuitem">
                        <a class="nav-link" href="snippets.php" aria-label="Manage your snippets">Manage Snippets</a>
                    </li>
                    <li class="nav-item" role="menuitem">
                        <a class="nav-link" href="profile.php" aria-label="View your profile">Profile</a>
                    </li>
                    <li class="nav-item" role="menuitem">
                        <a class="nav-link" href="logout.php" aria-label="Logout from your account">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item" role="menuitem">
                        <a class="nav-link" href="login.php" aria-label="Login to your account">Login</a>
                    </li>
                    <li class="nav-item" role="menuitem">
                        <a class="nav-link" href="register.php" aria-label="Create a new account">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
