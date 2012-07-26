
<script type="text/javascript" src="<?php echo JURI::root(); ?>modules/mod_k2_filter/assets/js/jquery-ui-1.8.16.custom.min.js"></script>
<link type="text/css" href="<?php echo JURI::root(); ?>modules/mod_k2_filter/assets/js/jquery-ui-1.8.16.custom.css" rel="stylesheet" />

	<script type="text/javascript">
	<?php 
		if(JRequest::getVar("searchword".$field_id[$j]) != "")
			$value = JRequest::getVar("searchword".$field_id[$j]);
		else 
			$value = 0;
	?>
	
	jQuery(document).ready(function() {

		jQuery("#slider<?php echo $field_id[$j];?>")[0].slide = null;
		jQuery("#slider<?php echo $field_id[$j];?>").slider({
			value: <?php echo $value; ?>,
			range: "min",
			min: 0,
			max: 10000,
			step: 100,
			slide: function(event, ui) {
				jQuery( "#amount<?php echo $field_id[$j];?>" ).val( "$" + ui.value );
				jQuery("input#slider<?php echo $field_id[$j];?>_val").val( ui.value );
			}
		});
		jQuery("#amount<?php echo $field_id[$j];?>").val("<?php if($value != 0) echo "$".$value; ?>");
	});
	</script>

	<div class="k2filter-field-slider k2filter-field-<?php echo $i; ?>">
		<h3>
			<?php echo $extra_fields_name[$j]; ?>
		</h3>

		<div class="slider<?php echo $field_id[$j];?>_wrapper">

		<input type="text" disabled id="amount<?php echo $field_id[$j];?>" class="k2filter-slider-amount" />

		<div id="slider<?php echo $field_id[$j];?>"></div>
		<input id="slider<?php echo $field_id[$j];?>_val" class="slider_val" type="hidden" name="searchword<?php echo $field_id[$j];?>" value="">
		</div>
	</div>

