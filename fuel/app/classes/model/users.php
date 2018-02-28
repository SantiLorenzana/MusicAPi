<?php 

class Model_Users extends Orm\Model
{
    protected static $_table_name = 'usuarios';
    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', 
        'usuario' => array(
            'data_type' => 'varchar'   
        ),
        'contraseña' => array(
            'data_type' => 'varchar'   
        ),
        'email' => array(
            'data_type' => 'varchar'   
        ),
        'id_device' => array(
            'data_type' => 'varchar'   
        ),
        'foto_perfil' => array(
            'data_type' => 'varchar'   
        ),
        'descripcion' => array(
            'data_type' => 'varchar'   
        ),
        'cumpleaños' => array(
            'data_type' => 'varchar'   
        ),
        'coordenada_x' => array(
            'data_type' => 'decimal'   
        ),
        'coordenada_y' => array(
            'data_type' => 'decimal'   
        ),
        'ciudad' => array(
            'data_type' => 'varchar'   
        ),
        'id_rol' => array(
            'data_type' => 'int'   
        )
    );
}
