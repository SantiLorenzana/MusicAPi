<?php 

class Model_Users extends Orm\Model
{
    protected static $_table_name = 'users';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', // both validation & typing observers will ignore the PK
        'name' => array(
            'data_type' => 'varchar'   
        ),
        'pass' => array(
            'data_type' => 'varchar'   
        ),
        'email' => array(
            'data_type' => 'varchar'   
        ),
        'id_rol' => array(
            'data_type' => 'int'   
        ),
        'id_device' => array(
            'data_type' => 'varchar'   
        ),
        'profilePic' => array(
            'data_type' => 'varchar'   
        ),
        'descripcion' => array(
            'data_type' => 'varchar'   
        ),
        'birthdate' => array(
            'data_type' => 'varchar'   
        ),
        'coord_x' => array(
            'data_type' => 'int'   
        ),
        'coord_y' => array(
            'data_type' => 'int'   
        ),
        'ciudad' => array(
            'data_type' => 'varchar'   
        )
    );

    protected static $_belongs_to = array(
    'rol' => array(
        'key_from' => 'id_rol',
        'model_to' => 'Model_Roles',
        'key_to' => 'id',
        'cascade_save' => false,
        'cascade_delete' => false,
        )
    );

    protected static $_has_many = array(
    'list' => array(
        'key_from' => 'id',
        'model_to' => 'Model_List',
        'key_to' => 'id_user',
        'cascade_save' => false,
        'cascade_delete' => false,
    	),
    'noticia' => array(
        'key_from' => 'id',
        'model_to' => 'Model_Noticias',
        'key_to' => 'id_user',
        'cascade_save' => false,
        'cascade_delete' => false,
        )    
    );

    protected static $_many_many = array(
    'seguido' => array(
        'key_from' => 'id',
        'key_through_from' => 'id_seguido', 
        'table_through' => 'siguen',
        'key_through_to' => 'id_seguidor',
        'model_to' => 'Model_Siguen',
        'key_to' => 'id',
        'cascade_save' => false,
        'cascade_delete' => false,
        ),
    'seguidor' => array(
        'key_from' => 'id',
        'key_through_from' => 'id_seguidor', 
        'table_through' => 'siguen',
        'key_through_to' => 'id_seguido',
        'model_to' => 'Model_Siguen',
        'key_to' => 'id',
        'cascade_save' => false,
        'cascade_delete' => true,
        )
    );
}
