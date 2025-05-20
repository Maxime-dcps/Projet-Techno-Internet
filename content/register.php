<?php

$erreurs_enregistrement = [];
$username_val = '';
$email_val = '';
$message_succes_enregistrement = '';

if(isset($cnx)) $utilisateurDAO = new UtilisateurDAO($cnx);

if (isset($_POST['action_register'])) {

    $username_val = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email_val = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

    // Validation du nom d'utilisateur
    if (empty($username_val)) {
        $erreurs_enregistrement["username"] = "Le nom d'utilisateur est requis.";
    } elseif (strlen($username_val) < 4) {
        $erreurs_enregistrement["username"] = "Minimum 4 caractères.";
    } elseif (strlen($username_val) > 24) {
        $erreurs_enregistrement["username"] = "Maximum 24 caractères.";
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $username_val)) {
        $erreurs_enregistrement["username"] = "Caractères autorisés : lettres, chiffres et underscore.";
    } elseif ($utilisateurDAO->pseudoExiste($username_val)) {
        $erreurs_enregistrement["username"] = "Ce nom d'utilisateur est déjà pris.";
    }

    // Validation de l'email
    if (empty($email_val)) {
        $erreurs_enregistrement["email"] = "L'adresse e-mail est requise.";
    } elseif (!filter_var($email_val, FILTER_VALIDATE_EMAIL)) {
        $erreurs_enregistrement["email"] = "L'adresse e-mail n'est pas valide.";
    } elseif ($utilisateurDAO->emailExiste($email_val)) {
        $erreurs_enregistrement["email"] = "Cette adresse e-mail est déjà utilisée.";
    }

    // Validation du mot de passe
    if (empty($password)) {
        $erreurs_enregistrement["mdp"] = "Le mot de passe est requis.";
    } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $password)) {
        $erreurs_enregistrement["mdp"] = "Le mot de passe doit contenir au moins 6 caractères, une lettre et un chiffre.";
    }

    if ($password !== $password_confirm) {
        $erreurs_enregistrement["mdp-confirm"] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($erreurs_enregistrement)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $idNewUser = $utilisateurDAO->enregistrerUtilisateur($username_val, $email_val, $password_hash);

        if ($idNewUser > 0) {
            $message_succes_enregistrement = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            $username_val = '';
            $email_val = '';
        } else {
            $erreurs_enregistrement["register"] = "Une erreur s'est produite lors de l'inscription. Veuillez réessayer.";
        }
    }
}

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h2 class="card-title text-center mb-4 fw-bold" style="color: #5D4037;">Créer un compte</h2>

                    <?php if (!empty($message_succes_enregistrement)): ?>
                        <div class="alert alert-success" role="alert">
                            <?= $message_succes_enregistrement ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($erreurs_enregistrement["register"])): ?>
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">Erreur d'inscription</h5>
                            <p><?= $erreurs_enregistrement["register"] ?></p>
                        </div>
                    <?php endif; ?>

                    <form id="form-enregistrement" action="<?php print $_SERVER['PHP_SELF']; ?>" method="POST" novalidate>
                        <div class="mb-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control form-control-lg <?= !empty($erreurs_enregistrement['username']) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= $username_val ?>" placeholder="Nom d'utilisateur">
                                <label for="username" class="form-label">Nom d'utilisateur</label>
                                <div id="username-feedback" class="invalid-feedback">
                                    <?php if(!empty($erreurs_enregistrement["username"])): ?>
                                        <?= $erreurs_enregistrement["username"] ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control form-control-lg <?= !empty($erreurs_enregistrement['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= $email_val ?>" placeholder="Adresse mail">
                                <label for="email" class="form-label">Adresse mail</label>
                                <div id="email-feedback" class="invalid-feedback">
                                    <?php if(!empty($erreurs_enregistrement["email"])): ?>
                                        <?= $erreurs_enregistrement["email"] ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control form-control-lg <?= !empty($erreurs_enregistrement['mdp']) ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Mot de passe">
                                <label for="password" class="form-label">Mot de passe</label>
                                <?php if(!empty($erreurs_enregistrement["mdp"])): ?>
                                    <div id="password-feedback" class="invalid-feedback"><?= $erreurs_enregistrement["mdp"] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control form-control-lg <?= !empty($erreurs_enregistrement['mdp-confirm']) ? 'is-invalid' : '' ?>" id="password_confirm" name="password_confirm" placeholder="Confirmer le mot de passe">
                                <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                                <?php if(!empty($erreurs_enregistrement["mdp-confirm"])): ?>
                                    <div id="password-confirm-feedback" class="invalid-feedback"><?= $erreurs_enregistrement["mdp-confirm"] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-grid" id="register-btn-div">
                            <button type="submit" name="action_register" class="btn btn-primary btn-lg" id="register-btn">S'inscrire</button>
                        </div>
                    </form>

                    <p class="text-center mt-4 text-muted">
                        Déjà un compte ? <a href="./index_.php?page=connexion.php" class="fw-bold text-decoration-none" style="color: #a07e70;">Connectez-vous</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./admin/assets/js/register.js"></script>
