<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<script type="text/javascript" src="/servizi/epico/assets/js/epico.js"></script>
<script type="text/javascript">
   $(document).ready(function(){
      $('#punteggio').autoNumeric({aSign: '', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty'});
      $('#alloggi_edificio').autoNumeric({aSep: '', aDec: null, aPad: false, wEmpty: 'empty'});
      $('#alloggi_coinvolti').autoNumeric({aSep: '', aDec: null, aPad: false, wEmpty: 'empty'});
      $('#superficie').autoNumeric({aSep: '', aDec: null, aPad: false, wEmpty: 'empty'});           
      $('#costo_mq').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty'});
      $('#risorse_aggiuntive').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty'});
      $('#iva_stimata').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty'});
      $('#costo_intervento').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty'});
      $('#costo_totale').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty'});
      $('#costo_alloggio').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty'});
      $('#costo_ammissibile').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ',', wEmpty: 'empty'});
      
      $('#modalita').change(function(){
            if($(this).val() == 1){
               $('#alloggi_edificio').val('').prop('disabled', true);
               $('#scala').prop('disabled', false);
               $('#piano').prop('disabled', false);
               $('#subalterno').prop('disabled', false);
            }
            if($(this).val() == 2){
               $('#alloggi_edificio').prop('disabled', false);
               $('#scala').val('').prop('disabled', true);
               $('#piano').val('').prop('disabled', true);
               $('#subalterno').val('').prop('disabled', true);
            }
      });

      $('#superficie, #costo_mq, #iva_stimata, #alloggi_coinvolti, #risorse_aggiuntive').change(function(){
         var tipo_domanda = $('#tipo_domanda').val();
         var tipo_intervento = $('#tipo_intervento').val();
         var massimale_mq = (tipo_intervento == 2) ? '' : '';
         var superficie = $('#superficie').autoNumeric('get');
         var costo_mq = $('#costo_mq').autoNumeric('get');
         var iva = $('#iva_stimata').autoNumeric('get');
         if (costo_mq > massimale_mq){
            alert('Massimale costo al mq: ' + massimale_mq + '');
            costo_mq = '';
            $('#costo_mq').val(costo_mq);
         }
         var costo = superficie * costo_mq;
         var tot = parseFloat(costo) + parseFloat(iva);
         $('#costo_intervento').autoNumeric('set', costo);
         if (tipo_domanda == 'a' && tot > 14999.99) {
            alert('Massimale costo complessivo: ');
            tot = '';
            $('#costo_totale').val(tot);
         } else $('#costo_totale').autoNumeric('set', tot);
         var alloggi = $('#alloggi_coinvolti').autoNumeric('get');
         if (alloggi > 0){
            var risorse = $('#risorse_aggiuntive').autoNumeric('get');
            if (risorse == '') risorse = 0;
            var costo_alloggio = tot / alloggi;
            if (costo_alloggio > 0){
               var costo_ammissibile = costo_alloggio - risorse;
               $('#costo_alloggio').autoNumeric('set', costo_alloggio);
               if (costo_ammissibile > 50000) {
                  alert('Massimale costo ammissibile: ');
                  costo_ammissibile = '';
                  $('#costo_ammissibile').val(costo_ammissibile);
               } else $('#costo_ammissibile').autoNumeric('set', costo_ammissibile);
            }
         }
      });
   });
</script>

 <?php
       $attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'form_domanda');
        echo form_open('edilizia/submit',$attributes);
?>
    <div class="container">
     <div class="row"><div class="col-md-12">

