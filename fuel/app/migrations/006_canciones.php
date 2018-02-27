<?php 

namespace Fuel\Migrations;

class Canciones
{

    function up()
    {
        \DBUtil::create_table('canciones', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'titulo' => array('type' => 'varchar', 'constraint' => 150),
            'url_cancion' => array('type' => 'varchar', 'constraint' => 300),
            'artista' => array('type' => 'varchar', 'constraint' => 150),
            'reproducciones' => array('type' => 'int', 'constraint' => 100),
        ), array('id'));
    }

    function down()
    {
       \DBUtil::drop_table('canciones');
    }
}
