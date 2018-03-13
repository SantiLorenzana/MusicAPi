<?php 
use \Firebase\JWT\JWT;

class Controller_Main extends Controller_Rest 
{   
    //encode del token
    protected function keyName(){
        $_key = "etkhtkhmth234234489T98NJDFNJSW33998df4werrt4jigtrg383hrwrfn4th4uHD98HO4383";
        return $_key;
    }
//comprobación de la existencia del token
    protected function checkToken()
    {
        $headers = apache_request_headers();
        $token = $headers['Authorization'];    
        $key = self::keyName(); 
        //clave de decode del token
        $tokenDecodificado = JWT::decode($token, $key, array('HS256'));
        //asignacion del token decodificado a la id
        return $tokenDecodificado->id;
    }
//creación del token
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
//decodificación del token
    protected function decodeToken(){
        try {
            $header = apache_request_headers();
            $token = $header['Authorization'];
            //comprueba si hay token y lo decodifica, si no da error 500
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

}
