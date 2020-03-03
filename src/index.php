<?php
    require_once('connexion.php');
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>BCBB</title>
    </head>
    <body>
        <ul class="nav justify-content-end">
            <li class="nav-item bg-secondary">
                <a class="nav-link text-white" href="register.php">Sign Up</a>
            </li>
            <li class="nav-item bg-secondary">
                <a class="nav-link text-white" href="login.php">Sign in</a>
            </li>
        </ul>
        <div class="container">
            <h1 class="text-center">BCBB</h1>
            <ul class="nav nav-justified">
                <li class="nav-item bg-secondary">
                    <a class="nav-link text-white" href="index.php?board=general">General</a>
                </li>
                <li class="nav-item bg-secondary">
                    <a class="nav-link text-white" href="index.php?board=development">Development</a>
                </li>
                <li class="nav-item bg-secondary">
                    <a class="nav-link text-white" href="index.php?board=smalltalk">Smalltalk</a>
                </li>
                <li class="nav-item bg-secondary">
                    <a class="nav-link text-white" href="index.php?board=events">Events</a>
                </li>
            </ul>
            <?php
                switch ($_GET['board']) {
                    case 'general':
                        $sql = $pdo->query("SELECT *, DATE_FORMAT(created_at,'%d/%m/%Y') FROM topics WHERE boards_id = 1 ORDER BY created_at DESC");
                        echo '<div class="list-group">';
                        while($reponse = $sql->fetch()){
                            echo '<a href="#" class="list-group-item list-group-item-action list-group-item-secondary">' . $reponse['title'] . '    ' . $reponse['created_at'] . $reponse['nickname'] . '</a>';
                        }
                        echo '</div>';
                        break;
                    case 'development':
                        $sql = $pdo->query("SELECT * FROM topics WHERE boards_id = 2 ORDER BY created_at DESC");
                        echo '<div class="list-group">';
                        while($reponse = $sql->fetch()){
                            echo '<a href="#" class="list-group-item list-group-item-action list-group-item-secondary">' . $reponse['title'] . '</a>';
                        }
                        echo '</div>';
                        break;
                    case 'smalltalk':
                        $sql = $pdo->query("SELECT * FROM topics WHERE boards_id = 3 ORDER BY created_at DESC");
                        echo '<div class="list-group">';
                        while($reponse = $sql->fetch()){
                            echo '<a href="#" class="list-group-item list-group-item-action list-group-item-secondary">' . $reponse['title'] . '</a>';
                        }
                        echo '</div>';
                        break;
                    case 'events':
                        $sql = $pdo->query("SELECT * FROM topics WHERE boards_id = 4 ORDER BY created_at DESC");
                        echo '<div class="list-group">';
                        while($reponse = $sql->fetch()){
                            echo '<a href="#" class="list-group-item list-group-item-action list-group-item-secondary">' . $reponse['title'] . '</a>';
                        }
                        echo '</div>';
                        break;
                    default:
                        $sql = $pdo->query("SELECT * FROM topics WHERE boards_id = 1 ORDER BY created_at DESC");
                        echo '<div class="list-group">';
                        while($reponse = $sql->fetch()){
                            echo '<a href="#" class="list-group-item list-group-item-action list-group-item-secondary">' . $reponse['title'] . '</a>';
                        }
                        echo '</div>';
                }
            ?>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>