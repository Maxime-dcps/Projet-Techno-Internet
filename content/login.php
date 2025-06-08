<?php

$previous_page = isset($_GET['previous_page']) ? $_GET['previous_page'] : '';

$username_val = "";
$erreurs_enregistrement = [];

if(isset($_POST['login_submit']))
{
    $username_val = isset($_POST['username']) ? trim($_POST['username']) : '';
    $submitted_password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username_val)) {
        $erreurs_enregistrement["username"] = "Le nom d'utilisateur est requis.";
    }

    if (empty($submitted_password)) {
        $erreurs_enregistrement["mdp"] = "Le mot de passe est requis.";
    }

    if(empty($erreurs_enregistrement))
    {
        if(isset($cnx))
        {
            $utilisateurDAO = new UtilisateurDAO($cnx);

            $utilisateur = $utilisateurDAO->getUtilisateur($username_val); //Retourne un objet Utilisateur ou null

            if($utilisateur != NULL && password_verify($submitted_password, $utilisateur->hash))
            {
                $_SESSION['utilisateur_id'] = $utilisateur->id_utilisateur;
                $_SESSION['utilisateur_username'] = $utilisateur->username;
                $_SESSION['utilisateur_role'] = $utilisateur->role;

                /*
                if($utilisateur->role == "administrateur")
                {
                    header('Location: ./admin/index_.php?page=accueil_admin.php');
                }
                else header('Location: ./index_.php?page=accueil.php');
                */

                $page = isset($_POST["previous_page"]) && $_POST["previous_page"] != "" ? basename($_POST['previous_page']) : "accueil.php";

                header('Location: ./index_.php?page=' . $page);

                exit;
            }
            else
            {
                $erreurs_enregistrement["generale"] = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }
        else
        {
            $erreurs_enregistrement["connexion"] = "Impossible d'établir la connexion avec la base de données";
        }
    }
}

?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h2 class="card-title text-center mb-4 fw-bold">Se connecter</h2>
                    <hr>

                    <?php if (!empty($erreurs_enregistrement["connexion"])): ?>
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">Erreur de connexion</h5>
                            <p><?= $erreurs_enregistrement["connexion"] ?></p>
                        </div>
                    <?php endif; ?>

                    <form id="form-login" action="<?php print $_SERVER['PHP_SELF']; ?>" method="POST" novalidate>
                        <div class="mb-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control form-control-lg <?= (!empty($erreurs_enregistrement['username']) || !empty($erreurs_enregistrement['generale'])) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= $username_val ?>" placeholder="Nom d'utilisateur">
                                <label for="username" class="form-label">Nom d'utilisateur</label>
                                <?php if(!empty($erreurs_enregistrement["username"])): ?>
                                    <div id="username-feedback" class="invalid-feedback"><?= $erreurs_enregistrement["username"] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control form-control-lg <?= (!empty($erreurs_enregistrement['mdp']) || !empty($erreurs_enregistrement['generale'])) ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Mot de passe">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div id="password-feedback" class="invalid-feedback">
                                    <?php if(!empty($erreurs_enregistrement["mdp"])): ?>
                                        <?= $erreurs_enregistrement["mdp"] ?>
                                    <?php elseif(!empty($erreurs_enregistrement["generale"])): ?>
                                        <?= $erreurs_enregistrement["generale"] ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="previous_page" value="<?= htmlspecialchars($previous_page) ?>">

                        <div class="d-grid">
                            <button type="submit" name="login_submit" class="btn btn-primary btn-lg">Se connecter</button>
                        </div>
                    </form>

                    <p class="text-center mt-4 text-muted">
                        Pas encore de compte ? <a href="./index_.php?page=register.php" class="fw-bold text-decoration-none">Enregistrez-vous</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>