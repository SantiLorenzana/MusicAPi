<?php 
use \Firebase\JWT\JWT;

class Controller_Canciones extends Controller_Main 
{   
    //crear la canción
    public function post_create()
    {
        try {
            //primero busca los tres parámetros para cerciorarse
            if ( ! isset($_POST['name'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'se necesita el name',
                    'data' => null
                ));

                return $json;
            }

            if ( ! isset($_POST['urlS'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'se necesita la url',
                    'data' => null
                ));

                return $json;
            }

            if ( ! isset($_POST['artista'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'se necesita el nombre del artista',
                    'data' => null
                ));

                return $json;
            }

            //checkea el modelo igualando la url que enviamos a las ya existentes
            $check = Model_Cancion::find('all', ['where' => ['urlSong' => $_POST['urlS']]]);
            
            $boolTested;
            //si no existe
            if ($check == null){
                $boolTested = false;
            }else{
                //si esxiste
                $boolTested = true;
            }
            //si no existe crea la cancion usando el modelo dándole los atributos que hemos introducido y despues los guarda

            if ($boolTested == false){
                $input = $_POST;
                $song = new Model_Cancion();
                $song->nameSong = $input['name'];
                $song->urlSong = $input['urlS'];
                $song->nameArtist = $input['artista'];
                $song->save();

                $json = $this->response(array(
                    'code' => 201,
                    'message' => 'Cancion creada con éxito',
                    'data' => $song
                ));

                return $json;
            }else{
                $json = $this->response(array(
                    'code' => 401,
                    'message' => 'Cancion ya existente',
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

    //modificar una cancion ya existente
    public function post_modify()
    {
    	try{
	        if ( ! isset($_POST['nameSong'])) 
	        {
	            $json = $this->response(array(
	                'code' => 400,
	                'message' => 'se necesita el name',
	                'data' => null
	            ));

	            return $json;
	        }

	        if ( ! isset($_POST['urlSong'])) 
	        {
	            $json = $this->response(array(
	                'code' => 400,
	                'message' => 'se necesita el url',
	                'data' => null
	            ));

	            return $json;
	        }

	        if ( ! isset($_POST['idsong'])) 
	        {
	            $json = $this->response(array(
	                'code' => 400,
	                'message' => 'se necesita el  id',
	                'data' => null
	            ));

	            return $json;
	        }
            //busca la cancion que le enviamos mediante el id y cambia los parámetros de name y url despues gurda
	        $input = $_POST;
	        $song = Model_Cancion::find($input['idsong']);
	        $song->nameSong = $input['nameSong'];
	        $song->urlSong = $input['urlSong'];
	        $song->save();

	        $json = $this->response(array(
	            'code' => 200,
	            'message' => 'cancion modificada',
	            'data' => $song->nameSong
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

    public function get_songs(){
		$song = Model_Cancion::find('all');

        $json = $this->response(array(
            'code' => 200,
            'message' => 'mostrando todas las canciones',
            'data' => ["songs" => arr::reindex($song)]
        ));

        return $json; 
    }
//borrar una canción
    public function post_delete()
    {
    	try{
            //busca el parámetro id de la camnción
	    	if ( ! isset($_POST['id'])) 
		        {
	            $json = $this->response(array(
	                'code' => 400,
	                'message' => 'se necesita el id valido',
	                'data' => null
	            ));

	            return $json;
	        }else{
                //checkea el id enviado y si existe la borra
			    $song = Model_Cancion::find($_POST['id']);
			    $song->delete();

			    $json = $this->response(array(
			        'code' => 200,
			        'message' => 'cancion borrada',
			        'name' => $song
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

}
