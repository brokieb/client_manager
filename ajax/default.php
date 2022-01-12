<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include(__DIR__ . '/../class.php');
session_start();
$connect = new ConfigAndConnect;
$det = new accountDetails();
$conn_user = $connect->db_connect('user');
$conn_form = $connect->db_connect('form');
$conn_data = $connect->db_connect('data');
$details = $det->accountData();
$group_det = $det->groupUsers();
$select = $conn_data->query("select * from `preference_user` where `preference_id`=4 AND `user_id`={$_SESSION['user']} ");
$row_demo = $select->fetch();
if ($select->rowCount() == 1 && $row_demo['preference_value'] == 1) {
    $demo = "OR `user_id`=1 ";
} else {
    $demo = "";
}