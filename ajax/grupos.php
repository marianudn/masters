<?php 
require_once "../modelos/Grupos.php";
if (strlen(session_id())<1) 
	session_start();

$grupos = new Grupos();

$idgrupo=isset($_POST["idgrupo"])? limpiarCadena($_POST["idgrupo"]):"";
$idusuario=$_SESSION["idusuario"];
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$favorito=isset($_POST["favorito"])? 1 :0;



switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idgrupo)) {
		if ($_SESSION["tipo_usuario"]!="ALUMNO"){
			$semilla = '0123456789abcdefghijklmnopqrstuvwxyz';
			$codigounico = substr( str_shuffle( $semilla ), 0, 8 );
			$rspta=$grupos->insertar($nombre,$favorito,$idusuario,$codigounico);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
		}else {
			$query=$grupos->obtener_grupo($nombre);
			$row = mysqli_fetch_array($query);
    		$idgrupoquery=$row['idgrupo'];
    		
    		if($idgrupoquery!=null)
    		{
				$rspta=$grupos->insertar_alumno($idgrupoquery,$idusuario);
				echo $rspta ? "Alumno agregado correctamente a grupo" : "No se pudo agegar a grupo";
			}else{
				echo $rspta = "No se pudo agegar a grupo";
			}
		}
	}else{
         $rspta=$grupos->editar($idgrupo,$nombre,$favorito,$idusuario,$codigounico);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
		break;
	

	case 'anular':
		$rspta=$grupos->anular($idgrupo);
		echo $rspta ? "Ingreso anulado correctamente" : "No se pudo anular el ingreso";
		break;
	
	case 'mostrar':
		$rspta=$grupos->mostrar($idgrupo);
		echo json_encode($rspta);
		break;

    case 'listar':
    	if ($_SESSION['tipo_usuario']=='ADMINISTRADOR') {
    		$rspta=$grupos->listar();
    	}elseif ($_SESSION['tipo_usuario']=='ALUMNO'){
    		$rspta=$grupos->listar_cursos_alumno($_SESSION['idusuario']);
    		}elseif ($_SESSION['tipo_usuario']=='PROFESOR'){
    			$rspta=$grupos->listar_profesor($_SESSION['nombre']);
    		}
		
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
                

			$data[]=array(

			"0"=>'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idgrupo.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idgrupo.')"><i class="fa fa-close"></i></button>',
            "1"=>$reg->nombre,
            "2"=>$reg->usuario,
            "3"=>$reg->codigo_unico,
              );
		}
		$results=array( 
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);
		break; 

}
 ?> 