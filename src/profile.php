<?php 
require "connexion.php";

if(isset($_POST['submit'])){
  if ($_POST['new_password'] === $_POST['confirmer']) {
  $nickname = $_POST['new_nickname'];
  $password = $_POST['new_password'];
  $signature = $_POST['signature'];
  $update = "UPDATE users "
          . "SET `nickname`='".trim($nickname)."', `password`='".trim($password)."', `signature`='".trim($signature)."' "
          . "WHERE id = 1";
          $sth = $pdo->prepare($update);
  $sth->execute();
  $sth->closeCursor();
  $sth = NULL;
  }
}
$sql = "SELECT * "
      . "FROM users "
      . "WHERE id = 1";
$sth = $pdo->prepare($sql);
$sth->execute();
$user = $sth->fetch(PDO::FETCH_OBJ);
$sth->closeCursor();
$sth= null;
$email = $user->email;
function get_gravatar( $email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array() ) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    var_dump($url);
    return $url;
}
$src = get_gravatar( $email, $s = 120, $d = 'mp', $r = 'g', $img = false, $atts = array() );
      var_dump($src);    

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Bootstrap CSS -->
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
      integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
      crossorigin="anonymous"
    />
    <title>Profile</title>
  </head>
  <body class="bg-white p-0 m-0">
    <h1 class="titre text-center">Profile</h1>
    <div class="bg-light rounded border border-light container">
      <form action="http://localhost/profile.php" method="post">
      <div class="d-flex justify-content-center mt-4">
      <img src="<?php echo $src?>"/>
      </div>
        <div class="form-group d-flex justify-content-center mt-3 pt-4">
          <a
            href="https://fr.gravatar.com/emails/"
            target="_blank"
            class="btn btn-secondary"
            >Modifier avatar</a
          >
        </div>
        <div class="form-group row mt-5">
          <label for="new_email" class="col-sm-2 col-form-label">Email :</label>
          <div class="col-sm-10">
            <input
              type="email"
              readonly
              class="form-control-plaintext"
              name="new_email"
              id="new_email"
              value="<?php echo $user->email ?>"
            />
          </div>
        </div>
        <div class="form-group row mt-5">
          <label for="new_nickname" class="col-sm-2 col-form-label"
            >Pseudo :</label
          >
          <div class="col-sm-10">
            <input
              type="text"
              name="new_nickname"
              id="new_nickname"
              class="form-control"
              maxlength = 10
              value="<?php echo $user->nickname ?>"
              required
            />
          </div>
        </div>
        <div class="form-group row mt-5">
          <label for="new_password" class="col-sm-2 col-form-label"
            >Mot de passe :</label
          >
          <div class="col-sm-10">
            <input
              type="password"
              name="new_password"
              id="new_password"
              class="form-control"
              value="<?php echo $user->password ?>"
              required
            />
          </div>
        </div>
        <div class="form-group row mt-5">
          <label for="confirmer" class="col-sm-2 col-form-label"
            >Confirmer :</label
          >
          <div class="col-sm-10">
            <input
              type="password"
              name="confirmer"
              id="confirmer"
              class="form-control"
              value="<?php echo $user->password ?>"
              required
            />
          </div>
        </div>
        <div class="form-group row mt-5">
          <label for="signature" class="col-sm-2 col-form-label"
            >Signature :</label
          >
          <div class="col-sm-10">
            <textarea style="width:100%" id="signature" name="signature">
            <?php echo $user->signature ?>
        </textarea
            >
          </div>
        </div>
        <div class="form-group mt-5 pb-3 d-flex justify-content-center">
          <input
            type="submit"
            name="submit"
            value="Valider"
            id="valider"
            class="btn btn-secondary justify-content-center"
          />
        </div>
      </form>
    </div>
    <script>
    document.getElementById("valider").addEventListener("click", () => {
      let password = document.getElementById("new_password");
      let confirm = document.getElementById("confirmer");
      if (password.value != confirm.value)
      {
        password.style.borderColor = "red";
        confirm.style.borderColor = "red";
        alert("Vous avez mal confirmé votre mot de passe, veuillez recommencez.");
      }
    });
    </script>
    <!-- Bootstrap JS -->
    <script
      src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
      integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
      integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
      integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
