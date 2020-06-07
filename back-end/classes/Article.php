<?php

class classes_Article{

    private $_id = null;
    private $_title = null;
    private $_text = null;
    private $_eventDate = null;
    private $_image = array();

    private $_newModel = array("titulo", "texto");
    private $_allowedActions = array("new", "existing", "image");

    public function __construct(array $articleData, string $action, $id = null){

        for($i = 0; $i < count($this->_newModel); $i++){ 

            if(isset($articleData["imagem"])){
                break;
            }

            if(!array_key_exists($this->_newModel[$i], $articleData)){
                throw new InvalidArgumentException("Invalid data");

            }
            
        }

        if(!in_array($action, $this->_allowedActions)){
            throw new InvalidArgumentException("Error processing request");
        }

        switch ($action) {
            case "new":
                
                $this->_title = $articleData["titulo"];
                $this->_text = $articleData["texto"];
                $this->_id = null;
                $this->_eventDate = $articleData["data"];

                break;

            case "existing":
            
                $this->_title = $articleData["titulo"];
                $this->_text = $articleData["texto"];
                $this->_image = $articleData["imagem"];
                $this->_id = $id;
                $this->_eventDate = $articleData["data"];
                
            break;

            case "image":

                $this->_image = $articleData;

            break;
            
        }

        
    }

    public function getId(): int
    {
        return $this->_id;
    }

    public function getTitle(): string
    {
        return $this->_title;
    }

    public function getText(): string
    {
        return $this->_text;
    }

    public function getEventDate(): string
    {
        return $this->_eventDate;
    }

    public function getImage(): array
    {
        return $this->_image;
    }

}


?>