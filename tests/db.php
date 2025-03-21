<?php

require_once(__DIR__.'/../src/struct/cred.php');
require_once(__DIR__.'/../src/db/dbcon.php');

use Vosiz\VaTools\Structure\Credentials;
use Vosiz\VaTools\Db\DbConnectionConfig;
use Vosiz\VaTools\Db\DbConnection;


// make connection
define('DB_CONNECT_STRING', "mysql:host=localhost;dbname=test_db");
define('DB_CONNECT_USER', 'root');
define('DB_CONNECT_PASS', '');
$conconf = new DbConnectionConfig(DB_CONNECT_STRING, new Credentials(DB_CONNECT_USER, DB_CONNECT_PASS));
$conconf->AddAttr(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$db = new DbConnection($conconf);
echo "db connected, no problem";


// DB structure assumed to be:
// db test_db:
$table_users = 'users';
// table users:
// - id, name, pass
// - values:
// -- 1, vosiz, v0$1Z
// -- 2, user, 1234
$table_weapons = 'registered_weapons';
// table registered_weapons
// - id, name, type(enum, NULLABLE), user_id
// - values:
// -- 1, pig launcher, unknown, 1
// -- 2, colt 1911, handgun, 2

// TEST
// - 00 - selections
require_once(__DIR__.'/db_00.php');
function TestDbSelection() {

    $all = TestSelectAll();
    var_dump($all);

    $one = TestSelectOne();
    var_dump($one);
    
    $wand = TestSelectWhereAnd();
    var_dump($wand);
    
    $wor = TestSelectWhereOr();
    var_dump($wor);
}

// - 01 - CRUD 
require_once(__DIR__.'/db_01.php');
function TestDbCrud() {

    $crud = TestCrudReadAll();
    var_dump($crud);

    $crud = TestCrudCreate();
    var_dump($crud);

    $crud = TestCrudReadAll();
    var_dump($crud);

    $crud = TestCrudUpdate();
    var_dump($crud);

    $crud = TestCrudReadAll();
    var_dump($crud);

    $crud = TestCrudDelete();
    var_dump($crud);

    $crud = TestCrudReadAll();
    var_dump($crud);
}