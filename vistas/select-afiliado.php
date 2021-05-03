<?php

include("../modelos/conexion.php");
$conexion = new Conexion();
$con = $conexion->conectarMysql();

if(isset($_POST['search'])){
 $search = addslashes($_POST['search']);

 $query = "SELECT *, case afiliado.socioos
    when 'si' then '1'
    end as es_socio FROM afiliado WHERE (nombre like '%".$search."%') or (legajo = '$search') and activo='si'";
 $result = mysqli_query($con,$query);

 $response = array();
 while($row = mysqli_fetch_array($result) ){
   $response[] = array("value"=>$row['clave'],"legajo"=>$row['legajo'],"es_socio"=>$row['es_socio'],"label"=>$row['nombre'].' Legajo: '.$row['legajo'],);

 }

 echo json_encode($response);
}

exit;
