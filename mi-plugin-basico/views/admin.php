 <?php
    defined('ABSPATH') or die('Acceso no permitido.');

    // Verificar si estamos en modo edición
    $modoEditar = isset($_GET['view']) && $_GET['view'] == 'edit_user' && isset($datos);
    
    // Valores predeterminados para el formulario
    $id_valor = $modoEditar ? $datos->id : '';
    $nombre_valor = $modoEditar ? esc_attr($datos->nombre) : '';
    $email_valor = $modoEditar ? esc_attr($datos->email) : '';
    $form_action = $modoEditar ? 'admin.php?page=pagina_admin&action=EDIT_USER&id='.$datos->id : '';
    $boton_texto = $modoEditar ? 'Actualizar' : 'GUARDAR';
    
    if(isset($_GET['res'])){
        echo '<div class="alert">'.$_GET['res'].'</div>';
    }
    
    // Título dinámico según el modo
    $titulo = $modoEditar ? '<h1>Editar Usuario</h1>' : '<h1>Nuevo Usuario</h1>';

  $html = '<div class="wrap">'.  $titulo .'
  <form method="post" action="'.$form_action.'">';
$html .= ( $modoEditar ? '<input type="hidden" name="id" value="'. $id_valor .'">' : '' );
$html .= '<input type="text" name="nombre" id="nombre" value="'.$nombre_valor.'" placeholder="Nombre:" required>
        <input type="email" name="email" id="email"  value="'.$email_valor.'" placeholder="E-mail:" required>
         <button type="submit" class="button button-primary">  '.$boton_texto.' </button>
     ' . ($modoEditar ? '<a href="admin.php?page=pagina_admin" class="button">Cancelar</a>' : '') . '
        </form>
        </div>
    
        <table>    
                <thead>
                    <th>id</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th></th>
                    <th></th>
                </thead>
                <tbody>
                    
                ';
                if(isset($registros)){
                    foreach($registros as $registro){
                        $html .= '<tr><td>'.$registro->id.'</td>';
                        $html .= '<td>'.$registro->nombre.'</td>';
                        $html .= '<td>'.$registro->email.'</td> ';
                        $html .= '<td><a href="admin.php?page=pagina_admin&id='.$registro->id.'&view=edit_user&action=edit_user"><i class="fas fa-edit"></i></a></td>';
                        $html .= '<td><a href="admin.php?page=pagina_admin&id='.$registro->id.'&action=BORRAR"><i class="fas fa-trash"></i></a></td></tr>';
                    }
                }

                
                $html .='
                   
                </tbody>
        </table>    
        </div>
        ';

        $html .= "<script>



        var cssId = 'CDNFONTAWESOME'; 
        if (!document.getElementById(cssId)) {
            var head = document.getElementsByTagName('head')[0];
            var link = document.createElement('link');
            link.id = cssId;
            link.rel = 'stylesheet';
            link.type = 'text/css';
            link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';
            link.media = 'all';
            head.appendChild(link);
        }
        
        
        
        </script>";


    echo $html;

