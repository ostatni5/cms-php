<?php
    include("php/sql.php");
?>
<!DOCTYPE>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Strona Głowna</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
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
            max-height:400px;
            overflow:hidden;
        }
        article img{
            max-height: 400px;
            object-fit: cover;
            width: 100%;
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
        <div class="userinfo"><a href="php/panel.php">Panel</a></div>
        <main>
            <h1>Artukuły z Doliny</h1>            
            <?php 
                    
                $sql  = $dbh->prepare("SELECT * FROM articles ORDER BY articles.date DESC");       
                $sql->execute();
                $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                

                for($i = 0 ;$i<count($result) ;$i++)
                {
                    echo "<article>";
                    echo "<span class=\"number\">".(count($result)-$i)."</span>";
                    echo '<span class="autor"> Autor: <b>'.$result[$i]['autor'].'</b></span>';                    
                    echo '<span class="date">'.$result[$i]['date'].'</span>';      
                    echo '<h2>'.$result[$i]['title'].'</h2>';
                    if(!empty ($result[$i]['photo_primary']))
                    echo '<img src="data:image/jpeg;base64,'.base64_encode( $result[$i]['photo_primary'] ).'"/>';                    
                    echo '<div class="content">'.substr($result[$i]['content'], 0, 401);                     
                    echo '</div>';       
                    echo '<a class="read-more" href=php/article.php?id='.$result[$i]['id'].'>Czytaj wiecej</a>';             
                    //echo '<img src="data:image/jpeg;base64,'.base64_encode( $result[$i]['photo_secondary'] ).'"/>';                                 
                    echo "</article> <br>";
                }
                
                   
                ?> 
        </main>
    </body>
    <script>
        var artCon=document.getElementsByClassName("content");
        for (var i in artCon )
        {
            var el= artCon[i]
            console.log(el)
            var ile=400;
            if(el.innerHTML)
            if(el.innerHTML.length>ile)
            {
                el.innerHTML = el.innerHTML.substr(0,ile)+'...';
            }
        }
    </script>
</html>