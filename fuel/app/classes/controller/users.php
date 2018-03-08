<?php 
use \Firebase\JWT\JWT;

class Controller_Users extends Controller_Main 
{   
    public function post_create()
    {
        try {
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

            $checkUsername = Model_Users::find('all', ['where' => ['name' => $_POST['name']]]);
			
            $checkEmail = Model_Users::find('all', ['where' => ['email' => $_POST['email']]]);

			$boolTested;

	        if ($checkUsername == null && $checkEmail == null){
	        	$boolTested = false;
	        }else{
	        	$boolTested = true;
	        }

            if ($boolTested == false){
	            $input = $_POST;
	            $user = new Model_Users();
	            $user->name = $input['name'];
	            $user->pass = $input['pass'];
	            $user->email = $input['email'];
                $user->id_rol = $input['rol'];
	            $user->save();

                $systemListListened = new Model_List();
                $systemListListened->nameList = 'songsListened';
                $systemListListened->id_user = $user->id;
                $systemListListened->systemList = 0;
                $systemListListened->save();
                
                $systemListLast = new Model_List();
                $systemListLast->nameList = 'lastListened';
                $systemListLast->id_user = $user->id;
                $systemListLast->systemList = 1;
                $systemListLast->save();

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

	        $input = $_POST;
	        $idUser = self::checkToken();
	        $user = Model_Users::find($idUser);
	        $user->pass = $_POST['pass'];
	        $user->save();

	        $json = $this->response(array(
	            'code' => 200,
	            'message' => 'contraseña modificada',
	            'data' => $user
	        ));

	        return $json;   
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

            $users = Model_Users::find('all', ['where' => ['name' => $_GET['name'], 'pass' => $_GET['pass']]]);

            foreach ($users as $key => $value) {
                $id = $value->id;
            }

            if ($users == null){
                $json = $this->response(array(
                    'code' => 401,
                    'message' => 'usuario o contraseña incorrecto',
                    'data' => null
                ));
                return $json;
            }else{
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

    public function get_recoverPass()
    {
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

            $users = Model_Users::find('all', ['where' => ['email' => $_GET['email'], 'name' => $_GET['name']]]);

            foreach ($users as $key => $value) {
                $id = $value->id;
            }

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


    public function get_users()
    {
    	try{
	        $users = Model_Users::find('all', ['select' => 'name']);

	        foreach ($users as $key => $value) {
	                $show[] = $value->name;
	                $showID[] = $value->id;
	        }
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

    public function post_delete()
    {
        try{
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
