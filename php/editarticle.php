<?php
    include("sql.php");
    session_start();
    if (!isset($_SESSION["login"]))
    {
        header("Location: logowanie.php");
        exit();
    }
    $login=$_SESSION['login'];   
    $action_type=$_POST['action_type'];
    $article_id=0;
    $title="";
    $content="";
    if($action_type=='edit')
    {
        $sql  = $dbh->prepare("SELECT * FROM articles WHERE id=:id");
        $sql->bindParam(':id', $_POST["id"], PDO::PARAM_INT);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        
        if(count($result)>0)
        {
            if($result[0]['autor']==$_SESSION["login"] || $_SESSION["level"] ==0)
            {
                $title=$result[0]['title'];
                $content=$result[0]['content'];
                $article_id=$_POST['id'];
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
    <!DOCTYPE>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Panel CMS | Edycja Postu</title>
        <!-- Main Quill library -->
        <script src="//cdn.quilljs.com/1.3.3/quill.js"></script>
        <script src="//cdn.quilljs.com/1.3.3/quill.min.js"></script>

        <!-- Theme included stylesheets -->
        <link href="//cdn.quilljs.com/1.3.3/quill.snow.css" rel="stylesheet">
        <link href="//cdn.quilljs.com/1.3.3/quill.bubble.css" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <style>
            .user_edit {
                width:100%;
            }

            label {
                box-sizing: border-box;
                float: left;
                clear: both;
                width: 100%;
                padding: 10px 10px;
                font-size: 30px;
                background-color: #2a2a2a;
                color: white;
            }

            input {
                float: right;
                width: 50%;
                min-width: 200px;
                box-sizing:border-box;
                padding: 10px;
                vertical-align: text-top;
                
            }

            [type="submit"] {
                margin: 10px;
                border: none;
                color: white;
                width: calc(50% - 10px);
                background-color: #3498DB;
            }
            [type="checkbox"]{
                height:31px;
            }
            .ql-container{
                color:black;
                height:500px;
            }
            .float-left{
                float:left;
            }
            img{
                max-width:100%;
                max-height:200px;
                object-fit:cover;
                float:left;
                clear:both;
            }
        </style>
    </head>

    <body>
    <div class="backhome"><a href="panel.php">Panel</a></div>
        <div class="userinfo">
            <?php  echo "Witaj: <span>".$_SESSION["login"]."</span> <a class=\"logout\" href=\"wyloguj.php\">Wyloguj</a> " ?>
        </div>
        <main>
            <h1><?php if($action_type=='edit' )echo"Edycja";else echo "Dodawanie" ?> postu</h1>
            <div class="user_edit">
                <form method="POST" action="savearticle.php" enctype="multipart/form-data">
                <input type="hidden" name="MAX_FILE_SIZE" value="2097152" /> 
                    <input name="action_type" type="hidden" value="<?php echo $action_type;?>">
                    <?php if($action_type=='edit' )echo'<input name="id" type="hidden" value="'.$article_id.'">';?>
                    <label>Zdjecie główne:
                        <input type="file" accept="image/*" name="photo_primary">
                        <?php if($action_type=='edit' )echo '<span class="float-left">(ostatnie ustawione)</span>'; ?>
                        <?php 
                        if($action_type=='edit' )
                        if(!empty ($result[0]['photo_primary']))
                        echo '<img src="data:image/jpeg;base64,'.base64_encode( $result[0]['photo_primary'] ).'"/>';
                        ?>
                        
                    </label>
                    <?php if($action_type=='edit' )echo'<label>Brak głównego zdjęcia <input type="checkbox" name="no_primary" value="yes"></label>';?>
                    <label>Tytuł:
                        <input name="title" type="text" value=" <?php if($action_type=='edit' )echo $title; ?>" required/>
                    </label>
                    <label>Treść:
                        <textarea class="hidden" name="content" id="editor-2"></textarea> 
                        <div class="bg-white">
                            <div class="editor" id="editor">
                            <?php if($action_type=='edit' )echo $content; ?>
                            </div>
                        </div>
                    </label>
                    <label>Zdjecie dodatkowe: 
                        <input type="file" accept="image/*" name="photo_secondary">
                        <?php if($action_type=='edit' )echo '<span class="float-left">(ostatnie ustawione)</span>'; ?>
                        <?php 
                        if($action_type=='edit' )
                        if(!empty ($result[0]['photo_secondary']))
                        echo '<img src="data:image/jpeg;base64,'.base64_encode( $result[0]['photo_secondary'] ).'"/>';
                        ?>
                    </label>
                    <?php if($action_type=='edit' )echo'<label>Brak dodatkowego zdjęcia <input type="checkbox" name="no_secondary" value="yes"></label>';?>
                    <input type="submit" name="savearticle" value="Zapisz" />
                </form>

            </div>


        </main>
    </body>
    <script>    
    var editor = new Quill('#editor',{
        modules: {
            toolbar: [
            [{ header: [2, 3,4, false] }],
            ['bold', 'italic', 'underline'],          
            ]
        },
        placeholder: 'Wpisz tu treść posta',
        theme: 'snow'  // or 'bubble'
        }); 
        document.onclick= function(){
            document.getElementById("editor-2").value =document.getElementsByClassName("ql-editor")[0].innerHTML;
            console.log(document.getElementById("editor-2").value);
        }
    </script>

    </html>