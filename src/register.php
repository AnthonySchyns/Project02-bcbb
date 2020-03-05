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
    $email = trim($_POST['email']);
    $nickname = trim($_POST['nickname']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);
    if (empty($nickname)) {array_push($errors, "Pseudo requis");}
    if (empty($email)) {array_push($errors, "Email requis");}
    if (empty($password)) {array_push($errors, "Mot de passe requis");}
    if ($password != $password2) {
        array_push($errors, "Les deux mots de passe ne correspondent pas");
    }
    foreach ($users as $user) {
        if ($email === $user->email) {
            array_push($errors, "L'email existe déjà");
        }
        if ($nickname === $user->nickname) {
            array_push($errors, "Ce pseudo existe déjà");
        }
    }
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
        //header('location: index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>Sign Up</title>
    </head>
    <body class="container">
        <h3 class="text-center mt-5 mb-5">Register</h3>
        <form action="http://localhost/register.php" method="post">
            <div class="form-group row justify-content-center">
                <div class="col-sm-5">
                <label for="email">
                    Email
                    <!-- Affichange erreur email requis-->
                    <?php if (count($errors) > 0): ?>
                        <?php foreach ($errors as $error): ?>
                            <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                <?php
if ($error === "Email requis") {
    echo $error;
}

if ($error === "L'email existe déjà") {
    echo $error;
}

?>
                            </span>
                        <?php endforeach?>
                    <?php endif?>
                    <!-- Affichange erreur email requis-->
                </label>
                    <input type="email" class="form-control" id="email" placeholder="example@example.com" name="email">
                </div>

            </div>
            <div class="form-group row justify-content-center">
                <div class="col-sm-5">
                    <label for="nickname">
                        Pseudo
                        <!-- Affichange erreur pseudo requis-->
                     
                        <?php if (count($errors) > 0): ?>
                            <?php foreach ($errors as $error): ?>
                                <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                    <?php
if ($error === "Pseudo requis") {
    echo $error;
}

if ($error === "Ce pseudo existe déjà") {
    echo $error;
}

?>
                                </span>
                            <?php endforeach?>
                        <?php endif?>
                    
                        <!-- Affichange erreur pseudo requis-->
                    </label>
                    <input type="text" class="form-control" id="nickname" placeholder="example55" name="nickname">
                </div>
            </div>
            <div class="form-group row justify-content-center">
                <div class="col-sm-5">
                    <label for="password">
                        Mot de passe
                        <!-- Affichange erreur mdp requis-->
                        <?php if (count($errors) > 0): ?>
                            <?php foreach ($errors as $error): ?>
                                <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                    <?php
if ($error === "Mot de passe requis") {
    echo $error;
}

if ($error === "Les deux mots de passe ne correspondent pas") {
    echo $error;
}

?>
                                </span>
                            <?php endforeach?>
                        <?php endif?>
                        <!-- Affichange erreur mdp requis-->
                    </label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
            </div>
            <div class="form-group row justify-content-center">
                <div class="col-sm-5">
                    <label for="password2">
                        Confirmer mot de passe
                        <!-- Affichange erreur mdp x 2-->
                        <?php if (count($errors) > 0): ?>
                            <?php foreach ($errors as $error): ?>
                                <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                    <?php
if ($error === "Les deux mots de passe ne correspondent pas") {
    echo $error
    ;
}
?>
                                </span>
                            <?php endforeach?>
                        <?php endif?>
                        <!-- Affichange erreur mdp x 2-->
                    </label>
                    <input type="password" class="form-control" id="password2" name="password2">
                </div>
            </div>
            <div class="form-group row justify-content-center">
                <button id="submit" type="submit" class="btn btn-secondary" name="submit">Register</button>
            </div>
        </form>
        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>