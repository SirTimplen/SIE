<?php

class comidas
{
   public $Comida;
   public $cantidad;

    public function __construct($idComida)
    {
        $this->Comida = new Comida($idComida);
        $this->cantidad = 1;
    }
    public function incrementarCantidad()
    {
        $this->cantidad++;
    }

public function decrementarCantidad()
    {
        $this->cantidad--;
    }

}