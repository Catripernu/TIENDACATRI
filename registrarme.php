<?php 
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: index.php");
} ?>
<?php include_once("head.php"); ?>
<body>
<div id="contenido"> 
    <div class="registro">
        <form align="center" method="post">
            <p>NUMERO DE CELULAR (SERA TU NOMBRE DE USUARIO)</p>
                <i class="fas fa-mobile-alt"></i>
                <input type="text" id="telefono" autocomplete="off" name="telefono" class="field" maxlength="10" onkeypress="return validaNumericos(event)" placeholder="ejemplo( 3794000000 )" onfocus="if (this.placeholder=='ejemplo( 3794000000 )') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='ejemplo( 3794000000 )';">
            <p>NOMBRE / APELLIDO</p>
                <i class="fas fa-address-card"></i>
                <input type="text" name="nombre" autocomplete="off" id="nombre" class="field espacioRight" placeholder="ejemplo( JUAN )" onfocus="if (this.placeholder=='ejemplo( JUAN )') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='ejemplo( JUAN )';">
                <br><br>
                <i class="fas fa-address-card"></i>
                <input type="text" name="apellido" autocomplete="off" id="apellido" class="field" placeholder="ejemplo( PEREZ )" onfocus="if (this.placeholder=='ejemplo( PEREZ )') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='ejemplo( PEREZ )';">
            <p>CONTRASEÑA / CONFIRMAR CONTRASEÑA</p>
                <i class="fas fa-user-lock"></i>
                <input id="password" type="password" autocomplete="off" name="password" class="field espacioRight" placeholder="***********" onfocus="if (this.placeholder=='***********') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='***********';">
                <br><br>
                <i class="fas fa-user-lock"></i>
                <input id="confirm_password" autocomplete="off" type="password" name="confirm_password" class="field" placeholder="***********" onfocus="if (this.placeholder=='***********') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='***********';">
            <p>DOMICILIO</p>
                <i class="fas fa-house-user"></i>
                <input type="text" id="domicilio" autocomplete="off" name="domicilio" class="field" placeholder="ejemplo( San Martin 0000 )" onfocus="if (this.placeholder=='ejemplo( San Martin 0000 )') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='ejemplo( San Martin 0000 )';">
            <p>CIUDAD</p>
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" id="ciudad" autocomplete="off" name="ciudad" class="field" placeholder="ejemplo( Corrientes )" onfocus="if (this.placeholder=='ejemplo( Corrientes )') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='ejemplo( Corrientes )';">
            <p>PROVINCIA</p>
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" id="provincia" autocomplete="off" name="provincia" class="field" placeholder="ejemplo( Corrientes )" onfocus="if (this.placeholder=='ejemplo( Corrientes )') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='ejemplo( Corrientes )';">
                        
            <div>
                <input class="btn_finish" id="js_register" name="js_register" type="submit" value="Registrarme">    
                <p>¿Ya tienes una cuenta? <a href="login.php">Ingresa aquí</a>.</p>
            </div>
        </form>
    </div>
</div>
</body>
</html>