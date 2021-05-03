<?php
require_once "../modelos/conexion.php";

if(isset($_POST["pedidos_id"])){
      $output = '';
      $idPedido = $_POST["pedidos_id"];
      $stmt = Conexion::conectar()->prepare("SELECT * FROM pedidos WHERE id = :id");
      $stmt -> bindParam(":id",$idPedido, PDO::PARAM_INT);
      $stmt -> execute();

      $output .= '
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Producto</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Precio x U.</th>
          </tr>
        </thead>
        <tbody>';
      //while($row = mysqli_fetch_array($result)){
      foreach ($stmt as $key => $pedido) {


          $listaProducto = json_decode($pedido["productos"], true);
          if(!empty($listaProducto)){
            foreach ($listaProducto as $key => $value) {


             $output .= '
             <tr>
                <td>'.$value["nombre"].' '.$value["descripcion"].'</td>
                <td><label class="label label-default">'.$value["cantidad"].'</label></td>
                <td><label class="label label-default">$ '.$value["precio"].'</label></td>
              </tr>

                  ';
            }
          }else{
            $output.= "<div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
              El pedido no posee productos asignados!.
            </div>";
          }
          $output.='
          <td>Pago en Efectivo : $ '.$pedido["pago_efectivo"].'</td>
          <td>Pago por Planilla : $ '.$pedido["pago_planilla"].'</td>
          <td>Comprobante : '.$pedido["comprobante"].'</td>
          <td>Nro Asiento : <strong>'.$pedido["fk_nro_asiento"].'</strong></td>';
      }
      $output .= "</tbody>
      </table>";
      echo $output;
 }
 ?>
