<div class="data" id="statistics">
    <span class="head">Cambiar Titulo</span>
    <form id="FormEditarImagen" name="form1" method="post">        
        <input type="hidden" name="IdImagen" value="<?php echo $_GET['Imagen']; ?>" />
        <div class="table">
            <dl>
                <dt><label for="titulo">Titulo</label></dt>
                <dd><input type="text" id="title" name="Titulo" class="input form text" value="<?php echo $_GET['Titulo']; ?>"></dd>
            </dl>
            <dl style="display:none">
                <dt><label for="descripcion">Descripcion</label></dt>
                <dd><textarea id="descripcion" name="Descripcion" class="input form text">Descripcion</textarea></dd>
            </dl>             
        </div>
        <input type="button" id="btnEditarImagen" onclick ="$.EditarImagen();" class="button orange" value="Actualizar" />
        <input type="button" id="btnCancelarEdicionImagen" onclick="$('.close').click();" class="button orange" value="Cancelar" />
    </form>    
</div>