<?php 
use \Firebase\JWT\JWT;

class Controller_Listas extends Controller_Main
{   
    //creacion de listas
    public function post_create()
    {
        try {
            //busca si no está definido el parametro namelist
            if ( ! isset($_POST['nameList'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro nombre de lista'
                ));

                return $json;
            }
            //envía los datos introducidos
            $input = $_POST;
            //comprueba el usuario mediante el token
            $idUsuarioEnTKN = self::checkToken();
            //crea una nueva lista mediante el modelo
            $list = new Model_List();
            //le da un nuevo nombre a esa lista
            $list->nameList = $input['nameList'];
            // y se la asigna a esa id
            $list->id_user = $idUsuarioEnTKN;
            $list->systemList = 2;
            $list->save();

            $json = $this->response(array(
                'code' => 201,
                'message' => 'Lista creada',
                'name' => $input['nameList'],
                'idCreador' => $idUsuarioEnTKN
            ));

            return $json;

        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor - error en token',
                'data' => null
            ));

            return $json;
        }        
    }
//modificacion de listas
    public function post_modify()
    {
        try {
            //busca si no esta definido el parametro namelist
            if ( ! isset($_POST['nameList'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro se llame nameList'
                ));

                return $json;
            }

            if ( ! isset($_POST['idList'])) 
            {
                //busca si no esta definido el parametro idList
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro idlIST'
                ));

                return $json;
            }
            //a la nueva lista le añade un id y un nombre, después guarda los datos
            $input = $_POST;
            $song = Model_List::find($input['idList']);
            $song->nameList = $input['nameList'];
            $song->save();

            $json = $this->response(array(
                'code' => 200,
                'message' => 'lista modificada',
                'name' => $song->nameList
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
//obtener las listas
    public function get_lists()
    {
    	try{
            //checkea el token y busca las listas asociadas a ese usuario con ese token y devuelve las listas en  el json
	        $idUser = self::checkToken();
	        $list = Model_List::find('all', ['where' => ['id_user' =>$idUser, 'systemList' => 2]]);

	        $json = $this->response(array(
	            $list,
	        ));

	        return $json;  
	    } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor - error en token',
                'data' => null
            ));

            return $json;
        }
    }
//devuelve todas las listas existentes
    public function get_listsAll()
    {
    	try{
            //checkea el token y luego devuelve las listas, todas las que existen
	        $idUser = self::checkToken();
	        $list = Model_List::find('all');

	        $json = $this->response(array(
	            'code' => 200,
                'message' => 'Mostrando todas las listas existentes',
                'data' => $list
	        ));

	        return $json;  
	    } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor - error en token',
                'data' => null
            ));

            return $json;
        }
    }
//borrar la lista x
    public function post_delete()
    {
        //busca la id de la lista y al pozo
        $list = Model_List::find($_POST['id']);
        $list->delete();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'lista borrada',
            'name' => $list
        ));

        return $json;
    }
}
