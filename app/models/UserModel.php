<?php
declare(strict_types=1); # volvemos a definir el strict types ya que únicamente se aplica al archivo donde está definida.

class UserModel extends Model {
    private string $table = 'usuarios';

    /* MÉTODOS PÚBLICOS */

    /** 
     * Constructor de la clase UserModel.
     **/
    function __construct() {
        parent::__construct();
    }

    /** 
     * Obtiene el usuario por su email.
     * @param string $email El email del usuario a buscar.
     **/
    public function obtenerPorEmail(string $email): ?array {
        $sql = "SELECT id, email, contraseña FROM {$this->table} WHERE email = :email";

        return $this->db->fetchOne($sql, ['email' => $email]);
    }

    public function crear(string $email, string $password_hash, string $nombre): ?array {
        $sql = "INSERT INTO {$this->table} (email, contraseña, nombre) VALUES (:email, :pass, :nombre)";
        
        $parametros = [
            'email' => $email,
            'pass'  => $password_hash,
            'nombre' => $nombre
        ];

        $insercion = $this->db->ejecutar($sql, $parametros);
        if ($insercion) {
            $token = bin2hex(random_bytes(16));
           $nuevoId = $this->db->ultimoIdInsertado();

            return [
                'id' => $nuevoId,
                'email' => $email,
                'nombre' => $nombre,
                'token' => $token
            ];
        }
        
    }
    
    /* public function obtenerPorId(int $id): ?array {
        // ... (Implementación similar a obtenerPorEmail) ...
    } */
    
}

?>