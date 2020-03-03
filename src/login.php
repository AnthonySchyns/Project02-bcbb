<?php

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="./styles/style.css"/>
        <title>Sing In</title>
    </head>
    <body class="container">
        <h3 class="text-center mt-5 mb-5">Login</h3>
        <form>
            <div class="form-group row justify-content-center">
                <label for="email" class="col-sm-1 col-form-label">Email</label>
                <div class="col-sm-4">
                    <input type="email" class="form-control" id="email" value="email@example.com">
                </div>
            </div>
            <div class="form-group row justify-content-center">
                <label for="password" class="col-sm-1 col-form-label">Password</label>
                <div class="col-sm-4">
                    <input type="password" class="form-control" id="password">
                </div>
            </div>
            <div class="form-group row justify-content-center ml-2">
                <button type="submit" class="btn btn-secondary col-sm-3">Login</button>
            </div>
        </form>
        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>