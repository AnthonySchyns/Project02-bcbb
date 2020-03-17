<?php

session_start();
if (!isset($_SESSION['idUser'])) {
    header('location: index.php');
}

require_once 'connexion.php';
// Add Message
$errors = array();
$errorsEmpty = array();
if (isset($_POST['addTopic'])) {
    $title = trim(addslashes($_POST['title']));
    $content = trim(addslashes($_POST['content']));
    $idBoards = $_POST['idBoards'];
    date_default_timezone_set('Europe/Brussels');
    $dateTime = date("Y-m-d H:i:s");
    $idUser = $_SESSION["idUser"];
    $file = $_FILES['imageUpload'];
    $fileName = $_FILES['imageUpload']['name'];
    $fileTmpname = $_FILES['imageUpload']['tmp_name'];
    $fileSize = $_FILES['imageUpload']['size'];
    $fileError = $_FILES['imageUpload']['error'];
    $fileType = $_FILES['imageUpload']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');
    if (empty($title)) {
        array_push($errorsEmpty, "Title required");
    }
    if (empty($content)) {
        array_push($errorsEmpty, "Content required");
    }
    if (!(in_array($fileActualExt, $allowed))) {
        array_push($errors, "You must use a jpg, jpeg or png type image");
    }
    if (!($fileError === 0)) {
        array_push($errors, "There was an error downloading the file");
    }
    if ($fileSize >= 1000000) {
        array_push($errors, "Error: file size is greater than 1 MB");
    }
    if ($fileSize === 0) {
        $errors = array();
    }
    if (count($errors) === 0 && count($errorsEmpty) === 0) {
        if($idBoards == 6){
            // Compter le nombre de topic du board Random
            $sqlCount = "SELECT COUNT(*) FROM topics WHERE boards_id = 6";
            $sthCount = $pdo->prepare($sqlCount);
            $sthCount->execute();
            // Lors de la création d'un topic Random
            // Si le nombre de topic Random est égal à 5
            // Alors on supprime le + vieux et on ajoute le nouveau
            if($sthCount > 5){
                $sqlDelete = "DELETE FROM topics WHERE boards_id = 6 ORDER BY created_at ASC LIMIT 1";
                $sthDelete = $pdo->prepare($sqlDelete);
                $sthDelete->execute();
            }
        }
        $sqlAjout = "INSERT INTO topics "
            . "SET title = '$title', "
            . "content = '$content', "
            . "created_at = '$dateTime', "
            . "boards_id = '$idBoards', "
            . "users_id = '$idUser' ";
        if ($fileSize > 0) {
            $fileNameNew = uniqid('', true) . "." . $fileActualExt;
            $fileDestination = "uploads/" . $fileNameNew;
            move_uploaded_file($fileTmpname, $fileDestination);
            $sqlAjout .= ", image = '$fileDestination' ";
        }
        $pdo->exec($sqlAjout);
        header("Location: index.php");
    }
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fontawesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" integrity="sha256-46qynGAkLSFpVbEBog43gvNhfrOj+BmwXdxFgVK/Kvc=" crossorigin="anonymous" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="style/style.css" rel="stylesheet" type="text/css" />
    <title>BCBB</title>
</head>

<body>
    <?php
    include 'menu.php';
    ?>
    <h1 class="text-center mt-5 pt-5">New Topic</h1>
    <section class="container mt-5">
        <div class="row border">
            <div class="col p-5">
                <form action="addTopic.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">
                            Title
                            <?php if (count($errorsEmpty) > 0) : ?>
                                <?php foreach ($errorsEmpty as $error) : ?>
                                    <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                        <?php if ($error === "Title required") : ?>
                                            <?php echo $error ?>
                                        <?php endif ?>
                                    </span>
                                <?php endforeach ?>
                            <?php endif ?>
                        </label>
                        <input type="text" class="form-control" name="title" placeholder="Title">
                    </div>
                    <div class="form-group">
                        <label for="title">
                            Message
                            <?php if (count($errorsEmpty) > 0) : ?>
                                <?php foreach ($errorsEmpty as $error) : ?>
                                    <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                        <?php if ($error === "Content required") : ?>
                                            <?php echo $error ?>
                                        <?php endif ?>
                                    </span>
                                <?php endforeach ?>
                            <?php endif ?>
                        </label>
                        <textarea type="text" class="form-control" name="content" placeholder="Message" rows="5"></textarea>
                    </div>
                    <div class="custom-file">
                        <input type="file" name="imageUpload" id="imageUpload">
                    </div>
                    <div class="form-group">
                        <label for="boards">Boards</label>
                        <select class="form-control" id="boards" name="idBoards">

                            <?php foreach ($boards as $board) { ?>
                                <option value="<?php echo $board->id ?>"><?php echo $board->name ?></option>
                            <?php } ?>

                        </select>
                    </div>
                    <button type="submit" name="addTopic" class="btn btn-secondary mt-3">Send</button>
                    <?php if (count($errors) > 0) : ?>
                        <?php foreach ($errors as $error) : ?>
                            <p class="error font-weight-bold text-danger mt-4" style="font-size:10px">
                                <?php echo $error ?>
                            </p>
                        <?php endforeach ?>
                    <?php endif ?>
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
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="js/scrollBar.js"></script>
</body>

</html>