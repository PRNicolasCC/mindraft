<?php
declare(strict_types=1);

class Controller{

    public $view;

    public function __construct(){
        $this->view = new View();
    }

    public function loadModel($model): void {
        $url = 'models/'.$model.'Model.php';

        if(file_exists($url)){
            require $url;

            $modelName = $model.'Model';
            $this->model = new $modelName();
        }
    }
}

?>