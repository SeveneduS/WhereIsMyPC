<?php

/*
 * Eduard Ragimov - 2016
 * WhereIsMyPC server
 * Server and DataBase server Configurations
 */

/* 
 * WhereIsMyPC - Computer's location tracking software.
 * WhereIsMyPC Server V 1.0.
 */

//Server state
/*
 * Available states:
 * Maintance
 * Development
 * Production
 */
define('SERVER_STATE', 'Development');

// Database connection details
define('DB_HOST', '127.0.0.1:3307');

//Update for production server
define('DB_USER',       'root');
define('DB_PASSWORD',   'password'); 
define('DB_NAME',       'whrim_db'); // DATABASE NAME
define('TB_NAME',       'main_tbl'); // TABLE NAME

define('SECRETKEY', 'SUPER_SECRET_KEY'); // use your own

define('CLIENT_CURRENT_VERSIOV', '1');

// Function to get the client IP address
function get_client_ip() 
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')) 
    {
        $ipaddress = getenv('HTTP_CLIENT_IP');
    }
    else if (getenv('HTTP_X_FORWARDED_FOR'))
    {
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    }
    else if (getenv('HTTP_X_FORWARDED')) 
    {
        $ipaddress = getenv('HTTP_X_FORWARDED');
    }
    else if (getenv('HTTP_FORWARDED_FOR')) 
    {
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    }
    else if (getenv('HTTP_FORWARDED')) 
    {
        $ipaddress = getenv('HTTP_FORWARDED');
    }
    else if (getenv('REMOTE_ADDR')) 
    {
        $ipaddress = getenv('REMOTE_ADDR');
    }
    else 
    {
        $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
}
