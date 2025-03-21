<?php


function TestCrudCreate() { 

    global $db, $table_weapons;
    return $db->Query($table_weapons)->Insert([
        'name' => 'doom device',
        'user_id' => 1
    ])->Execute();
}

function TestCrudReadAll() { 
    
    global $db, $table_weapons;
    return $db->Query($table_weapons)->Select()->Execute();
}

function TestCrudUpdate(){  

    global $db, $table_weapons; 
    return $db->Query($table_weapons)->Update([
        'name' => 'doom device2',
        'user_id' => 2
    ])->Where('name = ?', ['doom device'])->Execute();
}

function TestCrudDelete() { 

    global $db, $table_weapons;
    return $query = $db->Query($table_weapons)->Where('name = ?', ['doom device2'])->Delete()->Execute();
}

