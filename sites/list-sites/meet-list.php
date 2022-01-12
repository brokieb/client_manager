  <?php
$config = array(
  'columns'=>array(
      'Imię i nazwisko'=>2,
      'Adres'=>2,
      'Telefon'=>2,
      'Data spotkania'=>2,
      'Typ spotkania'=>2, 
      'Przyciski'=>2
  ),
  'config'=>array(
      'site'=>'meet',
      'order'=>" CASE WHEN TIMESTAMP(meet_date) = CURRENT_TIMESTAMP AND TIMESTAMP(meet_date,meet_time) > CURRENT_TIMESTAMP
      THEN 1
      WHEN TIMESTAMP(meet_date, meet_time) > CURRENT_TIMESTAMP 
      THEN 2
      ELSE 3 END,
 CASE WHEN TIMESTAMP(meet_date) > CURRENT_TIMESTAMP
      THEN TIMESTAMP(meet_date, meet_time) + 0
      ELSE DATEDIFF(CURRENT_DATE, meet_date) END, `meet_time` ASC",
      'legend'=>array( 
        'success'=>'Spotkania zaplanowane na dzisiaj',
        null=>'Spotkania na przyszłe dni', 
        'danger'=>'Spotkania minione'
      ),
      'group_rows'=>array(
        'build'=>'meet_build-group-id',
        'client'=>'meet_client-group-id')
  )
      );
      $c_order= "CASE WHEN TIMESTAMP(meet_date) = CURRENT_DATE AND TIMESTAMP(meet_date,meet_time) > CURRENT_TIMESTAMP
      THEN 1
      WHEN TIMESTAMP(meet_date, meet_time) > CURRENT_TIMESTAMP 
      THEN 2
      ELSE 3 END ";
include("view/view-content.php");
  ?>