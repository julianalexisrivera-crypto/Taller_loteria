<?php

class Model {
    private $db;

    public function __construct() {
        // Leer variables de entorno del docker-compose.yml
        // Usar valores por defecto si están en desarrollo local
        $host = getenv('DB_HOST') ?: 'localhost';
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: 'secret';  // Ojo: DB_PASS, no DB_PASSWORD
        $database = getenv('DB_NAME') ?: 'juego';

        // Crear conexión mysqli
        $this->db = new mysqli($host, $user, $password, $database);

        // Verificar conexión
        if ($this->db->connect_error) {
            die("❌ Error de conexión a la BD: " . htmlspecialchars($this->db->connect_error));
        }

        // Configurar charset UTF-8
        $this->db->set_charset("utf8mb4");
    }

    /**
     * Obtener la conexión a la base de datos
     * @return mysqli
     */
    public function getConnection() {
        return $this->db;
    }

    /**
     * Obtener todos los puntajes guardados
     * @return array
     */
    public function getPuntajes() {
        $query = "SELECT * FROM puntajes ORDER BY puntaje DESC LIMIT 10";
        $result = $this->db->query($query);
        
        if (!$result) {
            die("❌ Error en la consulta: " . htmlspecialchars($this->db->error));
        }

        $puntajes = [];
        while ($row = $result->fetch_assoc()) {
            $puntajes[] = $row;
        }
        return $puntajes;
    }

    /**
     * Guardar un nuevo puntaje
     * @param int $puntaje
     * @param string $fecha
     * @return bool
     */
    public function guardarPuntaje($puntaje, $fecha = null) {
        if ($fecha === null) {
            $fecha = date('Y-m-d H:i:s');
        }

        $puntaje = intval($puntaje);
        $fecha = $this->db->real_escape_string($fecha);

        $query = "INSERT INTO puntajes (puntaje, fecha) VALUES ($puntaje, '$fecha')";
        
        if (!$this->db->query($query)) {
            die("❌ Error al guardar puntaje: " . htmlspecialchars($this->db->error));
        }

        return true;
    }

    /**
     * Cerrar conexión
     */
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}

?>


