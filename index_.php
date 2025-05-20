<?php
session_start();
include('./admin/src/php/utils/header.php');
include('./admin/src/php/utils/all_includes.php');
?>

<!doctype html>
<html>
<head>
    <title><?= isset($title) ? $title : "Templum Artis"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js" integrity="sha256-AlTido85uXPlSyyaZNsjJXeCs07eSv3r43kyCVc8ChI=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./admin/assets/css/style.css">
    <!--<script src="./admin/assets/js/fonctionsJqueryUI.js"></script>-->
</head>

<body>
    <div id="page" class="container">
        <header class="img_header"></header>
        <section>
            <nav>
                <?php
                if(file_exists('admin/src/php/utils/public_menu.php'))
                    include('admin/src/php/utils/public_menu.php')?>
            </nav>
        </section>
        <section id="contenu">
            <div class="container mt-4">
                <?php
                include($_SESSION['page']);
                ?>
            </div>
        </section>

    </div>

    <?php include('./admin/src/php/utils/footer.php') ?>

    <!-- Modal pour la connexion -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="./index_.php?page=login.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Connexion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Nom d'utilisateur">
                            <label for="username">Nom d'utilisateur</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
                            <label for="password">Mot de passe</label>
                        </div>
                        <p class="text-center mt-4 text-muted">
                            Pas encore de compte ? <a href="./index_.php?page=register.php" class="fw-bold text-decoration-none">Enregistrez-vous</a>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="login_submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="./admin/assets/js/modal.js"></script>
</body>
</html>
