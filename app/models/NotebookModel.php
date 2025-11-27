<?php

class NotebookModel extends Model {
    private string $table = 'cuadernos';

    /** 
     * Constructor de la clase NotebookModel.
     **/
    function __construct() {
        parent::__construct();
    }

    function obtenerPorUsuario(int $userId): ?array{
        $sql = "SELECT * FROM {$this->table} WHERE usuario_id = :usuario_id AND estado_id = :estado_id";
        return $this->db->fetchAll($sql, ['usuario_id' => $userId, 'estado_id' => 'A']);
    }

    function obtenerPorId(int $id, int $userId): ?array{
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND usuario_id = :usuario_id";
        return $this->db->fetchOne($sql, ['id' => $id, 'usuario_id' => $userId]);
    }

    function crear(string $nombre, string $descripcion, string $color, int $userId): ?array{
        $sql = "INSERT INTO {$this->table} (nombre, descripcion, color, usuario_id) VALUES (:nombre, :descripcion, :color, :usuario_id)";
        $parametros = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'color' => $color,
            'usuario_id' => $userId
        ];
        $insercion = $this->db->ejecutar($sql, $parametros);
        if ($insercion > 0) {
            $nuevoId = $this->db->ultimoIdInsertado();
            return [
                'id' => $nuevoId,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'color' => $color,
                'usuario_id' => $userId
            ];
        }
        return null;
    }

    function actualizar(int $id, string $nombre, string $descripcion, string $color, int $userId): void{
        /* $estadosPermitidos = ['A', 'I'];
        if (!in_array($estadoId, $estadosPermitidos)) {
            throw new Exception("Estado no válido. Estados permitidos: " . implode(', ', $estadosPermitidos));
        } */

        $sql = "UPDATE {$this->table} SET nombre = :nombre, descripcion = :descripcion, color = :color WHERE id = :id AND usuario_id = :usuario_id";
        $parametros = [
            'id' => $id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'color' => $color,
            'usuario_id' => $userId
        ];
        $this->db->ejecutar($sql, $parametros);
    }

    function eliminar(int $id, int $userId): bool{
        $sql = "DELETE FROM {$this->table} WHERE id = :id AND usuario_id = :usuario_id";
        $parametros = [
            'id' => $id,
            'usuario_id' => $userId
        ];
        return $this->db->ejecutar($sql, $parametros) > 0;
    }
}
?>
