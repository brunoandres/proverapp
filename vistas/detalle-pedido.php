<?php
 if(isset($_POST["pedidos_id"])){
      $output = '';
      $connect = mysqli_connect("localhost", "soyem", "vMis823rWf", "soyem_proveeduria");
      mysqli_set_charset($connect, "utf8");
      $query = "SELECT * FROM pedidos WHERE id = '".$_POST["pedidos_id"]."'";
      $result = mysqli_query($connect, $query);

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
      while($row = mysqli_fetch_array($result)){

          $listaProducto = json_decode($row["productos"], true);
          if(!empty($listaProducto)){
            foreach ($listaProducto as $key => $value) {


             $output .= '
             <tr>
                <td>'.$value["descripcion"].'</td>
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
          <td>Pago en Efectivo : $ '.$row["pago_efectivo"].'</td>
          <td>Pago por Planilla : $ '.$row["pago_planilla"].'</td>
          <td>Comprobante : '.$row["comprobante"].'</td>';
      }
      $output .= "</tbody>
      </table>";
      echo $output;
 }
 ?>
