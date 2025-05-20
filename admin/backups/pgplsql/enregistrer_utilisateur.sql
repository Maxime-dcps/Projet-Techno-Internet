CREATE OR REPLACE FUNCTION enregistrer_utilisateur(p_username VARCHAR(50), p_email VARCHAR(255), p_hash VARCHAR(255))
RETURNS INTEGER AS 
$$
DECLARE
    v_user_id INTEGER;
BEGIN
    
    IF email_existe(p_email) OR pseudo_existe(p_username) THEN
        RETURN -1;
    END IF;

    INSERT INTO utilisateurs (username, email, hash, role, est_actif, date_inscription)
    VALUES (p_username, p_email, p_hash, 'utilisateur', TRUE, NOW())
    RETURNING id_utilisateur INTO v_user_id;

    RETURN v_user_id;
	
END;
$$ 
LANGUAGE 'plpgsql';
