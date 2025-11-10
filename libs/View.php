<?php
declare(strict_types=1);

class View{
    private array $icons;
    public array $message;
    public array $inputs;

    /* MÉTODOS PÚBLICOS */
    function __construct(){
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

    public function render(string $nombre): void{
        require 'public/views/' . $nombre . '.php';
    }

    public function successRedirect(string $mensaje, string $redirect): void{
        $this->setMessageAndIcon($mensaje, 'success');
        #$this->redirect();
        $this->render($redirect);
    }

    /**
     * Cambia el error de validación general de la aplicación 
     */
    public function cambiarError(string $mensaje): void {
        // Guardar mensaje en sesión para mostrarlo después del redirect
        $this->setMessageAndIcon($mensaje, 'error');
        #$this->render('user/register');
    }

    public function getDescriptionMessage(): ?string{
        if ($this->message['description'] !== ''){
            return '<div class="message ' . htmlspecialchars($this->message['type'] ?? 'info') . '">
                <i class="fa-solid ' . htmlspecialchars($this->getMessageIcon()) . '"></i>'
                . htmlspecialchars($this->message['description']) . '
            </div>';
        }
        return null;
    }


    /* MÉTODOS PRIVADOS */
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