<?php
declare(strict_types=1);

require_once 'app/services/requests/PostManager.php';

require_once 'app/controllers/ErroresController.php';

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class App{
    private $controller;

    function __construct(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $request = new PostManager();
            if(!empty($request->errorMessage)) $this->controller = new ErroresController($request->errorMessage);
        } else {
            $urlCompleta = isset($_GET['url']) ? $_GET['url']: '/';
            $url = rtrim($urlCompleta, '/');
            $url = explode('/', $url);

            // cuando se ingresa sin definir controlador
            if(empty($url[0])){
                $archivoController = 'app/controllers/MainController.php';
                require_once $archivoController;
                $this->controller = new MainController();
                $this->controller->render();
                return false;
            }

            $name = $url[0];
            $infoController = Controller::getInfoController($name);
            $archivoController = $infoController['file'];

            if(file_exists($archivoController)){
                require_once $archivoController;

                if (!SessionManager::has('csrf_token')) SessionManager::set('csrf_token', bin2hex(random_bytes(32)));

                #$urlController = $name . 'Controller';
                $controller = $infoController['controller'];
                // inicializar controlador
                $this->controller = new $controller;
                #$this->controller->loadModel($name);
                
                // elementos del arreglo
                $nparam = sizeof($url);

                if($nparam > 1){
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
                $this->controller = new ErroresController("Error 404: La página solicitada no existe.");
            }
        }
    }
    
}

?>