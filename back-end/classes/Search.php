<?php

class classes_Search{

    private $_db;

    public function __construct(classes_Database $db){

        $this->_db = $db;

    }

    public function searchText(array $string): PDOStatement{

        $search = $string["search"];
        
        try{
            
            //connection well established. Let's do a query. We are going to use named placeholders to enjoy the extra security provided by PDO.
            $query = "SELECT id, titulo, texto, data_evento, imagem FROM Artigo WHERE titulo LIKE :titulo OR texto LIKE :texto";	
            
            //now, create the associative array to carry the values to be used in the query. The names must be the same than the placeholders. We want all results in this example.
            $parameters = array('titulo' => $search, 'texto' => $search);
            
            //call the appropriate method to execute it safely.
            $result = $this->_db->executeQuery($query, $parameters);

            return $result;
            
            //print out the results
            /*
            while ( $line = $result->fetch() ){

                return $line;

                //echo "User " . $line['username'] . " with the email " . $line['email'] . "<br />"; 
            }
            */		
        }
        //we declare a generic exception to catch all exceptions from the PDO class. You can, however, declare them by type and then act accordingly.
        catch(Exception $e){
            echo $e->getMessage();
            die();
        }

         

    }

}

?>