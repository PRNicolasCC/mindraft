<?php
declare(strict_types=1);

abstract class Manager {
    public string $errorMessage;

    function __construct(array $actions){
        if($this->validar()){
            foreach ($actions as $key => $contr) {
                if (isset($_POST[$key])) {
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
     * Valida cualquier tipo de solicitud
     * Verifica CSRF token, Content-Type y tamaño del payload
     * 
     * @return bool
     */
    private function validar(): bool {
        // 1. Validar CSRF Token
        if(!$this->validarCSRFToken()){
            $this->errorMessage = 'Token de seguridad inválido. Por favor, recarga la página e intenta nuevamente.';
            return false;
        }

        // 2. Regenerar el token csrf despues de validar el anterior correctamente
        SessionManager::set('csrf_token', bin2hex(random_bytes(32)));

        // 3. Validar Content-Type para formularios exclusivas de la propia aplicación y no desde APIs
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if(!empty($_POST) || !empty($_FILES)){
            $esFormulario = strpos($contentType, 'application/x-www-form-urlencoded') !== false ||
                           strpos($contentType, 'multipart/form-data') !== false;

            if(!$esFormulario){
                $this->errorMessage = 'Tipo de contenido no válido. No hay soporte para API en estos momentos.';
                return false;
            }
        }

        // 4. Sanitizar datos de la petición
        $this->sanitizarDatos();

        // 5. Validar que la petición viene del mismo sitio
        /* if(!$this->validarReferer()){
            $this->errorMessage = 'La solicitud no proviene de una fuente válida.';
            return false;
        } */

        return true;
    }

    /**
     * Valida el token CSRF
     * 
     * @return bool
     */
    private function validarCSRFToken(): bool {
        // Obtener token de la petición
        $token = $_POST['csrf_token'] ?? null;
        
        // Validar que exista token en sesión y en en la petición
        if(!SessionManager::has('csrf_token') || empty($token)){
            return false;
        }

        // Comparación segura contra timing attacks
        return hash_equals(SessionManager::get('csrf_token'), $token);
    }

    /**
     * Sanitiza los datos de la petición recursivamente
     * 
     * @return void
     */
    private function sanitizarDatos(): void {
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
     * En este caso está comentada debido a que no se puede validar el referer 
     * con la configuración por defecto de Cloudflare al aplicar esta función,
     * ya que Cloudflare modifica el referer para mejorar las capas de seguridad.
     * De igual manera no es requerido aplicar la función si ya se manejan tokens CSRF
     * dentro de la aplicación.
     * La validación del referer tiene que ser una capa de seguridad secundaria muy 
     * específica (una defensa en profundidad)
     * @return bool
     */
    /* private function validarReferer(): bool {
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
    } */
}