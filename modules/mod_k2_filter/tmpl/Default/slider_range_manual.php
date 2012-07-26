
<script type="text/javascript" src="<?php echo JURI::root(); ?>modules/mod_k2_filter/assets/js/jquery-ui-1.8.16.custom.min.js"></script>
<link type="text/css" href="<?php echo JURI::root(); ?>modules/mod_k2_filter/assets/js/jquery-ui-1.8.16.custom.css" rel="stylesheet" />

	<script type="text/javascript">
	<?php 
		$from = JRequest::getVar("searchword".$field_id[$j]."-from", 0);
		$to = JRequest::getVar("searchword".$field_id[$j]."-to", 0);
		if($from != 0 && $to != 0)
			$value = $from. " - " .$to;
		if($from == 0 && $to != 0)
			$value = "0 - ".$to;
		if($from == 0 && $to == 0)
			$value = 0;
	?>
	
	jQuery(document).ready(function() {
	
		jQuery("#slider<?php echo $field_id[$j];?>")[0].slide = null;
		jQuery("#slider<?php echo $field_id[$j];?>").slider({
			range: true,
			min: 0,
			max: 20,
			step: 1,
			values: [ <?php if($from != 0) echo $from; else echo "0" ?>, <?php if($to != 0) echo $to; else echo "20" ?> ],
			slide: function(event, ui) {
				jQuery( "#amount<?php echo $field_id[$j];?>" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
				jQuery("input#slider<?php echo $field_id[$j];?>_val_from").val( ui.values[ 0 ] );
				jQuery("input#slider<?php echo $field_id[$j];?>_val_to").val( ui.values[ 1 ] );
			}
		});
		jQuery("#amount<?php echo $field_id[$j];?>").val("<?php echo $value; ?>");
	});
	</script>

	<div class="k2filter-field-slider k2filter-field-<?php echo $i; ?>">
		<h3>
			<?php echo $extra_fields_name[$j]; ?>
		</h3>

		<div class="slider<?php echo $field_id[$j];?>_wrapper">

			<input type="text" disabled id="amount<?php echo $field_id[$j];?>" class="k2filter-slider-amount" />

			<div id="slider<?php echo $field_id[$j];?>"></div>
			
			<input id="slider<?php echo $field_id[$j];?>_val_from" class="slider_val" type="hidden" name="searchword<?php echo $field_id[$j];?>-from" value="<?php if($from != 0) echo $from; else echo '0'; ?>">
			
			<input id="slider<?php echo $field_id[$j];?>_val_to" class="slider_val" type="hidden" name="searchword<?php echo $field_id[$j];?>-to" value="<?php if($to != 0) echo $to; else echo '20'; ?>">
		
		</div>
	</div>

