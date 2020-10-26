<?php

$con=mysqli_connect('localhost','soyem','vMis823rWf','soyem_')
    or die("connection failed".mysqli_errno());
mysqli_set_charset($con, "utf8");
if(isset($_POST['search'])){
 $search = addslashes($_POST['search']);

 $query = "SELECT * FROM afiliado WHERE (nombre like '%".$search."%') or (legajo = '$search') and activo='si'";
 $result = mysqli_query($con,$query);

 $response = array();
 while($row = mysqli_fetch_array($result) ){
   $response[] = array("value"=>$row['clave'],"label"=>$row['nombre'].' Legajo: '.$row['legajo']);

 }

 echo json_encode($response);
}

exit;
