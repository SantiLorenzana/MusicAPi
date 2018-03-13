<?php 
use \Firebase\JWT\JWT;

class Controller_Contienen extends Controller_Main
{   
    public function post_addsong()
    {
        try {
            //busca si están definidops los parámetros idcancion y la id lista de la cancion
            if ( ! isset($_POST['idCancion'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'se necesita la cancion'
                ));

                return $json;
            }

            if ( ! isset($_POST['idLista'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'se necesita la lista'
                ));

                return $json;
            }
            //envía la cancion con los parámetros y le añade un tiempo/dia de creacion despues guarda los datos en la bd
            $input = $_POST;
            $add = new Model_Contiene();
            $add->id_cancion = $input['idCancion'];
            $add->id_lista = $input['idLista'];
            $add->createdAt = time();
            $add->save();

            $json = $this->response(array(
                'code' => 201,
                'message' => 'Cancion añadida',
                'data' => null
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
    //devolver una lista
    public function get_singleList(){

            if ( ! isset($_GET['idLista'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'se necesita la lista'
                ));
                return $json;
            }

            $contiene = Model_Contiene::find('all', ['where' => ['id_lista' => $_GET['idLista']]]);
        
	        foreach ($contiene as $key => $value) {
	                $idL[] = $value->id_lista;
	                $idC[] = $value->id_cancion;
	        }
            //busca la lista por id
	        foreach ($idL as $key => $value) {
	        		$name = Model_List::find($value, ['select' => 'nameList']);
	                $nameL[] = $name;
	        }
        //busca las canciones por id cancion asignadas a esta lista
	        foreach ($idC as $key => $value) {
	        		$name = Model_Cancion::find($value, ['select' => 'nameSong']);
	                $nameC[] = $name;
	        }
	        
	        $json = $this->response(array(
	            'code' => 201,
	            'message' => 'Datos devueltos correctamente',
	            'data' => ['canciones' => $nameC, 'listas' => $nameL]
	        ));

	        return $json;  
     
    }
}
