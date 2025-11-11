<?php
declare(strict_types=1);

class PostManager {
    public bool $isValid;
    public string $message;

    function __construct(){
        if($this->validarPOST()) $this->isValid = true;
    }

    /**
     * Valida las solicitudes POST
     * Verifica CSRF token, Content-Type y tamaño del payload
     */
    private function validarPOST(): bool {
        // 1. Validar CSRF Token
        if(!$this->validarCSRFToken()){
            $this->message = 'Token de seguridad inválido. Por favor, recarga la página e intenta nuevamente.';
            return false;
        }

        // 2. Validar Content-Type para formularios exclusivas de la propia aplicación y no desde APIs
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if(!empty($_POST) || !empty($_FILES)){
            $esFormulario = strpos($contentType, 'application/x-www-form-urlencoded') !== false ||
                           strpos($contentType, 'multipart/form-data') !== false;

            if(!$esFormulario){
                $this->message = 'Tipo de contenido no válido. No hay soporte para API en estos momentos.';
                return false;
            }
        }

        //3. Sanitizar datos POST
        $this->sanitizarDatosPOST();

        // 4. Validar que la petición viene del mismo sitio
        if(!$this->validarReferer()){
            $this->message = 'La solicitud no proviene de una fuente válida.';
            return false;
        }

        return true;
    }

    /**
     * Gestiona y procesa la información enviada por POST
     */
    /* private function gestionarPOST(): bool { */
        // 1. Procesar archivos subidos
        /* if(!empty($_FILES)){
            $this->procesarArchivos();
        } */

        /* $userActions = [
            'create_user' => 'UserController',
            'activate_user' => 'UserController',
            'login' => 'UserController',
        ];

        $postActions = array_merge($userActions);

        foreach ($postActions as $postKey => $controller) {
            if (isset($_POST[$postKey])) {
                $this->controller = new $controller();
                $this->controller->checkCurrentModel('UserModel');
                return true;
            }

            return false;
        }
    } */

    /**
     * Valida el token CSRF
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
     * Procesa y valida archivos subidos
     */
    /* private function procesarArchivos(): void {
        foreach($_FILES as $campo => &$archivo){
            // Si es un array de archivos múltiples
            if(is_array($archivo['name'])){
                continue; // Los procesa el controlador específico
            }

            // Verificar si hubo error en la subida
            if($archivo['error'] !== UPLOAD_ERR_OK){
                $mensajesError = [
                    UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño máximo permitido',
                    UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo del formulario',
                    UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente',
                    UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo',
                    UPLOAD_ERR_NO_TMP_DIR => 'Falta carpeta temporal',
                    UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo',
                    UPLOAD_ERR_EXTENSION => 'Extensión de PHP detuvo la subida'
                ];

                $mensaje = $mensajesError[$archivo['error']] ?? 'Error desconocido al subir archivo';
                $this->cambiarError($mensaje);
            }

            // Validar tamaño máximo (5MB por defecto)
            $maxSize = 5 * 1024 * 1024; // 5MB
            if($archivo['size'] > $maxSize){
                $this->cambiarError('El archivo ' . htmlspecialchars($campo) . ' es demasiado grande (máx 5MB)');
            }

            // Validar que sea realmente un archivo subido
            if(!is_uploaded_file($archivo['tmp_name'])){
                $this->cambiarError('El archivo no es válido');
            }

            // Detectar tipo MIME real del archivo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeReal = finfo_file($finfo, $archivo['tmp_name']);
            finfo_close($finfo);

            $archivo['mime_real'] = $mimeReal;

            // Validar extensiones peligrosas
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $extensionesProhibidas = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phps', 'pht', 'phar', 'exe', 'sh', 'bat', 'cmd'];
            
            if(in_array($extension, $extensionesProhibidas)){
                $this->cambiarError('Tipo de archivo no permitido');
            }
        }
    } */
}

?>