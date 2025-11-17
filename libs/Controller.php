<?php
declare(strict_types=1);

abstract class Controller{
    protected View $view;
    protected Model $model;
    private array $getActions;
    private array $tokenMethods;

    /**
     * Constructor de la clase Controller.
     * 
     * Inicializa la vista y el modelo
     * 
     * @param string $model El nombre del modelo en minúscula
     * @return void
    */
    function __construct(string $model = ''){
        $this->view = new View();
        $this->loadModel($model);
        $this->setGetActions([]);
        $this->setTokenMethods([]);
    }

    /**
     * Carga el modelo correspondiente
     * 
     * @param string $model El nombre del modelo en minúscula
     * @return void
    */
    function loadModel(string $model): void {
        $model = ucfirst($model);
        $url = 'app/models/'.$model.'Model.php';

        if(file_exists($url)){
            require $url;
            $modelName = $model.'Model';
            $this->model = new $modelName();
        }
    }

    protected function setGetActions(array $actions): void {
        $this->getActions = $actions;
    }

    function getActions(): array {
        return $this->getActions;
    }

    protected function setTokenMethods(array $methods): void {
        $this->tokenMethods = $methods;
    }

    function getTokenMethods(): array {
        return $this->tokenMethods;
    }

    /**
     * Obtiene la información de un controlador específico
     * 
     * @param string $name El nombre del controlador en minúscula
     * @return array
    */
    static function getInfoController(string $name): array{
        $name = ucfirst($name);
        return [
            'file' => 'app/controllers/' . $name . 'Controller.php',
            'controller' => $name . 'Controller',
        ];
    }

    function successRedirect(string $mensaje, array $inputs = [], string $redirect = ''): void{
        $this->redirect($redirect, $mensaje, 'success', $inputs);
    }

    function warningRedirect(string $mensaje, array $inputs = [], string $redirect = ''): void{
        $this->redirect($redirect, $mensaje, 'warning', $inputs);
    }

    function infoRedirect(string $mensaje, array $inputs = [], string $redirect = ''): void{
        $this->redirect($redirect, $mensaje, 'info', $inputs);
    }

    /**
     * Cambia el error de validación general de la aplicación 
     */
    function cambiarError(string $mensaje, array $inputs = [], string $redirect = ''): void {
        $this->redirect($redirect, $mensaje, 'error', $inputs);
    }

    /**
     * Redirecciona la URL a una vista específica
     * Si no se pasa la url, entonces redirecciona de donde venía (referer) 
     * o en su defecto se redirige al inicio
     * 
     * @param string $url La URL a la que se redirigirá
     * @param string $description La descripción del mensaje
     * @param string $type El tipo del mensaje
     * @param array $inputs Los inputs del formulario
     * @return void
     */
    protected function redirect(string $route = '', string $description = '', string $type = '', array $inputs = []): void {
        $referer = $route !== '' ? ($_ENV['DOMAIN'] . $route) : ($_SERVER['HTTP_REFERER'] ?? $_ENV['DOMAIN']);
        $this->view->setMessageAndInputs($description, $type, $inputs);
        header('Location: ' . $referer);
        exit;
    }

    protected function isAuth(): void {
        if (!SessionManager::isAuthenticated()) {
            $this->redirect('/auth');
        }
    }

    protected function isNotAuth(): void {
        if (SessionManager::isAuthenticated()) {
            $this->redirect('/');
        }
    }
}
?>