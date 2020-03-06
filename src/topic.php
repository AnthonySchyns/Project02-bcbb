<?php

session_start();

require_once 'connexion.php';

$path = $_GET['idTopic'];

// Del Message
if (isset($_POST['del'])) {
    $dateTime = date("Y-m-d H:i:s");
    $idMessage = $_POST['del'];
    $sqlDelete = "UPDATE messages "
        . "SET deleted_at = '" . $dateTime . "' "
        . "WHERE id = '" . $idMessage . "'";

    $sth = $pdo->prepare($sqlDelete);
    $sth->execute();
    $sth->closeCursor();
    $sth = null;
}

// Update Message
if (isset($_POST['sendUpdate'])) {
    $dateTime = date("Y-m-d H:i:s");
    $idMessage = $_POST['sendUpdate'];
    $content = $_POST['content'];
    $sqlUpdate = "UPDATE messages "
        . "SET content = '" . $content . "', "
        . "updated_at = '" . $dateTime . "' "
        . "WHERE id = '" . $idMessage . "'";

    $sth = $pdo->prepare($sqlUpdate);
    $sth->execute();
    $sth->closeCursor();
    $sth = null;
}

// Get Topics
$idTopic = $_GET['idTopic'];
$sql = "SELECT * "
    . "FROM topics "
    . "INNER JOIN users "
    . "ON users.id = topics.users_id "
    . "WHERE topics.id = $idTopic";
$sth = $pdo->prepare($sql);
$sth->execute();
$topic = $sth->fetch(PDO::FETCH_OBJ);
$sth->closeCursor();
$sth = null;

// Add Message
if (isset($_POST['addMessage'])) {
    $content = $_POST['content'];
    $dateTime = date("Y-m-d H:i:s");
    $user = $_SESSION["idUser"];

    $sqlAjout = "INSERT INTO messages "
        . "SET content = '$content', "
        . "created_at = '$dateTime', "
        . "updated_at = '$dateTime', "
        . "users_id = '$user', "
        . "topics_id = '$idTopic' ";

    $pdo->exec($sqlAjout);
}

// Get Messages
$sql = "SELECT * "
    . "FROM users "
    . "INNER JOIN messages "
    . "ON users.id = messages.users_id "
    . "WHERE topics_id = $idTopic";
$sth = $pdo->prepare($sql);
$sth->execute();
$messages = $sth->fetchAll(PDO::FETCH_OBJ);
$sth->closeCursor();
$sth = null;

?>
<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Fontawesome  -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css"
            integrity="sha256-46qynGAkLSFpVbEBog43gvNhfrOj+BmwXdxFgVK/Kvc=" crossorigin="anonymous" />

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
            integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <title>BCBB</title>
    </head>

    <body>
        <?php
            include 'menu.php';
        ?>
        <h1 class="text-center mt-5"><?php echo $topic->title ?></h1>
        <section class="container mt-5">
            <div class="row border">
                <div class="col-md-2 border-right p-5">
                    <img src="<?php echo get_gravatar($topic->email); ?>" class="img-thumbnail">
                    <p class="text-center mt-4 font-weight-bold"><?php echo $topic->nickname ?></p>
                </div>
                <div class="col p-5">
                    <p><?php echo $topic->content ?></p>
                    <p class="text-right"><?php echo $topic->updated_at ?></p>
                </div>
            </div>
        </section>

    <?php if (isset($_SESSION['idUser'])) { ?>

        <section class="container mt-5">
            <h3 class="mb-5">Votre Message</h3>
            <form action="topic.php?idTopic=<?php echo $path ?>" method="post" class="row">
                <textarea type="text" class="form-control" name="content" placeholder="Message" rows="5"></textarea>
                <button type="submit" name="addMessage" class="btn btn-secondary mt-3">Envoyer</button>
            </form>
        </section>

    <?php } ?>   

        <section class="container mt-5">
            <h3 class="mb-5">Messages</h3>

        <?php foreach ($messages as $message) {?>

            <div class="row border">
                <div class="col-md-2 border-right p-5 align-middle">
                    <img src="<?php echo get_gravatar($message->email); ?>" alt="image user" class="img-thumbnail">
                    <p class="text-center mt-4 font-weight-bold"><?php echo $message->nickname ?></p>
                </div>
                <div class="col p-5">

            <?php if ($message->deleted_at == null) {?>
                <?php if ($_POST['update'] == $message->id) {?>
                    <form action="topic.php?idTopic=<?php echo $path ?>" method="post">
                        <textarea type="text" class="form-control" name="content" rows="10"><?php echo $message->content ?></textarea>
                        <button type="submit" name="sendUpdate" value="<?php echo $message->id ?>" class="btn btn-secondary mt-3">Modifier</button>
                    </form>
                <?php } else {?>
                    <p><?php echo $message->content ?></p>
                    <p class="text-right"><?php echo $message->updated_at ?></p>
                <?php }?>
            <?php } else {?>
                    <p>Le message a été supprimé !!</p>
            <?php }?>

                </div>
                <div class="col-1 d-flex flex-column justify-content-around align-items-center">

        <?php if (isset($_SESSION['idUser'])) { ?>
            <?php if ($message->deleted_at == null) {?>
                <?php if (empty($_POST['update'])) {?>
                    <form action="topic.php?idTopic=<?php echo $path ?>" method="post">
                            <button type="submit" name="update" value="<?php echo $message->id ?>" class="btn btn-outline-primary"><i class="fas fa-edit"></i></button>
                    </form>
                    <form action="topic.php?idTopic=<?php echo $path ?>" method="post">
                        <button type="submit" name="del" value="<?php echo $message->id ?>" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                    </form>
                <?php }?>
            <?php }?>
        <?php }?>

                </div>
            </div>
            <br>
        <?php }?>

        </section>

        <footer>
            <h1>&nbsp;</h1>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
        </script>

    </body>
</html>