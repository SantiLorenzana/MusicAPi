<?php 

use Firebase\JWT\JWT;

class Controller_Users extends Controller_Rest
{
    private $key = '53jDgdTf5efGH54efef978';

    private function authorization($token)
    {

        $decoded = JWT::decode($token, $this->key, array('HS256'));

        $userId = $decoded->id;

        $usuario = Model_users::find('all', array(
                'where' => array(
                    array('id', $userId)
                ),
        ));

        if ($usuario != null) {
            return true;
        }
        else 
        {
           return false; 
        }
    }

    public function post_createAdmin()
    {
        try {
            //Validar si hay usuarios si estan vacíos crea el usuario Administrador
            $usuario = Model_users::find('all');

            if (empty($usuario)) {
                $user = new Model_users();
                $user->usuario = 'admin';
                $user->email = 'admin@admin.com';
                $user->contraseña = '1234';
                $user->id_device = '0';
                $user->coordenada_x = '0';
                $user->coordenada_y = '0';
                $user->ciudad = 'unknown';
                $user->id_rol = 1;
                $user->save();
                $json = $this->response(array(
                   'code' => 200,
                   'message' => 'Administrador creado',
                    'data' => null
                ));

            return $json;
            }
            else
            {
                $json = $this->response(array(
                   'code' => 400,
                   'message' => 'Ya existe un administrador',
                    'data' => null
                ));
            }
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 502,
                'message' => $e->getMessage(),
                'data' => null
            ));

