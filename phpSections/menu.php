<div id="navigation">
    <div class="section">
        <h3 class="nav-head"><a href="#">General</a></h3>
        <div class="section-content">
            <ul>
                <li><a class="active" href="#"><span class="dashboard active dashboard-active">Inicio</span></a></li>
                <li><a class="" href="perfil.php"><span class="registered-users">Perfil</span></a></li>                
                <li><a style="cursor: pointer" onclick="$.CerrarSesion();"><span class="schedule">Salir</span></a></li>
            </ul>
        </div>
    </div>
    <?php if($AccesoRestringido->resultado == 1){ ?>
    <div class="section">
        <h3 class="nav-head"><a href="#">Administracion</a></h3>
        <div class="section-content">
            <ul>                
                <li><a href="admaddalbum.php"><span class="schedule">Albumes</span></a></li>                
                <li><a href="admusuarios.php"><span class="registered-users">Usuarios</span></a></li>
                <li><a href="admestadisticageneral.php"><span class="registered-users">Estadistica general</span></a></li>
                <li style="display: none"><a href="admestadisticaporalbum.php"><span class="registered-users">Estadistica por album</span></a></li>
            </ul>
        </div>
    </div>
    <?php } ?>
</div>