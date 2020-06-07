<?php

require_once "utils/Autoloader.php";

try{
    
    if(!empty($_POST)){

        $error = array();

        $validator = new classes_Validation;
        $error["email"] = $validator->checkEmail($_POST["email"]);
        $error["password"] = $validator->checkPassword($_POST["password"]);

        $j = 0;

        foreach($error as $key => $value) {
            if(is_null($value)){
                $j++;
            }
        }

        // Se não houver erros
        if($j == 2){

            $user = new classes_User($_POST, "login");
            $db = new classes_Database;
            $userManager = new classes_UserManager($db);

            $result = $userManager->loginUser($user);

            if(!$result){
                $email_flag = true;
            }

            else{
                header("Location: index.php?reg=login");
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
    <title>Login</title>
</head>

<body>

        <div class="row">
            <a class="colu-1-of-3" href="#"><img src="../front-end/img/bila.png" alt="Logótipo" class="logo-login"></a>
            <div class="col-1-of-3 login-text">
                Bem-vindo
            </div>
        </div>

        <div class="row">
                <form action="" method="POST" class="login-form">
                    <div class="row">
                        <div class="colu col-2-of-2">
                            <label for="email">Email</label>
                            <br>
                            <input type="email" name="email"
                            value="<?php if(isset($email_flag) && $email_flag){$_POST["email"] = "";}
                            if(!empty($_POST) && empty($error['email'])){echo $_POST['email'];} ?>">
                            <?php
                            if(!empty($_POST) && $error["email"]){
                                echo $error["email"];
                            }
                            elseif(isset($email_flag) && $email_flag){
                                echo "* O email ou a password estão errados";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-2">
                            <label for="nome" id="marcar">Password</label>
                            <br>
                            <input type="password" name="password"
                            value="<?php if(isset($email_flag) && $email_flag){$_POST["password"] = "";}
                            if(!empty($_POST) && empty($error['password'])){echo $_POST['password'];} ?>">
                            <?php
                            if(!empty($_POST) && $error["password"]){
                                echo $error["password"];
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