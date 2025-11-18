<?php
declare(strict_types=1);

class NotebookController extends Controller {
    function __construct(){
        parent::__construct('notebook');
    }

    function render(): void{
        $this->isAuth();
        $notebooks = $this->model->obtenerPorUsuario(SessionManager::get('user')['id']);
        $this->view->render('notebook/index', $notebooks);
    }

    function store(array $data): void{
        $this->isAuth();
        $notebook = $this->model->crear($data['nombre'], $data['descripcion'], $data['color'], SessionManager::get('user')['id']);
        if (!empty($notebook)) {
            $this->successRedirect(
                'Cuaderno creado correctamente',                 
                [],
                '/'
            );
        } else {
            $this->cambiarError('Error al crear el cuaderno. Por favor contacte al administrador');
        }
    }

    /* function edit(string $id): void{
        $this->isAuth();
        $this->view->render('notebook/index');
    }

    function update(array $data): void{
        $this->isAuth();
        $this->view->render('notebook/index');
    }

    function destroy(string $id): void{
        $this->isAuth();
        $this->view->render('notebook/index');
    } */
}

?>