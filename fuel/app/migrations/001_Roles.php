<?php

namespace Fuel\Migrations;

class Roles
{
    function up()
    {
        \DBUtil::create_table('roles', 
            array(
            'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
            'nombre_rol' => array('type' => 'text', 'null' => false)
            ), 
            array('id'), false, 'InnoDB', 'utf8_general_ci');
    }

    function down()
    {
       \DBUtil::drop_table('roles');
    }
}