            return $json;
        }
    }

    public function get_login()
    {
        try {
            if ( ! isset($_GET['usuario']) or
                 ! isset($_GET['contraseña']) or
                 $_GET['usuario'] == "" or
                 $_GET['contraseña'] == "") 
            {
                $json = $this->response(array(
                    'code' => 402,
                    'message' => 'parametros incorrectos/Los campos no pueden estar vacios',
                    'data' => null
                ));

                return $json;
            }

            $usuario = Model_users::find('first', array(
                'where' => array(
                    array('usuario', $_GET['usuario']),
                    array('contraseña', $_GET['contraseña'])
                ),
            ));
            
            //Validación usuario
            if (!empty($usuario)) {
                if($usuario->id_rol == 1){
                   //Generar token
                    $token = array(
                        'id'  => $usuario['id'],
                        'usuario' => $_GET['usuario'],
                        'contraseña' => $_GET['contraseña'],
                    );
                
                $jwt = JWT::encode($token, $this->key);

                $json = $this->response(array(
                        'code' => 201,
                        'message' => 'usuario logeado',
                        'data' => array(
                            'token' => $jwt,
                            'usuario' => $token['usuario']   
                        )
                    ));
                return $json;
                }
                else
                {
                    $json = $this->response(array(
                        'code' => 401,
                        'message' => 'Acceso denegado. Debes acceder con un usuario administrador',
                        'data' => null  
                    ));
                return $json;
                }
            }
            else
            {
                $json = $this->response(array(
                    'code' => 401,
                    'message' => 'El usuario no existe o contraseña incorrecta',
                    'data' => null
                ));
               return $json;
            }
        }
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 501,
                'message' => $e->getMessage(),
                'data' => null
            ));

            return $json;
        }
    }
    public function post_create()
    {
        try {
            //Validar el usuario adminsitrador
            $admin = Model_users::find('first', array(
                'where' => array(
                    array('usuario', 'admin'),
                ),
            ));
            if(empty($admin))
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Falta el usuario administrador',
                    'data' => null
                ));

                return $json;
            }

            //Validar campos rellenos y nombre correcto
            if ( ! isset($_POST['usuario']) or
                 ! isset($_POST['email']) or
                 ! isset($_POST['contraseña']) or
                 ! isset($_POST['repeatcontraseña']) or
                 $_POST['usuario'] == "" or
                 $_POST['email'] == "" or
                 $_POST['contraseña'] == "" or
                 $_POST['repeatcontraseña'] == "") 
            {
                $json = $this->response(array(
                    'code' => 402,
                    'message' => 'parametros incorrectos/Los campos no pueden estar vacios',
                    'data' => null
                ));

                return $json;
            }

            //busca usuarios
            $usuario = Model_users::find('all');

            //Validar usuario no existe
            $usuario = Model_users::find('all', array(
                'where' => array(
                    array('usuario', $_POST['usuario']),
                ),
            ));

            if (! empty($usuario)) {
               $json = $this->response(array(
                    'code' => 403,
                    'message' => 'Ya existe un usuario con este usuario',
                    'data' => null
                ));
               return $json;
            }

            //Validar email no existe
            $userEmail = Model_usuario::find('all', array(
                'where' => array(
                    array('email', $_POST['email']),
                ),
            ));

            if (! empty($userEmail)) {
               $json = $this->response(array(
                    'code' => 404,
                    'message' => 'Ya existe un usuario con este email',
                    'data' => null
                ));
               return $json;
            }

            if ($_POST['contraseña'] == $_POST['repeatcontraseña']) {
                
                $input = $_POST;
                $user = new Model_usuario();
                $user->usuario = $input['usuario'];
                $user->email = $input['email'];
                $user->contraseña = $input['contraseña'];
                $user->id_device = $input['id_device'];
                $user->coordenada_x = $input['coordenada_x'];
                $user->coordenada_y = $input['coordenada_y'];
                $user->id_rol = 2;
                $user->save();
                $json = $this->response(array(
                   'code' => 202,
                   'message' => 'usuario creado',
                    'data' => null
                ));

            return $json;
            }
            else
            {
                $json = $this->response(array(
                    'code' => 405,
                    'message' => 'Las contraseñas no coinciden',
                    'data' => null
                ));
               return $json;
            }

            

        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 502,
                'message' => $e->getMessage(),
                'data' => null
            ));

            return $json;
        }
    }

    public function get_usuario()
    {
        try {
            $token = apache_request_headers()['Authorization'];

            if ($this->authorization($token) == true){
               
                $decoded = JWT::decode($token, $this->key, array('HS256'));
                $id = $decoded->id;

                $usuario = Model_usuario::find('all');

                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Usuarios mostrados',
                    'data' => $usuario
                ));

                return $json;

            }
            else
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Token incorrecto, no tienes permiso'
                ));

                return $json;
            }
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => $e->getMessage(),
            ));

            return $json;
        }
    }

    public function post_delete()
    {
        try
        {
            $token = apache_request_headers()['Authorization'];

            if ($this->authorization($token) == true){
               
                $decoded = JWT::decode($token, $this->key, array('HS256'));
                $id = $decoded->id;
                $user = Model_users::find($id);

                if($user->id_rol != 1)
                {
                    $user->delete();
                    $json = $this->response(array(
                        'code' => 201,
                        'message' => 'usuario borrado',
                        'data' => null
                    ));
                    return $json;
                }
                else
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'No puede borrarse el usuario administrador',
                        'data' => null
                    ));
                    return $json;
                }
                
            
            }
            else
            {
                $json = $this->response(array(
                    'code' => 401,
                    'message' => 'Token incorrecto, no tienes permiso',
                    'data' => null
                ));

                return $json;
            }
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 501,
                'message' => $e->getMessage(),
                'data' => null
            ));

            return $json;
        }
    }
    public function post_modifyuser()
    {
        try
        {
            $token = apache_request_headers()['Authorization'];

            if ($this->authorization($token) == true){
               
                $decoded = JWT::decode($token, $this->key, array('HS256'));
                $id = $decoded->id;
                $user = Model_users::find($id);

                if (!empty($_POST['foto_perfil']) and !empty($_POST['contraseña']) or
                    !empty($_POST['foto_perfil']) and isset($_POST['contraseña']) or
                    isset($_POST['foto_perfil']) and !empty($_POST['contraseña']))
                {
                    //Guardar foto
                    if (isset($_POST['foto_perfil']) && !empty($_POST['foto_perfil']))
                    {
                        $user->foto_perfil = $_POST['foto_perfil'];
                        $user->save();
                    }
                    //Guardar contraseña
                    if (isset($_POST['contraseña']) && !empty($_POST['contraseña']))
                    {
                        $user->contraseña = $_POST['contraseña'];
                        $user->save();
                    }
                    

                    $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Usuario modificado correctamente',
                    'data' => $user
                    ));

                    return $json;
                }
                else
                {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Todos los parametros vacios',
                    'data' => $user
                ));

                return $json;
                }
            }
            else
            {
                $json = $this->response(array(
                    'code' => 401,
                    'message' => 'Token incorrecto, no tienes permiso',
                    'data' => null
                ));

                return $json;
            }
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 501,
                'message' => $e->getMessage(),
                'data' => null
            ));

            return $json;
        }
    }

    
}
