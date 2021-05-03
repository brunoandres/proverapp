<?php
include("../modelos/conexion.php");
$conexion = new Conexion();
$con = $conexion->conectarMysql();
$dato = new SED();

$request=$_REQUEST;
$col =array(
    0   =>  'legajo',
    1   =>  'nombre',
    2   =>  'clave',
    3   =>  'documento'
);  //create column like table in database

$sql ="SELECT *,TRIM(nombre) AS nombre FROM afiliado WHERE activo = 'si' AND (socioos = 'no' or socioos = '')";
$query=mysqli_query($con,$sql);

$totalData=mysqli_num_rows($query);

$totalFilter=$totalData;

//Search
$sql ="SELECT *,TRIM(nombre) AS nombre FROM afiliado WHERE activo = 'si' AND (socioos = 'no' or socioos = '')";
if(!empty($request['search']['value'])){
    $sql.=" AND (legajo Like '".$request['search']['value']."%' ";
    $sql.=" OR nombre Like '".$request['search']['value']."%' ";
    $sql.=" OR clave Like '".$request['search']['value']."%' ";
    $sql.=" OR documento Like '".$request['search']['value']."%' )";
}
$query=mysqli_query($con,$sql);
$totalData=mysqli_num_rows($query);

//Order
$sql.=" ORDER BY ".$col[$request['order'][0]['column']]."   ".$request['order'][0]['dir']."  LIMIT ".
    $request['start']."  ,".$request['length']."  ";

$query=mysqli_query($con,$sql);

$data=array();

while($row=mysqli_fetch_array($query)){
    $subdata=array();
    $subdata[]=$row[1];
    $subdata[]=$row[0];
    $subdata[]=$row[3];
    $subdata[]='<a href="index.php?ruta=afiliado-detalle&ref='.$dato::encryption($row[2]).'"><button type="button" class="btn btn-default btn-xs" name="button">Detalles</button></a>
    <a href="index.php?ruta=crear-pedido&ref='.$dato::encryption($row[2]).'&afiliado='.$row[1].'&socio=NO"><button type="button" class="btn btn-primary btn-xs" name="button">Crear Pedido</button></a>';
    $data[]=$subdata;
}

$json_data=array(
    "draw"              =>  intval($request['draw']),
    "recordsTotal"      =>  intval($totalData),
    "recordsFiltered"   =>  intval($totalFilter),
    "data"              => $data
);

echo json_encode($json_data);

?>
