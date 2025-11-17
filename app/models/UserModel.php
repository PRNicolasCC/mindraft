<?php
declare(strict_types=1); # volvemos a definir el strict types ya que únicamente se aplica al archivo donde está definida.

class UserModel extends Model {
    protected string $table = 'usuarios';
    protected string $tableTokens = 'tokens';

    /** 
     * Constructor de la clase UserModel.
     **/
    function __construct() {
        parent::__construct();
    }

    function crear(string $email, string $password_hash, string $nombre): ?array {
        $sql = "INSERT INTO {$this->table} (email, contraseña, nombre) VALUES (:email, :pass, :nombre)";       
        $parametros = [
            'email' => $email,
            'pass'  => $password_hash,
            'nombre' => $nombre
        ];

        $insercion = $this->db->ejecutar($sql, $parametros);
        if ($insercion > 0) {
            $nuevoId = $this->db->ultimoIdInsertado();
            $tokenInsert = $this->almacenarToken($nuevoId);
            return [
                'id' => $nuevoId,
                'email' => $email,
                'nombre' => $nombre,
                'token' => $tokenInsert
            ];
        }
        return null;
    }

    function activar(string $email, string $token): bool | string {
        if ($this->isUsedToken($email, $token)) return 'La cuenta ya ha sido activada. Por favor, inicia sesión.';
        if($this->isExpiredToken($email, $token)) return 'El token de activación ha expirado. Han pasado 24 horas desde que se creó la cuenta. Por favor, contacta al administrador.';

        $sql = "UPDATE {$this->table} a
                JOIN {$this->tableTokens} b ON a.id = b.usuario_id 
                SET a.is_active = :is_active, b.used = :used, b.used_at = :used_at, b.ip_address = :ip_address
                WHERE a.email = :email AND b.token = :token AND tipo = :tipo";

        $actualizacion = $this->db->ejecutar($sql, [
            'email' => $email, 
            'token' => $token,
            'is_active' => 1,
            'used' => 1,
            'used_at' => $this->dateTime->format('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'tipo' => 'A'
        ]);
        if ($actualizacion > 0) {
            return true;
        }
        return false;
    }

    /** 
     * Obtiene el usuario por su email.
     * @param string $email El email del usuario a buscar.
     **/
    function obtenerPorEmail(string $email): ?array {
        $sql = "SELECT id, email, nombre, contraseña FROM {$this->table} WHERE email = :email";
        return $this->db->fetchOne($sql, ['email' => $email]);
    }

    function verificarUsuarioActivo(string $email): bool {
        $sql = "SELECT id, email FROM {$this->table} WHERE email = :email AND is_active = :is_active";
        return !empty($this->db->fetchOne($sql, ['email' => $email, 'is_active' => 1]));
    }

    /**
     * Almacena un token en la base de datos asociado a un usuario.
     * 
     * Este método guarda un token en texto plano asociado al usuario y al tipo de token.
     * 
     * @param int $usuarioId ID del usuario al que se asocia el token
     * @param string $tipo Tipo de token. Por el momento solo están disponibles 'A' (activación) y 'P' (password recuperación)
     * @param int $expirationHours Cantidad de horas en las que expira el token
     * @return string Retorna el token generado
     */
    function almacenarToken(int $usuarioId, string $tipo='A', int $expirationHours=24): string {
        $tiposPermitidos = ['A', 'P'];
        if (!in_array($tipo, $tiposPermitidos)) {
            throw new Exception("Tipo de token no válido. Tipos permitidos: " . implode(', ', $tiposPermitidos));
        }

        $token = bin2hex(random_bytes(16));  // Genera un token de 16 bytes (32 caracteres hexadecimales)
        // $fecha_manana = $this->dateTime->modify('+' . $expirationHours . ' hours'); // No se puede modificar el objeto DateTime original
        $fecha_manana = clone $this->dateTime;
        $fecha_manana->modify('+' . $expirationHours . ' hours');
        $sqlFormattedTomorrow = $fecha_manana->format('Y-m-d H:i:s');

        $sql = "INSERT INTO {$this->tableTokens} (usuario_id, token, expires_at, tipo) VALUES (:usuario_id, :token, :expires_at, :tipo)";
        $parametros = [
            'usuario_id' => $usuarioId,
            'token' => $token,
            'expires_at' => $sqlFormattedTomorrow,
            'tipo' => $tipo
        ];
        $this->db->ejecutar($sql, $parametros);
        return $token;
    }

    function isUsedToken(string $email, string $token, string $tipo = 'A'): bool {
        $sql = "SELECT * FROM {$this->table} a
                JOIN {$this->tableTokens} b ON a.id = b.usuario_id 
                WHERE b.token = :token AND a.email = :email AND b.used = :used 
                AND b.tipo = :tipo";
        $parametros = [
            'token' => $token,
            'email' => $email,
            'used' => 1,
            'tipo' => $tipo,
        ];
        return !empty($this->db->fetchOne($sql, $parametros));
    }

    protected function isExpiredToken(string $email, string $token, string $tipo = 'A'): bool {
        $sql = "SELECT * FROM {$this->table} a
                JOIN {$this->tableTokens} b ON a.id = b.usuario_id 
                WHERE b.token = :token AND a.email = :email AND b.tipo = :tipo
                AND b.expires_at < :expires_at";
        $parametros = [
            'token' => $token,
            'email' => $email,
            'tipo' => $tipo,
            'expires_at' => $this->dateTime->format('Y-m-d H:i:s')
        ];
        return !empty($this->db->fetchOne($sql, $parametros));
    }

    function restablecerPassword(string $email, string $token, string $password): bool | string {
        if ($this->isUsedToken($email, $token, 'P')) return 'El token de recuperación ya ha sido utilizado.';
        if($this->isExpiredToken($email, $token, 'P')) return 'El token de recuperación ha expirado. Han pasado 2 horas desde que se solicitó el correo de recuperación. Por favor, solicita un nuevo correo de recuperación.';

        $sql = "UPDATE {$this->table} a
                JOIN {$this->tableTokens} b ON a.id = b.usuario_id 
                SET a.contraseña = :new_password, b.used = :used, b.used_at = :used_at, b.ip_address = :ip_address
                WHERE a.email = :email AND b.token = :token AND b.tipo = :tipo
                AND a.is_active = :is_active";

        $actualizacion = $this->db->ejecutar($sql, [
            'email' => $email, 
            'token' => $token,
            'new_password' => $password,
            'used' => 1,
            'used_at' => $this->dateTime->format('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'tipo' => 'P',
            'is_active' => 1,
        ]);
        if ($actualizacion > 0) {
            return true;
        }
        return false;
    }
    
}

?>