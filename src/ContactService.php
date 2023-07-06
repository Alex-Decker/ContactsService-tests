<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require 'invalidInputException.php';

class ContactService {
    public $pdo;

    /**
     * ContactService constructor.
     * Initialise la BDD
     */
    public function __construct() {
        $this->pdo = new PDO('sqlite:' . __DIR__ . '/contacts.sqlite');

        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * permet de de renvoyer les détails d'un contact
     * @param $id l'identifiant du contact recherché
     * @return mixed le retour de la requete SQL
     * @throws invalidInputException en cas d'erreur de paramètre
     */
    public function getContact($id) {
        if (empty($id)) {
            throw new invalidInputException("l'id doit être renseigné");
        }
        if (!is_numeric($id) || $id < 0) {
            throw new invalidInputException("l'id doit être un entier non nul");
        }
        $req = $this->pdo->query('SELECT * from contacts where id =' . $id);

        $row = $req->fetchAll();

        // si req ok (!false)
        if ($req) {
            // on renvoie le 1er et seul élément du tableau de résultats
            return $row[0];
        }
    }

    /**
     * Effectue une recherche de contact sur nom ou prénom
     * @param $search le critère de recherche
     * @return array le retour de la requete SQL
     * @throws invalidInputException en cas d'erreur de paramètre
     */
    public function searchContact($search) {
        if (empty($search)) {
            throw new invalidInputException('search doit être renseigné');
        }
        if (!is_string($search)) {
            throw new invalidInputException('search doit être une chaine de caractères');
        }
        $req = "SELECT * from contacts where nom like '%" . $search . "%' or prenom like '%" . $search . "%'";

        $res = $this->pdo->query($req);

        $row = $res->fetchAll();

        // si req ok (!false)
        if ($res) {
            return $row;
        }
    }

    /**
     * Récupère tous les contacts en BDD
     * @return array le retour de la requete SQL
     */
    public function getAllContacts() {
        $req = $this->pdo->query('SELECT * from contacts');

        $row = $req->fetchAll();

        // si req ok (!false)
        if ($req) {
            return $row;
        }
    }

    /**
     * Créé un nouveau contact
     * @param $nom le nom du contact
     * @param $prenom le prénom du contact
     * @return bool true si ok, false si erreur SQL
     * @throws invalidInputException en cas d'erreur de paramètre
     */
    public function createContact($nom, $prenom) {
        if (empty($nom) && !is_string($nom)) {
            throw new invalidInputException('le nom  doit être renseigné');
        }
        if (empty($prenom) && !is_string($prenom)) {
            throw new invalidInputException('le prenom doit être renseigné');
        }
        $stmt = $this->pdo->prepare('INSERT INTO contacts (nom, prenom) VALUES (:nom, :prenom)');

        return $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
        ]);
    }

    /**
     * Créé un nouveau contact
     * @param $id l'id du contact à modifier
     * @param $nom le nom du contact
     * @param $prenom le prénom du contact
     * @return bool true si ok, false si erreur SQL
     * @throws invalidInputException en cas d'erreur de paramètre
     */
    public function updateContact($id, $nom, $prenom) {
        if (empty($nom) && !is_string($nom)) {
            throw new invalidInputException('le nom  doit être renseigné');
        }

        if (empty($id)) {
            throw new invalidInputException("l'id doit être renseigné");
        }
        if (!is_numeric($id) || $id < 0) {
            throw new invalidInputException("l'id doit être un entier non nul");
        }
        if (empty($prenom) && !is_string($prenom)) {
            throw new invalidInputException('le prenom doit être renseigné');
        }
        $stmt = $this->pdo->prepare('UPDATE contacts SET nom=:nom, prenom=:prenom where id=:id');

        return $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'id' => $id,
        ]);
    }

    /**
     * Supprime un contact par son id
     * @param $id l'id du contact à supprimer
     * @return bool true si SQL ok, false si non
     * @throws invalidInputException en cas d'erreur de paramètre
     */
    public function deleteContact($id) {
        if (null === $id) {
            throw new invalidInputException("l'id doit être renseigné");
        }
        if (!is_numeric($id) || $id < 0) {
            throw new invalidInputException("l'id doit être un entier non nul");
        }
        $stmt = $this->pdo->prepare('DELETE from contacts where id=:id');

        return $stmt->execute([
            'id' => $id,
        ]);
    }

    /**
     * Supprime tous les contacts
     * @return false|PDOStatement
     */
    public function deleteAllContact() {
        return $this->pdo->query('DELETE from contacts');
    }
}
