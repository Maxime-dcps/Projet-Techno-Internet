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

    public function getTypeOeuvreById($id_type_oeuvre)
    {
        $query = "SELECT * FROM types_oeuvre WHERE id_type_oeuvre=:id_type_oeuvre";

        try {
            $resultset = $this->_bd->prepare($query);
            $resultset->bindValue(":id_type_oeuvre",$id_type_oeuvre);
            $resultset->execute();
            $data = $resultset->fetch();
            if($data){
                return new TypeOeuvre($data);
            } else {
                return null;
            }

        }catch(PDOException $e) {
            print "Echec ".$e->getMessage();
            return null;
        }
    }
}