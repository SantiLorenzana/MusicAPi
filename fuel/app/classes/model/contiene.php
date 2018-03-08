<?php 

class Model_Contiene extends Orm\Model
{
    protected static $_table_name = 'contiene';
    protected static $_primary_key = array('id_cancion','id_lista');
    protected static $_properties = array(
        'id_cancion' => array(
            'data_type' => 'int'   
        ),
        'id_lista' => array(
            'data_type' => 'int'   
        ),
        'createdAt' => array(
            'data_type' => 'int'   
        )
    ); 
}