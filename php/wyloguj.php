<?php
session_start();
?>
<HTML>
<HEAD>
  <TITLE>Wylogowanie</TITLE>
</HEAD>
<BODY>
<?php

  session_destroy();
  header("Location: logowanie.php?" . SID);
  exit();
?>
</BODY>
</HTML>
