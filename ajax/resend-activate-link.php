<?PHP
include('default.php');
$rh = new configAndConnect();
$det = new accountDetails();
$details = $det->accountData();
$register = new doRegisterAttempt($details['username'], "", $details['email']);
$alex = $register->sendActivateLink();
echo json_encode($alex)

?>