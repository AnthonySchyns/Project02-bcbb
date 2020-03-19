<?php 
    require_once 'connexion.php';
    session_start();
    
    // Rediriger vers la page d'acceuil si personne n'est connecté 
    // et qu'on essaie d'accéder à la page
    if (!isset($_SESSION['idUser'])) {
        header('location: index.php');
    }

    // Définir la limit et compter les données de la bd
    $limit = 5;
    $query = "SELECT count(*) FROM topics WHERE users_id = " . $_SESSION['idUser'];
    $s = $pdo->query($query);
    $totalResults = $s->fetchColumn();
    $totalPages = ceil($totalResults/$limit);
    
    // Si pas de page sélectionner alors la page est la 1ère
    if (!isset($_GET['page'])) {
        $page = 1;
    } else{
        $page = $_GET['page'];
    }
    $start = ($page-1)*$limit;

    // Récuperer les topics crée par la personne connecté
    $sql = "SELECT * "
        . "FROM topics "
        . "WHERE users_id = " . $_SESSION['idUser']
        . " ORDER BY created_at DESC "
        . "LIMIT " . $start . ", " . $limit;
    $sth = $pdo->prepare($sql);
    $sth->execute();
    $myTopics = $sth->fetchAll(PDO::FETCH_OBJ);
    $sth->closeCursor();
    $sth = null;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- Bootstrap CSS -->
        <link href="style/style.css" rel="stylesheet" type="text/css" />
        <title>My topics</title>
    </head>
    <body>
        <?php include 'menu.php'; ?>
        <div class="container">
            <h1 class="text-center mt-5 pt-5 mb-3">My Topics</h1>
            <?php 
                if($myTopics){
                    foreach ($myTopics as $myTopic) { ?>
                        <a href="topic.php?idTopic='<?php echo $myTopic->id ?>'" class="list-group-item list-group-item-action list-group-item-secondary">
                            <div class="row row-cols-2">
                                <div class="col text-uppercase"><?php echo $myTopic->title ?></div>
                                <div class="col"><?php $date = new DateTime($myTopic->created_at);
                                                    echo $date->format('H:m d/m/Y') ?></div>
                            </div>
                        </a>
                    <?php } ?>
                    <br/>
                    <ul class="pagination justify-content-center">
                    <?php
                        for($page=1; $page <= $totalPages ; $page++){
                    ?>
                        <li class="page-item">
                            <a href='<?php echo "myTopics.php?page=$page"; ?>' class="page-link text-light bg-secondary border-secondary"><?php  echo $page; ?></a>
                        </li>
                        <?php } ?>
                    </ul>
            <?php } else { ?>
                <li class="list-group-item list-group-item-secondary text-center">You didn't started any topic yet. You can start one anytime just by clicking down here.</li>
                <?php include 'creaTopic.php';?>
            <?php } ?>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="js/scrollBar.js"></script>
    </body>
</html>