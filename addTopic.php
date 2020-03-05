<?php

session_start();

if (!isset($_SESSION['idUser'])) {
    header('location: index.php');
}

require_once 'connexion.php';

// Add Message
if (isset($_POST['addTopic'])) {
    var_dump($_POST['title']);
    $title = $_POST['title'];
    $content = $_POST['content'];
    $idBoards = $_POST['idBoards'];
    $dateTime = date("Y-m-d H:i:s");
    $idUser = $_SESSION["idUser"];

    $sqlAjout = "INSERT INTO topics "
        . "SET title = '$title', "
        . "content = '$content', "
        . "created_at = '$dateTime', "
        . "boards_id = '$idBoards', "
        . "users_id = '$idUser' ";

    $pdo->exec($sqlAjout);
    header("Location: /index.php");
}

// Get Boards
$sql = "SELECT * "
    . "FROM boards ";
$sth = $pdo->prepare($sql);
$sth->execute();
$boards = $sth->fetchAll(PDO::FETCH_OBJ);
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
        <h1 class="text-center mt-5">New Topic</h1>
        <section class="container mt-5">
            <div class="row border">
                <div class="col p-5">
                    <form action="addtopic.php" method="post">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Title">
                        </div>
                        <div class="form-group">
                            <label for="title">Message</label>
                            <textarea type="text" class="form-control" name="content" placeholder="Message" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="boards">Boards</label>
                            <select class="form-control" id="boards" name="idBoards">

                        <?php foreach($boards as $board){ ?>
                                <option value="<?php echo $board->id ?>"><?php echo $board->name ?></option>
                        <?php } ?>

                            </select>
                        </div>
                        <button type="submit" name="addTopic" class="btn btn-secondary mt-3">Envoyer</button>
                    </form>
                    <p><?php echo $topic->content ?></p>
                    <p class="text-right"><?php echo $topic->updated_at ?></p>
                </div>
            </div>
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