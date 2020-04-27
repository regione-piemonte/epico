<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<script type="text/javascript">
	$(document).ready(function(){
	$("#sceltacomune2").change(function () {
		if ( $(this).val() ) {
				$(".buttonscelta2").prop('disabled', false);
		} else {
			$(".buttonscelta2").prop('disabled', true);
		}
	});
	$("#sceltaatc").change(function () {
		if ( $(this).val() ) {
				$(".buttonscelta2").prop('disabled', false);
		} else {
			$(".buttonscelta2").prop('disabled', true);
		}
	});
  });
</script>

<?php
		      $attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'index', 'method'=>'post');
		      echo form_open('edilizia/',$attributes);
		    ?>

		  <div class="container">
		     <div class="row"><div class="col-md-12">
		<h3>Recupero Immobili e Alloggi ERP</h3>
		       <?php if ($msg): ?>
		        <div class="alert alert-info">
		              <p>
							  <?php
							  if (!empty($COMUNI)) echo "E' necessario scegliere un comune per cui operare";
							  elseif (!empty($ATC)) echo "E' necessario scegliere un ATC per cui operare";
							  ?>
						  </p>
		        </div>
		   <?php endif;  ?>
		       <div class="form-group">
					 <?php
					 if(!empty($COMUNI)):
					?>
		         <label for="comune" class="col-sm-4 control-label">Scegli il comune su cui operare <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span> </label>
		         <div class="col-sm-4">
		           <?php
		            $attr='id="sceltacomune2" class="form-control"';
		            echo form_dropdown('comune', $COMUNI, '',$attr);
						?>
						</div>
					<?php
					elseif (!empty($ATC)):
					?>
					<label for="atc" class="col-sm-4 control-label">Scegli l'ATC su cui operare <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span> </label>
		         <div class="col-sm-4">
		           <?php
		            $attr='id="sceltaatc" class="form-control"';
		            echo form_dropdown('atc', $ATC, '',$attr);
						?>
						</div>
				<?php
					endif;
		       ?>
		         <div class="col-sm-4">
		           <!--<button id="btnsceltacomune" class="btn btn-primary" >imposta</button>-->
		         </div>
		       </div>
		       <!--/form-group-->
		  <div class="panel panel-default">
		         <div class="panel-heading">
		           <h4 class="panel-title">Domanda Tipo a)</h4>
		         </div>
		         <div class="panel-body">

		         <p>Modulo per domanda tipo a) </p>


		         <div class="form-group puls-group pull-right">
		          <div class="col-sm-12">
		            <button class="btn btn-primary buttonscelta2" disabled="disabled" name="tipo_domanda_edilizia" type="submit" value="a">Accedi</button>
		          </div>
		        </div>

		         </div>
		       </div>
		       <!-- /panel panel-default-->
				<div class="panel panel-default">
			         <div class="panel-heading">
			           <h4 class="panel-title">Domanda Tipo b)</h4>
			         </div>
			         <div class="panel-body">

			         <p>Modulo per domanda tipo b) </p>


			         <div class="form-group puls-group pull-right">
			          <div class="col-sm-12">
			            <button class="btn btn-primary " disabled="disabled" name="tipo_domanda_edilizia" type="submit" value="b">Accedi</button>
			          </div>
			        </div>

			         </div>
					</div>
			       <!-- /panel panel-default-->
					<?php
							echo form_close('</div></div></div>');
					?>
