<?php

session_start();

require_once 'connexion.php';

// Markdown
require('lib/Parsedown.php');

$path = $_GET['idTopic'];

// Del Message
if (isset($_POST['del'])) {
    date_default_timezone_set('Europe/Brussels');
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
    date_default_timezone_set('Europe/Brussels');
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
    date_default_timezone_set('Europe/Brussels');
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
    . "WHERE topics_id = $idTopic "
    . "ORDER BY updated_at DESC";
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

        <!-- Emoji Picker -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="lib/css/emoji.css" rel="stylesheet">
        <link href="style/style.css" rel="stylesheet" type="text/css"/>
        <title>BCBB</title>
    </head>

    <body>
        <?php
            include 'menu.php';
        ?>
        <h1 class="text-center mt-5 pt-5"><?php echo $topic->title ?></h1>
        <section class="container mt-5">
            <div class="row border">
                <div class="col-md-2 border-right p-5">
                    <img src="<?php echo get_gravatar($topic->email); ?>" class="img-thumbnail">
                    <p class="text-center mt-4 font-weight-bold"><?php echo $topic->nickname ?></p>
                </div>
                <div class="col p-5">
                    <p><?php echo $topic->content ?></p>
                    <p class="text-right"><?php $date = new DateTime($topic->created_at); echo $date->format('H:m d/m/Y') ?></p>
                </div>
            </div>
            <div class="col p-5">
                <p><?php echo $topic->content ?></p>
                <img src="<?php echo $topic->image ?>" style="width:300px; height:250px" class="mt-4" />
            </div>
        </div>
    </section>

    <?php if (isset($_SESSION['idUser'])) { ?>

        <section class="container mt-5">
            <h3 class="mb-5">Votre Message</h3>
            <form action="topic.php?idTopic=<?php echo $path ?>" method="post" class="row emoji-picker-container">
                <textarea type="text" class="form-control" name="content" placeholder="Message" rows="5" data-emojiable="true"></textarea>
                <button type="submit" name="addMessage" class="btn btn-secondary mt-3">Envoyer</button>
            </form>
        </section>

    <?php } ?>

    <section class="container mt-5">
        <h3 class="mb-5">Messages</h3>

        <?php foreach ($messages as $message) { ?>

            <div class="row border">
                <div class="col-md-2 border-right p-5 align-middle">
                    <img src="<?php echo get_gravatar($message->email); ?>" alt="image user" class="img-thumbnail">
                    <p class="text-center mt-4 font-weight-bold"><?php echo $message->nickname ?></p>
                </div>
                <div class="col p-5">

<<<<<<< HEAD
            <?php if ($message->deleted_at == null) {?>
                <?php if ($_POST['update'] == $message->id) {?>
                    <form action="topic.php?idTopic=<?php echo $path ?>" method="post" class="row emoji-picker-container">
                        <textarea type="text" class="form-control" name="content" rows="10" data-emojiable="true"><?php echo $message->content ?></textarea>
                        <button type="submit" name="sendUpdate" value="<?php echo $message->id ?>" class="btn btn-secondary mt-3">Modifier</button>
                    </form>
                <?php } else {?>
                    <p><?php $Parsedown = new Parsedown();
                            echo $Parsedown->text($message->content); ?></p>
                    <p class="text-right"><?php $date = new DateTime($message->updated_at); echo $date->format('H:m d/m/Y') ?></p>
                <?php }?>
            <?php } else {?>
                    <p>Le message a été supprimé !!</p>
            <?php }?>
=======
                    <?php if ($message->deleted_at == null) { ?>
                        <?php if ($_POST['update'] == $message->id) { ?>
                            <form action="topic.php?idTopic=<?php echo $path ?>" method="post" class="row emoji-picker-container">
                                <textarea type="text" class="form-control" name="content" rows="10" data-emojiable="true"><?php echo $message->content ?></textarea>
                                <button type="submit" name="sendUpdate" value="<?php echo $message->id ?>" class="btn btn-secondary mt-3">Modifier</button>
                            </form>
                        <?php } else { ?>
                            <p><?php $Parsedown = new Parsedown();
                                echo $Parsedown->text($message->content); ?></p>
                            <p class="text-right"><?php $date = new DateTime($message->updated_at);
                                                    echo $date->format('H:m d/m/Y'); ?></p>

                            <?php if ($message->signature != NULL) { ?>
                                <p><?php echo $message->signature ?></p>
                            <?php } ?>

                        <?php } ?>
                    <?php } else { ?>
                        <p>Le message a été supprimé !!</p>
                    <?php } ?>
>>>>>>> 33696bfe687df8016759e938595a7803f05a2f20

                </div>
                <div class="col-1 d-flex flex-column justify-content-around align-items-center">

                    <?php if (isset($_SESSION['idUser']) and $_SESSION['idUser'] == $message->users_id) { ?>
                        <?php if ($message->deleted_at == null) { ?>
                            <?php if (empty($_POST['update'])) { ?>
                                <form action="topic.php?idTopic=<?php echo $path ?>" method="post">
                                    <button type="submit" name="update" value="<?php echo $message->id ?>" class="btn btn-outline-primary"><i class="fas fa-edit"></i></button>
                                </form>
                                <form action="topic.php?idTopic=<?php echo $path ?>" method="post">
                                    <button type="submit" name="del" value="<?php echo $message->id ?>" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>

                </div>
            </div>
            <br>
        <?php } ?>

    </section>

    <footer>
        <h1>&nbsp;</h1>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>

    <!-- Emoji Picker -->
    <script src="lib/js/config.js"></script>
    <script src="lib/js/util.js"></script>
    <script src="lib/js/jquery.emojiarea.js"></script>
    <script src="lib/js/emoji-picker.js"></script>
    <script>
        $(function() {
            // Initializes and creates emoji set from sprite sheet
            window.emojiPicker = new EmojiPicker({
                emojiable_selector: '[data-emojiable=true]',
                assetsPath: '../lib/img/',
                popupButtonClasses: 'fa fa-smile-o'
            });
            // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
            // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
            // It can be called as many times as necessary; previously converted input fields will not be converted again
            window.emojiPicker.discover();
        });
    </script>

    <script src="js/scrollBar.js"></script>

</body>

</html>