<?php
require_once 'Comida.php';
/*class comidas
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

}*/
class Carrito
{
    public $comidas = array();
    public function __construct()
    {

    }
    public function agregarComida($idComida)
    {
        $encontrado = false;
        foreach ($this->comidas as $comida) {
            if ($comida->Comida->idComida == $idComida) {
                $comida->incrementarCantidad();
                $encontrado = true;
            }
        }
        if (!$encontrado) {
            $this->comidas[] = new comidas($idComida);
        }
    }


    public function eliminarComida($idComida)
    {
        foreach ($this->comidas as $key => $comida) {
            if ($comida->Comida->idComida == $idComida) {
                if ($comida->cantidad > 1) {
                    $comida->decrementarCantidad();
                } else {
                    unset($this->comidas[$key]);
                }
            }
        }
    }
    public function calcularTotal()
    {
        $total = 0;
        foreach ($this->comidas as $comida) {
            $total += $comida->Comida->precio * $comida->cantidad;
        }
        return $total;
    }
    public function vaciarCarrito()
    {
        $this->comidas = array();
    }
    public function mostrarCarrito()
    {
        $html = '';
        foreach ($this->comidas as $comida) {
$html .= '
<tr>
                                <th scope="row">
                                    <div class="d-flex align-items-center">
                                        <img src="img/vegetable-item-5.jpg" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="" alt="">
                                    </div>
                                </th>
                                <td>
                                    <p class="mb-0 mt-4">' . $comida->Comida->Nombre . '</p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">' . $comida->Comida->Precio . ' $</p>
                                </td>
                                <td>
                                    <div class="input-group quantity mt-4" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-minus rounded-circle bg-light border" >
                                            <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center border-0" value="' . $comida->cantidad . '">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">' . $comida->Comida->Precio * $comida->cantidad . ' $</p>
                                </td>
                                <td>
                                    <button class="btn btn-md rounded-circle bg-light border mt-4" >
                                        <i class="fa fa-times text-danger"></i>
                                    </button>
                                </td>
                            </tr>';
        }
        return $html;
    }
}?>

<?php
if($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_GET['aniadirCarrito'])){
    $input = json_decode(file_get_contents('php://input'), true);
    $idComida = $input['idComida'];
    $cantidad = $input['cantidad'];
    $carrito = $_SESSION['carrito'];
    if(!$carrito){
        $carrito = new Carrito();
    }
    $carrito->agregarComida($idComida);
    $_SESSION['carrito'] = $carrito;
    echo json_encode(["status" => "success", "message" => "Added to cart"]);
}

?>