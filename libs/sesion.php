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

    public function eliminar($id) {
        if($this->existe($id))
        {
            $this->valor = $_SESSION[$id] = NULL;
            unset ( $_SESSION[$id]);
        }
        return $this->valor;
    }

    public static function existe($id)
    {
        return isset ($_SESSION[$id]);
    }

    public static function getValor($id) {
        $this->existe(id) ? ($this->valor = $_SESSION[$this->id]) : $this->valor = NULL;
        return $this->valor;
    }

    public static function setValor($id, $valor) {
        $this->existe($id) ? ($this->valor = $_SESSION[$id] = $valor) : ($this->valor = $_SESSION[$id] = NULL);
        return $this->valor;
    }

    public static function iniciarSession() {
        return session_start();
    }

    public function destruirSession() {
        return session_destroy();
    }
}
?>
