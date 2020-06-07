<?php

session_start();

require_once "utils/Autoloader.php";

if(empty($_SESSION) || $_SESSION["tipo_user"] != 3){
    header("Location: forbidden.php");
    die();
}

$id = $_SESSION["id"];

try{
    
    if(!empty($_POST)){

        $error = array();

        if(empty($_POST["data"])){
            $error["data"] = "* Insira uma data";
        }

        else{
            $error["data"] = null;
        }

        $validator = new classes_Validation;
        $error["titulo"] = $validator->checkTitle($_POST["titulo"]);
        $error["texto"] = $validator->checkText($_POST["texto"]);

        $j = 0;

        foreach($error as $key => $value) {
            if(is_null($value)){
                $j++;
            }
        }

        // Se não houver erros
        if($j == 3){ 

            $article = new classes_Article($_POST, "new");
            $db = new classes_Database;
            $articleManager = new classes_ArticleManager($db);

            $result = $articleManager->addArticle($article, $id);

            header("Location: add_image.php?id=$result");
            die();

        }

    }

}

catch(Exception $e){
    $_POST = null;
    echo $e->getMessage();
    echo "wrong data";
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../front-end/css/styles.css">
    <title>Inserir artigo</title>
</head>

<body>

        <div class="row">
            <a class="colu-1-of-3" href="#"><img src="../front-end/img/bila.png" alt="Logótipo" class="logo-login"></a>
            <div class="col-1-of-3 login-text">
                <p>Insira um artigo
                </p>
            </div>
        </div>

        <div class="row">
                <form action="" method="POST" class="login-form">
                <div class="row">
                        <div class="colu col-2-of-2">
                            <label for="titulo">Título</label>
                            <br>
                            <input type="text" name="titulo" 
                            value="<?php if(!empty($_POST) && empty($error['titulo']))
                            {$_POST['titulo']=trim($_POST['titulo']); echo $_POST['titulo'];} ?>">
                            <?php
                            if(!empty($_POST) && $error["titulo"]){
                                echo $error["titulo"];
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-2">
                            <label>Texto</label>
                            <br>
                            <textarea name="texto"><?php if(!empty($_POST) && empty($error['texto']))
                            {$_POST['texto']=trim($_POST['texto']); echo $_POST['texto'];} ?></textarea>
                            <?php
                            if(!empty($_POST) && $error["texto"]){
                                echo $error["texto"];
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-2">
                            <label for="texto">Data do evento</label>
                            <br>
                            <input type="date" name="data" 
                            value="<?php if(!empty($_POST) && !empty($_POST['data']))
                            {echo $_POST['data'];} ?>">
                            <?php
                            if(!empty($_POST) && !empty($error["data"])){
                                echo $error["data"];
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-3">
                            <input type="submit" value="Inserir" class="btn">
                        </div>
                    </div>
                </form>
        </div>

</body>

</html>