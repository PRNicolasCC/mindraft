<?php
declare(strict_types=1); # strict types es recomendado cuando se maneja POO

class DBConnection {
    private string $host;
    private string $port;
    private string $db;
    private string $user;
    private string $password;
    private string $charset;

    private ?PDO $conn = null;

    public function __construct(
        ?string $host = null,
        ?string $port = null, 
        ?string $db = null,
        ?string $user = null,
        ?string $password = null,
        ?string $charset = null
    ) {
        $this->host = $host ?? $_ENV['DB_HOST'];
        $this->port = $port ?? $_ENV['DB_PORT'];
        $this->db = $db ?? $_ENV['DB_NAME'];
        $this->user = $user ?? $_ENV['DB_USER'];
        $this->password = $password ?? '';
        $this->charset = $charset ?? $_ENV['DB_CHARSET'];
    }

    // Patrón Lazy Loading: evita conexiones innecesarias si la clase se instancia pero no se usa.
    public function getConnection(): PDO {
        if ($this->conn === null) {
            $this->conn = $this->connect();
        }
        return $this->conn;
    }

    private function connect(): PDO {
        if (empty($this->db) || empty($this->user)) {
            throw new InvalidArgumentException('El nombre y usuario de la base de datos es obligatorio.');
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $this->host,
            $this->port,
            $this->db,
            $this->charset
        );

        $options = [
            // hace que PDO lance excepciones (Exception) cuando ocurre un error.:
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Facilita la detección y el manejo de errores mediante bloques try/catch.

            // devuelve los resultados como un array asociativo:
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // Controla si PDO emula las sentencias preparadas. el valor false indica que se deben usar sentencias preparadas reales del motor de base de datos.
            PDO::ATTR_EMULATE_PREPARES => false, // Mejora la seguridad contra inyecciones SQL y puede mejorar el rendimiento

            // Indica si se debe usar una conexión persistente. El valor false significa que no se reutiliza la conexión entre peticiones.
            PDO::ATTR_PERSISTENT => false, // Evita problemas de estado compartido entre conexiones, aunque puede tener un pequeño impacto en el rendimiento al estar abriendo y cerrando las conexiones.

            // Establece el tiempo máximo (en segundos) que PDO esperará para establecer una conexión.
            PDO::ATTR_TIMEOUT => 30 // Previene que el script se quede colgado indefinidamente si la base de datos no responde.
        ];

        try {
            return new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            error_log("Conexión a la base de datos fallida: " . $e->getMessage());
            throw new RuntimeException('No se ha podido establecer conexión a la base de datos.', 0, $e);
        }
    }

    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetchOne(string $sql, array $params = []): ?array {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;  # El operador (?:) es llamado Operador Ternario Conciso y devuelve null si $result es false
    }

    public function ejecutar(string $sql, array $params = []): int {
        # El resultado de rowCount puede ser cero para INSERT (no es el caso para el presente proyecto) debido a diferentes razones:
            # Comportamiento específico del driver MySQL: Algunos drivers de MySQL retornan 0 para INSERT cuando no hay filas "afectadas" en el sentido tradicional (como en UPDATE o DELETE).
            # Configuración de PDO: Con PDO::ATTR_EMULATE_PREPARES => false, el comportamiento de rowCount() puede ser inconsistente para INSERT.
            # Versión de MySQL/MariaDB: Diferentes versiones pueden tener comportamientos distintos.
        return $this->query($sql, $params)->rowCount();
    }

    public function ultimoIdInsertado(): string {
        return $this->getConnection()->lastInsertId();
    }

    public function beginTransaction(): bool {
        return $this->getConnection()->beginTransaction();
    }

    public function commit(): bool {
        return $this->getConnection()->commit();
    }

    public function rollback(): bool {
        return $this->getConnection()->rollback();
    }

    // Limpia la conexión a la base de datos: Cierra apropiadamente la conexión PDO, Libera recursos de memoria, Evita conexiones "zombi" que consuman recursos del servidor
    // Es decir que al terminar de realizar cualquier funcion o funciones, __destruct cierra automáticamente la conexión a la base de datos.
    public function __destruct() {
        $this->conn = null;
    }

}

?>