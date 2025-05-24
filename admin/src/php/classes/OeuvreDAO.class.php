<?php


class OeuvreDAO
{

    private $_bd;

    public function __construct($cnx)
    {
        $this->_bd = $cnx;
    }

    public function getAllOeuvres($filtre_type_id = null, $order_by = 'id_oeuvre', $order_direction = 'ASC', string $search_term = null, $asArray = false)
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

        $whereClauses = ["statut_oeuvre = 'disponible'"];

        if (!is_null($filtre_type_id)) {
            $whereClauses[] = "id_type_oeuvre = :type_id";
        }

        if (!empty($search_term)) {
            $whereClauses[] = "(UPPER(titre) LIKE UPPER(:search_term) OR UPPER(artiste) LIKE UPPER(:search_term) OR UPPER(description) LIKE UPPER(:search_term))";
        }

        if (!empty($whereClauses)) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $query .= " ORDER BY $order_by $order_direction";

        try {
            $stmt = $this->_bd->prepare($query);

            if (!is_null($filtre_type_id)) $stmt->bindValue(":type_id", $filtre_type_id, PDO::PARAM_INT);

            if (!empty($search_term)) {
                $stmt->bindValue(":search_term", "%" . $search_term . "%", PDO::PARAM_STR);
            }

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

    public function supprimerOeuvre(int $id_oeuvre){
        $query = "SELECT soft_delete_oeuvre(:id_oeuvre)";
        try {
            $stmt = $this->_bd->prepare($query);
            $stmt->bindValue(':id_oeuvre', $id_oeuvre);
            $stmt->execute();
            return $stmt->fetchColumn(); // 1=succès, 0=non trouvée, -1=erreur DB
        } catch (PDOException $e) {
            print "Erreur DB (supprimerOeuvre): " . $e->getMessage();
            return -1;
        }
    }

    public function getOeuvreById($id_oeuvre)
    {
        $query = "SELECT * FROM vue_oeuvres_details WHERE id_oeuvre=:id_oeuvre";

        try {
            $resultset = $this->_bd->prepare($query);
            $resultset->bindValue(":id_oeuvre",$id_oeuvre);
            $resultset->execute();
            $data = $resultset->fetch();
            if($data){
                return new Oeuvre($data);
            } else {
                return null;
            }

        }catch(PDOException $e) {
            print "Echec ".$e->getMessage();
            return null;
        }
    }

    public function updateOeuvre(
        int $id_oeuvre,
        string $titre,
        string $description,
        string $artiste,
        int $id_type_oeuvre,
        int $id_utilisateur,
        int $annee_creation,
        string $dimensions,
        float $prix,
        string $chemin_image
    ): bool {

        $query = "SELECT update_oeuvre(
                        :id_oeuvre,
                        :titre,
                        :description,
                        :artiste,
                        :id_type_oeuvre,
                        :id_utilisateur,
                        :annee_creation,
                        :dimensions,
                        :prix,
                        :chemin_image
                    )";

        try {
            $this->_bd->beginTransaction();
            $stmt = $this->_bd->prepare($query);

            $stmt->bindValue(':id_oeuvre', $id_oeuvre);
            $stmt->bindValue(':titre', $titre);
            $stmt->bindValue(':description', $description);
            $stmt->bindValue(':artiste', $artiste);
            $stmt->bindValue(':id_type_oeuvre', $id_type_oeuvre);
            $stmt->bindValue(':id_utilisateur', $id_utilisateur);

            // Gérer les valeurs potentiellement nulles pour annee_creation, dimensions, prix
            $stmt->bindValue(':annee_creation', $annee_creation, $annee_creation === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':dimensions', $dimensions, $dimensions === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
            if ($prix === null) {
                $stmt->bindValue(':prix', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':prix', $prix);
            }

            $stmt->bindValue(':chemin_image', $chemin_image);

        $stmt->execute();
        $retour_db = $stmt->fetchColumn(0);

        if ($retour_db === 1) {
            $this->_bd->commit();
            return true;
        } else {
            $this->_bd->rollBack(); // Annule la transaction
            return false;
        }

        } catch (PDOException $e) {
            $this->_bd->rollBack();
            print("Erreur PDO lors de la mise à jour de l'oeuvre (ID: {$id_oeuvre}): " . $e->getMessage());

            return false;
        }
    }

    public function getOeuvreSuggestions(string $term, int $limit = 5) {

        $query = "SELECT DISTINCT titre FROM oeuvres WHERE UPPER(titre) LIKE UPPER(:term) LIMIT :limit";

        try {
            $stmt = $this->_bd->prepare($query);
            $stmt->bindValue(':term', '%' . $term . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetchAll();

            foreach ($data as $row) {
                $result[] = $row['titre'];
            }

            if($data){
                return $result;
            } else {
                return [];
            }

        }
        catch(PDOException $e) {
            print "Erreur ".$e->getMessage();
            return null;
        }
    }
}