<?php 
use \Firebase\JWT\JWT;

class Controller_Listas extends Controller_Main
{   
    public function post_create()
    {
        try {
            if ( ! isset($_POST['nameList'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro nombre de lista'
                ));

                return $json;
            }

            $input = $_POST;
            $idUsuarioEnTKN = self::checkToken();
            $list = new Model_List();
            $list->nameList = $input['nameList'];
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

    public function post_modify()
    {
        try {
            if ( ! isset($_POST['nameList'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro se llame name'
                ));

                return $json;
            }

            if ( ! isset($_POST['idList'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro id'
                ));

                return $json;
            }

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

    public function get_lists()
    {
    	try{
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

    public function get_listsAll()
    {
    	try{
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

    public function post_delete()
    {
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
