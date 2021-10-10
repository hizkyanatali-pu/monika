<?php
function switch_db($name_db)
{

    $dbname = WRITEPATH . "emon/sqlite/" . $name_db;
    $dbnamestr = strval($dbname);

    // 210417152546_fromemonSQLITE.db
    // print_r($name_db);
    // exit;

    $config_app['DSN'] = '';
    $config_app['hostname'] = '';
    $config_app['username'] = '';
    $config_app['password'] = '';
    $config_app['database'] =  $dbnamestr;
    $config_app['DBDriver'] = 'SQLite3';
    $config_app['DBPrefix'] = '';
    $config_app['pConnect'] = false;
    $config_app['DBDebug'] = (ENVIRONMENT !== 'production');
    $config_app['cacheOn'] = false;
    $config_app['cacheDir'] = '';
    $config_app['charset'] = 'utf8';
    $config_app['DBCollat'] = 'utf8_general_ci';
    $config_app['swapPre'] = '';
    $config_app['encrypt'] = false;
    $config_app['compress'] = false;
    $config_app['strictOn'] = false;
    $config_app['failover'] = [];
    $config_app['port'] = 3306;

    return $config_app;
}
