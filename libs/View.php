<?php
declare(strict_types=1);

class View{
    private array $icons;
    public array $message;
    public array $inputs;
    private string $redirect;

    function __construct(string $redirect = ''){
        $this->redirect = $redirect;
        $this->icons = [
            'error' => 'fa-exclamation-triangle',
            'success' => 'fa-check-circle', 
            'warning' => 'fa-exclamation-circle',
            'info' => 'fa-info-circle'
        ];

        $this->message = [
            'description' => '',
            'type' => ''
        ];

        $this->inputs = [];
    }

    function setRedirect(string $redirect): void{
        $this->redirect = $redirect;
    }

    function render(): void{
        require 'public/views/' . $this->redirect . '.php';
    }

    function successRedirect(string $mensaje, array $inputs = [], string $redirect = ''): void{
        $this->setMessageAndIcon($mensaje, 'success');
        $this->inputs = $inputs;
        if ($redirect !== '') $this->redirect = $redirect;
        $this->render();
    }

    /**
     * Cambia el error de validación general de la aplicación 
     */
    function cambiarError(string $mensaje, array $inputs = [], string $redirect = ''): void {
        // Guardar mensaje en sesión para mostrarlo después del redirect
        $this->setMessageAndIcon($mensaje, 'error');
        $this->inputs = $inputs;
        if ($redirect !== '') $this->redirect = $redirect;
        $this->render();
        exit();
    }

    function getDescriptionMessage(): ?string{
        if ($this->message['description'] !== ''){
            return '<div class="message ' . htmlspecialchars($this->message['type'] ?? 'info') . '">
                <i class="fa-solid ' . htmlspecialchars($this->getMessageIcon()) . '"></i>'
                . htmlspecialchars($this->message['description']) . '
            </div>';
        }
        return null;
    }


    private function setMessageAndIcon(string $description, string $type): void {
        $this->message['description'] = $description;
        $this->message['type'] = $type;
    }

    private function getMessageIcon(): string {
        return $this->icons[$this->message['type']] ?? $this->icons['info'];
    }

    /**
     * Redirecciona la URL a una vista específica
     * Si no se pasa vista, entonces redirecciona de donde venía (referer) o en su defecto se redirige al inicio
     */
    /* private function redirect(string $view = null): void {
        // Obtener la URL de donde venía (referer) o redirigir al inicio
        $referer = !$view ? ($_SERVER['HTTP_REFERER'] ?? 'index.php') : $view;
        
        // Redirigir de vuelta con el error
        header('Location: ' . $referer);
        exit;
    } */

    private function redirect(): void {
        header('Location: index.php');
        exit;
    }
}

?>