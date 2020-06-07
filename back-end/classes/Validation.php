<?php

class classes_Validation{

    public function checkName(string $name){

        $min = 2;
        $max = 40;
        $exp = '/^[A-ZÁÉÍÓÕÀÈÒÙÚÄËÏÖÜ]{1}[\w\sçáàãéëêíïìóõôöüúù]{'.$min.','.$max.'}$/';
        
        if(strlen($name) <= $min || strlen($name) > $max){
            $min++;
            $error = "* O nome tem de ter entre ".$min." a ".$max." caracteres";
            return $error;
        }

        elseif(!preg_match($exp, $name)){ 
            $error = "* O nome tem de começar por letra maiúscula e não pode ter caracteres especiais";
            return $error;
        }

    }

    public function checkPassword(string $password){
        
        $min = 8;
        $max = 50;
        $exp = '/^[A-z0-9_\-]{'.$min.','.$max.'}$/';

        if(!preg_match($exp, $password)){
            $error = "* A password tem de ter entre ".$min." a ".$max." caracteres";
            return $error;
        }
        
    }

    public function repeatPassword(string $password, string $r_password){
        if($password != $r_password){
            $error = "* Repita a password inserida";
            return $error;
        }
    }

    public function checkEmail(string $email){
        
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = "* Email inválido";
            return $error;
        }

    }

    public function checkTitle(string $title){
        
        $min = 2;
        $max = 60;
        $exp = '/^[A-ZÁÉÍÓÕÀÈÒÙÚÄËÏÖÜ]{1}[\w\-\s\/çáàãéëêíïìóõôöüúù?!]{'.$min.','.$max.'}$/';

        if(strlen($title) <= $min || strlen($title) > $max){
            //$min++;
            $error = "* O título tem de ter entre ".$min." a ".$max." caracteres";
            return $error;
        }

        elseif(!preg_match($exp, $title)){ 
            $error = "* O título tem de começar por letra maiúscula e não pode ter caracteres especiais";
            return $error;
        }

    }

    public function checkText(string $text){
        
        $min = 5;
        $max = 5000;
        
        $exp = '/^[\w\-\"\', .\sçáàãâéëêíïìóõôöüúùÁÉÍÓÕÀÈÒÙÚÄËÏÖÜ?!]{'.$min.','.$max.'}$/';

        if((strlen($text) <= $min || strlen($text) > $max)){
            $error = "* O texto tem de ter entre ".$min." a ".$max." caracteres";
            return $error;
        }
        
        elseif(!preg_match($exp, $text)){
            $error = "* O texto não pode ter caracteres especiais";
            return $error;
        }

    }

    public function checkSearch(array $search){

        $search = $search["search"];

        $exp = '/^[\w\-\"\', .çÁáàãÉéêÍíÓóõô]*$/';
        
        if(!preg_match($exp, $search)){
            $error = "A pesquisa não pode conter caracteres especiais";
            return $error;
        }
        

        elseif(strlen($search) < 3){
            $error = "A pesquisa tem de ter pelo menos 3 caracteres";
            return $error;
        }

        else{
           return true;
        }

    }

    public function checkImage(array $image){
    
        $extensionsAllowed = array("jpeg","jpg","png");

        $imageName = $image["imagem"]['name'];
        $imageSize = $image["imagem"]['size'];
        $imageTemp = $image["imagem"]['tmp_name'];
        $imageType = $image["imagem"]['type'];

        $fileExt = mime_content_type($imageTemp);

        $mimeType = explode('/', $fileExt);

        if(!in_array($mimeType[1], $extensionsAllowed)){
            $error = "* A extensão do ficheiro só pode ser JPEG, JPG, ou PNG";
            return $error;
        }

        elseif($imageSize > 2097152){
            $error = "* O tamanho da imagem não pode ser maior do que 2 MB";
            return $error;
        }
        
        else{
            $imageName = trim($imageName);
            $imageName = strip_tags($imageName);
            $imageName = stripslashes($imageName);
        }

    }

}

?>