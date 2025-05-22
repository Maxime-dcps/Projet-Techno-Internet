<?php


class TypeOeuvreDAO
{

    private $_bd;

    public function __construct($cnx)
    {
        $this->_bd = $cnx;
    }

    public function getAllTypes()
    {
        $query = "SELECT * FROM types_oeuvre";
        $types = array();

        try {
            $stmt = $this->_bd->prepare($query);
            $stmt->execute();
            $dataSet = $stmt->fetchAll();

            foreach ($dataSet as $data) {
                $types[] = new TypeOeuvre($data);
            }

            return $types;

        } catch (PDOException $e) {
            print "Erreur lors de la récupération des types : " . $e->getMessage();
            return [];
        }
    }


}