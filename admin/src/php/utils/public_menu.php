<nav class="navbar navbar-expand-lg navbar-custom fixed-top shadow-sm" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center text-decoration-none" href="index_.php?page=accueil.php">
            <img src="./admin/assets/images/logo.png" alt="Logo Galerie" width="30" height="30" class="me-2">
            <span>Templum Artis</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Catégories
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="#">Peinture</a></li>
                        <li><a class="dropdown-item" href="#">Sculpture</a></li>
                        <li><a class="dropdown-item" href="#">Photographie</a></li>
                        <li><a class="dropdown-item" href="#">Art numérique</a></li>
                        <li><a class="dropdown-item" href="#">Dessin</a></li>
                    </ul>
                </li>

                <?php if(!isset($_SESSION["utilisateur_id"])):?>
                    <li class="nav-item ms-lg-3">
                        <a id="login-link" class="nav-link <?php // if ($currentPage == 'connexion.php') echo 'active'; ?>"
                           href="./index_.php?page=login.php">
                           Connexion
                        </a>
                    </li>

                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary btn-sm <?php // if ($currentPage == 'enregistrement.php') echo 'active'; ?>"
                           href="./index_.php?page=register.php" role="button">
                           Inscription
                        </a>
                    </li>
                <?php else:?>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link"
                           href="./index_.php?page=create_oeuvre.php">
                            Nouvelle annonce
                        </a>
                    </li>

                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary btn-sm"
                           href="./index_.php?page=disconnect.php" role="button">
                           Déconnexion
                        </a>
                    </li>
                <?php endif;?>

            </ul>
        </div>
    </div>
</nav>
