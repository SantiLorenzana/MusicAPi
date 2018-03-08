php<?php 
use \Firebase\JWT\JWT;

class Controller_Contienen extends Controller_Main
{   
    public function post_addsong()
    {
        try {
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

    public function get_contieneAll(){
    	$contiene = Model_Contiene::find('all');

        foreach ($contiene as $key => $value) {
                $idL[] = $value->id_lista;
                $idC[] = $value->id_cancion;
        }

        foreach ($idL as $key => $value) {
        		$name = Model_List::find($value, ['select' => 'nameList']);
                $nameL[] = $name;
        }

        foreach ($idC as $key => $value) {
        		$name = Model_Cancion::find($value, ['select' => 'nameSong']);
                $nameC[] = $name;
        }
        
        $json = $this->response(array(
            'code' => 201,
            'message' => 'Datos devueltos',
            'data' => ['canciones' => $nameC, 'listas' => $nameL]
        ));

        return $json;  
    }

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

	        foreach ($idL as $key => $value) {
	        		$name = Model_List::find($value, ['select' => 'nameList']);
	                $nameL[] = $name;
	        }

	        foreach ($idC as $key => $value) {
	        		$name = Model_Cancion::find($value, ['select' => 'nameSong']);
	                $nameC[] = $name;
	        }
	        
	        $json = $this->response(array(
	            'code' => 201,
	            'message' => 'Datos devueltos',
	            'data' => ['canciones' => $nameC, 'listas' => $nameL]
	        ));

	        return $json;  
     
    }
}
