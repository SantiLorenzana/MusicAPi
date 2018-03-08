<?php

namespace Fuel\Migrations;

class Canciones
{

    function up()
    {
        \DBUtil::create_table('cancion', 
            array(
            'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
            'nameSong' => array('type' => 'text', 'null' => false),
            'nameArtist' => array('type' => 'text', 'null' => false),
            'urlSong' => array('type' => 'text', 'null' => false),
            ), 
            array('id'), false, 'InnoDB', 'utf8_general_ci');
    }

    function down()
    {
       \DBUtil::drop_table('cancion');
    }
}
