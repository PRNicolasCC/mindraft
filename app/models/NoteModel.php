<?php
declare(strict_types=1);

class NoteModel extends Model {
    private string $table = 'notas';
    private string $tableNotebooks = 'cuadernos';
    private string $tableDetails = 'detalles';

    function __construct(){
        parent::__construct();
    }

    function obtenerPorCuaderno(int $cuadernoId, int $userId): ?array{
        $sql = "SELECT a.id, a.nombre, a.fecha FROM {$this->table} a 
                JOIN {$this->tableNotebooks} b ON a.cuaderno_id = b.id 
                WHERE a.cuaderno_id = :cuaderno_id AND b.usuario_id = :usuario_id
                ORDER BY a.fecha DESC";
        $parametros = [
            'cuaderno_id' => $cuadernoId,
            'usuario_id' => $userId
        ];
        $resultado = $this->db->fetchAll($sql, $parametros);
        return $resultado;
    }

    function obtenerPorId(int $id, int $cuadernoId, int $userId): ?array{
        $sql = "SELECT * FROM notas WHERE id = :id AND cuaderno_id = :cuaderno_id AND usuario_id = :usuario_id";
        $parametros = [
            'id' => $id,
            'cuaderno_id' => $cuadernoId,
            'usuario_id' => $userId
        ];
        $resultado = $this->db->fetchOne($sql, $parametros);
        return $resultado;
    }

    function crear(string $nombre, string $detalle, int $cuadernoId): ?array{
        $sql = "INSERT INTO {$this->table} (nombre, cuaderno_id) VALUES (:nombre, :cuaderno_id)";
        $parametros = [
            'nombre' => $nombre,
            'cuaderno_id' => $cuadernoId
        ];
        $insercion = $this->db->ejecutar($sql, $parametros);
        if ($insercion > 0) {
            $nuevoId = $this->db->ultimoIdInsertado();
            $this->crearDetalle($nuevoId, $detalle);
            return [
                'id' => $nuevoId,
                'nombre' => $nombre,
                'fecha' => date('Y-m-d H:i:s'),
            ];
        }
        return null;
    }

    private function crearDetalle(int $notaId, string $descripcion): void{
        $sql = "INSERT INTO {$this->tableDetails} (nota_id, descripcion) VALUES (:nota_id, :descripcion)";
        $parametros = [
            'nota_id' => $notaId,
            'descripcion' => $descripcion
        ];
        $this->db->ejecutar($sql, $parametros);
    }
}