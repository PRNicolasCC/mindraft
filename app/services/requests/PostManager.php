<?php
declare(strict_types=1);

class PostManager {
    public string $errorMessage;

    /**
     * Constructor de la clase PostManager.
     * 
     * Valida si la solicitud es de tipo POST y, en caso afirmativo, procesa la acción
     * correspondiente según el parámetro POST recibido. Las acciones disponibles se definen
     * en el array $postActions, donde cada clave es el nombre del parámetro POST y el valor
     * es un array con el controlador y método que deben ejecutarse.
     *
     * Es importante no asignar valor alguno a $this->errorMessage ya que se utiliza para mostrar mensajes de error
     * y el constructor está planeado para procesar la solicitud cuando no hay ningun error.
     * 
     * @return void
     */
    function __construct(){
        if($this->validarPOST()){
            $userActions = [
                'create_user' => ['user', 'register'],
                'login' => ['user', 'login'],
                'password_reset' => ['user', 'passwordSendEmail'],
                'password_change' => ['user', 'passwordChange'],
            ];
            $postActions = array_merge($userActions);

            foreach ($postActions as $postKey => $contr) {
                if (isset($_POST[$postKey])) {
                    $infoController = Controller::getInfoController($contr[0]);
                    require_once $infoController['file'];
                    $controller = $infoController['controller'];
                    $postController = new $controller;
                    $method = $contr[1];
                    $postController->$method($_POST);
                }
            }
        }
    }

    /**
     * Valida las solicitudes POST
     * Verifica CSRF token, Content-Type y tamaño del payload
     * 
     * @return bool
     */
    private function validarPOST(): bool {
        // 1. Validar CSRF Token
        if(!$this->validarCSRFToken()){
            $this->errorMessage = 'Token de seguridad inválido. Por favor, recarga la página e intenta nuevamente.';
            return false;
        }

        // 2. Validar Content-Type para formularios exclusivas de la propia aplicación y no desde APIs
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if(!empty($_POST) || !empty($_FILES)){
            $esFormulario = strpos($contentType, 'application/x-www-form-urlencoded') !== false ||
                           strpos($contentType, 'multipart/form-data') !== false;

            if(!$esFormulario){
                $this->errorMessage = 'Tipo de contenido no válido. No hay soporte para API en estos momentos.';
                return false;
            }
        }

        //3. Sanitizar datos POST
        $this->sanitizarDatosPOST();

        // 4. Validar que la petición viene del mismo sitio
        if(!$this->validarReferer()){
            $this->errorMessage = 'La solicitud no proviene de una fuente válida.';
            return false;
        }

        return true;
    }

    /**
     * Valida el token CSRF
     * 
     * @return bool
     */
    private function validarCSRFToken(): bool {
        // Obtener token del POST
        $token = $_POST['csrf_token'] ?? null;
        
        // Validar que exista token en sesión y en POST
        if(!SessionManager::has('csrf_token') || empty($token)){
            return false;
        }

        // Comparación segura contra timing attacks
        return hash_equals(SessionManager::get('csrf_token'), $token);
    }

    /**
     * Sanitiza los datos POST recursivamente
     * 
     * @return void
     */
    private function sanitizarDatosPOST(): void {
        if(!empty($_POST)){
            $_POST = $this->sanitizarArray($_POST);
        }
    }

    /**
     * Sanitiza un array recursivamente
     * 
     * @param array $data El array a sanitizar
     * @return array
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
     * 
     * @param string $value El string a sanitizar
     * @return string
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
     * 
     * @return bool
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
}

?>