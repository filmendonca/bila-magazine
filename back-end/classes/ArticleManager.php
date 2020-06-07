<?php

class classes_ArticleManager{

    private $_db;

    public function __construct(classes_Database $db){

        $this->_db = $db;

    }

    public function addArticle(classes_Article $article, int $userId){

            $title = $article->getTitle();
            $text = $article->getText();
            $date = $article->getEventDate();

            $query = "INSERT INTO Artigo(titulo, texto, data_evento) VALUES (:titulo, :texto, :data_evento)";
            $params = array("titulo" => $title, "texto" => $text, "data_evento" => $date);

            $result = $this->_db->executeQuery($query, $params);

            $lastId = $this->_db->getDB()->lastInsertID();

            $previousLastID = $lastId--;

            $query1 = "DELETE FROM Artigo WHERE id = :id";
            $params1 = array("id" => $lastId);

            $result1 = $this->_db->executeQuery($query1, $params1);

            $query = "INSERT INTO Escrever(id_utilizador, id_artigo) VALUES (:id_util, :id_art)";
            $params = array("id_util" => $userId, "id_art" => $previousLastID);

            $result = $this->_db->executeQuery($query, $params);

            //Criar nova pasta de imagens na galeria
            mkdir("upload/gallery/folder", 0777);
            rename("upload/gallery/folder", "upload/gallery/".$previousLastID);

            $lastId = $this->_db->getDB()->lastInsertID();

            $query1 = "DELETE FROM Escrever WHERE id = :id";
            $params1 = array("id" => $lastId);

            $result = $this->_db->executeQuery($query1, $params1);

            return $previousLastID;

    }

    public function addImage(classes_Article $data, int $id){

        $imagem = $data->getImage();
        $nome = $imagem["imagem"]["name"];

        $query = "SELECT * FROM Artigo WHERE imagem = :imagem";
        $params = array("imagem" => $nome);
        $result = $this->_db->executeQuery($query, $params);

        if($result->rowCount() > 0){
            $nome = date('Y-m-d_H-i-s')."_".$nome;
        }

        $query = "UPDATE Artigo SET imagem = :imagem WHERE id = :id";
        $params = array("imagem" => $nome, "id" => $id);

        $result = $this->_db->executeQuery($query, $params);

        $temp = $imagem["imagem"]["tmp_name"];

        move_uploaded_file($temp,"upload/".$nome);

    }

    public function editArticle(classes_Article $article, int $id){

        $title = $article->getTitle();
        $text = $article->getText();
        $date = $article->getEventDate();

        $query = "UPDATE Artigo SET titulo = :titulo, texto = :texto, data_evento = :data_evento WHERE id = :id";
        $params = array("titulo" => $title, "texto" => $text, "data_evento" => $date, "id" => $id);

        $result = $this->_db->executeQuery($query, $params);

        if(!$result){
            throw new Exception("Error editing article");
        }

    }

    public function editImage(classes_Article $img, int $id){

        $query = "SELECT imagem FROM Artigo WHERE id = :id";
        $params = array("id" => $id);

        $result = $this->_db->executeQuery($query, $params);

        $data = $result->fetchAll();

        foreach($data as $value){
            $imageName = $value["imagem"];
        }

        if($imageName != "default.jpg"){
            unlink("upload/".$imageName);
        }

        $imagem = $img->getImage();
        $nome = $imagem["imagem"]["name"];

        $query = "SELECT * FROM Artigo WHERE imagem = :imagem";
        $params = array("imagem" => $nome);
        $result = $this->_db->executeQuery($query, $params);

        if($result->rowCount() > 0){
            $nome = date('Y-m-d_H-i-s')."_".$nome;
        }

        $query = "UPDATE Artigo SET imagem = :imagem WHERE id = :id";
        $params = array("imagem" => $nome, "id" => $id);

        $result = $this->_db->executeQuery($query, $params);

        $temp = $imagem["imagem"]["tmp_name"];

        move_uploaded_file($temp,"upload/".$nome);

    }

