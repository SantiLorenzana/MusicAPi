<?php 
use \Firebase\JWT\JWT;

class Controller_Main extends Controller_Rest 
{   
    protected function keyName(){
        $_key = "etkhtkhmth234234489T98NJDFNJSW33998df4werrt4jigtrg383hrwrfn4th4uHD98HO4383";
        return $_key;
    }

    protected function checkToken()
    {
        $headers = apache_request_headers();
        $token = $headers['Authorization'];    
        $key = self::keyName();    
        $tokenDecodificado = JWT::decode($token, $key, array('HS256'));

        return $tokenDecodificado->id;
    }

    protected function createToken($name, $pass, $id)
    {
        $token = array(
            "name" => $name,
            "pass" => $pass,
            "id" => $id,
            "logged" => true
        );
        
        $key = self::keyName(); 
        $jwt = JWT::encode($token, $key);

        return $jwt;  
    }

    public function post_createRol()
    {
        try {
            if ( ! isset($_POST['nombre_rol'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'se requiere el nombre del rol correcto',
                    'data' => null
                ));

                return $json;
            }

            $input = $_POST;
            $rol = new Model_Roles();
            $rol->descripcion = $input['descripcion'];
            $rol->save();

            $json = $this->response(array(
                'code' => 201,
                'message' => 'rol creado correctamente',
                'data' => $rol
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

    protected function decodeToken(){
        try {
            $header = apache_request_headers();
            $token = $header['Authorization'];
            if(!empty($token))
            {
                return $this->decode($token);
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

    public function post_firstConfig(){
        
            $checkDataBase = Model_Users::find('all');
            if ($checkDataBase == null){
                $rol = new Model_Roles();
                $rol->descripcion = "administrator";
                $rol->save();

                $rolGuest = new Model_Roles();
                $rolGuest->descripcion = "guest";
                $rolGuest->save();

                $user = new Model_Users();
                $user->name = "admin";
                $user->pass = "1234";
                $user->email = "admin@admin.es";
                $user->id_rol = "1";
                $user->save();
                $configDone = true;

                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Primera configuración finalizada',
                    'data' => null
                ));
                return $json;
            }else{
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'No es necesario hacer la primera configuración',
                    'data' => null
                ));
                return $json;
            }
        
    }
}
