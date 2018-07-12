<?php
    include("sql.php");
    session_start();
    if (!isset($_SESSION["login"]))
    {
        header("Location: logowanie.php");
        exit();
    }

    $login=$_SESSION['login'];

    $article_title=$_POST['title'];
    $article_content=$_POST['content'];
    if(isset($_FILES['photo_primary']))
    {
        $photo_primary_n =$_FILES['photo_primary']['name'];
        $photo_primary =addslashes (file_get_contents($_FILES['photo_primary']['tmp_name']));
    }
    if(isset($_FILES['photo_secondary']))
    {
        $photo_secondary_n =$_FILES['photo_secondary']['name'];
        $photo_secondary =addslashes (file_get_contents($_FILES['photo_secondary']['tmp_name']));
    }

    $action_type=$_POST['action_type'];
    print_r($_POST);
    print_r($_FILES);
     if($action_type=="add")    
     {      
        try{ 
            if(isset($_FILES['photo_secondary'])&&(isset($_FILES['photo_primary'])))                  
            {    
                $sql = $dbh->prepare("INSERT INTO articles (title,content,autor,photo_primary,photo_secondary) VALUES (:article_title, :article_content,:login,'$photo_primary','$photo_secondary');");

            }else if(isset($_FILES['photo_secondary'])&&(!isset($_FILES['photo_primary'])))                  
            {    
                $sql = $dbh->prepare("INSERT INTO articles (title,content,autor,photo_primary,photo_secondary) VALUES (:article_title, :article_content,:login,Null,'$photo_secondary');");

            }else if(!isset($_FILES['photo_secondary'])&&(isset($_FILES['photo_primary'])))                  
            {    
                $sql = $dbh->prepare("INSERT INTO articles (title,content,autor,photo_primary,photo_secondary) VALUES (:article_title, :article_content,:login,'$photo_primary',Null);");

            }
            $sql->bindParam(':article_content', $article_content, PDO::PARAM_STR);
            $sql->bindParam(':article_title', $article_title, PDO::PARAM_STR);
            $sql->bindParam(':login', $login, PDO::PARAM_STR);
            $sql->execute(); 
            echo "OK"; 
            header("Location: panel.php?" . SID);    
        }
        catch(PDOException $e){
            echo 'Erroer:' . $e->getMessage();
        }
    }
    else if ($action_type=="edit")    
    {
        try{ 

            if(isset($_POST["no_primary"])&&$_POST["no_primary"]=="yes")
            $photo_primary=null;
            if(isset($_POST["no_secondary"])&&$$_POST["no_secondary"]=="yes")
            $photo_secondary=null;

            if($_FILES['photo_secondary']['size']>0&&$_FILES['photo_primary']['size']>0)                  
            {    
                $sql = $dbh->prepare(" UPDATE  articles SET title= :article_title,content=:article_content,photo_primary='$photo_primary',photo_secondary ='$photo_secondary' WHERE id=:id ;");

            }else if($_FILES['photo_secondary']['size']==0&&$_FILES['photo_primary']['size']>0)                  
            {    
                $sql = $dbh->prepare(" UPDATE  articles SET title= :article_title,content=:article_content,photo_primary='$photo_primary' WHERE id=:id ;");

            }else if($_FILES['photo_secondary']['size']>0&&$_FILES['photo_primary']['size']==0)                  
            {    
                $sql = $dbh->prepare(" UPDATE  articles SET title= :article_title,content=:article_content,photo_secondary ='$photo_secondary' WHERE id=:id ;");
            }
            else if($_FILES['photo_secondary']['size']==0&&$_FILES['photo_primary']['size']==0)                  
            {    
                $sql = $dbh->prepare(" UPDATE  articles SET title= :article_title,content=:article_content WHERE id=:id ;");
            }
            $sql->bindParam(':article_content', $article_content, PDO::PARAM_STR);
            $sql->bindParam(':article_title', $article_title, PDO::PARAM_STR);
            $sql->bindParam(':id', $_POST["id"], PDO::PARAM_INT);
            $sql->execute(); 
            echo "OK"; 

            if(isset($_POST["no_primary"])&& $_POST["no_primary"]=="yes")
            {
                $sql = $dbh->prepare(" UPDATE  articles SET photo_primary=NULL WHERE id=:id ;");
                $sql->bindParam(':id', $_POST["id"], PDO::PARAM_INT);
                $sql->execute(); 
                echo "OK"; 
            }

            if(isset($_POST["no_secondary"])&& $_POST["no_secondary"]=="yes")
            {
                $sql = $dbh->prepare(" UPDATE  articles SET photo_secondary=NULL WHERE id=:id ;");
                $sql->bindParam(':id', $_POST["id"], PDO::PARAM_INT);
                $sql->execute(); 
                echo "OK";   
            }

            header("Location: panel.php?" . SID);    
        }
        catch(PDOException $e){
            echo 'Erroer:' . $e->getMessage();
        }
    }


 ?>