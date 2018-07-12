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
    {

        $sql  = $dbh->prepare("SELECT * FROM articles WHERE id=:id");
        $sql->bindParam(':id', $_POST["id"], PDO::PARAM_INT);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        
        if(count($result)>0)
        {
            if($result[0]['autor']==$_SESSION["login"] || $_SESSION["level"] ==0)
            {
                $sql = $dbh->prepare("DELETE FROM articles WHERE  id=:id");   
                $sql->bindParam(':id', $_POST["id"], PDO::PARAM_INT);
                $sql->execute();
                echo "OK";
                header("Location: panel.php");
            }
            else
            {
                header("Location: panel.php");
                exit();
            }
        }
        else
        {
            header("Location: panel.php");
            exit();
        }
    }

 ?>