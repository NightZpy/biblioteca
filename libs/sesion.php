<?php

class Sesion {

    private $valor;
    private $id;

    function __construct($id, $valor = NULL) {
        $this->id       = $id;
        if($this->existe($id))
            $this->valor = $_SESSION[$id];
        else
            $_SESSION[$id] = $valor;
        return $this->valor;
    }


    public function cargar($id) {
        $this->id       = $id;
        if($this->existe($id))
            $this->valor = $_SESSION[$id];
        else
            $this->valor = NULL;
        return $this->valor;
    }

    public static function eliminar($id) {
        if(Sesion::existe($id))
        {
            $_SESSION[$id] = NULL;
            unset ( $_SESSION[$id]);
        }
    }

    public static function existe($id)
    {
        return isset ($_SESSION[$id]);
    }

    public static function getValor($id) {
        return Sesion::existe($id) ? $_SESSION[$id] : NULL;
    }

    public static function setValor($id, $valor) {
        $_SESSION[$id] = $valor;
    }

    public static function iniciarSesion() {
        return session_start();
    }

    public function destruirSesion() {
        return session_destroy();
    }
}
?>
