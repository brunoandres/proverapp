<?php
 if(isset($_POST["entregas_id"])){
      $output = '';
      $connect = mysqli_connect("localhost", "root", "", "proverapp");
      mysqli_set_charset($connect, "utf8");
      $query = "SELECT a.numero as entrega,b.*,c.nombre,d.estado FROM entregas a, pedidos b,usuarios c, estados d WHERE a.id = b.entregas_id AND d.id = b.estados_id AND b.usuarios_id = c.id AND a.id = '".$_POST["entregas_id"]."'";
      $result = mysqli_query($connect, $query);

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

        if(mysqli_num_rows($result)>=1){
          while($row = mysqli_fetch_array($result)){

            if($row['estado'] ==  "Pendiente"){
              $estadoPedido = '<label class="label label-default">'.$row['estado'].'</label>';
            }else if($row['estado'] ==  "Entregado"){
              $estadoPedido = '<label class="label label-success">'.$row['estado'].'</label>';
            }else if($row['estado'] ==  "Preparado"){
              $estadoPedido = '<label class="label label-warning">'.$row['estado'].'</label>';
            }else{
              $estadoPedido = '<label class="label label-danger">'.$row['estado'].'</label>';
            }

               $output .= '
               <tr>
                  <td>'.$row["numero"].'</td>
                  <td>'.$estadoPedido.'</td>
                  <td>'.date('d/m/Y', strtotime($row["fecha_pedido"])).'</td>
                  <td>'.$row["nombre"].'</td>
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
