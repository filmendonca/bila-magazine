<?php

require_once "utils/Autoloader.php";

try{
    
    if(!empty($_POST)){

        $error = array();

        $validator = new classes_Validation;
        $error["nome"] = $validator->checkName($_POST["nome"]);
        $error["email"] = $validator->checkEmail($_POST["email"]);
        $error["password"] = $validator->checkPassword($_POST["password"]);
        $error["r_password"] = $validator->repeatPassword($_POST["password"], $_POST["r_password"]);

        $j = 0;

        foreach($error as $key => $value) {
            if(is_null($value)){
                $j++;
            }
        }

        // Se não houver erros
        if($j == 4){

            $user = new classes_User($_POST, "register");
            $db = new classes_Database;
            $userManager = new classes_UserManager($db);

            $result = $userManager->addUser($user);

            if(!$result){
                $email_flag = true;
            }

            else{
                header("Location: index.php?reg=success");
                die();
            }

        }

    }

}

catch(Exception $e){
    $_POST = null;
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
    <title>Registo</title>
</head>

<body>

        <div class="row">
            <a class="colu-1-of-3" href="#"><img src="../front-end/img/bila.png" alt="Logótipo" class="logo-login"></a>
            <div class="col-1-of-3 login-text">
                <p>Já está registado?
                <a class="click-here" href="login.php">Clique aqui!</a>
                </p>
            </div>
        </div>

        <div class="row">
                <form action="" method="POST" class="login-form">
                <div class="row">
                        <div class="colu col-2-of-2">
                            <label for="nome">Nome</label>
                            <br>
                            <input type="text" name="nome" 
                            value="<?php if(!empty($_POST) && empty($error['nome']))
                            {$_POST['nome']=trim($_POST['nome']); echo $_POST['nome'];} ?>">
                            <?php
                            if(!empty($_POST) && $error["nome"]){
                                echo $error["nome"];
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-2">
                            <label for="email">Email</label>
                            <br>
                            <input type="email" name="email" 
                            value="<?php if(!empty($_POST) && empty($error['email'])){echo $_POST['email'];} ?>">
                            <?php
                            if(!empty($_POST) && $error["email"]){
                                echo $error["email"];
                            }
                            elseif(isset($email_flag) && $email_flag){
                                echo "Esse email já existe.";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-2">
                            <label for="password" id="marcar">Password</label>
                            <br>
                            <input type="password" name="password" 
                            value="<?php if(!empty($_POST) && empty($error['password'])){echo $_POST['password'];} ?>">
                            <?php
                            if(!empty($_POST) && $error["password"]){
                                echo $error["password"];
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-2">
                            <label for="password" id="marcar">Repetir password</label>
                            <br>
                            <input type="password" name="r_password" 
                            value="<?php if(!empty($_POST) && empty($error['r_password'])){echo $_POST['r_password'];} ?>">
                            <?php
                            if(!empty($_POST) && $error["r_password"]){
                                echo $error["r_password"];
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-3">
                            <input type="submit" value="Entrar" class="btn">
                        </div>
                    </div>
                </form>
        </div>

</body>

</html>