<?php $this->load->view('include/menu_edilizia', $_ci_data['_ci_vars']);?>
      <h3>
         <?php if ($_SESSION['tipo_domanda_edilizia']):?>
            Lettera <?php echo strtoupper($_SESSION['tipo_domanda_edilizia']); ?>)
         <?php endif; ?>
     </h3>
      <?php  if ($ID): ?>
        <h4>Modifica domanda</h4>
      <?php else: ?>
        <h4>Inserisci domanda</h4>
      <?php endif; ?>

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
          <div class="pull-right"> 
  
           <a href="<?php echo base_url().'index.php/edilizia/domanda/?tipo_domanda_edilizia='.$_SESSION['tipo_domanda_edilizia']; ?>" class="btn btn-primary btn-lg"><span class="icon icon-plus"></span> nuova domanda</a>
              
           <a href="<?php echo base_url().'index.php/edilizia/elenco/?tipo_domanda_edilizia='.$_SESSION['tipo_domanda_edilizia']; ?>" class="btn btn-primary btn-lg"><span class="icon icon-list"></span> elenco domande</a>
          </div>

          </div>
      </div></div>
    </div>

 <div class="container fixedScroll">
  <?php if ($msg=='bozzaok'): ?>
   <?php elseif ($msg=='giavalidata'):  ?>
      <div class="alert alert-info">
              <p>La domanda è già stata validata</p>
        </div>
  <?php endif; ?>
    <?php if ($ID): ?>
    <div class="detail pull-right">
    Domanda numero: <?php echo $ID; ?>
     </div>
      <?php endif; ?>
     <p><em>Tutti i campi sono obbligatori.</em></p>

     <?php if($TIPO_DOMANDA == 'b'): ?>
        <div class="panel panel-default">
          <div class="panel-heading">
         <h4 class="panel-title">Informazioni generali</h4>
       </div>

         <div class="panel-body">
              <div class="form-group">
               <label for="mod_intervento" class="col-sm-2 control-label">Oggetto d'intervento </label>
               <div  class="col-sm-4">
                  <?php
                  $attr='id="modalita" class="form-control validate[required]"';
                      echo form_dropdown('modalita', $modalita, $MODALITA, $attr);
                   ?>
               </div>


              <label for="alloggi_edificio" class="col-sm-4 control-label">Numero alloggi dell'immobile</label>
               <div class="col-sm-2">
                 <input type="text" class="form-control validate[required]" <?php if($MODALITA != 2) echo 'disabled="disabled"'; ?> value="<?php echo set_value('ALLOGGI_EDIFICIO',$ALLOGGI_EDIFICIO); ?>" name="alloggi_edificio" id="alloggi_edificio" placeholder="" />
               </div>

             </div>
             <!--/form-group-->

         </div>
     </div>

   <?php endif; ?>

     <div class="panel panel-default">
      <div class="panel-heading">
         <h4 class="panel-title"> Localizzazione alloggio  </h4>
      </div>

         <div class="panel-body">
            <input type="hidden" id="id" name="id" value="<?php echo set_value('id',$ID); ?>" />
            <input type="hidden" id="tipo_domanda" name="tipo_domanda" value="<?php echo set_value('TIPO_DOMANDA',$TIPO_DOMANDA); ?>" />
            <input type="hidden" id="data_domanda" name="data_domanda" value="<?php echo set_value('DATA_DOMANDA',$DATA_DOMANDA); ?>" />
             <div class="form-group">
               <label for="indirizzo_residenza" class="col-sm-2 control-label">Indirizzo </label>
               <div class="col-sm-8">
                 <input type="text" class="form-control validate[required]" id="indirizzo_residenza"  value="<?php echo set_value('INDIRIZZO',$INDIRIZZO);?>" name="indirizzo" placeholder="" />
               </div>
               <div class="col-sm-2">
                 <label for="civico_residenza">
                 <input type="text" class="form-control validate[required]" id="civico_residenza" value="<?php echo set_value('CIVICO',$CIVICO);?>" name="civico" placeholder="Nr civico" />
                 </label>
               </div>
             </div>
             <!--/form-group-->
             <div class="form-group">
              <label for="CAP" class="col-sm-2 control-label ">CAP</label>
              <div class="col-sm-2">
                 <input type="text" class="form-control validate[required,custom[onlyNumber]]" value="<?php echo set_value('CAP',$CAP);?>" name="cap" id="cap" placeholder="CAP" />
              </div>
              <label for="provincia_residenza" class="col-sm-1 control-label">Prov.</label>
              <div  class="col-sm-2">
                 <div id="js-option_1" class="js-disabled">
                 <?php
                 //print_r($province);
                 $attr=' id="provincia_residenza"  class="form-control validate[required]" ';
                 echo form_dropdown('provincia', $province, $COD_PROVINCIA, $attr);
                 ?>
                 </div>
                 <!--/js-option-->
              </div>
              <label for="comune_residenza" class="col-sm-2 control-label">Comune</label>
              <div class="col-sm-3">
                 <div class="ui-widget">
                 <?php
                 $attr='id="comune_residenza" class="form-control validate[required]"';
                      echo form_dropdown('comune', $comuni, $COMUNE, $attr);
                 ?>
                 </div>
              </div>
             </div>
             <!--/form-group-->
             <div class="form-group">
              <label for="scala" class="col-sm-2 control-label">Scala</label>
               <div class="col-sm-2">
                 <input type="text" class="form-control validate[required]" <?php if($MODALITA == 2) echo 'disabled="disabled"'; ?> value="<?php echo set_value('SCALA',$SCALA);?>" name="scala" id="scala" placeholder="" />
               </div>


                 <label for="piano" class="col-sm-3 control-label">Piano fuori terra
                    <div class="popover-control">
                    <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
                    <em class="popover-text">
                    es. piano terra = 1; piano primo = 2
                    </em>
                    </div>
                 </label>
               <div class="col-sm-2">
                 <input type="text" class="form-control validate[required,custom[onlyNumber]]" <?php if($MODALITA == 2) echo 'disabled="disabled"'; ?> value="<?php echo set_value('PIANO_FT',$PIANO_FT);?>" name="piano_ft" id="piano" />
               </div>
               </div>
         <!--/form-group-->

        <div class="form-group">
        <label for="estremi_catastali_foglio" class="col-sm-2 control-label">Estremi catastali identificativi dell'unit&agrave; immobiliare</label>
        <div class="col-sm-3">
        <label>Foglio:</label> <input type="text" class="form-control validate[required]" id="foglio" value="<?php echo set_value('FOGLIO',$FOGLIO);?>" name="foglio" placeholder="" />
        </div>

        <div class="col-sm-3">
        <label>Particella</label>: <input type="text" class="form-control validate[required]" id="particella" value="<?php echo set_value('PARTICELLA',$PARTICELLA);?>" name="particella" placeholder="" />
        </div>
        <div class="col-sm-3">
        <label>Subalterno:</label> <input type="text" class="form-control validate[required]" id="subalterno" <?php if($MODALITA == 2) echo 'disabled="disabled"'; ?> value="<?php echo set_value('SUBALTERNO',$SUBALTERNO);?>" name="subalterno" placeholder="" />
        </div>
        </div>

        <!--/form-group-->

        <div class="form-group">
        <label for="Proprieta_alloggio" class="col-sm-2 control-label">Propriet&agrave; alloggio</label>
        <div class="col-sm-4">
           <?php
           $attr='id="proprieta_alloggio" class="form-control validate[required]"';
               echo form_dropdown('proprieta', $proprietari, $PROPRIETA, $attr);
            ?>
        </div>
        <?php if($TIPO_DOMANDA == 'b'): ?>
             <label for="quote_proprieta" class="col-sm-2 control-label">Quote di propriet&agrave; detenuta</label>
             <div class="col-sm-4">
             <?php
             $attr='id="quote_proprieta" class="form-control validate[required]"';
                 echo form_dropdown('quote_proprieta', $quote_proprieta, $QUOTE_PROPRIETA, $attr);
             ?>
             </div>
        <?php endif; ?>
        </div>

        <!--/form-group-->

        <div class="form-group">
        <label for="anno_ristrutturazione" class="col-sm-4">Anno di costruzione / ristrutturazione integrale dell'alloggio</label>
        <div class="col-sm-2">
        <input type="text" class="form-control validate[required,custom[onlyNumber]]" name="anno_ristrutturazione" id="anno_ristrutturazione" value="<?php echo set_value('ANNO_RISTRUTTURAZIONE',$ANNO_RISTRUTTURAZIONE);?>" placeholder="aaaa" />
        </div>

          <label for="numero_alloggi" class="col-sm-4 control-label">Numero alloggi sfitti per edificio</label>
           <div class="col-sm-2">
             <input type="text" class="form-control validate[required,custom[onlyNumber]]" value="<?php echo set_value('ALLOGGI_SFITTI',$ALLOGGI_SFITTI);?>" name="alloggi_sfitti" id="alloggi_sfitti" placeholder="" />
           </div>
        </div>
         <!--/form-group-->
        </div>
        <!--/.panel-body -->

        </div>
        <!-- /.panel.panel-default -->


