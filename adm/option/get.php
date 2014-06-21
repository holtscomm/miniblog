<?php
include('../../includes/config.php');
include('../../includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

// Get and then cleanse the input
$config_name = get_value($_REQUEST, 'configName', false);
$config_name = $database->real_escape_string($config_name);

if($config_name)
{
    $options = get_options($config_name, $database);

    header('Content-Type: application/json');
    echo json_encode($options);
}
