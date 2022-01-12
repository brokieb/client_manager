<?php

switch($_POST['content']){
    case 'success':
        ?>
Czy na pewno chcesz oznaczyć tą pozycję jako pomyślnie rozwiązaną? Pozycja ta zostanie przeniesiona do archiwum ( Wolniejszej bazie danych, dostęp do tych danych może nie być aż tak szybki jak do normalnych danych )
<input type='hidden' name='status' value='2'>
        <?php
        break;
    case 'loss':
        ?>
        Czy na pewno chcesz oznaczyć tą pozycję jako nierozwiązaną?  Pozycja ta zostanie przeniesiona do archiwum ( Wolniejszej bazie danych, dostęp do tych danych może nie być aż tak szybki jak do normalnych danych )
        <input type='hidden' name='status' value='1'>
                <?php
        break;
    case 'remove':
        ?>
        Rozpoczęto proces usuwania pozycji, jest to proces nieodwracalny, po usunięciu nie będzie możliwości przejścia do tej pozycji. Kontynuować?
        <input type='hidden' name='status' value='0'>
                <?php
        break;
}
?>
<input type='hidden' name='id' value='<?=$_POST['id']?>'>
<input type='hidden' name='page' value='<?=$_POST['page']?>'>
<input type='hidden' name='000mode' value='move'>