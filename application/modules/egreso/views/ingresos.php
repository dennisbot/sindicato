<div class="centered-text" style="width: 60%; margin: 0px auto; padding: 15px;">
<?php $this->load->view('dashboard/system_messages'); ?>

</div>

<!-- fecha de la que se desea consultar la ganancia -->

<table class="table table-striped table-hover form-agregar" style="margin: 0px auto;">

	<div class="input-append date" id="dp3" data-date="" data-date-format="dd-mm-yyyy" language="es">
                <input  style="width:193px; "class="span2"  name="fecha" type="text" value="<?php echo $this->mdl_egreso->form_value('fecha'); ?>" readonly>
                <span class="add-on"><i class="icon-calendar"></i></span>
    </div>


</table>
<?php if ($this->mdl_egreso->page_links) { ?>
    <div id="loading" style="position: relative"></div>
        <div id="pagination" class="pagination pagination-centered">
            <ul>
                <?php echo $this->mdl_egreso->page_links; ?>
            </ul>
        </div>
<?php } ?>

<script type="text/javascript">
$('#dp3').datepicker();
$('#dp3').attr('data-date',datepicker().now);
 $('#dp3').datepicker({
    language: 'es' // as defined in bootstrap-datepicker.XX.js

</script>
