<?php
require "connexion.php";
session_start();
$errors = array();
if (isset($_POST['submit'])) {
    $sql = "SELECT * "
        . " FROM users";
    $sth = $pdo->prepare($sql);
    $sth->execute();
    $users = $sth->fetchAll(PDO::FETCH_OBJ);
    $sth->closeCursor();
    $sth = null;
    // supprimer espace debut et fin chaine de charactère + empêcher insertion sql dans input
    $email = trim(addslashes($_POST['email']));
    $nickname = trim(addslashes($_POST['nickname']));
    $password = trim(addslashes($_POST['password']));
    $password2 = trim(addslashes($_POST['password2']));
    // gestion des erreurs
    if (empty($nickname)) {
        array_push($errors, "Nickname required");
    }
    if (empty($email)) {
        array_push($errors, "Email required");
    }
    if (empty($password)) {
        array_push($errors, "Password required");
    }
    if ($password != $password2) {
        array_push($errors, "Passwords don't match");
    }
    foreach ($users as $user) {
        if ($email === $user->email) {
            array_push($errors, "Email already exists");
        }
        if ($nickname === $user->nickname) {
            array_push($errors, "Nickname already exists");
        }
    }
    // création de l'utilisateur dans la base de donnée
    if (count($errors) == 0) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $insert = "INSERT INTO users (email, nickname, password, signature, avatar)
                VALUES ('$email', '$nickname', '$hash', NULL, NULL)";
        $sth = $pdo->prepare($insert);
        $sth->execute();
        $sth->closeCursor();
        $sth = null;
        $sql = "SELECT * "
            . " FROM users WHERE nickname = '" . $nickname . "'";
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $user = $sth->fetch(PDO::FETCH_OBJ);
        $sth->closeCursor();
        $sth = null;
        $_SESSION['idUser'] = $user->id;
        $_SESSION['nickname'] = $nickname;
        $_SESSION['success'] = "Vous êtes maintenant connecté";
        header('location: index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css" type="text/css" />
    <title>Sign Up</title>
</head>

<body>
    <?php
    include 'menu.php';
    ?>
    <div class="container">
        <h3 class="text-center mt-5 pt-5 mb-5">Register</h3>
        <form action="register.php" method="post">
            <div class="form-group row justify-content-center">
                <div class="col-sm-5">
                    <label for="email">
                        Email
                        <!-- Affichange erreur Email required-->
                        <?php if (count($errors) > 0) : ?>
                            <?php foreach ($errors as $error) : ?>
                                <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                    <?php
                                    if ($error === "Email required") {
                                        echo $error;
                                    }

                                    if ($error === "Email already exists") {
                                        echo $error;
                                    }

                                    ?>
                                </span>
                            <?php endforeach ?>
                        <?php endif ?>
                        <!-- Affichange erreur Email required-->
                    </label>
                    <input type="email" class="form-control" id="email" placeholder="example@example.com" name="email">
                </div>

            </div>
            <div class="form-group row justify-content-center">
                <div class="col-sm-5">
                    <label for="nickname">
                        Nickname
                        <!-- Affichange erreur Nickname required-->

                        <?php if (count($errors) > 0) : ?>
                            <?php foreach ($errors as $error) : ?>
                                <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                    <?php
                                    if ($error === "Nickname required") {
                                        echo $error;
                                    }

                                    if ($error === "Nickname already exists") {
                                        echo $error;
                                    }

                                    ?>
                                </span>
                            <?php endforeach ?>
                        <?php endif ?>

                        <!-- Affichange erreur Nickname required-->
                    </label>
                    <input type="text" class="form-control" id="nickname" placeholder="example55" name="nickname">
                </div>
            </div>
            <div class="form-group row justify-content-center">
                <div class="col-sm-5">
                    <label for="password">
                        Password
                        <!-- Affichange erreur mdp requis-->
                        <?php if (count($errors) > 0) : ?>
                            <?php foreach ($errors as $error) : ?>
                                <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                    <?php
                                    if ($error === "Password required") {
                                        echo $error;
                                    }

                                    if ($error === "Passwords don't match") {
                                        echo $error;
                                    }

                                    ?>
                                </span>
                            <?php endforeach ?>
                        <?php endif ?>
                        <!-- Affichange erreur mdp requis-->
                    </label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
            </div>
            <div class="form-group row justify-content-center">
                <div class="col-sm-5">
                    <label for="password2">
                        Confirm password
                        <!-- Affichange erreur mdp x 2-->
                        <?php if (count($errors) > 0) : ?>
                            <?php foreach ($errors as $error) : ?>
                                <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                    <?php
                                    if ($error === "Passwords don't match") {
                                        echo $error;
                                    }
                                    ?>
                                </span>
                            <?php endforeach ?>
                        <?php endif ?>
                        <!-- Affichange erreur mdp x 2-->
                    </label>
                    <input type="password" class="form-control" id="password2" name="password2">
                </div>
            </div>
            <div class="form-group row justify-content-center ml-2">
                <button id="submit" type="submit" class="btn btn-secondary col-sm-3" name="submit">Register</button>
            </div>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>