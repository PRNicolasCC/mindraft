<?php
    $children = '
    <div class="form-container">
        <div class="form-card">
                <h1 class="form-title">' . htmlspecialchars($propsAuth['title']) . '</h1>
                <p class="form-subtitle">' . htmlspecialchars($propsAuth['subtitle']) . '</p>

                <!-- Formulario -->
                <form action="" method="post" id="sendForm">
                    ' . $childrenAuth . '
                    <button type="submit" class="btn btn-register" id="sendButton">
                        <span class="btn-text">'.htmlspecialchars($propsAuth['sendButton']).'</span>
                        <div class="spinner" id="sendSpinner"></div>
                    </button>';

    $children .= $this->getDescriptionMessage();
    $children .= $aditionalAuth ?? '';
    $children .= '</form>
            </div>
        </div>
        <script src="public/js/index.js"></script>
        ' . $scriptsAuth;

    require_once 'public/views/index.php';
?>
