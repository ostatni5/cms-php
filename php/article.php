<?php
    include("sql.php");

    if(isset($_GET["id"]))
    {
        $id=$_GET["id"];
        $sql  = $dbh->prepare("SELECT * FROM articles WHERE id = :id ");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);       
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
?>
<!DOCTYPE>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Artykuł</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <style>
        article{
            padding:20px;
            background-color:#fdfdfd;
            margin:50px 0;
            text-align:center;
        }
        article .content{
            margin:50px 50px;
            text-align:justify;            
            overflow:hidden;
        }
        article img{
            max-width:100%;
        }
        article img.head{            
            object-fit: cover;
            width: 100%;
            max-height:500px;
        }
        .autor,.date{
            font-style:italic;
        }
        .autor{
            float:left;
        }
        .date{
           float:right; 
        }
        </style>
    </head>
    <body>
        <div class="backhome"><a href="../index.php">Strona Główna</a></div>
        <div class="userinfo"><a href="panel.php">Panel</a></div>
        <main>
            <h1>Artukuł z Doliny</h1>            
            <?php 
            if(isset($result))
                for($i = 0 ;$i<count($result) ;$i++)
                {                    
                    echo "<article>";
                    echo '<span class="autor"> Autor: <b>'.$result[$i]['autor'].'</b></span>';                    
                    echo '<span class="date">'.$result[$i]['date'].'</span>';      
                    echo '<h2>'.$result[$i]['title'].'</h2>';
                    if(!empty ($result[$i]['photo_primary']))
                    echo '<img class="head" src="data:image/jpeg;base64,'.base64_encode( $result[$i]['photo_primary'] ).'"/>';                    
                    echo '<div class="content">'.$result[$i]['content'];                     
                    echo '</div>';
                    if(!empty ($result[$i]['photo_secondary']))                    
                    echo '<img src="data:image/jpeg;base64,'.base64_encode( $result[$i]['photo_secondary'] ).'"/>';                                 
                    echo "</article> <br>";
                }
                else
                {
                    echo "<article>";
                    echo "<h2>Brak takiego artykułu</h2>";
                    echo "</article>";
                    header("Location: ../index.php");
                }
                   
                ?> 
        </main>
    </body>

</html>