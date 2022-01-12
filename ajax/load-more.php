<?php
include('default.php');

$select=$conn_data->query(strstr($_SESSION['q_pagination'], 'OFFSET', true). "OFFSET " .($_POST['offset'] + 10));
$c_pagination = $_POST['offset'];
$i = 0;

$select ->execute();
$config = json_decode($_POST['config'],true);
       if($select->rowCount()>0){
           
        while ($row = $select->fetch()) {
            $i++;
            if($c_pagination==($_POST['offset']+10)){
                $c_pagination++;
                break;
            }else{
                $c_pagination++;
            }
           
            // $config = json_decode($_POST['config']);
include('../view/'.$_POST['view'].'.php');
        }
    }else{
        ?>
KONIEC
        <?php
    }
?>