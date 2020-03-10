<?php

require_once 'connexion.php';

session_start();
$errors = array();
if (isset($_POST['submit'])) {
// empecher injections sql dans les inputs
$email = addslashes($_POST['email']);
$password = addslashes($_POST['password']);

$sql = "SELECT * "
    . "FROM users "
    . "WHERE email = '" . $email . "'";
$sth = $pdo->prepare($sql);
$sth->execute();
$userCo = $sth->fetch(PDO::FETCH_OBJ);
$isValid = password_verify($password, $userCo->password);
if(empty($email)) {array_push($errors, "Email requis");}
if(empty($password)) {array_push($errors, "Mot de passe requis");}
if($email != $userCo->email or $isValid == false)
{
    array_push($errors, "L'email ou le mot de passe saisi est incorrect");
}
if($email == $userCo->email){
    if($isValid){
        $_SESSION["idUser"] = $userCo->id;
        header("Location: index.php");
    }
}
$sth->closeCursor();
$sth = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style/style.css" type="text/css"/>
        <title>Sign In</title>
    </head>
    <body>
        <?php
            include 'menu.php';
        ?>
        <div class="container">
        <h3 class="text-center mt-5 pt-5 mb-5">Login</h3>
        <form action="login.php" method="post">
            <div class="form-group row justify-content-center">
                <div class="col-sm-5">
                    <label for="email">
                        Email
                        <?php if (count($errors) > 0) :?>
                        <?php foreach ($errors as $error) :?>
                            <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                <?php if($error === "Email requis")echo $error?>
                            </span>
                        <?php endforeach?>
                    <?php endif?>
                    </label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="email@example.com">
                </div>
            </div>
            <div class="form-group row justify-content-center">
                <div class="col-sm-5">
                    <label for="password">
                        Password
                        <?php if (count($errors) > 0) :?>
                        <?php foreach ($errors as $error) :?>
                            <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                                <?php if($error === "Mot de passe requis")echo $error?>
                            </span>
                        <?php endforeach?>
                    <?php endif?>
                    </label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>
            <div class="form-group row justify-content-center ml-2">
                <button type="submit" name="submit" class="btn btn-secondary col-sm-3">Login</button>
            </div>
            <div class="text-center">
            <?php if (count($errors) > 0) :?>
                <?php foreach ($errors as $error) :?>
                    <span class="error text-center font-weight-bold text-danger ml-1" style="font-size:10px">
                        <?php if($error === "L'email ou le mot de passe saisi est incorrect")echo $error?>
                    </span>
                <?php endforeach?>
            <?php endif?>
            </div>
        </form>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    </body>
</html>