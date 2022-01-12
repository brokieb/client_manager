<?php
require_once('default.php');
$det = new accountDetails();

echo json_encode($det->accountData());

?>