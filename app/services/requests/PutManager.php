<?php
declare(strict_types=1);

require_once 'Manager.php';

class PutManager extends Manager {
    function __construct(){
        $notebookActions = [
            'edit_notebook' => ['notebook', 'update'],
        ];
        $passwordActions = [
            'password_change' => ['password', 'change'],
        ];
        $noteActions = [
            'edit_note' => ['note', 'update'],
        ];
        $putActions = array_merge(
            $notebookActions,
            $passwordActions,
            $noteActions
        );

        parent::__construct($putActions);
    }
}

?>