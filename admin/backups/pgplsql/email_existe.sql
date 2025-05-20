CREATE OR REPLACE FUNCTION email_existe(p_email VARCHAR)
RETURNS BOOLEAN AS 
'
DECLARE
	v_count integer;
BEGIN
	SELECT count(*) INTO v_count FROM utilisateurs WHERE UPPER(email) = UPPER(p_email);
	RETURN v_count > 0;
END;
'
LANGUAGE 'plpgsql';