<?php
session_start();

// Gravatar 
$sql = "SELECT * "
    . "FROM users "
    . "WHERE id = '" . $_SESSION['idUser'] . "'";
$sth = $pdo->prepare($sql);
$sth->execute();
$useru = $sth->fetch(PDO::FETCH_OBJ);
$sth->closeCursor();
$sth = null;
$email = $useru->email;
function get_gravatar($email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array())
{
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5(strtolower(trim($email)));
    $url .= "?s=$s&d=$d&r=$r";
    if ($img) {
        $url = '<img src="' . $url . '"';
        foreach ($atts as $key => $val) {
            $url .= ' ' . $key . '="' . $val . '"';
        }

        $url .= ' />';
    }
    return $url;
}
$profile = get_gravatar($email, $s = 30, $d = 'mp', $r = 'g', $img = false, $atts = array());
?>
<?php if ($_SESSION["idUser"] == true) { ?>
    <nav class="navbar navbar-dark bg-secondary position-fixed">
        <a class="navbar-brand" href="index.php">Home</a>
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <a class="nav-link text-white" href="profile.php">
                    <?php if ($_SESSION['profile'] === NULL) : ?>
                        <img src="<?php echo $profile ?>" class="rounded-circle" />
                    <?php else : ?>
                        <img src="<?php echo $_SESSION['profile'] ?>" style="width:30px; height:30px" class="rounded-circle" />
                    <?php endif ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="disconnect.php">Disconnect</a>
            </li>
        </ul>
    </nav>
<?php } else { ?>
    <nav class="navbar navbar-dark bg-secondary position-fixed">
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
<?php } ?>