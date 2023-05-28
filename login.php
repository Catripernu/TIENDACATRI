<?php 
include_once("head.php");
if(isset($_SESSION["loggedin"])){
  header("location: index.php");
}
$username = $password = "";
$username_err = $password_err = "";
if(isset($_POST['username']) && isset($_POST['password'])){
    if(empty(trim($_POST["username"]))){
        $username_err = "POR FAVOR, INGRESE SU NUMERO DE CELULAR.";
    } else{
        $username = trim($_POST["username"]);
    }
    if(empty(trim($_POST["password"]))){
        $password_err = "POR FAVOR, INGRESE SU CONTRASEÑA.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
        $stmt = $conexion->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $usuario_encontrado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario_encontrado) {
            if(password_verify($password, $usuario_encontrado['password'])){
                // iniciar sesión y redirigir al usuario a la página de inicio
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $usuario_encontrado['id'];
                $_SESSION["username"] = $username;
                $_SESSION["nombre"] = strtoupper($usuario_encontrado['nombre']);   
                $_SESSION["rol"] = $usuario_encontrado['rol']; 
                // Redirect user to welcome page
                if ($rol == 1) {
                    header("Location: ./admin/ver_pedidos.php");
                } else {
                    header("Location: index.php");
                }
            } else {
                $password_err = "LA CONTRASEÑA QUE HAS INGRESADO NO ES VALIDA."; 
            }            
        } else {
            $username_err = "NO EXISTE CUENTA REGISTRADA CON ESE NUMERO DE TELEFONO.";
        }
    }
    $conexion = null;
}
?>
<body>
<div id="contenido">
    <div class="login">
        <form action="" method="post">
            <input type="text" name="username" placeholder="NUMERO CELULAR" onfocus="if (this.placeholder=='NUMERO CELULAR') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='NUMERO CELULAR';">
            <span class="rojo"><?php echo $username_err; ?></span>
            <input type="password" name="password" placeholder="CONTRASEÑA" onfocus="if (this.placeholder=='CONTRASEÑA') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='CONTRASEÑA';">
            <span class="rojo"><?php echo $password_err; ?></span>
            <input type="submit" class="btn_finish" value="Iniciar Sesion">
        </form>
        <p><a href="./olv_password.php">¿Olvidaste la contraseña?</a></p>
        <p>¿No tenes cuenta? <a href="./registrarme.php">Registrate ahora</a>.</p>
    </div>
</div>
</body>
</html>