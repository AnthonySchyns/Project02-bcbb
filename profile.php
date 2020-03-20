<?php
require "connexion.php";
session_start();

if (!isset($_SESSION['idUser'])) {
  header('location: index.php');
}

$errors = array();
$errorsFile = array();
$select = "SELECT * "
  . " FROM users WHERE id = '" . $_SESSION['idUser'] . "'";
$sth = $pdo->prepare($select);
$sth->execute();
$users = $sth->fetch(PDO::FETCH_OBJ);
$sth->closeCursor();
$sth = null;
$_SESSION['profile'] = $users->avatar;
if (isset($_POST['gravatar']) && $_SESSION['profile'] != null) {
  unlink($_SESSION['profile']);
  $delete = "UPDATE users SET avatar = NULL";
  $sth = $pdo->prepare($delete);
  $sth->execute();
  $sth->closeCursor();
  $sth = null;
  $_SESSION['profile'] = null;
}
if (isset($_POST['avatar'])) {
  $file = $_FILES['imageProfile'];
  $fileName = $_FILES['imageProfile']['name'];
  $fileTmpname = $_FILES['imageProfile']['tmp_name'];
  $fileSize = $_FILES['imageProfile']['size'];
  $fileError = $_FILES['imageProfile']['error'];
  $fileType = $_FILES['imageProfile']['type'];

  $fileExt = explode('.', $fileName);
  $fileActualExt = strtolower(end($fileExt));

  $allowed = array('jpg', 'jpeg', 'png');

  if (!(in_array($fileActualExt, $allowed)) && $fileSize > 0) {
    array_push($errorsFile, "You must use a jpg, jpeg or png type image");
  }
  if (!($fileError === 0) && $fileSize > 0) {
    array_push($errorsFile, "There was an error downloading the file");
  }
  if ($fileSize >= 1000000) {
    array_push($errorsFile, "Error: file size is greater than 1 MB");
  }
  if ($fileSize === 0) {
    array_push($errorsFile, "You have to upload a file to change your avatar");
  }
  if (count($errorsFile) === 0) {
    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
    $fileDestination = "uploads/profiles/" . $fileNameNew;
    if ($_SESSION['profile'] != null) {
      unlink($_SESSION['profile']);
    }
    move_uploaded_file($fileTmpname, $fileDestination);
    $updatePro = "UPDATE users SET avatar ='" . $fileDestination . "' WHERE id='" . $_SESSION['idUser'] . "'";
    $sth = $pdo->prepare($updatePro);
    $sth->execute();
    $sth->closeCursor();
    $sth = null;
    $_SESSION['profile'] = $fileDestination;
  }
}
if (isset($_POST['submit'])) {
  // supprimer espace debut et fin chaine de charactère + empêcher insertion sql dans input

  $nickname = trim(addslashes($_POST['new_nickname']));
  $password = trim(addslashes($_POST['new_password']));
  $password2 = trim(addslashes($_POST['confirmer']));
  $signature = trim(addslashes($_POST['signature']));

  // Erreurs
  if ($password != $password2) {
    array_push($errors, "Change canceled: the two passwords did not match");
  }
  $sql = "SELECT * "
    . " FROM users WHERE id != '" . $_SESSION['idUser'] . "'";
  $sth = $pdo->prepare($sql);
  $sth->execute();
  $users = $sth->fetchAll(PDO::FETCH_OBJ);
  $sth->closeCursor();
  $sth = null;
  foreach ($users as $user) {
    if ($nickname === $user->nickname) {
      array_push($errors, "Modification canceled: nickname already existing");
    }
  }
  // Modifications des données dans la base de données
  if (count($errors) === 0) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if (empty($password)) {
      $update = "UPDATE users "
        . "SET `nickname`='" . $nickname . "', `signature`='" . $signature . "' "
        . "WHERE id = '" . $_SESSION['idUser'] . "'";
    } else {
      $update = "UPDATE users "
        . "SET `nickname`='" . $nickname . "', `password`='" . $hash . "', `signature`='" . $signature . "' "
        . "WHERE id = '" . $_SESSION['idUser'] . "'";
    }
    $sth = $pdo->prepare($update);
    $sth->execute();
    $sth->closeCursor();
    $sth = null;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Bootstrap CSS -->
  <link href="style/style.css" rel="stylesheet" type="text/css" />
  <title>Profile</title>
</head>

<body class="bg-white p-0 m-0">
  <?php
  include 'menu.php';
  $src = get_gravatar($email, $s = 120, $d = 'mp', $r = 'g', $img = false, $atts = array());
  ?>
  <h1 class="titre text-center mt-5 pt-5">Profile</h1>
  <div class="bg-light rounded border border-light container">
    <form action="profile.php" method="post" enctype="multipart/form-data">
      <div class="d-flex justify-content-center mt-4">
        <?php if ($_SESSION['profile'] === NULL) : ?>
          <img src="<?php echo $src ?>" class="rounded-circle" />
        <?php else : ?>
          <img src="<?php echo $_SESSION['profile'] ?>" style="width:120px; height:120px" class="rounded-circle" />
        <?php endif ?>
      </div>
      <div class="form-group d-flex justify-content-center mt-3 pt-4">
        <input type="submit" value="Use Gravatar" name="gravatar" class="btn btn-secondary">
      </div>
      <div class="custom-file d-flex justify-content-center">
        <input type="file" name="imageProfile" class="inputfile" id="inputfile">
        <input id="buttonfile" type="button" value="Choose an avatar" class="btn btn-primary">
      </div>
      <div class="form-group d-flex justify-content-center mt-3">
        <input type="submit" value="Change avatar" name="avatar" class="btn btn-secondary">
      </div>
      <div class="form-group d-flex flex-column mt-3">
        <?php if (count($errorsFile) > 0) : ?>
          <?php foreach ($errorsFile as $errorFile) : ?>
            <?php echo '<p class="error font-weight-bold text-center text-danger mt-O" style="font-size:10px">' . $errorFile . '</p>' ?>
          <?php endforeach ?>
        <?php endif ?>
      </div>
      <div class="form-group row mt-5">
        <label for="new_email" class="col-sm-2 col-form-label">Email :</label>
        <div class="col-sm-10">
          <input type="email" readonly class="form-control-plaintext" name="new_email" id="new_email" value="<?php echo $useru->email ?>" />
        </div>
      </div>
      <div class="form-group row mt-5">
        <label for="new_nickname" class="col-sm-2 col-form-label">Nickname :</label>
        <div class="col-sm-10">
          <input type="text" name="new_nickname" id="new_nickname" class="form-control" value="<?php echo $useru->nickname ?>" required />
        </div>
      </div>
      <div class="form-group row mt-5">
        <label for="new_password" class="col-sm-2 col-form-label">Password :</label>
        <div class="col-sm-10">
          <input type="password" name="new_password" id="new_password" class="form-control" value="" />
        </div>
      </div>
      <div class="form-group row mt-5">
        <label for="confirmer" class="col-sm-2 col-form-label">Confirm :</label>
        <div class="col-sm-10">
          <input type="password" name="confirmer" id="confirmer" class="form-control" value="" />
        </div>
      </div>
      <div class="form-group row mt-5">
        <label for="signature" class="col-sm-2 col-form-label">Signature :</label>
        <div class="col-sm-10">
          <textarea class="border border-gray" style="width:100%" id="signature" name="signature">
            <?php echo $useru->signature ?>
        </textarea>
        </div>
      </div>
      <div class="form-group mt-5 pb-3 d-flex justify-content-center">
        <input type="submit" name="submit" value="Validate" id="valider" class="btn btn-secondary justify-content-center" />
      </div>
      <?php if (count($errors) > 0) : ?>
        <?php foreach ($errors as $error) : ?>
          <p class="error text-center font-weight-bold text-danger mt-O" style="font-size:10px">
            <?php
            if ($error === "Change canceled: the two passwords did not match") {
              echo $error;
            }

            if ($error === "Modification canceled: nickname already existing") {
              echo $error;
            }

            ?>
          </p>
        <?php endforeach ?>
      <?php endif ?>
    </form>
  </div>
  </script>
  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  <script src="js/scrollBar.js"></script>
</body>

</html>