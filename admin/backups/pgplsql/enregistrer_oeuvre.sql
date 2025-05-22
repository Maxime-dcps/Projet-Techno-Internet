CREATE OR REPLACE FUNCTION enregistrer_oeuvre(
    p_titre VARCHAR,
    p_description TEXT,
    p_artiste VARCHAR,
    p_annee_creation INTEGER,
    p_dimensions VARCHAR,
    p_prix DECIMAL,
    p_image_url VARCHAR,
    p_id_type_oeuvre INTEGER,
    p_id_utilisateur INTEGER
)
RETURNS INTEGER AS
$$
DECLARE
    v_oeuvre_id INTEGER;
BEGIN

	IF p_prix IS NOT NULL AND p_prix < 0 THEN
        RETURN -1;
    END IF;

	IF p_annee_creation IS NOT NULL AND (p_annee_creation < 1000 OR p_annee_creation > EXTRACT(YEAR FROM NOW())) THEN
        RETURN -1;
    END IF;
	
    INSERT INTO oeuvres (
        titre, 
        description, 
        artiste, 
        annee_creation, 
        dimensions, 
        prix, 
        image_url, 
        date_publication,
        id_type_oeuvre, 
        id_utilisateur
    )
    VALUES (
        p_titre, 
        p_description, 
        p_artiste, 
        p_annee_creation, 
        p_dimensions, 
        p_prix, 
        p_image_url, 
        NOW(),
        p_id_type_oeuvre, 
        p_id_utilisateur
    )
    RETURNING id_oeuvre INTO v_oeuvre_id;

    RETURN v_oeuvre_id;

EXCEPTION
    WHEN OTHERS THEN
        RETURN -1;
END;
$$ 
LANGUAGE 'plpgsql';