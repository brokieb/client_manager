<?php
include('default.php');

$lock = "";
$method = "";
$active = "";
$href = "#back";
$icon = "fa-arrow-left";
$title = "Cofnij";
include('../view/nav-element.php');

$select = $conn_form->prepare('SELECT `nav_group`.`nav_href` as "href",
`nav_group`.`nav_icon` as "icon",
`nav_group`.`nav_title` as "title",
`nav`.`nav_href` as "main" FROM `nav_group` INNER JOIN `nav` ON `nav`.`nav_id`=`nav_group`.`nav_id` WHERE `nav_group`.`nav_id` = ?');
$select->execute([$_POST['id']]);
$site_array = explode("/",$_POST['site']); 
while($row = $select->fetch()){
    $lock = null;
    $method = null;
    if(count($site_array)>1){
        if($site_array[1]==$row['href']){
            $active = "active";
        }else{
            $active = null;
        }
    }else{
        $active = null;
    }
    
    $href = "?site=".strtolower($row['main'])."/".$row['href'];
    $icon = $row['icon'];
    $title = $row['title'];
    include('../view/nav-element.php');
}
 