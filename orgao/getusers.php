<?php
require('../config.php');
header('Content-Type: application/json;charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

getUsers();

function getUsers()
{
    global $DB;

    $users = $DB->get_records_sql(
        'SELECT id, firstname, lastname from {user}'
    );
    echo json_encode($users);
}


