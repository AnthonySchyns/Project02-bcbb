<?php
    if($_SESSION["idUser"] == true){
        echo '<nav class="navbar navbar-dark bg-secondary">';
        echo '<a class="navbar-brand" href="index.php">Home</a>';
        echo '<ul class="nav justify-content-end">';
        echo '<li class="nav-item">';
        echo '<a class="nav-link text-white" href="profile.php">Profile</a>';
        echo '</li>';
        echo '<li class="nav-item">';
        echo '<a class="nav-link text-white" href="disconnect.php">Disconnect</a>';
        echo '</li>';
        echo '</ul>';
        echo '</nav>';
    } else {
        echo '<nav class="navbar navbar-dark bg-secondary">';
        echo '<a class="navbar-brand" href="index.php">Home</a>';
        echo '<ul class="nav justify-content-end">';
        echo '<li class="nav-item">';
        echo '<a class="nav-link text-white" href="register.php">Sign Up</a>';
        echo '</li>';
        echo '<li class="nav-item">';
        echo '<a class="nav-link text-white" href="login.php">Sign In</a>';
        echo '</li>';
        echo '</ul>';
        echo '</nav>';
    }
?>