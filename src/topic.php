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
        <h1 class="text-center mt-5">Titre Topic</h1>
        <section class="container mt-5">
            <div class="row border">
                <div class="col-md-2 border-right p-5">
                    <img src="img/profil-test.webp" alt="image user" class="img-thumbnail">
                    <p class="text-center mt-4 font-weight-bold">User Name</p>
                </div>
                <div class="col p-5">
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Velit obcaecati corporis ratione quos
                        exercitationem fuga, sint eaque. Similique nesciunt accusantium recusandae soluta repellat cum,
                        nulla, eveniet libero cumque modi doloribus.Lorem ipsum dolor sit, amet consectetur adipisicing
                        elit. Velit obcaecati corporis ratione quos exercitationem fuga, sint eaque. Similique nesciunt
                        accusantium recusandae soluta repellat cum, nulla, eveniet libero cumque modi doloribus.Lorem
                        ipsum dolor sit, amet consectetur adipisicing elit. Velit obcaecati corporis ratione quos
                        exercitationem fuga, sint eaque. Similique nesciunt accusantium recusandae soluta repellat cum,
                        nulla, eveniet libero cumque modi doloribus.</p>
                    <p class="text-right">02/03/2020</p>
                </div>
            </div>
        </section>
        <section class="container mt-5">
            <h3 class="mb-5">Votre Message</h3>
            <form action="index.php" method="post" class="row">
                <textarea type="text" class="form-control" placeholder="Message"></textarea>
                <button type="submit" class="btn btn-secondary mt-3">Envoyer</button>
            </form>
        </section>

        <section class="container mt-5">
            <h3 class="mb-5">Messages</h3>
            <div class="row border">
                <div class="col-md-2 border-right p-5 align-middle">
                    <img src="img/profil-test.webp" alt="image user" class="img-thumbnail">
                    <p class="text-center mt-4 font-weight-bold">User Name</p>
                </div>
                <div class="col p-5">
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Velit obcaecati corporis ratione quos
                        exercitationem fuga, sint eaque. Similique nesciunt accusantium recusandae soluta repellat cum,
                        nulla, eveniet libero cumque modi doloribus.Lorem ipsum dolor sit, amet consectetur adipisicing
                        elit. Velit obcaecati corporis ratione quos exercitationem fuga, sint eaque. Similique nesciunt
                        accusantium recusandae soluta repellat cum, nulla, eveniet libero cumque modi doloribus.Lorem
                        ipsum dolor sit, amet consectetur adipisicing elit. Velit obcaecati corporis ratione quos
                        exercitationem fuga, sint eaque. Similique nesciunt accusantium recusandae soluta repellat cum,
                        nulla, eveniet libero cumque modi doloribus.</p>
                    <p class="text-right">02/03/2020</p>
                </div>
                <div class="col-1 d-flex flex-column justify-content-around align-items-center">
                    <a href="#"><i class="fas fa-edit"></i></a>
                    <a href="#"><i class="fas fa-trash-alt"></i></a>
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