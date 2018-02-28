<?php 

namespace Fuel\Migrations;

class Privacidad
{

    function up()
    {
        \DBUtil::create_table('privacidad', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'perfil' => array('type' => 'int', 'constraint' => 10),
            'amigos' => array('type' => 'int', 'constraint' => 10),
            'listas' => array('type' => 'int', 'constraint' => 10),
            'notificaciones' => array('type' => 'int', 'constraint' => 10),
            'ubicacion' => array('type' => 'int', 'constraint' => 10),
            'id_usuario' => array('type' => 'int', 'constraint' => 11),
        ), array('id'), false, 'InnoDB', 'utf8_unicode_ci',
		    array(
		        array(
		            'constraint' => 'claveAjenaPrivacidadAUsuarios',
		            'key' => 'id_usuario',
		            'reference' => array(
		                'table' => 'usuarios',
		                'column' => 'id'
		            ),
		            'on_update' => 'CASCADE',
		            'on_delete' => 'RESTRICT'
		        )
		    )
		);
    }

    function down()
    {
       \DBUtil::drop_table('privacidad');
    }
}
