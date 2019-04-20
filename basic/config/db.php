<?php

//Database for production
$db = parse_url(getenv('JAWSDB_URL'));
if ($_SERVER['SERVER_NAME'] == "secure-spire-32930.herokuapp.com") {
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=' . $db["host"] . ';dbname=' . ltrim($db["path"], '/'),
        'username' => $db["user"],
        'password' => $db["pass"],
        'charset' => 'utf8',
        // Schema cache options (for production environment)
        'enableSchemaCache' => true,
        'schemaCacheDuration' => 60,
        'schemaCache' => 'cache',
    ];
} else {
    //Database for development
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=yii2basic',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ];
}




