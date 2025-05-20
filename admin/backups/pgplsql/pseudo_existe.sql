CREATE OR REPLACE FUNCTION pseudo_existe(p_username VARCHAR)
RETURNS BOOLEAN AS 
'
DECLARE
	v_count integer;
BEGIN
	SELECT count(*) INTO v_count FROM utilisateurs WHERE username = p_username;
	RETURN v_count > 0;
END;
'
LANGUAGE 'plpgsql';
