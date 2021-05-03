<?php
require_once "../modelos/conexion.php";
 if(isset($_POST["entregas_id"])){
      $output = '';
      $idEntrega = $_POST["entregas_id"];
      $stmt = Conexion::conectar()->prepare("SELECT a.numero as entrega,b.*,c.nombre,d.estado FROM entregas a, pedidos b,usuarios c, estados d WHERE a.id = b.entregas_id AND d.id = b.estados_id AND b.usuarios_id = c.id AND a.id = :id");
      $stmt -> bindParam(":id",$idEntrega, PDO::PARAM_INT);
      $stmt -> execute();

      $output .= '
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Pedido N°</th>
            <th scope="col">Estado</th>
            <th scope="col">Fecha Creación</th>
            <th scope="col">Usuario</th>
          </tr>
        </thead>
        <tbody>';

        if(!empty($stmt)){
          foreach ($stmt as $key => $value) {


            if($value['estado'] ==  "Pendiente"){
              $estadoPedido = '<label class="label label-default">'.$value['estado'].'</label>';
            }else if($value['estado'] ==  "Entregado"){
              $estadoPedido = '<label class="label label-success">'.$value['estado'].'</label>';
            }else if($value['estado'] ==  "Preparado"){
              $estadoPedido = '<label class="label label-warning">'.$value['estado'].'</label>';
            }else{
              $estadoPedido = '<label class="label label-danger">'.$value['estado'].'</label>';
            }

               $output .= '
               <tr>
                  <td>'.$value["numero"].'</td>
                  <td>'.$estadoPedido.'</td>
                  <td>'.date('d/m/Y', strtotime($value["fecha_pedido"])).'</td>
                  <td>'.$value["nombre"].'</td>
                </tr>

                ';
          }
        }else{
          $output.= "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
            La entrega no posee Pedidos asignados!.
          </div>";
        }

      $output .= "</tbody>
      </table>";
      echo $output;
 }
 ?>
