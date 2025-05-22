<?php


class OeuvreDAO
{

    private $_bd;

    public function __construct($cnx)
    {
        $this->_bd = $cnx;
    }

    public function getAllOeuvres($filtre_type_id = null, $order_by = 'id_oeuvre', $order_direction = 'ASC', $asArray = false)
    {
        $query = "SELECT * FROM oeuvres";
        $colonnes_autorisees = ['id_oeuvre', 'date_publication', 'prix'];
        $directions_autorisees = ['ASC', 'DESC'];

        if (!in_array($order_by, $colonnes_autorisees)) {
            $order_by = 'id_oeuvre';
        }
        if (!in_array($order_direction, $directions_autorisees)) {
            $order_direction = 'ASC';
        }

        //$query .= " WHERE status_oeuvre = 'disponible'";

        if (!is_null($filtre_type_id)) {
            $query .= " WHERE id_type_oeuvre = :type_id";
            //$query .= " AND id_type_oeuvre = :type_id";
        }

        $query .= " ORDER BY $order_by $order_direction";

        try {
            $stmt = $this->_bd->prepare($query);
            if (!is_null($filtre_type_id)) $stmt->bindValue(":type_id", $filtre_type_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($asArray) {
                return $stmt->fetchAll();
            } else {
                $dataSet = $stmt->fetchAll();
                $oeuvres = [];
                foreach ($dataSet as $data) {
                    $oeuvres[] = new Oeuvre($data);
                }
                return $oeuvres; // pour usage objet
            }

        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des oeuvres : " . $e->getMessage());
            return [];
        }
    }

    public function createOeuvre(string $titre, string $description, string $artiste, int $id_type_oeuvre, int $id_utilisateur, int $annee_creation = null, string $dimensions = null, float $prix = null, string $image_url = "/images/placeholder.png")
    {
        $query = "SELECT enregistrer_oeuvre(:titre, :description, :artiste, :annee_creation, :dimensions, :prix, :image_url, :id_type_oeuvre, :id_utilisateur)";


        try {
            $stmt = $this->_bd->prepare($query);
            $stmt->bindValue(':titre', $titre);
            $stmt->bindValue(':description', $description);
            $stmt->bindValue(':artiste', $artiste);
            $stmt->bindValue(':annee_creation', $annee_creation, $annee_creation === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':dimensions', $dimensions, $dimensions === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
            if ($prix === null) {
                $stmt->bindValue(':prix', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':prix', $prix);
            }
            $stmt->bindValue(':image_url', $image_url, $image_url === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $stmt->bindValue(':id_type_oeuvre', $id_type_oeuvre);
            $stmt->bindValue(':id_utilisateur', $id_utilisateur);

            $stmt->execute();

            return $stmt->fetchColumn();

        } catch (PDOException $e) {
            print "Erreur DB (createOeuvre): " . $e->getMessage();
            return -1;
        }
    }


}