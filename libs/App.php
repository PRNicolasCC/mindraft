<?php
declare(strict_types=1);

require_once 'app/services/requests/PostManager.php';
require_once 'app/services/requests/PutManager.php';
require_once 'app/services/requests/DeleteManager.php';
require_once 'app/controllers/ErroresController.php';

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class App{
    private $controller;

    function __construct(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $request = null;
            if (isset($_POST['_method'])) {
                $simulated_method = strtoupper($_POST['_method']);
                switch ($simulated_method) {
                    case 'PUT':
                        $request = new PutManager();
                        break;
                    case 'DELETE':
                        $request = new DeleteManager();
                        break;
                    default:
                        $this->controller = new ErroresController('Error 403: Método no permitido');
                        break;
                }
            }
            if($request === null) $request = new PostManager();
            if(!empty($request->errorMessage)) $this->controller = new ErroresController($request->errorMessage);
        } else {
            if (!SessionManager::has('csrf_token')) SessionManager::set('csrf_token', bin2hex(random_bytes(32)));

            $url = isset($_GET['url']) ? $_GET['url']: '/';
            $url = rtrim($url, '/');
            $url = explode('/', $url);

            // cuando se ingresa sin definir controlador
            if(empty($url[0])){
                require_once 'app/controllers/NotebookController.php';
                $this->controller = new NotebookController();
                $this->controller->render();
                return;
            }

            $name = $url[0];
            $infoController = Controller::getInfoController($name);
            $archivoController = $infoController['file'];

            if(file_exists($archivoController)){
                require_once $archivoController;

                $controller = $infoController['controller'];
                // inicializar controlador
                $this->controller = new $controller;
                $getActionsController = $this->controller->getActions();
                
                //$url = array_filter($url); // array_filter elimina los valores vacíos por defecto.
                // elementos del arreglo
                $nparam = sizeof($url);
                if($nparam > 1){
                    // Comprobar si el método está disponible para ingresar manualmente
                    /* $is_token_method = in_array($url[1], $tokenMethodsController); */
                    $is_restricted_method = !in_array($url[1], $getActionsController);                                        

                    // Verificar el Referer si el método es restringido
                    // Para métodos que no sean GET y estén intentando ingresar mediante tal método
                    if ($is_restricted_method) {
                        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
                        // Obtenemos el host de la aplicación actual para compararlo con el Referer
                        $app_host = $_SERVER['HTTP_HOST'];
                        
                        // Comprueba si el referer no está presente O si el host del referer NO es el host de la aplicación
                        if (empty($referer) || !strpos($referer, $app_host) || !method_exists($this->controller, $url[1])) {
                            $this->controller = new ErroresController();
                            return;
                        }
                    }

                    $tokenMethodsController = $this->controller->getTokenMethods();
                    $is_token_method = in_array($url[1], $tokenMethodsController);

                    if($is_token_method) {
                        // Para métodos que incluyen token mediante el método GET (redireccionados desde un cliente de correo electrónico)
                        // Si el método requiere de un token y no tiene al menos 2 parámetros extras (token y email),
                        // significa que la URL no está completa. Se muestra error 404.
                        if($nparam !== 4) {
                            $this->controller = new ErroresController();
                            return;
                        }
                    }

                    if($nparam > 2){
                        $param = [];
                        for($i = 2; $i<$nparam; $i++){
                            array_push($param, $url[$i]);
                        }
                        
                        $this->controller->{$url[1]}($param);
                    }else{
                        $this->controller->{$url[1]}();
                    }
                }else{
                    $this->controller->render();
                }
            }else{
                $this->controller = new ErroresController();
            }
        }
    }
}
?>