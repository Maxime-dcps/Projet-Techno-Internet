<?php


class OeuvreDAO
{

    private $_bd;

    public function __construct($cnx)
    {
        $this->_bd = $cnx;
    }


    public function getAllOeuvres()
    {
        $query = "SELECT * FROM oeuvres ORDER BY id_oeuvre ASC";
        $oeuvres = array();

        try {

            $resultset = $this->_bd->query($query);
            $dataSet = $resultset->fetchAll();

            foreach ($dataSet as $data) {
                $oeuvres[] = new Oeuvre($data);
            }

            return $oeuvres;

        } catch (PDOException $e) {
            print "Erreur lors de la récupération des oeuvres : " . $e->getMessage();
            return [];
        }
    }

}