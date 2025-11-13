<?php
declare(strict_types=1);

class View{
    private array $icons;

    function __construct(){
        $this->icons = [
            'error' => 'fa-exclamation-triangle',
            'success' => 'fa-check-circle', 
            'warning' => 'fa-exclamation-circle',
            'info' => 'fa-info-circle'
        ];
    }

    function render(string $file): void{
        require 'public/views/' . $file . '.php';
    }

    function getDescriptionMessage(): ?string{
        if (SessionManager::has('redirectMessage')){
            $return = '<div class="message ' . htmlspecialchars(SessionManager::get('redirectMessage')['type'] ?? 'info') . '">
                <i class="fa-solid ' . htmlspecialchars($this->getMessageIcon()) . '"></i>'
                . htmlspecialchars(SessionManager::get('redirectMessage')['description']) . '
            </div>';
            $this->removeMessageAndInputs();
            return $return;
        }
        return null;
    }

    function setMessageAndInputs(string $description, string $type, array $inputs): void {
        if (!empty($description) && !empty($type)) {
            SessionManager::set('redirectMessage', [
                'description' => $description,
                'type' => $type
            ]);
        }
        if (!empty($inputs)) SessionManager::set('redirectInputs', $inputs);
    }

    private function removeMessageAndInputs(): void {
        SessionManager::remove('redirectMessage');
        SessionManager::remove('redirectInputs');
    }

    private function getMessageIcon(): string {
        return $this->icons[SessionManager::has('redirectMessage') ? SessionManager::get('redirectMessage')['type'] : 'info'];
    }
}

?>