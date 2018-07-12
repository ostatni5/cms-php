<?php
    include("sql.php");
    session_start();
    if (!isset($_SESSION["login"]))
    {
        header("Location: logowanie.php");
        exit();
    }

    $login=$_SESSION['login'];


    $sql  = $dbh->prepare("SELECT level FROM users WHERE login = :login "); 
    $sql->bindParam(':login', $login, PDO::PARAM_STR);      
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    if(count($result)>0)
         if($result[0]['level']==0)
         {
             try{
                 $user_login=$_POST['login'];
                $sql = $dbh->prepare("DELETE FROM users WHERE login = :user_login ");   
                $sql->bindParam(':user_login', $user_login, PDO::PARAM_STR);
                
                $sql->execute();
                echo "gotowe ".$_POST['login'];
                header("Location: panel.php?" . SID);
             }
             catch(PDOException $e){
                echo 'Erroer:' . $e->getMessage();
             }
           
         }
         else{
             echo "nie mozesz";
         }

 ?>