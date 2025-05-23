<nav class="navbar navbar-expand-lg navbar-custom fixed-top shadow-sm" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center text-decoration-none" href="index_.php?page=accueil.php">
            <img src="./admin/assets/images/logo.png" alt="Logo Galerie" width="45" height="45" class="me-4">
            <span id="text-logo">Templum Artis</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <div class="d-none d-lg-flex w-100 align-items-center">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./index_.php?page=create_oeuvre.php">Partager Votre Art</a>
                    </li>
                </ul>

                <form class="d-flex ms-auto me-3 my-2 my-lg-0" role="search" action="./index_.php">
                    <input type="hidden" name="page" value="accueil.php">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Rechercher" aria-label="Rechercher" name="q">
                        <button class="btn btn-primary input-group-text" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Liens de connexion/déconnexion pour le bureau -->
                <ul class="navbar-nav align-items-lg-center">
                    <?php if(!isset($_SESSION["utilisateur_id"])):?>
                        <li class="nav-item ms-lg-3">
                            <a id="login-link" class="btn btn-primary btn-sm" href="./index_.php?page=login.php" >
                                <i class="fa-solid fa-user fa-2x"></i>
                            </a>
                        </li>
                    <?php else:?>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-secondary btn-sm" href="./index_.php?page=disconnect.php" role="button" id="logout-btn">
                                <i class="fas fa-sign-out-alt fa-2x"></i>
                            </a>
                        </li>
                    <?php endif;?>
                </ul>
            </div>

            <!-- CONTENU POUR LE MOBILE (menu burger) -->
            <div class="d-lg-none">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index_.php?page=accueil.php">Accueil</a>
                    </li>

                    <?php if(isset($_SESSION["utilisateur_id"])):?>
                        <li class="nav-item">
                            <a class="nav-link" href="./index_.php?page=create_oeuvre.php">Partager Votre Art</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./index_.php?page=disconnect.php">Déconnexion</a>
                        </li>
                    <?php else:?>
                        <li class="nav-item">
                            <a class="nav-link" href="./index_.php?page=login.php">Connexion / Inscription</a>
                        </li>
                    <?php endif;?>

                    <li class="nav-item mt-3">
                        <form class="d-flex" role="search" action="./index_.php">
                            <input type="hidden" name="page" value="accueil.php">
                            <input class="form-control me-2" type="search" placeholder="Rechercher" aria-label="Rechercher" name="q">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>