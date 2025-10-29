<?php
declare(strict_types=1);

require_once 'app/controllers/ErroresController.php';

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class App{

    function __construct(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->validarPOST();
        }
        $url = isset($_GET['url']) ? $_GET['url']: '/';
        $url = rtrim($url, '/');
        $url = explode('/', $url);

        // cuando se ingresa sin definir controlador
        if(empty($url[0])){
            $archivoController = 'app/controllers/MainController.php';
            require_once $archivoController;
            $controller = new MainController();
            $controller->loadModel('Main');
            $controller->render();
            return false;
        }

        $name = ucfirst($url[0]);
        $archivoController = 'app/controllers/' . $name . 'Controller.php';

        if(file_exists($archivoController)){
            require_once $archivoController;

            $urlController = $name . 'Controller';
            // inicializar controlador
            $controller = new $urlController;
            $controller->loadModel($name);
            
            // # elementos del arreglo
            $nparam = sizeof($url);

            if($nparam > 1){
                if($nparam > 2){
                    $param = [];
                    for($i = 2; $i<$nparam; $i++){
                        array_push($param, $url[$i]);
                    }
                    $controller->{$url[1]}($param);
                }else{
                    $controller->{$url[1]}();
                }
            }else{
                $controller->render();
            }
        }else{
            $controller = new ErroresController();
        }
    }

    /**
     * Valida las solicitudes POST
     * Verifica CSRF token, Content-Type y tamaño del payload
     */
    private function validarPOST(): void {
        // 1. Validar CSRF Token
        if(!$this->validarCSRFToken()){
            $this->mostrarError('Token de seguridad inválido. Por favor, recarga la página e intenta nuevamente.');
        }

        // 2. Validar Content-Type para formularios exclusivas de la propia aplicación y no desde JSON o API
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if(!empty($_POST) || !empty($_FILES)){
            $esFormulario = strpos($contentType, 'application/x-www-form-urlencoded') !== false ||
                           strpos($contentType, 'multipart/form-data') !== false;

            if(!$esFormulario){
                $this->mostrarError('Tipo de contenido no válido. No hay soporte para JSON/API en estos momentos.');
            }
        }

        //3. Sanitizar datos POST
        $this->sanitizarDatosPOST();

        // 4. Validar que la petición viene del mismo sitio
        if(!$this->validarReferer()){
            $this->mostrarError('La solicitud no proviene de una fuente válida.');
        }
    }

    /**
     * Valida el token CSRF
     */
    private function validarCSRFToken(): bool {
        // Obtener token del POST
        $token = $_POST['csrf_token'] ?? null;
        
        // Validar que exista token en sesión y en POST
        if(!isset($_SESSION['csrf_token']) || empty($token)){
            return false;
        }

        // Comparación segura contra timing attacks
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Sanitiza los datos POST recursivamente
     */
    private function sanitizarDatosPOST(): void {
        if(!empty($_POST)){
            $_POST = $this->sanitizarArray($_POST);
        }
    }

    /**
     * Sanitiza un array recursivamente
     */
    private function sanitizarArray(array $data): array {
        $sanitizado = [];
        foreach($data as $key => $value){
            // Sanitizar la clave
            $keySanitizada = $this->sanitizarString($key);
            
            if(is_array($value)){
                $sanitizado[$keySanitizada] = $this->sanitizarArray($value);
            }else{
                $sanitizado[$keySanitizada] = $this->sanitizarString($value);
            }
        }
        return $sanitizado;
    }

    /**
     * Sanitiza un string
     */
    private function sanitizarString(string $value): string {
        // Eliminar caracteres nulos que pueden causar problemas
        $value = str_replace(chr(0), '', $value);
        // Eliminar espacios al inicio y final
        $value = trim($value);
        // Eliminar caracteres de control excepto saltos de línea y tabs
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
        
        return $value;
    }

    /**
     * Valida que la petición venga del mismo sitio (Referer)
     */
    private function validarReferer(): bool {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $esquema = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $urlBase = $esquema . '://' . $host;

        // Si no hay referer, rechazar (los navegadores modernos siempre lo envían)
        if(empty($referer)){
            return false;
        }

        // Validar que el referer inicie con la URL base del sitio
        return strpos($referer, $urlBase) === 0;
    }

    /**
     * Muestra un error de validación y detiene la ejecución
     */
    private function mostrarError(string $mensaje): void {
        // Guardar mensaje en sesión para mostrarlo después del redirect
        $_SESSION['error_validacion'] = $mensaje;
        
        // Obtener la URL de donde venía (referer) o redirigir al inicio
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        
        // Redirigir de vuelta con el error
        header('Location: ' . $referer);
        exit;
    }
}

?>