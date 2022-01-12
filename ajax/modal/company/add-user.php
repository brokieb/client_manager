<?php
include('../../default.php');
$select = $conn_user->prepare("SELECT `group`.* FROM `users` INNER JOIN `group` ON `group`.`group_id`=`users`.`group_id` WHERE `uid` = ? LIMIT 1");
$select->execute([$_SESSION['user']]);
if($select->rowCount()>0){
$row = $select->fetch();
?>
<div class="alert alert-primary" role="alert">
        <h4 class="alert-heading">Panel dodawania pracownikówdo utworzonej grupy</h4>
        <p>
            W tym panelu można dodawać nowych pracowników którzy będą należeć do utworzonej przez Ciebie grupy. Podając kolejne adresy email system sam rozpozna czy konto o podanym przez Ciebie adresie:</br> 
            <ul>
                <li>już istnieje w naszej bazie - w takim przypadku użytkownik ten zostanie zaproszony mailowo do dołącznia do twojej grupy, klikając  w link zamieszczony w dostarczonym mailu automatycznie doda tego użytkownika</li>
                <li>nie istnieje - taki adres zostanie przypisany do nowego konta, a hasło pierwszego logowania zostanie utworzone losowo i dostarczone zostanie w mailu aktywacyjnym</li>
            </ul>
            Ograniczeniem przy dodawaniu użytkowników do twojej firmy to posiadanie przez wszystkich takiej samej domeny mailowej, w tym przypadku @<?=$row['group_domain']?>. Jeżeli jakiś użytkownik ma już konto w naszym serwisie, ale nie jest w twojej domenie - w takim przypadku proszę o kontakt z administratorem w celu zmienienia adresu email pracownika
            
</p>
</div>

<div class='col-5' id='add-company-emails' data-email="<?=$row['group_domain']?>">
    <div class='add-email  input-content'>

<label for="InputEmail1" class="form-label my-0 mt-2">Adres email</label>
<div class="input-group">
    <input type='email' name='email[]' class="form-control " required='required'> 
    <button class="btn btn-outline-secondary remove-company-email" disabled='disabled' type="button"><i class="fas fa-times"></i></button>
</div>
    <div id="invalid-InputEmail1" class="invalid-feedback feedback"> </div>
    <div id="invalid-InputEmail1" class="valid-feedback feedback"> </div>
</div>
<div class='buttons'>
    <button type='button' class='btn btn-sm btn-success m-2 add-another-email'>DODAJ KOLEJNY ADRES</button>
    <input type='hidden' name='000mode' value='company-addUser'>
</div>
</div>
<?php
}
?>
