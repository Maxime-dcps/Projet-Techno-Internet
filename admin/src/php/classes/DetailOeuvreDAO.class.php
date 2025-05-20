<?php


class DetailOeuvreDAO
{

    private $_bd;

    public function __construct($cnx)
    {
        $this->_bd = $cnx;
    }


    public function getDetailOeuvresById($id_oeuvre)
    {
        $query = "SELECT * FROM vue_oeuvres_details WHERE id_oeuvre=:id_oeuvre ORDER BY id_oeuvre";

        try {
            $resultset = $this->_bd->prepare($query);
            $resultset->bindValue(":id_oeuvre",$id_oeuvre);
            $resultset->execute();
            $data = $resultset->fetch();
            if($data){
                return new DetailOeuvre($data);
            } else {
                return null;
            }

        }catch(PDOException $e) {
            print "Echec ".$e->getMessage();
        }
    }

}