CREATE OR REPLACE FUNCTION update_oeuvre(
    p_id_oeuvre INTEGER,
    p_titre TEXT,
    p_description TEXT,
    p_artiste TEXT,
    p_id_type_oeuvre INTEGER,
    p_id_utilisateur INTEGER,
    p_annee_creation INTEGER, 
    p_dimensions TEXT, 
    p_prix NUMERIC,
    p_image_url TEXT
)
RETURNS INTEGER
AS 
$$
DECLARE
	v_rows_affected INTEGER;
BEGIN
	UPDATE oeuvres SET
		titre = p_titre,
		description = p_description,
		artiste = p_artiste,
		id_type_oeuvre = p_id_type_oeuvre,
		id_utilisateur = p_id_utilisateur,
		annee_creation = p_annee_creation,
		dimensions = p_dimensions,
		prix = p_prix,
		image_url = p_image_url
	WHERE id_oeuvre = p_id_oeuvre;

	GET DIAGNOSTICS v_rows_affected = ROW_COUNT;

	RETURN v_rows_affected;
EXCEPTION
	WHEN OTHERS THEN
		RETURN -1;
END;
$$
LANGUAGE 'plpgsql';