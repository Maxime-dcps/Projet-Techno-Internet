<?php
/**
 * Ce fichier contient le formulaire de création/modification d'une oeuvre.
 * Il est conçu pour être inclus par un fichier parent (ex: create_oeuvre.php ou update_oeuvre.php).
 *
 * Les variables suivantes DOIVENT être définies dans le fichier parent avant d'inclure ce formulaire :
 *
 * @var string $form_title           Le titre du formulaire (ex: "Créer une oeuvre", "Modifier une oeuvre").
 *
 * @var string $titre_val            La valeur du champ titre.
 * @var string $description_val      La valeur du champ description.
 * @var string $artiste_val          La valeur du champ artiste.
 * @var string $annee_creation_val   La valeur du champ année de création.
 * @var string $dimensions_val       La valeur du champ dimensions.
 * @var string $prix_val             La valeur du champ prix.
 *
 * @var string $id_type_val          La valeur sélectionnée pour le type d'oeuvre.
 *
 * @var array  $erreurs     Un tableau associatif des erreurs de validation (ex: ['titre' => 'Erreur...']).
 *
 * @var array  $types                Un tableau d'objets ou de tableaux représentant les types d'oeuvre disponibles pour la liste déroulante (doivent contenir 'id_type_oeuvre' et 'nom_type').
 * @var string $submit_button_name   L'attribut 'name' du bouton de soumission (ex: "oeuvre_register", "oeuvre_update").
 * @var string $submit_button_text   Le texte affiché sur le bouton de soumission (ex: "Créer l'oeuvre", "Mettre à jour l'oeuvre").
 *
 * Les variables suivantes sont OPTIONNELLES et utilisées principalement pour le mode "mise à jour" :
 * @var bool   $is_update            Vrai si le formulaire est en mode modification, faux sinon (par défaut: false). Affecte la validation de l'image.
 * @var string $image_current_path   Le chemin relatif de l'image actuelle (pour affichage en mode modification).
 */

// Initialisation des variables optionnelles si non définies dans le parent
if (!isset($is_update)) {
    $is_update = false;
}
if (!isset($image_current_path)) {
    $image_current_path = '';
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h2 class="card-title text-center mb-4 fw-bold"><?= htmlspecialchars($form_title) ?></h2>
                    <hr>

                    <?php if (!empty($erreurs['general'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($erreurs['general']) ?>
                        </div>
                    <?php endif; ?>

                    <form id="form-oeuvre" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data" novalidate>

                        <?php if ($is_update && isset($id_oeuvre)): ?>
                            <input type="hidden" name="id_oeuvre" value="<?= htmlspecialchars($id_oeuvre) ?>">
                        <?php endif; ?>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control <?= !empty($erreurs['titre']) ? 'is-invalid' : '' ?>" id="titre" name="titre" value="<?= htmlspecialchars($titre_val) ?>" placeholder="Titre" required>
                            <label for="titre">Titre</label>
                            <div class="invalid-feedback"><?= !empty($erreurs['titre']) ? $erreurs['titre'] : '' ?></div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control <?= !empty($erreurs['description']) ? 'is-invalid' : '' ?>" id="description" name="description" placeholder="Description" required><?= htmlspecialchars($description_val) ?></textarea>
                            <label for="description">Description</label>
                            <div class="invalid-feedback"><?= !empty($erreurs['description']) ? $erreurs['description'] : '' ?></div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control <?= !empty($erreurs['artiste']) ? 'is-invalid' : '' ?>" id="artiste" name="artiste" value="<?= htmlspecialchars($artiste_val) ?>" placeholder="Artiste" required>
                            <label for="artiste">Artiste</label>
                            <div class="invalid-feedback"><?= !empty($erreurs['artiste']) ? $erreurs['artiste'] : '' ?></div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" min="1000" max="<?= date('Y') ?>" class="form-control <?= !empty($erreurs['annee_creation']) ? 'is-invalid' : '' ?>" id="annee_creation" name="annee_creation" value="<?= htmlspecialchars($annee_creation_val) ?>" placeholder="Année de création">
                            <label for="annee_creation">Année de création</label>
                            <div class="invalid-feedback"><?= !empty($erreurs['annee_creation']) ? $erreurs['annee_creation'] : '' ?></div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control <?= !empty($erreurs['dimensions']) ? 'is-invalid' : '' ?>" id="dimensions" name="dimensions" value="<?= htmlspecialchars($dimensions_val) ?>" placeholder="Dimensions">
                            <label for="dimensions">Dimensions</label>
                            <div class="invalid-feedback"><?= !empty($erreurs['dimensions']) ? $erreurs['dimensions'] : '' ?></div>
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control <?= !empty($erreurs['prix']) ? 'is-invalid' : '' ?>" id="prix" name="prix" value="<?= htmlspecialchars($prix_val) ?>" placeholder="Prix">
                                <span class="input-group-text">€</span>
                                <div class="invalid-feedback"><?= !empty($erreurs['prix']) ? $erreurs['prix'] : '' ?></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image_oeuvre" class="form-label">Photographie</label>
                            <?php if ($is_update && !empty($image_current_path)): ?>
                                <div class="mb-2">
                                    <img src="<?= htmlspecialchars($image_current_path) ?>" alt="Image actuelle de l'oeuvre" style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 2px;">
                                    <small class="form-text text-muted d-block">Laissez vide pour conserver l'image actuelle.</small>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control <?= !empty($erreurs['image']) ? 'is-invalid' : '' ?>" id="image_oeuvre" name="image_oeuvre" accept="image/*" <?= ($is_update) ? '' : 'required' ?>>
                            <div class="invalid-feedback"><?= !empty($erreurs['image']) ? $erreurs['image'] : '' ?></div>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select <?= !empty($erreurs['id_type_oeuvre']) ? 'is-invalid' : '' ?>" id="id_type_oeuvre" name="id_type_oeuvre" aria-label="Type d'oeuvre" required>
                                <option value="" <?= $id_type_val === '' ? 'selected' : '' ?>>Choisir un type</option>
                                <?php if (!empty($types)): ?>
                                    <?php foreach ($types as $type): ?>
                                        <option value="<?= htmlspecialchars($type->id_type_oeuvre) ?>" <?= (string)$id_type_val === (string)$type->id_type_oeuvre ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($type->nom_type) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <label for="id_type_oeuvre">Type d'oeuvre</label>
                            <div class="invalid-feedback"><?= !empty($erreurs['id_type_oeuvre']) ? $erreurs['id_type_oeuvre'] : '' ?></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" name="<?= htmlspecialchars($submit_button_name) ?>"><?= htmlspecialchars($submit_button_text) ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
