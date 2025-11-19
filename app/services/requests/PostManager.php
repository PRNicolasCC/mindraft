<?php
declare(strict_types=1);

require_once 'Manager.php';

class PostManager extends Manager {
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
        $userActions = [
            'create_user' => ['user', 'register'],
        ];
        $authActions = [
            'login' => ['auth', 'login'],
            'logout' => ['auth', 'logout'],
        ];
        $passwordActions = [
            'password_reset' => ['password', 'sendEmail'],
            'password_change' => ['password', 'change'],
        ];
        $notebookActions = [
            'create_notebook' => ['notebook', 'store'],
        ];
        $postActions = array_merge(
            $userActions, 
            $authActions, 
            $passwordActions, 
            $notebookActions
        );

        parent::__construct($postActions);
    }
}

?>