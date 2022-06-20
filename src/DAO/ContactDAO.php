<?php
namespace src\DAO;

class ContactDAO {
    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT *
            FROM contact;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT *
            FROM contact
            WHERE id = $id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO contact
                (image, firstname, lastname, email, phonenumber)
            VALUES
                (:image, :firstname, :lastname, :email, :phoneNumnber),
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'image' => $input['image'] ?? null,
                'firstname' => $input['firstname'],
                'lastname'  => $input['lastname'],
                'email'  => $input['email'],
                'phoneNumber'  => $input['phoneNumber']
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE contact
            SET 
                image = :image,
                firstname = :firstname,
                lastname  = :lastname,
                email = :email,
                phoneNumber = :phoneNumber
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'image' => $input['image'] ?? null,
                'firstname' => $input['firstname'],
                'lastname'  => $input['lastname'],
                'email'  => $input['email'],
                'phoneNumber'  => $input['phoneNumber'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM contact
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}