<?php

class classes_User{

    private $_id = null;
    private $_name = null;
    private $_email = null;
    private $_password = null;
    private $_userType = null;
    private $_regDate = null;

    private $_registerModel = array("email", "password");
    private $_allowedActions = array("register", "login");

    public function __construct(array $userData, string $action){

        if(!in_array($action, $this->_allowedActions)){
            throw new InvalidArgumentException("Error processing request");
        }

        for($i = 0; $i < count($this->_registerModel); $i++){ 

            if(!array_key_exists($this->_registerModel[$i], $userData)){
                throw new InvalidArgumentException("Invalid data");
            }
            
        }

        switch ($action) {
            case "register":
                
                $this->_name = $userData["nome"];
                $this->_email = $userData["email"];
                $this->_password = $userData["password"];
                $this->_id = null;
                $this->_registered = null;
                $this->_userType = null;
                $this->_regDate = null;

                break;

            case "login":
            
                //$this->_name = $userData["nome"];
                $this->_email = $userData["email"];
                $this->_password = $userData["password"];
                //$this->_id = $userData["id"];
                //$this->_userType = $userData["userType"];
                //$this->_regDate = $userData["regDate"];
                
                break;
            
        }

    }

    public function getId(): int
    {
        return $this->_id;
    }

    public function getPassword(): string
    {
        return $this->_password;
    }
    
    public function getName(): string
    {
        return $this->_name;
    }

    public function getEmail(): string
    {
        return $this->_email;
    }

    public function getUserType(): int
    {
        return $this->_userType;
    }

    public function getRegDate(): string
    {
        return $this->_regDate;
    }

}

?>