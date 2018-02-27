<?php 

namespace Fuel\Migrations;

class Amigos
{

    function up()
    {
        \DBUtil::create_table('amigos', array(
            'id_usuario_agregador' => array('type' => 'int', 'constraint' => 11),
            'id_usuario_agregado' => array('type' => 'int', 'constraint' => 11),
        ), array('id_usuario_agregador', 'id_usuario_agregado'), false, 'InnoDB', 'utf8_unicode_ci',
		    array(
		        array(
		            'constraint' => 'claveAjenaAmigosAUsuariosAgragador',
		            'key' => 'id_usuario_agregador',
		            'reference' => array(
		                'table' => 'usuarios',
		                'column' => 'id'
		            ),
		            'on_update' => 'CASCADE',
		            'on_delete' => 'RESTRICT'
		        ),
		        array(
		            'constraint' => 'claveAjenaAmigosAUsuariosAgregado',
		            'key' => 'id_usuario_agregado',
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
       \DBUtil::drop_table('amigos');
    }
}
