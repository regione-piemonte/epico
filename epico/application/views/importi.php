<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<script type="text/javascript">
  $(document).ready(function(){
	  $('input[name="importo"]').autoNumeric({aSign: '€ ', pSign: 'p', aSep: '.', aDec: ',', wEmpty: 'empty'});
	  $('#assegnate, #liquidate, #economie').validationEngine('attach');
	  $('select[name="comune"]').change(function(){
		 $(this).closest('form').submit();
	  });
	  $('.elimina').click(function(e){
		e.preventDefault();
		var deletion = confirm("Stai per eliminare la risorsa. Vuoi continuare?");
		if (deletion) {
			$.get(this.href);  
			location.reload();	
		}
		return false;
	  });
  });
</script>
<?php
setlocale(LC_MONETARY, 'it_IT.utf8');
$this->load->view('include/status', $_ci_data['_ci_vars']);
?>

<div class="container">
	<div class="row"><div class="col-md-12">
		<?php $this->load->view('include/menu', $_ci_data['_ci_vars']);?>    
		<h3>Importi assegnati/liquidati</h3>
	    <?php if ($msg): ?>
        <div class="alert alert-info">
            <p>
				<?php echo $msg; ?>
			</p>
        </div>
	   <?php endif;  ?>
	   <?php
			$attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'index', 'method'=>'post');
			echo form_open('importi/',$attributes);
		?>
       <div class="form-group">
	       <label for="comune" class="col-sm-4 control-label">Comune <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span> </label>
	       <div class="col-sm-4">
	           <?php
	            $attr='class="form-control validate[required]"';
	            echo form_dropdown('comune', $COMUNI, $comune, $attr);
				?>
			</div>
       </div>
	   <?php 
		   echo form_close(); 
		?>   
		    
       <h4>Risorse assegnate</h4>
        
		<table class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<th>Annualità</th>
					<th>Importo</th>
					<th>Data determina</th>
					<th>Numero determina</th>
					<th class="text-center">Azione</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($assegnate as &$risorsa): ?>
				<tr>
					<td><?php echo $risorsa->ANNUALITA; ?></td>
					<td><?php echo money_format('%.0n', $risorsa->IMPORTO);?></td>
					<td><?php echo date("d/m/Y", strtotime($risorsa->DATA_DETERMINA)); ?></td>
					<td><?php echo $risorsa->NUM_DETERMINA; ?></td>
					<td class="text-center">
						<a href="<?php echo base_url().'index.php/importi/elimina/'.$risorsa->ID; ?>" title="elimina" class="elimina"><span class="icon icon-remove"></span><span class="hidden">elimina</span></a>
					</td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td>TOTALE</td>
					<td><?php echo money_format('%.0n', $tot_assegnate); ?></td>
				</tr>
			</tbody>
		</table>
		<?php
			$attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'assegnate', 'method'=>'post');
			echo form_open('importi/',$attributes);
		?>
		<div class="form-group">
	    	<div class="col-sm-2">
		       <label for="annualita" class="control-label">Annualità <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
	           <?php 
		           $years = range(date('Y')-3, date('Y')+1);
		           echo form_dropdown('annualita', array_combine($years, $years) , date('Y'), $attr); 
		       ?>
			</div>
			<div class="col-sm-2">
				<label for="importo" class="control-label">Importo <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
				<input type="text" name="importo" class="form-control validate[required]"/>
			</div>
			<div class="col-sm-2">
				<label for="data_determina" class="control-label">Data determina <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
				<div class="calendar-control">
					<input type="text" class="form-control calendarioCustom" name="data_determina" placeholder="gg/mm/aaaa" />
				</div>
			</div>
			<div class="col-sm-2">
				<label for="num_determina" class="control-label">Numero determina <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
				<input type="text" name="num_determina" class="form-control validate[required,custom[onlyNumberSp]]"/>
			</div>
			<div class="col-sm-4 text-right">
				<input type="hidden" value="<?php echo $comune; ?>" name="comune"/><br>
				<button <?php if (empty($comune)) echo 'disabled'; ?> type="submit" name="submit" id="valida" class="btn btn-primary" value="assegnati"><span class="icon icon-plus"></span> Aggiungi</button>
			</div>
		</div>
		<?php 
		   echo form_close(); 
		?> 
		
		<h4>Risorse liquidate</h4>
        
		<table class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<th>Annualità</th>
					<th>Importo</th>
					<th>Data determina</th>
					<th>Numero determina</th>
					<th>AL</th>
					<th>Data AL</th>
					<th>Capitolo</th>
					<th>Anno capitolo</th>
					<th>Numero impegno</th>
					<th class="text-center">Azione</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($liquidate as &$risorsa): ?>
				<tr>
					<td><?php echo $risorsa->ANNUALITA; ?></td>
					<td><?php echo money_format('%.0n', $risorsa->IMPORTO);?></td>
					<td><?php echo date("d/m/Y", strtotime($risorsa->DATA_DETERMINA)); ?></td>
					<td><?php echo $risorsa->NUM_DETERMINA; ?></td>
					<td><?php echo $risorsa->AL; ?></td>
					<td><?php echo date("d/m/Y", strtotime($risorsa->DATA_AL)); ?></td>
					<td><?php echo $risorsa->CAPITOLO; ?></td>
					<td><?php echo $risorsa->ANNO_CAPITOLO; ?></td>
					<td><?php echo $risorsa->NUM_IMPEGNO; ?></td>
					<td class="text-center">
						<a href="<?php echo base_url().'index.php/importi/elimina/'.$risorsa->ID; ?>" title="elimina" class="elimina"><span class="icon icon-remove"></span><span class="hidden">elimina</span></a>
					</td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td>TOTALE</td>
					<td><?php echo money_format('%.0n', $tot_liquidate); ?></td>
				</tr>
			</tbody>
		</table>
		<?php
			$attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'liquidate', 'method'=>'post');
			echo form_open('importi/',$attributes);
		?>
		<div class="form-group">
	    	<div class="col-sm-2">
		       <label for="annualita" class="control-label">Annualità <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
	           <?php 
		           $years = range(date('Y')-3, date('Y')+1);
		           echo form_dropdown('annualita', array_combine($years, $years) , date('Y'), $attr); 
		       ?>
			</div>
			<div class="col-sm-2">
				<label for="importo" class="control-label">Importo <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
				<input type="text" name="importo" class="form-control validate[required]"/>
			</div>
			<div class="col-sm-2">
				<label for="data_determina" class="control-label">Data determina <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
				<div class="calendar-control">
					<input type="text" class="form-control calendarioCustom" name="data_determina" placeholder="gg/mm/aaaa" />
				</div>
			</div>
			<div class="col-sm-2">
				<label for="num_determina" class="control-label">Numero determina <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
				<input type="text" name="num_determina" class="form-control validate[required,custom[onlyNumberSp]]"/>
			</div>
			<div class="col-sm-2">
				<label for="al" class="control-label">AL <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
				<input type="text" name="al" class="form-control validate[required]"/>
			</div>
			<div class="col-sm-2">
				<label for="data_al" class="control-label">Data AL <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
				<div class="calendar-control">
					<input type="text" class="form-control calendarioCustom" name="data_al" placeholder="gg/mm/aaaa" />
				</div>
			</div>
			<div class="col-sm-2">
				<label for="capitolo" class="control-label">Capitolo <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
				<input type="text" name="capitolo" class="form-control validate[required]"/>
			</div>
			<div class="col-sm-4">
				<label for="anno_capitolo" class="control-label">Anno capitolo <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
				<input type="text" name="anno_capitolo" class="form-control validate[required]"/>
			</div>
			<div class="col-sm-2">
				<label for="num_impegno" class="control-label">Numero impegno <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
				<input type="text" name="num_impegno" class="form-control validate[required]"/>
			</div>
			<div class="col-sm-4 text-right">
				<input type="hidden" value="<?php echo $comune; ?>" name="comune"/><br>
				<button <?php if (empty($comune)) echo 'disabled'; ?> type="submit" name="submit" id="valida" class="btn btn-primary" value="liquidati"><span class="icon icon-plus"></span> Aggiungi</button>
			</div>
		</div>
		<?php 
		   echo form_close(); 
		?> 
		<div class="row">
		<div class="col-sm-6">
			
			<h4>Risorse residue da liquidare</h4>
			
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<th>Annualità</th>
						<th>Importo</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($residue as &$risorsa): ?>
					<tr>
						<td><?php echo $risorsa->annualita; ?></td>
						<td><?php echo money_format('%.0n', $risorsa->importo);?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		
		<div class="col-sm-6">
			
			<h4>Economie</h4>
			
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<th>Annualità</th>
						<th>Importo</th>
						<th class="text-center">Azione</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($economie as &$risorsa): ?>
					<tr>
						<td><?php echo $risorsa->ANNUALITA; ?></td>
						<td><?php echo money_format('%.0n', $risorsa->IMPORTO);?></td>
						<td class="text-center">
							<a href="<?php echo base_url().'index.php/importi/elimina/'.$risorsa->ID; ?>" title="elimina" class="elimina"><span class="icon icon-remove"></span><span class="hidden">elimina</span></a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php
				$attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'economie', 'method'=>'post');
				echo form_open('importi/',$attributes);
			?>
			<div class="form-group">
		    	<div class="col-sm-4">
			       <label for="annualita" class="control-label">Annualità <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
		           <?php 
			           $years = range(date('Y')-3, date('Y')+1);
			           echo form_dropdown('annualita', array_combine($years, $years) , date('Y'), $attr); 
			       ?>
				</div>
				<div class="col-sm-4">
					<label for="importo" class="control-label">Importo <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span></label>
					<input type="text" name="importo" class="form-control validate[required]"/>
				</div>
				<div class="col-sm-4 text-right">
					<input type="hidden" value="<?php echo $comune; ?>" name="comune"/><br>
					<button <?php if (empty($comune)) echo 'disabled'; ?> type="submit" name="submit" id="valida" class="btn btn-primary" value="economie"><span class="icon icon-plus"></span> Aggiungi</button>
				</div>
			</div>
			<?php 
			   echo form_close(); 
			?> 
		</div>
		</div>
	</div>
</div>
	    
		