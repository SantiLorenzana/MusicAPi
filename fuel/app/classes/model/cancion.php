<?php 

class Model_Cancion extends Orm\Model
{
    protected static $_table_name = 'cancion';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id',
        'nameSong' => array(
            'data_type' => 'varchar'   
        ),
        'urlSong' => array(
            'data_type' => 'varchar'   
        ),
        'nameArtist' => array(
            'data_type' => 'varchar'   
        ),
        'playsCount' => array(
            'data_type' => 'int'   
        )
    );

    protected static $_many_many = array(
    'list' => array(
        'key_from' => 'id',
        'key_through_from' => 'id_lista', 
        'table_through' => 'contiene', 
        'key_through_to' => 'id_cancion', 
        'model_to' => 'Model_List',
        'key_to' => 'id',
        'cascade_save' => false,
        'cascade_delete' => false,
        )
    );

    protected static $_has_many = array(
    'comentario' => array(
        'key_from' => 'id',
        'model_to' => 'Model_Comentarios',
        'key_to' => 'id_cancion',
        'cascade_save' => false,
        'cascade_delete' => false,
        )
    );
}
