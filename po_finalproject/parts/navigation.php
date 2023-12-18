<nav class="navbar navbar-expand-xl navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <i class="fas fa-3x fa-tachometer-alt tm-site-icon"></i>
        <h1 class="tm-site-title mb-0">Dashboard</h1>
    </a>
    <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>" href="index.php">Dashboard
                    <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : ''; ?>" href="products.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'sales.php' ? 'active' : ''; ?>" href="sales.php">Sales</a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'account.php' ? 'active' : ''; ?>" href="account.php">Account</a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link d-flex" href="logout.php">
                    <i class="far fa-user mr-2 tm-logout-icon"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>