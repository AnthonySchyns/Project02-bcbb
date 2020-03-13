<?php

require_once('connexion.php');

session_start();

$board .= $_SERVER['REQUEST_URI'];

$limit = 3;

// Get Boards
$sql = "SELECT * "
    . "FROM boards ";
$sth = $pdo->prepare($sql);
$sth->execute();
$boards = $sth->fetchAll(PDO::FETCH_OBJ);
$sth->closeCursor();
$sth = null;

// Get Topics
function getTopics(PDO $pdo, $a)
{
    $sql = "SELECT * "
        . "FROM users "
        . "INNER JOIN topics "
        . "ON users.id = topics.users_id "
        . "WHERE boards_id = '" . $a . "'";
    $sth = $pdo->prepare($sql);
    $sth->execute();
    return $sth->fetchAll(PDO::FETCH_OBJ);
    $sth->closeCursor();
    $sth = null;
}
function getDescription(PDO $pdo, $i){
    $sql = "SELECT description "
        . "FROM boards "
        . "WHERE id = '" . $i . "'";
    $sth = $pdo->prepare($sql);
    $sth->execute();
    return $sth->fetchAll(PDO::FETCH_OBJ);
    $sth->closeCursor();
    $sth = null;
}
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
                    <a title="<?php getDescription($pdo, 1) ?>" class="nav-link text-white <?php if($board == "/index.php?board=General" OR $board == "/" OR $board == "/index.php"){ echo 'bg-dark';} else { echo 'bg-secondary';}; ?>" href="index.php?board=General">General</a>
                </li>
                <li class="nav-item">
                    <a title="<?php getDescription($pdo, 2) ?>"class="nav-link text-white <?php if($board == "/index.php?board=Development"){ echo 'bg-dark';} else { echo 'bg-secondary';}; ?>" href="index.php?board=Development">Development</a>
                </li>
                <li class="nav-item">
                    <a title="<?php getDescription($pdo, 3) ?>"class="nav-link text-white <?php if($board == "/index.php?board=Smalltalk"){ echo 'bg-dark';} else { echo 'bg-secondary';};?>" href="index.php?board=Smalltalk">Smalltalk</a>
                </li>
                <li class="nav-item">
                    <a title="<?php getDescription($pdo, 4) ?>"class="nav-link text-white <?php if($board == "/index.php?board=Events"){ echo 'bg-dark';} else { echo 'bg-secondary';}; ?>" href="index.php?board=Events">Events</a>
                </li>
            </ul>
            <?php include 'creaTopic.php';?>
            <div class="list-group">

        <?php 
            if($_GET['board'] == "General"){
                $topics = getTopics($pdo, 1);
            } elseif ($_GET['board'] == "Development") {
                $topics = getTopics($pdo, 2);
            } elseif ($_GET['board'] == "Smalltalk") {
                $topics = getTopics($pdo, 3);
            } elseif ($_GET['board'] == "Events") {
                $topics = getTopics($pdo, 4);
            } else {
                $topics = getTopics($pdo, 1);
            }
            ?>

            <?php foreach ($topics as $topic) { ?>
                <a href="topic.php?idTopic='<?php echo $topic->id ?>'" class="list-group-item list-group-item-action list-group-item-secondary">
                    <div class="row row-cols-2">
                        <div class="col text-uppercase"><?php echo $topic->title ?></div>
                        <div class="col"><?php $date = new DateTime($topic->created_at);
                                            echo $date->format('H:m d/m/Y') ?></div>
                        <div class="col text-info"><?php echo $topic->nickname ?></div>
                    </div>
                </a>
            <?php } ?>

        </div>
    </div>
    </script>
        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <script src="js/scrollBar.js"></script>
    </body>
</html>
