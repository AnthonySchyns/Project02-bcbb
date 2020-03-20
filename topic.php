<?php

session_start();

require_once 'connexion.php';

// Markdown
require('lib/Parsedown.php');
require("lib/sendgrid-php/sendgrid-php.php");

$idTopic = $_GET['idTopic'];

// Toggle Lock Topic
if (isset($_POST['lock'])) {
    if ($_POST['lock'] == 1) {
        $sqlUpdate = "UPDATE topics "
            . "SET lockTopic = 1 "
            . "WHERE id = " . $idTopic;
    } else {
        $sqlUpdate = "UPDATE topics "
            . "SET lockTopic = 0 "
            . "WHERE id = " . $idTopic;
    }

    $sth = $pdo->prepare($sqlUpdate);
    $sth->execute();
    $sth->closeCursor();
    $sth = null;
}

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
$errors = array();
if (isset($_POST['addMessage'])) {
    $content = trim(addslashes($_POST['content']));
    date_default_timezone_set('Europe/Brussels');
    $dateTime = date("Y-m-d H:i:s");
    $user = $_SESSION["idUser"];

    if (empty($content)) {
        array_push($errors, "You can't send en empty message !");
    }
    $allEmail = array();
    if (count($errors) === 0) {
        $sqlAjout = "INSERT INTO messages "
            . "SET content = '$content', "
            . "created_at = '$dateTime', "
            . "updated_at = '$dateTime', "
            . "users_id = '$user', "
            . "topics_id = '$idTopic' ";

        $sth = $pdo->prepare($sqlAjout);
        $sth->execute();
        $sth->closeCursor();
        $sth = null;
        $envoiEmail = "SELECT email FROM users WHERE id='" . $user . "' ";
        $sth = $pdo->prepare($envoiEmail);
        $sth->execute();
        $emails = $sth->fetch(PDO::FETCH_OBJ);
        $sth->closeCursor();
        $sth = null;
        array_push($allEmail, $emails->email);
        $sendEmail = array_unique($allEmail);
        foreach ($sendEmail as $emailUnique) {
            $request_body = json_decode('{
  "personalizations": [
    {
      "to": [
        {
          "email": "aschyns499@gmail.com"
        }
      ],
      "subject": "Hello World from the SendGrid PHP Library!"
    }
  ],
  "from": {
    "email": "aschyns499@gmail.com"
  },
  "content": [
    {
      "type": "text/plain",
      "value": "Hello, Email!"
    }
  ]
}');

            $apiKey = 'SENDGRID_API_KEY';
            $sg = new \SendGrid($apiKey);

            $response = $sg->client->mail()->send()->post($request_body);
            echo $response->statusCode();
            echo $response->body();
            echo $response->headers();
        }
    }
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

// Add Reaction
$errors = array();
if (isset($_POST['sendReact'])) {
    $emojiInput = trim(addslashes($_POST['reaction']));
    $json = json_encode($emojiInput);
    $idMessage = $_POST["sendReact"];

    $sqlVerif = "SELECT * "
        . "FROM reactions "
        . "WHERE json IN (" . $json . ")";
    $sth = $pdo->prepare($sqlVerif);
    $sth->execute();
    $emoji = $sth->fetch(PDO::FETCH_OBJ);
    $sth->closeCursor();
    $sth = null;

    $sqlVerif = "SELECT * "
        . "FROM reactions_has_messages "
        . "WHERE messages_id IN (" . $idMessage . ") "
        . "AND reactions_id IN (" . $emoji->id . ")";
    $sth = $pdo->prepare($sqlVerif);
    $sth->execute();
    $lienEmoji = $sth->fetch(PDO::FETCH_OBJ);
    $sth->closeCursor();
    $sth = null;
    var_dump($lienEmoji, $idMessage, $emoji->id);

    if (!$emoji) {
        $sqlAjout = "INSERT INTO reactions "
            . "SET emoji = '" . $emojiInput . "', "
            . "json = " . $json;
        $sth = $pdo->prepare($sqlAjout);
        $sth->execute();
        $sth->closeCursor();
        $sth = null;

        $sql = "SELECT LAST_INSERT_ID() "
            . "FROM reactions ";
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $idEmoji = $sth->fetch();
        $sth->closeCursor();
        $sth = null;

        $sqlAjout = "INSERT INTO reactions_has_messages "
            . "SET reactions_id = '" . $idEmoji[0] . "', "
            . "messages_id = '" . $idMessage . "', "
            . "count = '1' ";
        $sth = $pdo->prepare($sqlAjout);
        $sth->execute();
        $sth->closeCursor();
        $sth = null;
    } else if ($emoji and !$lienEmoji) {
        $sqlAjout = "INSERT INTO reactions_has_messages "
            . "SET reactions_id = '" . $emoji->id . "', "
            . "messages_id = '" . $idMessage . "', "
            . "count = '1' ";
        $sth = $pdo->prepare($sqlAjout);
        $sth->execute();
        $sth->closeCursor();
        $sth = null;
    } else {
        $sqlUpdate = "UPDATE reactions_has_messages "
            . "SET count = count + 1 "
            . "WHERE reactions_id = '" . $emoji->id . "' "
            . "AND messages_id = '" . $idMessage . "'";

        $sth = $pdo->prepare($sqlUpdate);
        $sth->execute();
        $sth->closeCursor();
        $sth = null;
    }
}

// Get Reaction
$sql = "SELECT * "
    . "FROM reactions "
    . "LEFT JOIN reactions_has_messages "
    . "ON reactions.id = reactions_has_messages.reactions_id "
    . "LEFT JOIN messages "
    . "ON messages.id = reactions_has_messages.messages_id ";
$sth = $pdo->prepare($sql);
$sth->execute();
$reactions = $sth->fetchAll(PDO::FETCH_OBJ);
$sth->closeCursor();
$sth = null;

//var_dump($reactions[0]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fontawesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" integrity="sha256-46qynGAkLSFpVbEBog43gvNhfrOj+BmwXdxFgVK/Kvc=" crossorigin="anonymous" />

    <!-- Emoji Picker -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="lib/css/emoji.css" rel="stylesheet">
    <link href="style/style.css" rel="stylesheet" type="text/css" />
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
                <p class="text-center">Topic created<br /><?php $date = new DateTime($topic->created_at);
                                                            echo $date->format('H:m d/m/Y') ?></p>
            </div>
            <div class="col p-5">
                <p><?php echo $topic->content ?></p>
                <?php if ($topic->image !== null) : ?>
                    <img src="<?php echo $topic->image ?>" style="width:300px; height:250px" class="mt-4" />
                <?php endif ?>
            </div>

            <?php if (isset($_SESSION['idUser']) and $_SESSION['idUser'] == $topic->users_id) { ?>
                <form action="topic.php?idTopic=<?php echo $idTopic ?>" method="post" class="col-1 align-self-end mb-3">

                    <?php if ($topic->lockTopic == 0) { ?>
                        <button type="submit" name="lock" value="1" class="btn btn-outline-danger">Lock</button>
                    <?php } else { ?>
                        <button type="submit" name="lock" value="0" class="btn btn-outline-secondary">Unlock</button>
                    <?php } ?>

                </form>
            <?php } ?>
        </div>
    </section>

    <?php if (isset($_SESSION['idUser']) and $topic->lockTopic == 0 and $messages[0]->users_id != $_SESSION['idUser']) { ?>

        <section class="container mt-5">
            <h3 class="mb-5">
                Your Message
                <?php if (count($errors) > 0) { ?>
                    <?php foreach ($errors as $error) { ?>
                        <span class="error font-weight-bold text-danger ml-2" style="font-size:10px">
                            <?php echo $error ?>
                        </span>
                    <?php } ?>
                <?php } ?>
            </h3>
            <form action="topic.php?idTopic=<?php echo $idTopic ?>" method="post" class="row emoji-picker-container">
                <textarea type="text" class="form-control" name="content" placeholder="Message" rows="5" data-emojiable="true"></textarea>
                <button type="submit" name="addMessage" class="btn btn-secondary mt-3">Send</button>
            </form>
        </section>

    <?php } ?>

    <section class="container mt-5">
        <h3 class="mb-5">Messages</h3>

        <?php foreach ($messages as $message) { ?>

            <div class="border">
                <div class="row">
                    <div class="col-md-2 border-right p-5 align-middle">
                        <img src="<?php echo get_gravatar($message->email); ?>" alt="image user" class="img-thumbnail">
                        <p class="text-center mt-4 font-weight-bold"><?php echo $message->nickname ?></p>
                    </div>
                    <div class="col p-5">

                        <?php if ($message->deleted_at == null) { ?>
                            <?php if ($_POST['update'] == $message->id) { ?>
                                <form action="topic.php?idTopic=<?php echo $idTopic ?>" method="post" class="row emoji-picker-container">
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
                            <p>Message has been deleted !!</p>
                        <?php } ?>

                    </div>
                    <div class="col-1 d-flex flex-column justify-content-around align-items-center">

                        <?php if ((isset($_SESSION['idUser']) and $_SESSION['idUser'] == $message->users_id) and $topic->lockTopic == 0) { ?>
                            <?php if ($message->deleted_at == null) { ?>
                                <?php if (empty($_POST['update'])) { ?>
                                    <?php if ($message->id == $messages[0]->id) { ?>

                                        <form action="topic.php?idTopic=<?php echo $idTopic ?>" method="post">
                                            <button type="submit" name="update" value="<?php echo $message->id ?>" class="btn btn-outline-primary"><i class="fas fa-edit"></i></button>
                                        </form>

                                    <?php } ?>
                                    <form action="topic.php?idTopic=<?php echo $idTopic ?>" method="post">
                                        <button type="submit" name="del" value="<?php echo $message->id ?>" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="row d-flex flex-row-reverse container">
                    <form action="topic.php?idTopic=<?php echo $idTopic ?>" method="post">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">

                                    <?php foreach ($reactions as $reaction) { ?>
                                        <?php if ($reaction->messages_id == $message->id) { ?>
                                            <?php echo $reaction->emoji . " " . $reaction->count . "  " ?>
                                        <?php } ?>
                                    <?php } ?>

                                </span>
                            </div>
                            <div class=" emoji-picker-container">
                                <input id="test" type="text" class="form-control" name="reaction" data-emojiable="true" maxlength="1" required="required">
                            </div>
                            <div class="input-group-append">
                                <button type="submit" name="sendReact" value="<?php echo $message->id ?>" class="btn btn-secondary">Send</button>
                            </div>
                        </div>
                    </form>
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