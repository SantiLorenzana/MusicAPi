<?php 
use \Firebase\JWT\JWT;

class Controller_Users extends Controller_Main 
{   
    public function post_create()
    {
        try {
            //isset nos dice si la variable está definida(en este caso el parametro del array post name) isset devuelve valores true o false
            //en este caso si el name no está definido nos dará el code 400
            if ( ! isset($_POST['name'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro se llame name',
                    'data' => null
                ));

                return $json;
            }

            if ( ! isset($_POST['pass'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro se llame pass',
                    'data' => null
                ));

                return $json;
            }

            if ( ! isset($_POST['email'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro email',
                    'data' => null
                ));

                return $json;
            }

            if ( ! isset($_POST['rol'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro rol',
                    'data' => null
                ));

                return $json;
            }
            //comparacion de nombres por si hay alguno igual en la bd gracias al modelo
            $checkUsername = Model_Users::find('all', ['where' => ['name' => $_POST['name']]]);
			//comparacion de mail por si hay alguno igual en la bd
            $checkEmail = Model_Users::find('all', ['where' => ['email' => $_POST['email']]]);
            //testeo de la comprobacion
			$boolTested;

	        if ($checkUsername == null && $checkEmail == null){
	        	$boolTested = false;
	        }else{
                //si es true no hace nada
	        	$boolTested = true;
	        }
            //si es false entra en el if creando el user
            if ($boolTested == false){
	            $input = $_POST;
	            $user = new Model_Users();
	            $user->name = $input['name'];
	            $user->pass = $input['pass'];
	            $user->email = $input['email'];
                $user->id_rol = $input['rol'];
	            $user->save();

	            $json = $this->response(array(
	                'code' => 201,
	                'message' => 'usuario creado',
	                'data' => $user
	            ));

	            return $json;
            }else{
            	$json = $this->response(array(
	                'code' => 401,
	                'message' => 'el usuario ya existe',
                    'data' => null
	            ));
	            return $json;
            }

        } 
        // En caso de que haya conflictos con la conexion del servidor
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor',
                'data' => null
            ));

            return $json;
        }        
    }
//funcion de modificación de datos
    public function post_modify()
    {
    	try {
	        if ( ! isset($_POST['pass'])) 
	        {
	            $json = $this->response(array(
	                'code' => 400,
	                'message' => 'parametro incorrecto, se necesita que el parametro se llame pass',
	                'data' => null
	            ));

	            return $json;
	        }
            //recogemos los datos del input, en este caso la nueva pass
	        $input = $_POST;
            //asignación del token al usuario
	        $idUser = self::checkToken();
            //busca el usuario asignado a la id
	        $user = Model_Users::find($idUser);
            //envía la pass nueva
	        $user->pass = $_POST['pass'];
            // y se guardan los datos
	        $user->save();

            //Una vez hecho se envía el code 200 en caso afirmativo
	        $json = $this->response(array(
	            'code' => 200,
	            'message' => 'contraseña modificada',
	            'data' => $user
	        ));

	        return $json;   
	    }
        // en caso negativo
	    catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor',
                'data' => null
            ));

            return $json;
        }      
    }
    //funcion de login del usuario
    public function get_login()
    {
        try {
            if ( ! isset($_GET['name'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro se name',
                    'data' => null
                ));

                return $json;
            }

            if ( ! isset($_GET['pass'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro se llame pass',
                    'data' => null
                ));

                return $json;
            }
            //busca en el modelo al user con los parámetros introducidos
            $users = Model_Users::find('all', ['where' => ['name' => $_GET['name'], 'pass' => $_GET['pass']]]);
            //le asigna una id
            foreach ($users as $key => $value) {
                $id = $value->id;
            }
            //si no lo encuentra devuelve el 401
            if ($users == null){
                $json = $this->response(array(
                    'code' => 401,
                    'message' => 'usuario o contraseña incorrecto',
                    'data' => null
                ));
                return $json;
            }else{
                // si por el contrario lo encuentra devuelve el code 201 de logueado y se le asigna un token, que es creado ahí, achacado a ese nombre y esa contraseña
            	$token = self::createToken($_GET['name'],$_GET['pass'],$id);  
                $json = $this->response(array(
                    'code' => 201,
                    'message' => 'Logeado',
                    'data' => ["token" => $token]           
                ));
                return $json;  
            }
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor',
                'data' => null
            ));

            return $json;
        }                       
    }
    
    //funcion para recuperar contraseña
    public function get_recoverPass()
    {
        //primero compara que hay ese email y nombre en la bd
        try {
            if ( ! isset($_GET['email'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro email',
                    'data' => null
                ));

                return $json;
            }

            if ( ! isset($_GET['name'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro se llame name',
                    'data' => null
                ));

                return $json;
            }
            //si existe dentro de la bd trae de vuelta el name
            $users = Model_Users::find('all', ['where' => ['email' => $_GET['email'], 'name' => $_GET['name']]]);

            foreach ($users as $key => $value) {
                $id = $value->id;
            }
            // en caso de no existir ese nombre dentro de la bd
            if ($users == null){
                $json = $this->response(array(
                    'code' => 401,
                    'message' => 'usuario o email incorrecto',
                    'data' => null
                ));
                return $json;
            }else{
                $token = self::createToken($_GET['name'],"NotDefined",$id);
                $json = $this->response(array(
                    'code' => 201,
                    'message' => 'Logeado',
                    'data' => ['token'=>$token]           
                ));
                return $json;  
            }
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor',
                'data' => null
            ));

            return $json;
        }                       
    }

//funcion para mostrar todos los usuarios
    public function get_users()
    {
    	try{
            //recaba todos por el parametro name
	        $users = Model_Users::find('all', ['select' => 'name']);

	        foreach ($users as $key => $value) {
	                $show[] = $value->name;
	                $showID[] = $value->id;
	        }
            //introduce los valores encontrados en un array y lo devuelve como json al final
	        $json = $this->response(array(
	            'name' => $show,
	            'id' => $showID
	        ));

	        return $json; 
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor',
            ));

            return $json;
        }  
    }
//funcion de borrado de usuario
    public function post_delete()
    {
        try{
            //iguala el id que queremos borrar con el token actual que contiene los datos, los busca dentro del modelo y lo borra. 
        	$idABorrar = self::checkToken();
            $user = Model_Users::find($idABorrar);
            $userName = $user->name;
            $user->delete();

            $json = $this->response(array(
                'code' => 200,
                'message' => 'usuario borrado',
                'data' => $userName
            ));

            return $json;
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor',
            ));

            return $json;
        }
    }
}
