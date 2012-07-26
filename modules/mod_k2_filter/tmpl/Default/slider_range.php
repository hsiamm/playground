
<link type="text/css" href="<?php echo JURI::root(); ?>modules/mod_k2_filter/assets/js/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo JURI::root(); ?>modules/mod_k2_filter/assets/js/jquery-ui-1.8.16.custom.min.js"></script> 

	<script type="text/javascript">
	
	var <?php echo "slider_range".$field_id[$j]; ?>_values = new Array(
	<?php foreach ($extra_fields_content[$j] as $k => $field) {
			echo "\"".$field;
			if($k+1 < sizeof($extra_fields_content[$j]))
				echo "\", ";
			}
			echo "\"";
	?>
	);
	
	jQuery(document).ready(function() {
					var length = <?php echo (count($extra_fields_content[$j]) - 1); ?>;
		
				<?php 
					if (JRequest::getVar('slider_range'.$field_id[$j])) {
						$vals = explode(" - ", JRequest::getVar('slider_range'.$field_id[$j]));							
						echo "jQuery(\"#amount".$field_id[$j]."\").val('" . $vals[0] . " - " . $vals[1] . "');\n";
						echo "jQuery(\"input#slider_range" . $field_id[$j] . "_val\").val('" . $vals[0] . " - " . $vals[1] . "');\n";
					}
					else {
						echo "jQuery(\"#amount".$field_id[$j]."\").val(slider_range".$field_id[$j]."_values[0] + \" - \" + slider_range".$field_id[$j]."_values[length]);\n";
						echo "jQuery(\"input#slider_range" . $field_id[$j] . "_val\").val(slider_range".$field_id[$j]."_values[0] + \" - \" + slider_range".$field_id[$j]."_values[length]);\n";						
					}
				?>
				
		jQuery("#slider_range<?php echo $field_id[$j];?>")[0].slide = null;
		jQuery("#slider_range<?php echo $field_id[$j];?>").slider({
			<?php 
					if (JRequest::getVar('slider_range'.$field_id[$j])) {
						$vals = explode(" - ", JRequest::getVar('slider_range'.$field_id[$j]));	
						
						echo "values: [";
						
						$values = Array(); 
						//$values[0] = "";
						//$jk = 1;
						$jk = 0;
							foreach ($extra_fields_content[$j] as $which=>$field) {
							$values[$jk] = $field;
							$jk++;
							}
						for($jj=0; $jj<sizeof($vals); $jj++) {
							$vall = "0";
							for($jk=0; $jk<sizeof($values); $jk++) {
								if(($vals[$jj]) == $values[$jk]) {
									$vall = $jk;
								}
							}
							echo $vall;
							if($jj+1 < sizeof($vals))
								echo ", ";
						}
						
						echo "],";
					}
					else {
						echo "values: [ 0, length ],";
					}
			?>
			range: true,
			min: 0,
			max: <?php echo (sizeof($extra_fields_content[$j]) - 1); ?>,
			slide: function(event, ui) {
				if(<?php echo "slider_range".$field_id[$j]; ?>_values[ui.values[0]] == "")
					jQuery("#amount<?php echo $field_id[$j];?>").val("0 - " + <?php echo "slider_range".$field_id[$j]; ?>_values[ui.values[1]]);
				else 
					jQuery("#amount<?php echo $field_id[$j];?>").val(<?php echo "slider_range".$field_id[$j]; ?>_values[ui.values[0]] + " - " + <?php echo "slider_range".$field_id[$j]; ?>_values[ui.values[1]]);	
				
				if (<?php echo "slider_range".$field_id[$j]; ?>_values[ui.values[1]] == "")
					jQuery("#amount<?php echo $field_id[$j];?>").val("");
				
				var vals = jQuery("#amount<?php echo $field_id[$j];?>").val();
				jQuery("input#slider_range<?php echo $field_id[$j];?>_val").val(vals);
			}
		});
	});
	</script>

	<div class="k2filter-field-slider k2filter-field-<?php echo $i; ?>">
		<h3>
			<?php echo $extra_fields_name[$j]; ?>
		</h3>

		<div class="slider_range<?php echo $field_id[$j];?>_wrapper">

		<input type="text" disabled id="amount<?php echo $field_id[$j];?>" class="k2filter-slider-amount" />

		<div id="slider_range<?php echo $field_id[$j];?>"></div>
		<input id="slider_range<?php echo $field_id[$j];?>_val" class="slider_val" type="hidden" name="slider_range<?php echo $field_id[$j];?>" value="">
		</div>
	</div>

