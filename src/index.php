<?php
    require_once('connexion.php');
    session_start();
    $board .= $_SERVER['REQUEST_URI'];
    $limit = 3;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="style/style.css" rel="stylesheet" type="text/css"/>
        <title>BCBB</title>
    </head>
    <body>
        <?php
            include 'menu.php';
        ?>
        <div class="container">
            <h1 class="text-center mt-5 pt-5 mb-3">BCBB</h1>
            <ul class="nav nav-justified">
                <li class="nav-item bg-secondary">
                    <?php
                    
                        if($board == "/index.php?board=General" OR $board == "/" OR $board == "/index.php"){
                            echo '<a class="nav-link text-white bg-dark" href="index.php?board=General">General</a>';
                        } else {
                            echo '<a class="nav-link text-white bg-secondary" href="index.php?board=General">General</a>';
                        }
                    ?>
                </li>
                <li class="nav-item">
                    <?php
                        if($board == "/index.php?board=Development"){
                            echo '<a class="nav-link text-white bg-dark" href="index.php?board=Development">Development</a>';
                        } else {
                            echo '<a class="nav-link text-white bg-secondary" href="index.php?board=Development">Development</a>';
                        }
                    ?>                
                </li>
                <li class="nav-item">
                    <?php
                        if($board == "/index.php?board=Smalltalk"){
                            echo '<a class="nav-link text-white bg-dark" href="index.php?board=Smalltalk">Smalltalk</a>';
                        } else {
                            echo '<a class="nav-link text-white bg-secondary" href="index.php?board=Smalltalk">Smalltalk</a>';
                        }
                    ?>                
                </li>
                <li class="nav-item">
                    <?php
                        if($board == "/index.php?board=Events"){
                            echo '<a class="nav-link text-white bg-dark" href="index.php?board=Events">Events</a>';
                        } else {
                            echo '<a class="nav-link text-white bg-secondary" href="index.php?board=Events">Events</a>';
                        }
                    ?>                
                </li>
            </ul>
            <?php
                switch ($_GET['board']) {
                    case 'General':
                        $sql = $pdo->query("SELECT * 
                                            FROM users 
                                            INNER JOIN topics 
                                            ON users.id = topics.users_id 
                                            WHERE boards_id = 1 
                                            ORDER BY created_at DESC
                                            LIMIT " . $limit);
                        $date = new DateTime($reponse['created_at']);
                        echo '<div class="list-group">';
                        include 'creaTopic.php';
                        while($reponse = $sql->fetch()){
                            echo '<a href="topic.php?idTopic=' . $reponse['id'] . '" class="list-group-item list-group-item-action list-group-item-secondary">';
                            echo '<div class="row row-cols-2">';
                            echo '<div class="col text-uppercase">' . $reponse['title'] . '</div>';
                            echo '<div class="col">' . $date->format('H:m d/m/Y') . '</div>';
                            echo '<div class="col text-info">' . $reponse['nickname'] . '</div>';
                            echo '</div>';
                            echo '</a>';
                        }
                        echo '</div>';
                        break;
                    case 'Development':
                        $sql = $pdo->query("SELECT * 
                                            FROM users 
                                            INNER JOIN topics 
                                            ON users.id = topics.users_id 
                                            WHERE boards_id = 2 
                                            ORDER BY created_at DESC
                                            LIMIT " . $limit);
                        $date = new DateTime($reponse['created_at']);
                        echo '<div class="list-group">';
                        include 'creaTopic.php';
                        while($reponse = $sql->fetch()){
                            echo '<a href="topic.php?idTopic=' . $reponse['id'] . '" class="list-group-item list-group-item-action list-group-item-secondary">';
                            echo '<div class="row row-cols-2">';
                            echo '<div class="col text-uppercase">' . $reponse['title'] . '</div>';
                            echo '<div class="col">' . $date->format('H:m d/m/Y') . '</div>';
                            echo '<div class="col text-info">' . $reponse['nickname'] . '</div>';
                            echo '</div>';
                            echo '</a>';
                        }
                        echo '</div>';
                        break;
                    case 'Smalltalk':
                        $sql = $pdo->query("SELECT * 
                                            FROM users 
                                            INNER JOIN topics 
                                            ON users.id = topics.users_id 
                                            WHERE boards_id = 3 
                                            ORDER BY created_at DESC
                                            LIMIT " . $limit);
                        $date = new DateTime($reponse['created_at']);
                        echo '<div class="list-group">';
                        include 'creaTopic.php';
                        while($reponse = $sql->fetch()){
                            echo '<a href="topic.php?idTopic=' . $reponse['id'] . '" class="list-group-item list-group-item-action list-group-item-secondary">';
                            echo '<div class="row row-cols-2">';
                            echo '<div class="col text-uppercase">' . $reponse['title'] . '</div>';
                            echo '<div class="col">' . $date->format('H:m d/m/Y') . '</div>';
                            echo '<div class="col text-info">' . $reponse['nickname'] . '</div>';
                            echo '</div>';
                            echo '</a>';
                        }
                        echo '</div>';
                        break;
                    case 'Events':
                        $sql = $pdo->query("SELECT * 
                                            FROM users 
                                            INNER JOIN topics 
                                            ON users.id = topics.users_id 
                                            WHERE boards_id = 4 
                                            ORDER BY created_at DESC
                                            LIMIT " . $limit);
                        $date = new DateTime($reponse['created_at']);
                        echo '<div class="list-group">';
                        include 'creaTopic.php';
                        while($reponse = $sql->fetch()){
                            echo '<a href="topic.php?idTopic=' . $reponse['id'] . '" class="list-group-item list-group-item-action list-group-item-secondary">';
                            echo '<div class="row row-cols-2">';
                            echo '<div class="col text-uppercase">' . $reponse['title'] . '</div>';
                            echo '<div class="col">' . $date->format('H:m d/m/Y') . '</div>';
                            echo '<div class="col text-info">' . $reponse['nickname'] . '</div>';
                            echo '</div>';
                            echo '</a>';
                        }
                        echo '</div>';
                        break;
                    default:
                        $sql = $pdo->query("SELECT * 
                                            FROM users 
                                            INNER JOIN topics 
                                            ON users.id = topics.users_id 
                                            WHERE boards_id = 1 
                                            ORDER BY created_at DESC
                                            LIMIT " . $limit);
                        $date = new DateTime($reponse['created_at']);
                        echo '<div class="list-group">';
                        include 'creaTopic.php';
                        while($reponse = $sql->fetch()){
                            echo '<a href="topic.php?idTopic=' . $reponse['id'] . '" class="list-group-item list-group-item-action list-group-item-secondary">';
                            echo '<div class="row row-cols-2">';
                            echo '<div class="col text-uppercase">' . $reponse['title'] . '</div>';
                            echo '<div class="col">' . $date->format('H:m d/m/Y') . '</div>';
                            echo '<div class="col text-info">' . $reponse['nickname'] . '</div>';
                            echo '</div>';
                            echo '</a>';
                        }
                            echo '</div>';
                }
            ?>
        </div>
    </script>
        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <script src="js/scrollBar.js">
    </body>
</html>