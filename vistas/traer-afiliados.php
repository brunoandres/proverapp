<?php
$con=mysqli_connect('localhost','soyem','vMis823rWf','soyem_')or die("connection failed".mysqli_errno());
mysqli_set_charset($con, "utf8");

$request=$_REQUEST;
$col =array(
    0   =>  'legajo',
    1   =>  'nombre',
    2   =>  'clave',
    3   =>  'documento'
);  //create column like table in database

$sql ="SELECT * FROM afiliado WHERE activo = 'si'";
$query=mysqli_query($con,$sql);

$totalData=mysqli_num_rows($query);

$totalFilter=$totalData;

//Search
$sql ="SELECT * FROM afiliado WHERE activo = 'si'";
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
    /*$subdata[]='<button type="button" id="getEdit" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" data-id="'.$row[0].'"><i class="glyphicon glyphicon-pencil">&nbsp;</i>Edit</button>
                <a href="index.php?ruta=crear-pedido&clave='.$row[0].'" onclick="return confirm(\'Confirma crear nuevo pedido ?\')" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-pencil">&nbsp;</i>Crear pedido</a>';*/
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
