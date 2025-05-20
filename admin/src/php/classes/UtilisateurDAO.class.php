<?php


class UtilisateurDAO
{

    private $_bd;

    public function __construct($cnx)
    {
        $this->_bd = $cnx;
    }


    public function pseudoExiste($username)
    {
        $query = "SELECT pseudo_existe(:username)";
        try {
            $stmt = $this->_bd->prepare($query);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            print "Erreur DB (pseudoExisteDeja): " . $e->getMessage();
            return true;
        }
    }

    public function emailExiste($email)
    {
        $query = "SELECT email_existe(:email)";
        try {
            $stmt = $this->_bd->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            print "Erreur DB (emailExisteDeja): " . $e->getMessage();
            return true;
        }
    }

    public function enregistrerUtilisateur($username, $email, $password_hash)
    {
        $query = "SELECT enregistrer_utilisateur(:username, :email, :hash)";

        try {
            $stmt = $this->_bd->prepare($query);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':hash', $password_hash);
            $stmt->execute();

            return $stmt->fetchColumn(); // -1 si l'utilisateur n'a pas pu être créer

        } catch (PDOException $e) {
            print "Erreur DB (enregistrerNouvelUtilisateur): " . $e->getMessage();
            return -1;
        }
    }

    public function getUtilisateur($login)
    {
        $query = "SELECT * FROM utilisateurs WHERE username=:login OR email=:login";

        try {
            $stmt = $this->_bd->prepare($query);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch();

            if ($data) {
                return new Utilisateur($data);
            }
            else return null;
        }
        catch (PDOException $e) {
            print "Erreur DB (getUtilisateur): " . $e->getMessage();
            return null;
        }
    }

}