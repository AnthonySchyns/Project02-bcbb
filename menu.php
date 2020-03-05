<?php if ($_SESSION["idUser"] == true) {?>
    <nav class="navbar navbar-dark bg-secondary">
        <a class="navbar-brand" href="index.php">Home</a>
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <a class="nav-link text-white" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="disconnect.php">Disconnect</a>
            </li>
        </ul>
    </nav>
<?php } else {?>
    <nav class="navbar navbar-dark bg-secondary">
        <a class="navbar-brand" href="index.php">Home</a>
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <a class="nav-link text-white" href="register.php">Sign Up</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="login.php">Sign In</a>
            </li>
        </ul>
    </nav>
<?php }?>