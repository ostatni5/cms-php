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
    $user_login="";
    $user_pass="";
    $user_level="";
    
    $sql  = $dbh->prepare("SELECT level FROM users WHERE login = '$login' ");       
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    if(count($result)>0)
        if($result[0]['level']==0)
        {
            if($action_type=="edit")
            {
                try{
                    $user_login="ERROR";
                    $user_pass="ERROR";
                    $user_level="ERROR";
                    $user_login=$_POST['login'];
                    $sql  = $dbh->prepare("SELECT login,pass,level FROM users WHERE login = '$user_login' ");  
                        
                    $sql->execute();
                    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                    if(count($result)>0)
                    {
                        $user_login=$result[0]["login"];
                        $user_pass=$result[0]["pass"];
                        $user_level=$result[0]["level"];
                    
                    }
                }
                catch(PDOException $e){
                    echo 'Erroer:' . $e->getMessage();
                }
            }
            else if($action_type=="add")
            {

            }   
        }
        else{
            echo "nie mozesz";
        }   
 ?>
    <!DOCTYPE>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Panel CMS | Edycja Użytkownika</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <style>
            .user_edit {
                width: 100%;
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
                padding: 10px;
            }

            [type="submit"] {
                margin: 10px;
                border: none;
                color: white;
                width: calc(50% - 10px);
                background-color: #3498DB;
            }
        </style>
    </head>

    <body>
    <div class="backhome"><a href="panel.php">Panel</a></div>
        <div class="userinfo">
            <?php  echo "Witaj: <span>".$_SESSION["login"]."</span> <a class=\"logout\" href=\"wyloguj.php\">Wyloguj</a> " ?>
        </div>
        <main>
            <h1><?php if($action_type=='edit' )echo"Edycja";else echo "Dodawanie" ?> użytkownika</h1>
            <div class="user_edit">
                <form method="POST" action="saveuser.php">
                    <input name="action_type" type="hidden" value="<?php echo $action_type;?>">
                    <label>Login:
                        <input name="login" type="text" value="<?php echo $user_login;?>" required <?php if($action_type=='edit' )echo"readonly=\"readonly\"";?>/>
                    </label>
                    <label>Password:
                        <input name="pass" type="text" value="<?php echo $user_pass;?>" required/>
                    </label>
                    <label>Level:
                        <input name="level" type="number" max="99" min="0" step="1" value="<?php echo $user_level;?>" required />
                    </label>
                    <input type="submit" name="saveuser" value="Zapisz" />
                </form>

            </div>


        </main>
    </body>

    </html>