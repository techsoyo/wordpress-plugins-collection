<?php
 defined('ABSPATH') or die('Acceso no permitido.');
 
 require_once ABSPATH.$RUTA_AB.'model/model.php';   
  if($_POST){
            $nombre = htmlspecialchars(trim($_POST['nombre']));
            $email = trim($_POST['email']);
            $datos = ['nombre'=>$nombre, 'email' => $email];
            
            // Verificar si estamos actualizando (existe ID en POST)
            if(isset($_POST['id']) && !empty($_POST['id'])){
                // Es una actualización
                $id = intval($_POST['id']);
                $condicion = ['id' => $id];
                $res = EditarDatos($tabla, $datos, $condicion);
            } else {
                // Es una inserción
                $res = InsertarDatos($tabla, $datos);
            }
            
            // JavaScript para redireccionar si los headers ya fueron enviados
            echo '<script>window.location = "admin.php?page=pagina_admin&res='.urlencode($res).'";</script>';
            exit;
        } 
  
    // Procesamiento de acciones GET
    if($_GET){
                // Manejar vistas específicas
        if(isset($_GET['view']) && !empty($_GET['view'])){
            // Si la vista es EDIT_USER
            if($_GET['view'] == 'edit_user' && isset($_GET['id'])){
                // Capturar el ID de la URL
                $id = intval($_GET['id']);
                $condicion = ['id' => intval($_GET['id'])];;
                $datos = ObtenerDatos($tabla, $condicion);
            }
        }

    if(isset($_GET['action']) && !empty($_GET['action'])){
            // Acción BORRAR
            if($_GET['action'] == 'BORRAR'){
                $id = intval($_GET['id']);
                $condicion = ['id' => intval($_GET['id'])]; 
                $res = BorrarDatos($tabla, $condicion);
                echo '<script>window.location = "admin.php?page=pagina_admin&res='.urlencode($res).'";</script>';
                exit;
            }
            
        }
    
    }
    // Si no hay acciones específicas en GET, mostrar la página principal
    require_once ABSPATH.$RUTA_AB.'views/admin.php';    