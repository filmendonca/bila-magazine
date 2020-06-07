<?php

class classes_UserManager{

    private $_db;

    public function __construct(classes_Database $db){

            $this->_db = $db;

    }

    public function addUser(classes_User $user){

            $query = "SELECT * FROM Utilizador WHERE email = :email";
            $email = $user->getEmail();
            $params = array("email" => $email);
            $result = $this->_db->executeQuery($query, $params);

            if($result->rowCount() > 0){
                return false;
            }

            $name = $user->getName();
            $password = $user->getPassword();

            $password = md5($password);

            $query1 = "INSERT INTO Utilizador(nome, email, password) VALUES (:nome, :email, :password)";
            $params1 = array("nome" => $name, "email" => $email, "password" => $password);

            $result1 = $this->_db->executeQuery($query1, $params1);

            $lastId = $this->_db->getDB()->lastInsertID();

            $query1 = "DELETE FROM Utilizador WHERE id = :id";
            $params1 = array("id" => $lastId);

            $result1 = $this->_db->executeQuery($query1, $params1);

            $newID = $lastId--;

            $query = "UPDATE Utilizador SET id = :id_u WHERE id = :id";
            $params = array("id_u" => $lastId, "id" => $newID);
    
            $result = $this->_db->executeQuery($query, $params);

            return true;

    }

    public function loginUser(classes_User $user){

        $password = $user->getPassword();
        $password = md5($password);
        $email = $user->getEmail();

        $query = "SELECT * FROM Utilizador WHERE email = :email AND password = :password";
        $params = array("email" => $email, "password" => $password);

        $result = $this->_db->executeQuery($query, $params);

        if($result->rowCount() == 0){
            return false;
        }

        session_start();

        $data = $result->fetchAll();

        foreach($data as $value){
            $_SESSION["id"] = $value["id"];
            $_SESSION["nome"] = $value["nome"];
            $_SESSION["email"] = $value["email"];
            $_SESSION["tipo_user"] = $value["tipo_user"];
            $_SESSION["data_reg"] = $value["data_reg"];
        }

        return true;

    }

    public function sendEmail(array $emailInfo){

        $email = $emailInfo["email"];
        $nome = $emailInfo["nome"];
        $mensagem = "Mensagem enviada por: ".$nome."\r \n";
        $mensagem .= $emailInfo["mensagem"];
        $assunto = "Email teste";
        
        $header = "MIME-Version: 1.0 \r \n";
        $header .= "Content-type text/html; charset = iso-8859-1 \r \n";
        $header .= "From: $email \r \n";

        if(mail("fil40320@gmail.com", $assunto, $mensagem, $header)){
            return true;
        }
    
        else{
            return false;
        }

    }

}

?>