<?php

namespace Fuel\Migrations;

class Comentarios
{

    function up()
    {
        \DBUtil::create_table('comentarios', 
            array(
            'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
            'comentario' => array('type' => 'text', 'null' => false),
            'id_cancion' => array('constraint' => 11, 'type' => 'int', 'null' => false)
            ), 
            array('id'), false, 'InnoDB', 'utf8_general_ci',
            array(
		        array(
		            'constraint' => 'foreignkeyComentariosACanciones',
		            'key' => 'id_cancion',
		            'reference' => array(
		                'table' => 'cancion',
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
       \DBUtil::drop_table('comentarios');
    }
}
