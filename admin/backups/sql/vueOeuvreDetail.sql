CREATE OR REPLACE VIEW vue_oeuvres_details AS
SELECT 
    o.id_oeuvre, 
    o.titre, 
    o.description, 
    o.artiste, 
    o.annee_creation, 
    o.dimensions, 
    o.prix, 
    o.image_url, 
    o.date_publication, 
    o.statut_oeuvre, 
    o.id_type_oeuvre,
    o.id_utilisateur,
    t.nom_type AS nom_type_oeuvre, 
    u.username AS nom_vendeur 
FROM 
    oeuvres o
LEFT JOIN 
    types_oeuvre t ON o.id_type_oeuvre = t.id_type_oeuvre
LEFT JOIN 
    utilisateurs u ON o.id_utilisateur = u.id_utilisateur;