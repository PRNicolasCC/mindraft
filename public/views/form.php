<?php
    $children = '
    <div class="form-container">
        <div class="form-card">
            <h1 class="form-title">' . $propsAuth['title'] . '</h1>
            <p class="form-subtitle">' . $propsAuth['subtitle'] . '</p>
            ' . $childrenAuth . '
        </div>
    </div>
    ';

    require_once 'public/views/index.php';
?>
