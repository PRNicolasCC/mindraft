<?php
declare(strict_types=1);

class Controller{
    public $view;
    public $model;

    public function __construct(string $model = ''){
        $this->view = new View();
        if($model !== '') $this->loadModel($model);
    }

    public function loadModel(string $model): void {
        $model = ucfirst($model);
        $url = 'app/models/'.$model.'Model.php';

        if(file_exists($url)){
            require $url;
            $modelName = $model.'Model';
            $this->model = new $modelName();
        }
    }

    public function checkCurrentModel(string $model): void{
        $clase = get_class($this->model);
        if ($clase !== $model) {
            $this->view->cambiarError('El modelo no coincide con el controlador');
        }
    }

    /**
     * Obtener un controlador específico
     * @param string $name El nombre del controlador en minúscula
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