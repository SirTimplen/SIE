<?php
/*create table comidas
(
    id               int auto_increment
        primary key,
    Nombre           varchar(100)                             not null,
    Precio           decimal(10, 2)                           not null,
    Peso             decimal(10, 2)                           not null,
    Calorías         int                                      null,
    Ingredientes     text                                     null,
    Valoración_media decimal(3, 2)                            null,
    Tipo             enum ('Entrante', 'Principal', 'Postre') not null
);*/
class Comida
{
    private $id;
    private $Nombre;
    private $Precio;
    private $Peso;
    private $Calorías;
    private $Ingredientes;
    private $Valoración_media;
    private $Tipo;
    public function __construct($id)
    {

        global $conexion;
        $this->id = $id;
        $this->$conexion = mysqli_connect("localhost", "root", "", "sabercomer");
        $sql = "SELECT * FROM comidas WHERE id = $id";
        $result = $conexion->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->Nombre = $row['Nombre'];
            $this->Precio = $row['Precio'];
            $this->Peso = $row['Peso'];
            $this->Calorías = $row['Calorías'];
            $this->Ingredientes = $row['Ingredientes'];
            $this->Valoración_media = $row['Valoración_media'];
            $this->Tipo = $row['Tipo'];
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->Nombre;
    }
    public function getPrecio()
    {
        return $this->Precio;
    }

}