<div class="panel panel-default">
 <div class="panel-heading">
    <h4 class="panel-title"> Interventi di manutenzione </h4>
 </div>

    <div class="panel-body">
 <h4>PUNTEGGIO</h4>

    <div class="form-group form-group-check">
           <div class="col-sm-6">
            <label>Presenza di una graduatoria vigente da cui attingere gli assegnatari
            <?php if($TIPO_DOMANDA == 'b') echo '(solo per interventi su alloggi sfitti)'; ?>
            </label>
           </div>

           <div class="col-sm-6">
            <label for="graduatoria_si" class="checkbox-inline">
               <?php
               $attributes = 'id="graduatoria_si" class="from-control validate[required]"';
               echo form_radio('graduatoria', 1, ($GRADUATORIA === "1"),$attributes); ?> Si
            </label>
            <label for="graduatoria_no" class="checkbox-inline">
              <?php
              $attributes = 'id="graduatoria_no" class="from-control validate[required]"';
              echo form_radio('graduatoria', 0, ($GRADUATORIA === "0"),$attributes); ?> No
           </label>
          </div>
     </div><!--/form-group-check-->

<?php if($TIPO_DOMANDA == 'b'): ?>
   <div class="form-group form-group-check">
          <div class="col-sm-6">
           <label>Intervento relativo ad alloggio sfitto per mancanza di manutenzione</label>
          </div>

          <div class="col-sm-6">
           <label for="manutenzione_si" class="checkbox-inline">
              <?php
              $attributes = 'id="manutenzione_si" class="from-control validate[required]"';
              echo form_radio('manutenzione', 1, ($MANUTENZIONE === "1"),$attributes); ?> Si
           </label>
           <label for="manutenzione_no" class="checkbox-inline">
             <?php
             $attributes = 'id="manutenzione_no" class="from-control validate[required]"';
             echo form_radio('manutenzione', 0, ($MANUTENZIONE === "0"),$attributes); ?> No
          </label>
         </div>
    </div><!--/form-group-check-->

    <div class="form-group form-group-check">
          <div class="col-sm-6">
           <label>Intervento collegato a interventi proposti sulla lettera a) del presente programma di recupero</label>
          </div>

          <div class="col-sm-6">
           <label for="collegata_si" class="checkbox-inline">
              <?php
              $attributes = 'id="collegata_si" class="from-control validate[required]"';
              echo form_radio('collegata', 1, ($COLLEGATA === "1"),$attributes); ?> Si
           </label>
           <label for="collegata_no" class="checkbox-inline">
             <?php
             $attributes = 'id="collegata_no" class="from-control validate[required]"';
             echo form_radio('collegata', 0, ($COLLEGATA === "0"),$attributes); ?> No
          </label>
         </div>
    </div><!--/form-group-check-->

