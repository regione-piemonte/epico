<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<!--<div id="myAffix" data-spy="affix" data-offset-top="300">-->
<script type="text/javascript" src="/servizi/epico/assets/js/epico.js"></script>
<script type="text/javascript">
   $(document).ready(function(){
      $('#alloggi_edificio').autoNumeric({aSep: '', aDec: null, aPad: false, wEmpty: 'empty'});
      $('#superficie').autoNumeric({aSep: '', aDec: null, aPad: false, wEmpty: 'empty'});
      $('#costo_mq').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty', vMax: 524});
      $('#iva_stimata').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty'});
      $('#costo_intervento').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty'});
      $('#costo_totale').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty'});

      $('#superficie, #costo_mq, #iva_stimata').change(function(){
         var costo = $('#superficie').autoNumeric('get') * $('#costo_mq').autoNumeric('get');
         var iva = $('#iva_stimata').autoNumeric('get');
         var tot = parseInt(costo) + parseInt(iva);
         $('#costo_intervento').autoNumeric('set', costo);
         $('#costo_totale').autoNumeric('set', tot);
      });                         
   });
</script>

    <div class="container">
     <div class="row"><div class="col-md-12">

<?php $this->load->view('include/menu_edilizia', $_ci_data['_ci_vars']);?>
      <h3>
         <?php if ($_SESSION['tipo_domanda_edilizia']):?>
            Lettera <?php echo strtoupper($_SESSION['tipo_domanda_edilizia']); ?>)
         <?php endif; ?>
     </h3>

     <div class="alert alert-info col-sm-5">
      <p>Stai operando come: <strong>
               <?php
      if ($_SESSION['atc']){
         echo $_SESSION['atc'];
      } elseif ($usertype == 'COM') {
        echo 'Comune di '.$comune_utente->DESCRIZIONE;
      } elseif ($usertype == 'REG') {
         echo 'Regione';
      } elseif ($usertype == 'SUP') {
         echo 'Superuser';
      }
       ?></strong></p>
        </div>
          <div class="col-sm-7">
          <div class="pull-right">   <!-- form-group puls-group-->
          
          <?php  if ($usertype != 'REG') { ?>
           <a href="<?php echo base_url().'index.php/edilizia/domanda/?tipo_domanda_edilizia='.$_SESSION['tipo_domanda_edilizia']; ?>" class="btn btn-primary btn-lg"><span class="icon icon-plus"></span> nuova domanda</a>
           <?php } ?>

           <a href="<?php echo base_url().'index.php/edilizia/elenco/?tipo_domanda_edilizia='.$_SESSION['tipo_domanda_edilizia']; ?>" class="btn btn-primary btn-lg"><span class="icon icon-list"></span> elenco domande</a>
          </div>

          </div>
      </div></div>
    </div>

 <div class="container fixedScroll">
 
 
    <h3>Domanda  <?php echo $ID_DOMANDA; ?> </h3>
      <?php if ($msg=='validata'): ?>
       <div class="alert alert-success">
                <p>La domanda è stata validata correttamente</p>
          </div>
       <?php endif; ?>
                                   
     <div class="panel panel-default">
      <div class="panel-heading">
         <h4 class="panel-title"> Localizzazione alloggio  </h4>
      </div>

         <div class="panel-body">
            <ul>
               <li>Indirizzo: <?php echo $INDIRIZZO; ?> <?php echo $CIVICO; ?></li>
               <li>CAP: <?php echo $CAP; ?></li>
               <li>Prov.:  <?php echo $SIGLA_PROV; ?></li>
               <li>Comune:  <?php echo $DESCR_COMUNE; ?></li>
               <li>Scala:  <?php echo $SCALA; ?></li>
               <li>Piano fuori terra:  <?php echo $PIANO_FT; ?></li>
               <li>Foglio:  <?php echo $FOGLIO; ?></li>
               <li>Particella:  <?php echo $PARTICELLA; ?></li>
               <li>Subalterno:  <?php echo $SUBALTERNO; ?></li>
               <li>Propriet&agrave; alloggio:  <?php echo $PROPRIETA; ?></li>
               <li>Anno di costruzione / ristrutturazione integrale dell'alloggio:  <?php echo $ANNO_RISTRUTTURAZIONE; ?></li>
               <li>Numero alloggi sfitti per edificio:  <?php echo $ALLOGGI_SFITTI; ?></li>
               <li>Cronoprogramma - giorni previsti:  <?php echo $GIORNI_PREVISTI; ?></li>
        </div>
        <!--/.panel-body -->

        </div>
        <!-- /.panel.panel-default -->
                                  

<div class="panel panel-default">
 <div class="panel-heading">
    <h4 class="panel-title"> Interventi di manutenzione </h4>
 </div>

    <div class="panel-body">
      <ul>
         <li>Presenza di una graduatoria vigente da cui attingere gli assegnatari: <?php echo ($GRADUATORIA) ? 'Si' : 'No';?></li>
          <?php
              foreach ($interventi as $intervento):
         ?>
         <li>
         <?php
               echo $intervento->descrizione . ': ';
               echo ($intervento->attivo) ? 'Si' : 'No';
         ?>
         </li>
         <?php
            endforeach;
         ?>
          <li><strong>Punteggio totale: <?php echo $PUNTEGGIO;?></strong></li>
         <li>Superficie complessiva dell'alloggio (mq): <?php echo $SUPERFICIE;?></li>
         <li>Costo al mq: <?php echo money_format('%.2n', $COSTO_MQ);?></li>
         <li>Costo dell'intervento: <?php echo money_format('%.2n', $COSTO_INTERVENTO);?></li>
         <li>IVA stimata: <?php echo money_format('%.2n', $IVA_STIMATA);?></label>
         <li><strong>Costo complessivo dell'intervento: <?php echo money_format('%.2n', $COSTO_TOTALE);?></strong></li>
      </ul>

         </div>
         <!--/.panel-body -->

         </div>
         <!-- /.panel.panel-default -->

  </div><!--panel-group-->

  </div><!--/container-->
