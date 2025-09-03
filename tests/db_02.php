<?php


function TestConnectionInfoAsArray() { 

    global $db, $table_weapons;
    return $db->ConnInfo()->AsArray();
}

function TestConnectionInfoAsString() { 

    global $db, $table_weapons;
    return $db->ConnInfo()->AsString();
}