<?php endif; ?>


<h4>Tipologia degli interventi</h4>

 <div class="form-group form-group-check">

          <?php
              foreach ($interventi as $intervento):
         ?>
         <div class="col-sm-12 checkbox">
            <label>
         <?php
                 echo form_checkbox('interventi[]', $intervento->id, $intervento->attivo, '') . $intervento->descrizione;
         ?>
            </label>
         </div>
          <?php
              endforeach;
          ?>
         </div><!--/form-group-check-->


         <div class="form-group">
         <label for="punteggio_totale" class="col-sm-2 control-label">Punteggio totale</label>
         <div class="col-sm-3">
            <input type="text" class="form-control" value="<?php echo set_value('PUNTEGGIO',$PUNTEGGIO);?>" name="punteggio" id="punteggio" disabled="disabled" />
         </div>
         </div><!--/form-group-->



         <h4>COSTI</h4>

         <?php if($TIPO_DOMANDA == 'b'): ?>
            <div class="form-group" style="margin-bottom: 25px;">
               <label for="tipo_intervento" class="col-sm-2 control-label">Tipo di intervento</label>
               <div class="col-sm-4">
               <?php
               $attr='id="tipo_intervento" class="form-control validate[required]"';
                  echo form_dropdown('tipo_intervento', $tipo_intervento, $TIPO_INTERVENTO, $attr);
               ?>
               </div>
            </div>
         <?php endif; ?>

         <div class="form-group">
         <label for="superficie" class="col-sm-2 control-label">Superficie complessiva dell'alloggio (mq)
         <div class="popover-control">
         <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
         <em class="popover-text">
         Se alloggio ≤ Superficie utile * 1,25
         <br>
         Se immobile ≤ Superficie utile * 1,54
         </em>
         </div>
         </label>
         <div class="col-sm-4">
         <input type="text" class="form-control  validate[required]"  id="superficie" value="<?php echo set_value('SUPERFICIE',$SUPERFICIE);?>" name="superficie" />
         </div>
         <label for="costo_mq" class="col-sm-2 control-label">Costo al mq
            <div class="popover-control">
            <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
            <em class="popover-text">
            <?php
            if($TIPO_DOMANDA == 'b') echo 'Massimale recupero 1573€; manutenzione 524€';
            else echo 'Massimale x €';
            ?>
            </em>
            </div>
         </label>
         <div class="col-sm-4">
         <input type="text" class="form-control validate[required]" id="costo_mq" value="<?php echo set_value('COSTO_MQ',$COSTO_MQ);?>" name="costo_mq" />
         </div>
         </div><!--/form-group-->

         <div class="form-group">

         <label for="costo_intervento" class="col-sm-2 control-label">Costo dell'intervento</label>
         <div class="col-sm-2">
         <input type="text" class="form-control validate[required]" id="costo_intervento" value="<?php echo set_value('COSTO_INTERVENTO',$COSTO_INTERVENTO);?>" name="costo_intervento"  readonly="readonly" />

         </div>

         <label for="IVA_stimata" class="col-sm-2 control-label">IVA stimata</label>
         <div class="col-sm-2">
         <input type="text" class="form-control validate[required]" id="iva_stimata" value="<?php echo set_value('IVA_STIMATA',$IVA_STIMATA);?>" name="iva_stimata" />
         </div>


         <label for="costo_comlessivo" class="col-sm-2 control-label">Costo complessivo dell'intervento</label>
         <div class="col-sm-2">
         <input type="text" class="form-control validate[required]" id="costo_totale" value="<?php echo set_value('COSTO_TOTALE',$COSTO_TOTALE);?>" name="costo_totale"  readonly="readonly" />

         </div>
         </div><!--/form-group-->

         <?php if($TIPO_DOMANDA == 'b'): ?>
         <div class="form-group">


               <div class="col-sm-3">
                 <label for="alloggi_coinvolti">Numero alloggi coinvolti nell'intervento</label>
                 <input type="text" name="alloggi_coinvolti" id="alloggi_coinvolti" value="<?php echo set_value('ALLOGGI_COINVOLTI',$ALLOGGI_COINVOLTI);?>" class="form-control validate[required,custom[onlyNumber]]">
               </div>

               <div class="col-sm-3">
                 <label for="costo_alloggio"><br>Costo per alloggio</label>
                 <input type="text" name="costo_alloggio" id="costo_alloggio" value="<?php echo set_value('COSTO_ALLOGGIO',$COSTO_ALLOGGIO);?>" class="form-control validate[required]" readonly="readonly" >
               </div>

                <div class="col-sm-3">
                 <label for="risorse_aggiuntive">Risorse aggiuntive al finanziamento statale</label>
                 <input type="text" name="risorse_aggiuntive" id="risorse_aggiuntive" value="<?php echo set_value('RISORSE_AGGIUNTIVE',$RISORSE_AGGIUNTIVE);?>" class="form-control validate[required]">
               </div>

               <div class="col-sm-3">
                 <label for="costo_ammissibile">Costo ammissibile a finanziamento per alloggio</label>
                 <input type="text" name="costo_ammissibile" id="costo_ammissibile" value="<?php echo set_value('COSTO_AMMISSIBILE',$COSTO_AMMISSIBILE);?>" class="form-control validate[required]" readonly="readonly">
               </div>
         </div><!--/form-group-->
      <?php endif; ?>


         </div>
         <!--/.panel-body -->

         </div>
         <!-- /.panel.panel-default -->

   <?php if($TIPO_DOMANDA == 'b'): ?>
         <div class="panel panel-default">
          <div class="panel-heading">
             <h4 class="panel-title"> Cronoprogramma </h4>
          </div>

             <div class="panel-body">

                <div class="form-group">
                   <label for="giorni_previsti" class="col-sm-4 control-label">Giorni previsti per l'esecuzione dei lavori</label>
                   <div class="col-sm-2">
                   <input type="text" class="form-control validate[required,custom[onlyNumber]]" id="giorni_previsti" value="<?php echo set_value('GIORNI_PREVISTI',$GIORNI_PREVISTI);?>" name="giorni_previsti" />

                   </div>
                </div>
            </div>
            <!--/.panel-body -->

         </div>
         <!-- /.panel.panel-default -->
   <?php endif; ?>


   <div class="form-group puls-group">
          <div class="col-sm-12">
        <?php if ($STATO_DOMANDA=='1'): ?>
        <button type="submit" name="submit" id="valida" class="btn btn-primary btn-lg" value="valida" <?php if($vincoli->VALIDABILE != 1) echo 'disabled="disabled"'?> ><span class="icon icon-ok"></span> Valida</button>
        <?php endif; ?>
            <button type="submit" name="submit" id="btnsalvabozza" class="btn btn-primary btn-lg" value="salvabozza" ><span class="icon icon-save"></span> Salva Bozza</button>
			<button type="button" id="btnstampa" class="btn btn-primary btn-lg" value="stampa"><span class="icon icon-print"></span> Stampa</button>
          </div>
        </div><!--/puls-group-->

        <div class="print-only data-firma">
           <label>Data: <input type="text"/></label><br/>
           <label>Firma: <textarea class="firma"></textarea></label>
        </div>

  </div><!--panel-group-->


  </div><!--/container-->
</form>
