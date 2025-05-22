CREATE OR REPLACE FUNCTION soft_delete_oeuvre(p_id_oeuvre INTEGER)
RETURNS INTEGER AS
$$
DECLARE
    v_rows_affected INTEGER;
BEGIN
    UPDATE oeuvres
    SET statut_oeuvre = 'supprime'
    WHERE id_oeuvre = p_id_oeuvre AND statut_oeuvre <> 'supprime';

    GET DIAGNOSTICS v_rows_affected = ROW_COUNT;

    RETURN v_rows_affected;

EXCEPTION
    WHEN OTHERS THEN
        RETURN -1;
END;
$$
LANGUAGE 'plpgsql';