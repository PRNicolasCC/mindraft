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

    function render(string $file, ?array $data = null): void{
        $render = BASE_PATH . 'public/views/' . $file . '.php';
        require BASE_PATH . 'public/views/' . $file . '.php';
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

    function modal(string $id, string $content): string{
        return '<div class="modal fade" id="'.htmlspecialchars($id).'" tabindex="-1" aria-labelledby="'.htmlspecialchars($id).'Label" aria-hidden="true">
                <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                        '.$content.'
                </div>
                </div>
                </div>
            </div>';
    }

    function formModal(string $id, string $title, string $formContent, string $formId, string $modalWidth = ''): string{
        return '<div class="modal fade" id="'.htmlspecialchars($id).'" tabindex="-1" aria-labelledby="'.htmlspecialchars($id).'Label" aria-hidden="true">
                <div class="modal-dialog '.htmlspecialchars($modalWidth).'">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="'.htmlspecialchars($id).'Label">'.htmlspecialchars($title).'</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <form method="post" action="" id="'.htmlspecialchars($formId).'">
                    <input type="hidden" name="csrf_token" value="'.htmlspecialchars(SessionManager::get('csrf_token')).'">
                        '.$formContent.'
                    </form>
                </div>
                </div>
                </div>
            </div>';
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