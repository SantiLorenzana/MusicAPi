<?php

namespace Fuel\Migrations;

class Users
{
    function up()
    {
        \DBUtil::create_table('users', 
            array(
            'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
            'name' => array('type' => 'text', 'null' => false),
            'email' => array('type' => 'text', 'null' => false),
            'id_device' => array('type' => 'text', 'null' => true),
            'profilePic' => array('type' => 'text', 'null' => true),
            'pass' => array('type' => 'text', 'null' => false),
            'descripcion' => array('type' => 'text', 'null' => true),
            'birthdate' => array('type' => 'int', 'null' => true),
            'coord_x' => array('type' => 'int', 'null' => true),
            'coord_y' => array('type' => 'int', 'null' => true),
            'ciudad' => array('type' => 'text', 'null' => true),
            'id_rol' => array('type' => 'int', 'null' => false)
            ), 
            array('id'), false, 'InnoDB', 'utf8_general_ci',
            array(
                array(
                    'constraint' => 'foreignkeyUsuariosARoles',
                    'key' => 'id_rol',
                    'reference' => array(
                        'table' => 'roles',
                        'column' => 'id',
                    ),
                    'on_update' => 'RESTRICT',
                    'on_delete' => 'RESTRICT'
                )
            )     
        );

        \DB::query("ALTER TABLE `users` ADD UNIQUE (`id`)",
            "ALTER TABLE `users` ADD UNIQUE (`email`)")->execute();
    }

    function down()
    {
       \DBUtil::drop_table('users');
    }
}
