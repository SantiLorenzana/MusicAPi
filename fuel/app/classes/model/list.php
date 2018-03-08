<?php 

class Model_List extends Orm\Model
{
    protected static $_table_name = 'list';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', 
        'nameList' => array(
            'data_type' => 'varchar'   
        ),
        'id_user' => array(
            'data_type' => 'int'   
        ),
        'systemList' => array(
            'data_type' => 'int'   
        ),
    );
   
    protected static $_belongs_to = array(
    'user' => array(
        'key_from' => 'id_user',
        'model_to' => 'Model_Users',
        'key_to' => 'id',
        'cascade_save' => false,
        'cascade_delete' => false,
        )
    );

    protected static $_many_many = array(
    'cancion' => array(
        'key_from' => 'id',
        'key_through_from' => 'id_cancion', 
        'table_through' => 'contiene',
        'key_through_to' => 'id_lista',
        'model_to' => 'Model_Cancion',
        'key_to' => 'id',
        'cascade_save' => false,
        'cascade_delete' => false,
        )
    );
}
