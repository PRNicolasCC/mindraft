<?php
    $children = '

    <div id="main" class="error-view">
        <h1 class="center error fw-bold">'.htmlspecialchars($this->message['description']).'</h1>
    </div>
    
    ';

    require_once 'public/views/index.php';
?>
