<?php

require_once 'connexion.php';

session_start();

$board .= $_SERVER['REQUEST_URI'];

// Get Topics
function getTopics(PDO $pdo, $a, $limit)
{
    // Définir la limit et compter les données de la bd
    $query = "SELECT count(*) FROM topics WHERE boards_id = " . $a;
    $s = $pdo->query($query);
    $totalResults = $s->fetchColumn();
    global $page;
    global $totalPages;
    $totalPages = ceil($totalResults/$limit);
    
    // Si pas de page sélectionner alors la page est la 1ère
    if (!isset($_GET['page'])) {
        $page = 1;
    } else{
        $page = $_GET['page'];
    }
    $start = ($page-1)*$limit;

    $sql = "SELECT * "
        . "FROM users "
        . "INNER JOIN topics "
        . "ON users.id = topics.users_id "
        . "WHERE boards_id = '" . $a . "'"
        . " ORDER BY created_at DESC"
        . " LIMIT " . $start . ", " . $limit;
    $sth = $pdo->prepare($sql);
    $sth->execute();
    return $sth->fetchAll(PDO::FETCH_OBJ);
    $sth->closeCursor();
    $sth = null;
}
function getDescription(PDO $pdo, $i){
    $sqlDescription = "SELECT description "
                    . "FROM boards "
                    . "WHERE id = " . $i;
    $sthDescription = $pdo->prepare($sqlDescription);
    $sthDescription->execute();
    return $sthDescription->fetchColumn();
    $sthDescription->closeCursor();
    $sthDescription = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="style/style.css" rel="stylesheet" type="text/css"/>
        <title>Harrington BCBB</title>
    </head>

    <body>

    <?php
    include 'menu.php';
    ?>

        <div class="container">
            <h1 class="text-center mt-5 pt-5 mb-3">HARRINGTON BCBB</h1>
            <ul class="nav nav-justified">
                <li class="nav-item">
                    <a title="<?php getDescription($pdo, 1) ?>" class="nav-link text-white <?php if(strpos($board, "General") || $board == "/" || $board == "/index.php"){ echo 'bg-dark';} else { echo 'bg-secondary';}; ?>" href="index.php?board=General">General</a>
                </li>
                <li class="nav-item">
                    <a title="<?php getDescription($pdo, 2) ?>" class="nav-link text-white <?php if(strpos($board, "Development")){ echo 'bg-dark';} else { echo 'bg-secondary';}; ?>" href="index.php?board=Development">Development</a>
                </li>
                <li class="nav-item">
                    <a title="<?php getDescription($pdo, 3) ?>" class="nav-link text-white <?php if(strpos($board, "Smalltalk")){ echo 'bg-dark';} else { echo 'bg-secondary';};?>" href="index.php?board=Smalltalk">Smalltalk</a>
                </li>
                <li class="nav-item">
                    <a title="<?php getDescription($pdo, 4) ?>" class="nav-link text-white <?php if(strpos($board, "Events")){ echo 'bg-dark';} else { echo 'bg-secondary';}; ?>" href="index.php?board=Events">Events</a>
                </li>
                <li class="nav-item">
                    <a title="<?php getDescription($pdo, 5) ?>" class="nav-link text-white <?php if(strpos($board, "Very-secret")){ echo 'bg-dark';} else { echo 'bg-secondary';}; ?>" href="index.php?board=Very-secret">Very secret</a>
                </li>
                <li class="nav-item">
                    <a title="<?php getDescription($pdo, 6) ?>" class="nav-link text-white <?php if(strpos($board, "Random")){ echo 'bg-dark';} else { echo 'bg-secondary';}; ?>" href="index.php?board=Random">Random</a>
                </li>
            </ul>
            <?php include 'creaTopic.php';?>
            <div class="list-group">

        <?php 
            if($_GET['board'] == "General"){
                $topics = getTopics($pdo, 1, 3);
            } elseif ($_GET['board'] == "Development") {
                $topics = getTopics($pdo, 2, 3);
            } elseif ($_GET['board'] == "Smalltalk") {
                $topics = getTopics($pdo, 3, 3);
            } elseif ($_GET['board'] == "Events") {
                $topics = getTopics($pdo, 4, 3);
            } elseif ($_GET['board'] == "Very-secret") {
                // Avoir accès à la page uniquement si on entre le code HarringtonSecretCode
                if($_GET['code'] == "HarringtonSecretCode"){
                    $topics = getTopics($pdo, 5, 3);
                } else {
                    echo '<li class="list-group-item list-group-item-danger">STOP!! You can not see these topics. You need to add the special code to the URL. Example = "index.php?board=Very-secret&code=something".</li>';
                }
            } elseif ($_GET['board'] == "Random") {
                $topics = getTopics($pdo, 6, 5);
            } else {
                $topics = getTopics($pdo, 1, 3);
            }
            ?>
            
            <?php 
            if($topics){
                foreach ($topics as $topic) { ?>
                    <a href="topic.php?idTopic=<?php echo $topic->id ?>" class="list-group-item list-group-item-action list-group-item-secondary">
                        <div class="row row-cols-2">
                            <div class="col text-uppercase"><?php echo $topic->title ?></div>
                            <div class="col"><?php $date = new DateTime($topic->created_at);
                                                echo $date->format('H:m d/m/Y') ?></div>
                            <div class="col text-info"><?php echo $topic->nickname ?></div>
                        </div>
                    </a>
                <?php } ?>
                <br/>
                <?php
                    if($totalPages > 1){
                ?>
                    <ul class="pagination justify-content-center">
                <?php
                    for($page=1; $page <= $totalPages ; $page++){
                ?>
                    <li class="page-item">
                        <a href='<?php echo "$board&page=$page"; ?>' class="page-link text-light bg-secondary border-secondary"><?php  echo $page; ?></a>
                    </li>
                    <?php } ?>
                </ul>
            <?php }} ?>
            </div>
        </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script src="js/scrollBar.js"></script>
<body>
</html>