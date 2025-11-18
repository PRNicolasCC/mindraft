<?php

class NotebookController extends Controller {
    function __construct(){
        parent::__construct('notebook');
    }

    function render(): void{
        $this->redirect('/');
    }

    function store(array $data): void{
        $datosUsuario = $this->model->crear($data['email'], $passHash, $data['username']);

        if (!empty($datosUsuario)) {
            EmailService::sendWelcomeEmail(
                $datosUsuario['email'], 
                $datosUsuario['token']
            );

            $this->successRedirect(
                'Usuario registrado correctamente. Se ha enviado un correo electrónico con un enlace para activar tu cuenta',                 
                [],
                '/'
            );
        } else {
            $this->cambiarError('Error al registrar el usuario. Por favor contacte al administrador');
        }
    }

    function show(string $id): void{
        $this->isAuth();
        $this->view->render('notebook/index');
    }

    function edit(string $id): void{
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
    }
}

?>