    public function deleteArticle(int $id){

            $query = "SELECT imagem FROM Artigo WHERE id = :id";
            $params = array("id" => $id);

            $result = $this->_db->executeQuery($query, $params);

            $data = $result->fetchAll();

            foreach($data as $value){
                $imageName = $value["imagem"];
            }

            $query = "DELETE FROM Artigo WHERE id = :id";
            $params = array("id" => $id);

            $result = $this->_db->executeQuery($query, $params);

            $query = "DELETE FROM Escrever WHERE id_artigo = :id";
            $params = array("id" => $id);

            $result = $this->_db->executeQuery($query, $params);

            if($imageName != "default.jpg"){
                unlink("upload/".$imageName);
            }

            $query = "DELETE FROM Galeria WHERE id_artigo = :id_art";
            $params = array("id_art" => $id);
    
            $result = $this->_db->executeQuery($query, $params);

            $this->_deleteDir("upload/gallery/".$id);

            if(!$result){
                throw new Exception("Error deleting article from database");
            }

    }

    public function displayArticle(?int $id = null, bool $all = false){

        try{

            if($all){
                $query = "SELECT id, titulo, texto, data_evento, imagem FROM Artigo";
                $parameters = array("id" => null);
            }

            elseif($id != null && !$all){
                $query = "SELECT id, titulo, texto, data_evento, imagem FROM Artigo WHERE id = :id";
                $parameters = array("id" => $id);
            }

            $result = $this->_db->executeQuery($query, $parameters);

            return $result;

        }

        catch(Exception $e){
            echo $e->getMessage();
            die();
        }

    }

    public function displayGallery(int $idArticle){

        $query = "SELECT id, imagem FROM Galeria WHERE id_artigo = :id";
        $parameters = array("id" => $idArticle);

        $result = $this->_db->executeQuery($query, $parameters);

        return $result;

    }

    public function addImageGallery(classes_Article $data, int $articleId){

        $imagem = $data->getImage();
        $nome = $imagem["imagem"]["name"];

        $query = "SELECT * FROM Galeria WHERE imagem = :imagem AND id = :id";
        $params = array("imagem" => $nome, "id" => $articleId);
        $result = $this->_db->executeQuery($query, $params);

        if($result->rowCount() > 0){
            $nome = date('Y-m-d_H-i-s')."_".$nome;
        }

        $query = "INSERT INTO Galeria(id_artigo, imagem) VALUES (:id, :imagem)";
        $params = array("imagem" => $nome, "id" => $articleId);

        $result = $this->_db->executeQuery($query, $params);

        $lastId = $this->_db->getDB()->lastInsertID();

        $lastId--;
            
        $query = "DELETE FROM Galeria WHERE id = :id";
        $params = array("id" => $lastId);

        $result = $this->_db->executeQuery($query, $params);

        $temp = $imagem["imagem"]["tmp_name"];

        if(is_dir("upload/gallery/".$articleId)){
            move_uploaded_file($temp,"upload/gallery/".$articleId."/".$nome);
        }

    }

    public function deleteImageGallery(int $articleId, int $imageId){

        $query = "SELECT imagem FROM Galeria WHERE id = :id AND id_artigo = :id_art";
        $params = array("id" => $imageId, "id_art" => $articleId);

        $result = $this->_db->executeQuery($query, $params);

        $data = $result->fetchAll();

        foreach($data as $value){
            $imgName = $value["imagem"];
        }

        $query = "DELETE FROM Galeria WHERE id = :id AND id_artigo = :id_art";
        $params = array("id" => $imageId, "id_art" => $articleId);

        $result = $this->_db->executeQuery($query, $params);

        unlink("upload/gallery/".$articleId."/".$imgName);

    }

    private function _deleteDir($target){

        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK );
    
            foreach( $files as $file ){
                $this->_deleteDir( $file );      
            }
    
            rmdir( $target );
        }
        
        elseif(is_file($target)) {
            unlink( $target );  
        }

    }

}

?>