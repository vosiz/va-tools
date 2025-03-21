<?php


function TestSelectAll() {

    global $db, $table_users;
    return $db->Query($table_users)->Select()->Execute();
}

function TestSelectOne() {

    global $db, $table_users;
    return $db->Query($table_users)->Where('id = ?', [1])->Select(['name'])->Execute();
}

function TestSelectWhereAnd() {

    global $db, $table_users;
    return $db->Query($table_users)->Where('id = ?', [1])->AndWhere('id = ?', [1])->Select(['id'])->Execute();
}

function TestSelectWhereOr() {

    global $db, $table_users;
    return $db->Query($table_users)->Where('id = ?', [1])->OrWhere('id = ?', [2])->Select(['id'])->Execute();
}