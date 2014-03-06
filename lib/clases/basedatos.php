<?php
class Basedatos
{

    /**
     * @var Basedatos Contiene la instancia de Basedatos.
     */
    private static $_instancia;

    /**
     *
     * @var boolean|mysqli Contiene el objeto mysqli después de que se haya
     * establecido la conexión.
     */
    private static $_mysqli = false;

    //----------------------------------------------------------------------------------------------------
    /**
     * Crea la conexión al servidor o devuelve error parando la ejecución.
     *
     * @return Basedatos Devuelve la referencia al objeto Basedatos.
     */
    public static function getInstancia()
    {
        if (!self::$_instancia instanceof self)
        {
            // Creamos una nueva instancia de basedatos.
            self::$_instancia = new self;
        }

        // Si la instancia ya estaba creada, la devolvemos.
        return self::$_instancia;
    }

    private function __construct()
    {
        // Creamos el objeto mysqli y lo asignamos a $_mysqli
        self::$_mysqli = @new mysqli(BD_SERVIDOR, BD_USUARIO, BD_PASSWORD, BD_NOMBRE);
        if (self::$_mysqli->connect_error)
        {
            echo "Error conectando Base Datos: " . self::$_mysqli->connect_error;
            self::$_mysqli = false;
            die();
        }
    }

    //----------------------------------------------------------------------------------------------------
    /**
     * Función close()
     * Cierra una conexión activa con el servidor
     *
     * @access public
     * @return boolean Siempre devolverá true.
     */
    public function close()
    {
        if (self::$_mysqli)
        {
            self::$_mysqli->close();
            self::$_mysqli = false;
        }
        return true;
    }

}
?>
