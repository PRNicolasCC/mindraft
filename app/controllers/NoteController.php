<?php
declare(strict_types=1);

class NoteController extends Controller {
    function __construct(){
        parent::__construct('note');
        $this->setGetActions([
            'content'
        ]);
    }

    function render(): void{
        $this->isAuth();
        $this->redirect('/');
    }

    function content(array $data = ['0']): void{
        $this->isAuth();
        if(!ctype_digit($data[0])) $this->redirect('/'); #ctype_digit verifica que la cadena sea un número
        header('Content-Type: application/json');
        $id = (int) $data[0];
        $note = $this->model->obtenerPorCuaderno($id, SessionManager::get('user')['id']);
        $json = json_encode([]);
        if (!empty($note)) $json = json_encode($note);
        echo $json;
    }

    function store(array $data): void{
        $this->isAuth();
        $note = $this->model->crear($data['nombre'], $data['observacion'], intval($data['cuaderno_id']));
        if (!empty($note)) {
            $this->successRedirect(
                'Nota creada correctamente',                 
                [],
                '/'
            );
        } else {
            $this->cambiarError('Error al crear la nota. Por favor contacte al administrador');
        }
    }
}