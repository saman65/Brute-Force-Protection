<?php
$db['db_name'] = "database_name";
$db['db_user'] = "root";
$db['db_pass'] = "";
$db['db_host'] = "localhost";

foreach($db as $key => $value){
    define(strtoupper($key), $value);
}

try {
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    // Rest of your database code here
} catch (mysqli_sql_exception $e) {
    header("Location: " . strtok($_SERVER['HTTP_REFERER'], '?')."?message=connection_error");
    exit;
}
?>
