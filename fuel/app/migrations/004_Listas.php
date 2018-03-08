<?php

namespace Fuel\Migrations;

class Listas
{

    function up()
    {
        \DBUtil::create_table('list', 
            array(
            'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
            'nameList' => array('type' => 'text', 'null' => false),
            'id_user' => array('type' => 'int', 'null' => false),
            ), 
            array('id'), false, 'InnoDB', 'utf8_general_ci',
            array(
		        array(
		            'constraint' => 'foreignkeyListasAUsuarios',
		            'key' => 'id_user',
		            'reference' => array(
		                'table' => 'users',
		                'column' => 'id',
		            ),
		            'on_update' => 'RESTRICT',
		            'on_delete' => 'RESTRICT'
		        )
			)
        );
    }

    function down()
    {
       \DBUtil::drop_table('list');
    }
}
