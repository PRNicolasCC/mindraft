<?php
declare(strict_types=1);

require_once 'Manager.php';

class DeleteManager extends Manager {
    function __construct(){
        $notebookActions = [
            'delete_notebook' => ['notebook', 'destroy'],
        ];
        $deleteActions = array_merge(
            $notebookActions,
        );

        parent::__construct($deleteActions);
    }
}

?>