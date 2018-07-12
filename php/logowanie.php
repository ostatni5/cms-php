<?php
include("sql.php");
$error=false;
if (isset($_POST['login']) && isset($_POST['pass'])){
    
    if($_POST['login']=="admin" && get_client_ip()!="127.0.0.1" )
    {   
        
        if(get_client_ip()!="127.0.0.1")
        $error = "<span class=\"error\">Adminem to ty nie jesteś</span>";
    }
    else{
        $user_login=htmlentities($_POST['login']);
        $user_pass=htmlentities($_POST['pass']);

        $sql  = $dbh->prepare("SELECT Count(id),level FROM users WHERE login=:user_login AND pass=sha1(:user_pass)"); 
        $sql->bindParam(':user_login', $user_login, PDO::PARAM_STR); 
        $sql->bindParam(':user_pass', $user_pass, PDO::PARAM_STR);      
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);

        if ($result[0]["Count(id)"]>0){
            session_start();
            $_SESSION['login']=$_POST['login'];
            $_SESSION['level']=$result[0]['level'];
            header("Location: panel.php?" . SID);
            exit();
        }
        else
        $error = "<span class=\"error\">Zły login lub hasło!</span>";
    }
} 
else
  $error = false;
?>

<!DOCTYPE>
<html>

<head>
    <meta charset="UTF-8">
    <title>Panel CMS | Logowanie</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <style>

        .login-box {
            padding: 20px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
            background-color: #ECF0F1;
        }

        label {
            float: left;
            clear: both;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        input {
            float: right;
        }

        INPUT[type="submit"] {
            margin-top: 10px;
            width: 100%;
            padding: 5px;
            background-color: #2980B9;
            border: none;
            color: #ECF0F1;
            font-size: 20px;
            cursor: pointer;
        }

        .vignette {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            box-shadow: 0 0 200px #2C3E50 inset;
        }
        .error{
            position: absolute;
            bottom: 90%;
            left: 0;
            width:100%;
            display:block;
            font-style: italic;
            color: #E74C3C;
            font-size: 20px;
            text-align: center;
        }
        .homelink{
            padding:10px;
            box-sizing:border-box;
            cursor: pointer;
            display:block;
            background:#3498DB;
            width:50%;
            margin-left:25%;
            text-decoration:none;
            text-align: center;
            float: left;
            clear: both;
            font-weight: bold;
            margin-top: 15px;
            color:#ECF0F1;
        }
    </style>
</head>

<body>
    <div class="vignette"></div>
    <div class="login-box">
         <?php  echo $error ? $error : "";?>
        <form method="POST">
            <label>
                Login:
                <INPUT type="text" name="login">
            </label>
            <label>
                Password:
                <INPUT type="password" name="pass">
            </label>
            <INPUT type="submit" value="Login">
        </form>

        <a class="homelink" href="../index.php"> HOME </a>
    </div>
</body>

</html>