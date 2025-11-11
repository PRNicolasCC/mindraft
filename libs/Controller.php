<?php
declare(strict_types=1);

abstract class Controller{
    public $view;
    public $model;

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

    /**
     * Obtiene la información de un controlador específico
     * 
     * @param string $name El nombre del controlador en minúscula
     * @return array
    */
    public static function getInfoController(string $name): array{
        $name = ucfirst($name);
        return [
            'file' => 'app/controllers/' . $name . 'Controller.php',
            'controller' => $name . 'Controller',
        ];
    }
}

?>