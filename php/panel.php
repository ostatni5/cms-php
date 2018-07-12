<?php
    include("sql.php");

    session_start();
    if (!isset($_SESSION["login"]))
    {
        header("Location: logowanie.php");
        exit();
    }
?>

<!DOCTYPE>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Panel CMS | Panel</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <style>
        .manager-articles{
            text-align:left;
            padding:20px;
            box-sizing:border-box;
        }
        .manager-articles h2{
            text-align:center;
        }
        .info1 span{
            width:calc(100% / 3);
            float:left;
            display:block;
        }
        .manager-articles div{
            margin:20px 0;
        }
        .manager-articles .actions form{
            width:50%;
            float: left;

        }
        </style>
    </head>
    <body>
        <div class="backhome"><a href="../index.php">Strona Główna</a></div>
        <div class="userinfo"><?php  echo "Witaj: <span>".$_SESSION["login"]."</span> <a class=\"logout\" href=\"wyloguj.php\">Wyloguj</a> " ?></div>
        <main>
            <h1>PANEL ADMINISTRACYJNY</h1>            
                <?php 
                    if($_SESSION['level']==0)
                    {
                        echo '<div class="manager manager-users"><h2>Mendżer użytkowników</h2> <form action="edituser.php" method="POST"> <input type="hidden" name="action_type" value="add"/> <input type="submit" class="button bg-green btn-add" value="Dodaj"> </form>';
                        $sql  = $dbh->prepare("SELECT id,login,level FROM users where login <> \"admin\" ");       
                        $sql->execute();
                        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                        

                        for($i = 0 ;$i<count($result) ;$i++)
                        {
                            
                            echo "<div>";
                            echo "<span >".$result[$i]["login"]."</span>";
                            echo "<form  action=\"deluser.php\" method=\"POST\" > <input type=\"hidden\" name=\"login\" value=\"".$result[$i]["login"]."\"/> <input class=\"button\" type=\"submit\" name=\"deluser\" value=\"Usuń\" /></form>";
                            echo "<form  action=\"edituser.php\" method=\"POST\" > <input type=\"hidden\" name=\"login\" value=\"".$result[$i]["login"]."\"/>   <input type=\"hidden\" name=\"action_type\" value=\"edit\"/><input class=\"button\" type=\"submit\" name=\"edituser\" value=\"Edytuj\" /></form>";
                            echo "</div>";
                        }
                        echo '</div>';
                    }
                ?> 
            <div class="manager manager-articles">
                <h2>Mendżer artykułów</h2>
                <form action="editarticle.php" method="POST"> 
                    <input type="hidden" name="action_type" value="add"/> 
                    <input type="submit" class="button bg-green btn-add" value="Dodaj"> 
                </form>
                <?php                     
                    if($_SESSION['level']==0)    
                    $sql  = $dbh->prepare("SELECT id,title,content,photo_primary,autor,articles.date FROM articles ORDER BY articles.date DESC ");
                    else       
                    $sql  = $dbh->prepare("SELECT id,title,content,photo_primary,autor,articles.date FROM articles WHERE autor=:login ORDER BY articles.date DESC ");
                    $sql->bindParam(':login', $_SESSION["login"], PDO::PARAM_STR); 
                    
                    $sql->execute();
                    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                    

                    for($i = 0 ;$i<count($result) ;$i++)
                    {
                        echo '<hr>';
                        echo "<div>";
                        echo "<div class=\"info1\">";
                        echo "<span>ID: <b>".$result[$i]["id"]."</b></span>";
                        echo "<span>Autor: <b>".$result[$i]["autor"]."</b></span>";
                        echo "<span>Data: <b>".$result[$i]["date"]."</b></span>";
                        echo "</div>";
                        if(strlen($result[$i]["title"])<100)
                        echo "<span > Tytuł:  <b>".$result[$i]["title"]." </b></span>";
                        else
                        echo "<span > Tytuł:  <b>".substr($result[$i]["title"],0,100)."[...]</b></span>";
                        echo '<a class="read-more" href=article.php?id='.$result[$i]['id'].'>Cały post</a>';
                        echo '<div class="actions">';
                        echo "<form  action=\"delarticle.php\" method=\"POST\" > <input type=\"hidden\" name=\"id\" value=\"".$result[$i]["id"]."\"/> <input class=\"button\" type=\"submit\" name=\"delarticle\" value=\"Usuń\" /></form>";
                        echo "<form  action=\"editarticle.php\" method=\"POST\" > <input type=\"hidden\" name=\"id\" value=\"".$result[$i]["id"]."\"/>   <input type=\"hidden\" name=\"action_type\" value=\"edit\"/><input class=\"button\" type=\"submit\" name=\"edituser\" value=\"Edytuj\" /></form>";
                        echo '</div>';
                        echo "</div>";
                    }
         
                    
                ?> 
            </div>

        </main>
    </body>
</html>