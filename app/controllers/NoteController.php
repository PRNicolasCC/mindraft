<?php
declare(strict_types=1);

class NoteController extends Controller {
    function __construct(){
        parent::__construct('note');
        $this->setGetActions([
            'content',
            'detail'
        ]);
    }

    function render(): void{
        $this->isAuth();
        $this->redirect('/');
    }

    function content(array $data = ['0']): void{
        $this->isAuth();
        if(!ctype_digit($data[0])) $this->redirect('/'); #ctype_digit verifica que la cadena sea un número
        header('Content-Type: application/json');
        $id = (int) $data[0];
        $note = $this->model->obtenerPorCuaderno($id, SessionManager::get('user')['id']);
        $json = json_encode([]);
        if (!empty($note)) $json = json_encode($note);
        echo $json;
    }

    function detail(array $data = ['0', '0']): void{
        $this->isAuth();
        if(!ctype_digit($data[0]) || !ctype_digit($data[1])) $this->redirect('/');
        header('Content-Type: application/json');
        $cuadernoId = (int) $data[0];
        $id = (int) $data[1];
        $note = $this->model->obtenerDescripcion($id, $cuadernoId);
        $json = json_encode([]);
        if (!empty($note)) $json = json_encode($note);
        echo $json;
    }

    function store(array $data): void{
        $this->isAuth();
        $data['observacion'] = $this->eliminarEtiquetas($data['observacion']);
        $note = $this->model->crear($data['nombre'], $data['observacion'], intval($data['cuaderno_id']));
        if (!empty($note)) {
            $this->successRedirect(
                'Nota creada correctamente',                 
                [],
                '/'
            );
        } else {
            $this->cambiarError('Error al crear la nota. Por favor contacte al administrador');
        }
    }

    function update(array $data): void{
        $this->isAuth();
        $data['observacion'] = $this->eliminarEtiquetas($data['observacion']);
        $this->model->actualizar(
            intval($data['id']), 
            $data['nombre'], 
            $data['observacion'], 
            intval($data['cuaderno_id'])
        );
        $this->successRedirect(
            'Nota actualizada correctamente',                 
            [],
            '/'
        );
    }

    function destroy(array $data): void{
        $this->isAuth();
        $this->model->eliminar(intval($data['id']), intval($data['cuaderno_id']));
        $this->successRedirect(
            'Nota eliminada correctamente',                 
            [],
            '/'
        );
    }

    private function eliminarEtiquetas(string $html): string{
        $eliminarClases = [
            ['div', 'ql-editor'],
            ['div', 'ql-tooltip'],
            ['a', 'ql-preview'],
            ['a', 'ql-action'],
            ['a', 'ql-remove'],
            ['input']
        ];
        foreach ($eliminarClases as $etiqueta) {
            // La parte `[^>]*` permite capturar otros atributos ANTES de la clase.
            // El patrón `(.*?)*` dentro de `[^>]*` asegura que la clase se encuentra.
            switch (strtolower($etiqueta[0])) {
                case 'div': // La etiqueta 'div' no necesita una acción separada porque comparte la misma estructura de patrón de expresión regular que la etiqueta 'a'.
                    if($etiqueta[1] == 'ql-editor'){
                        $patron_apertura = '/<div[^>]*>/i';
    
                        // Reemplazamos todas las etiquetas de apertura con una cadena vacía.
                        $html = preg_replace($patron_apertura, '', $html);
                        
                        // 2. Eliminar etiquetas de cierre de </div>
                        // Patrón: /<\/div>/i
                        // - <\/div>: Coincide con "</div>" (el "/" debe ser escapado)
                        $patron_cierre = '/<\/div>/i';
                        
                        // Reemplazamos todas las etiquetas de cierre con una cadena vacía.
                        $html = preg_replace($patron_cierre, '', $html);
                        break; // si la clase es 'ql-editor', si ejecuta una acción específica, pero en caso de que no, entonces salta al siguiente caso.
                    }
                case 'a':
                    // Patrón para etiquetas con contenido (apertura + contenido + cierre)
                    // Buscamos la apertura de la etiqueta que contenga la clase, cualquier contenido (.*?), y el cierre de la etiqueta.
                    // La parte `.*?class=["'][^"']*?` ayuda a asegurar que la clase esté presente.
                    $patron = '/<' . $etiqueta[0] . '[^>]*class=["\'][^"\']*?' . preg_quote($etiqueta[1]) . '[^"\']*?["\'][^>]*>(.*?)<\/' . $etiqueta[0] . '>/si';
                    $html = preg_replace($patron, '', $html);
                    break;
                case 'input':
                    // Patrón para etiquetas de cierre automático (solo la etiqueta de apertura)
                    // Un input no tiene etiqueta de cierre, por lo que buscamos solo la apertura que contenga la clase.
                    $patron = '/<input.*?' . 
                        'type="text".*?' . 
                        'data-formula="e=mc\^2".*?' . 
                        'data-link="https:\/\/quilljs\.com".*?' . 
                        'data-video="Embed URL".*?' . 
                        '\s*\/?>/si';
                    $html = preg_replace($patron, '', $html);
                    break;
                    
                default:
                    // Si se incluye una etiqueta no manejada, la ignoramos.
                    continue 2; # El número despues del continue indica el nivel de salto. En este caso se salta el bloque switch y el bucle foreach.
                    # No se deja continue con 1, ya que este es el equivalente a un break.
                    #continue;
            }
        }
        return $html;
    }
}