<?php
session_start();
session_destroy();
?>
<meta http-equiv="refresh" content="0;url=<?=configAndConnect::URL?>index.php?site=login"/> 
