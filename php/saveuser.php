<?php
    include("sql.php");
    session_start();
    if (!isset($_SESSION["login"]))
    {
        header("Location: logowanie.php");
        exit();
    }

    $login=$_SESSION['login'];

    $user_login=$_POST['login'];
    $user_pass=$_POST['pass'];
    $user_level=$_POST['level'];
    $action_type=$_POST['action_type'];

    $sql  = $dbh->prepare("SELECT level FROM users WHERE login = :login "); 
    $sql->bindParam(':login', $login, PDO::PARAM_STR);       
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    if(count($result)>0)
         if($result[0]['level']==0)
         {
             if($action_type=='edit')
            {
                try{
                    if( strlen ($_POST['pass'])>=40)
                    $sql = $dbh->prepare("UPDATE users SET  pass = :user_pass, level = :user_level WHERE login = :user_login");
                    else
                    $sql = $dbh->prepare("UPDATE users SET  pass = sha1(:user_pass), level = :user_level WHERE login = :user_login");
                    

                    $sql->bindParam(':user_login', $user_login, PDO::PARAM_STR); 
                    $sql->bindParam(':user_pass', $user_pass, PDO::PARAM_STR); 
                    $sql->bindParam(':user_level', $user_level, PDO::PARAM_INT); 
                    $sql->execute();    
                               
                    header("Location: panel.php?" . SID);
                }
                catch(PDOException $e){
                    echo 'Erroer:' . $e->getMessage();
                }
            }
            else if($action_type=='add')
            {
                $sql  = $dbh->prepare("SELECT * FROM users WHERE login = '$user_login' ");       
                $sql->execute();
                $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                if(count($result)==0)
                {                    
                    try{                    
                        $sql = $dbh->prepare("INSERT INTO `users` (`id`, `login`, `pass`, `level`) VALUES (NULL, :user_login, sha1(:user_pass), :user_level);");                  
                        $sql->bindParam(':user_login', $user_login, PDO::PARAM_STR); 
                        $sql->bindParam(':user_pass', $user_pass, PDO::PARAM_STR); 
                        $sql->bindParam(':user_level', $user_level, PDO::PARAM_INT); 
                        $sql->execute();                
                        header("Location: panel.php?" . SID);
                    }
                    catch(PDOException $e){
                        echo 'Erroer:' . $e->getMessage();
                    }
                }
                else{
                    echo "taki login już istnieje";                    
                    header("Location: panel.php?" . SID);
                }
            }
           
         }
         else{
             echo "nie mozesz";
         }

 ?>