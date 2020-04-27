<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<script type="text/javascript">
	$(document).ready(function(){
    $("#sceltacomune").change(function () {
      if ( $(this).val() ) {
          $(".buttonscelta").prop('disabled', false);
      } else {
        $(".buttonscelta").prop('disabled', true);
      }
    });
  });
</script>

<?php
      $attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'index', 'method'=>'post');
      echo form_open('domande/',$attributes);
    ?>

  <div class="container">
     <div class="row"><div class="col-md-12">
<h3>Gestione domande relative ai bandi dell'edilizia sociale</h3>
       <?php if ($msg): ?>
        <div class="alert alert-info">
              <p>E' necessario scegliere un comune per cui operare</p>
        </div>
   <?php endif;  ?>
       <div class="form-group">
         <label for="comune" class="col-sm-4 control-label">Scegli il comune su cui operare <span class="glyphicon-asterisk" title="Inserimento obbligatorio"><strong>[Obbligatorio]</strong></span> </label>
         <div class="col-sm-4">
           <?php
            $attr='id="sceltacomune" class="form-control"';
            echo form_dropdown('comune', $COMUNI, '',$attr);
       ?>
         </div>
         <div class="col-sm-4">
           <!--<button id="btnsceltacomune" class="btn btn-primary" >imposta</button>-->
         </div>
       </div>
       <!--/form-group-->
  <div class="panel panel-default">
         <div class="panel-heading">
           <h4 class="panel-title">Agenzie Sociali per la Locazione</h4>
         </div>
         <div class="panel-body">

         <p>Modulo per la richiesta di contributi finalizzati a favorire la mobilità abitativa dei cittadini residenti nei Comuni ad alta tensione abitativa e nei Comuni con più di 15.000 abitanti attraverso nuovi contratti di locazione a canone concordato. </p>


         <div class="form-group puls-group pull-right">
          <div class="col-sm-12">
            <button class="btn btn-primary buttonscelta" disabled="disabled" name="tipo_domanda" type="submit" value="2">Accedi</button>
          </div>
        </div>

         </div>
       </div>
       <!-- /panel panel-default-->


       <div class="panel panel-default">
         <div class="panel-heading">
           <h4 class="panel-title">Fondo per la Morosit&agrave; Incolpevole</h4>
         </div>
         <div class="panel-body">

         <p>Modulo per la richiesta di contributi destinati ai cittadini residenti nei Comuni ad alta tensione abitativa sottoposti a sfratto per morosità non volontaria</p>


         <div class="form-group puls-group pull-right">
          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary buttonscelta" disabled="disabled" name="tipo_domanda" value="1">Accedi</button>
          </div>
        </div>

         </div>
       </div>
       <!-- /panel panel-default-->


       <div class="panel panel-default">
         <div class="panel-heading">
           <h4 class="panel-title">Fondo sostegno alla locazione</h4>
         </div>
         <div class="panel-body">

         <p>Modulo per la richiesta di contributi finalizzati a ristorare la spesa del canone di locazione corrisposto dai cittadini, conduttori di alloggi di edilizia privata.
		 </p>


         <div class="form-group puls-group pull-right">
          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary" disabled="disabled">Accedi</button>
          </div>
        </div>

         </div>
       </div>
       <!-- /panel panel-default-->
		<?php
				echo form_close('</div></div></div>');

		?>
