
<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<script type="text/javascript" src="/servizi/epico/assets/js/epico.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    if ($("#tipo_godimento_abitazione").val() == 1) {  
            $("#tipo_contratto_atto").prop("disabled", false);
            $("#durata_contratto").prop("disabled", false);
            $("#data_contratto").prop("disabled", false);
            $("#ammontare_atto").prop("disabled", false);
          } else {
            $("#tipo_contratto_atto").prop("disabled", true);
            $("#durata_contratto").prop("disabled", true);
            $("#data_contratto").prop("disabled", true);
            $("#ammontare_atto").prop("disabled", true);
     }
        $("#tipo_godimento_abitazione").change(function(){
              if ($(this).val() == 1) {              
                $("#tipo_contratto_atto").prop("disabled", false);
                $("#durata_contratto").prop("disabled", false);
                $("#data_contratto").prop("disabled", false);
                $("#ammontare_atto").prop("disabled", false);
              } else {
                  $("#tipo_contratto_atto").prop("disabled", true);
                  $("#durata_contratto").prop("disabled", true);
                  $("#data_contratto").prop("disabled", true);          
                  $("#ammontare_atto").prop("disabled", true);
                  $("#tipo_contratto_atto").val("");
                  $("#durata_contratto").val("");
                  $("#data_contratto").val("");
                  $("#ammontare_atto").val("");
              }
      	}); 
});          
</script>
 <?php 
       $attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'form_domanda');
        echo form_open('domande/submit',$attributes); 
?>                
    <div class="container">
     <div class="row"><div class="col-md-12">
     
<?php $this->load->view('include/menu', $_ci_data['_ci_vars']);?>    
      <h3>Agenzie sociali per la locazione</h3>
      <?php  if ($ID): ?>
        <h4 class="no-print">Modifica domanda</h4>
      <?php else: ?>
        <h4 class="no-print">Inserisci domanda</h4>      
      <?php endif; ?>
        
     <div class="alert alert-info col-sm-5">
      <p class="no-print">Stai operando come: <strong>
               <?php 
      if ($usertype == 'COM') {
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
           <a href="<?php echo base_url().'index.php/domande/domanda/?tipo_domanda=2'; ?>" class="btn btn-primary btn-lg"><span class="icon icon-plus"></span> nuova domanda</a>
             <a href="<?php echo base_url().'index.php/domande/elenco/?tipo_domanda=2'; ?>" class="btn btn-primary btn-lg"><span class="icon icon-list"></span> elenco domande</a>
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
 

     <?php if ($DATA_SALVATAGGIO_BOZZA): ?>
    <div class="detail pull-right">
    Domanda numero: <?php echo $ID; ?> - Ultimo salvataggio BOZZA il <?php echo date("d/m/Y H:i:s",strtotime($DATA_SALVATAGGIO_BOZZA)); ?> 
     </div>
      <?php endif; ?> 
     <p><em>Tutti i campi sono obbligatori.</em></p>
     
    <div class="panel-group"> 
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title"> <a href="#tab1" data-toggle="collapse"> Nucleo richiedente </a> </h4>
        </div>
        <div id="tab1" class="panel-collapse collapse in">
          <div class="panel-body">

                <h4>Dati anagrafici del richiedente</h4>
     <input type="hidden" id="id" name="id" value="<?php echo set_value('id',$ID); ?>" />
     <input type="hidden" id="tipo_domanda" name="tipo_domanda" value="<?php echo set_value('id',$TIPO_DOMANDA); ?>" />      
    <div class="form-group">
      <label for="nome" class="col-sm-2 control-label">Nome 
      </label>
      <div class="col-sm-4">
        <input type="text" class="form-control validate[required] clsbozza" id="nome" name="nome" placeholder="Nome" value="<?php echo set_value('nome',$NOME); ?>" />        
      </div>
      <label for="cognome" class="col-sm-2 control-label">Cognome </label>
      <div class="col-sm-4">
        <input type="text" class="form-control validate[required]" id="cognome" name="cognome" placeholder="Cognome" value="<?php echo set_value('cognome',$COGNOME); ?>" />
      </div>                          
    </div><!--/form-group-->
    <div class="form-group">    
      <label for="codicefiscale" class="col-sm-2 control-label">Codice fiscale o partita IVA</label>
      <div class="col-sm-4">
        <input type="text" class="form-control validate[required]" id="codicefiscale" value="<?php echo set_value('codicefiscale',$CODICE_FISCALE); ?>" name="codicefiscale" placeholder="Codice fiscale o Partita IVA" />
      </div>      
      <label for="datanascita" class="col-sm-2 control-label">Data di nascita 
      </label>
      <div class="col-sm-4 calendar-control">
        <input type="text" class="form-control  validate[required] calendarioCustom" value="<?php if ($DATA_NASCITA) {echo set_value('datanascita',date('d/m/Y',strtotime($DATA_NASCITA)));} ?>" id="datanascita" name="datanascita" placeholder="gg/mm/aaaa" />      
      </div>
    </div><!--/form-group-->
    <div class="form-group">
      <label for="titolostudio" class="col-sm-2 control-label">Titolo di studio </label>
      <div class="col-sm-4">     
      <?php
            $attr='id="titolostudio" class="form-control"';                    
            echo form_dropdown('titolostudio', $titoli_studio, $TITOLO_STUDIO,$attr); 
       ?>  

      </div>
     <label class="col-sm-2 control-label">Cittadinanza</label>
               <div class="col-sm-4">
               
               
<label for="cittadinanza-ue" class="checkbox-inline">
  <?php 
  $attributes = 'id="cittadinanza-ue"'; 
  echo form_radio('cittadinanza', 'itue', ($CITTADINANZA =='itue' ? '1' : '0'),$attributes); ?> Italiana/UE
</label>

<label for="cittadinanza-extra"  class="checkbox-inline">
  <?php
  $attributes = 'id="cittadinanza-extra"';  
  echo form_radio('cittadinanza', 'exue', ($CITTADINANZA =='exue' ? '1' : '0'),$attributes); ?> Extra UE
</label>
        
            
                </div>       
    </div><!--/form-group-->
    
    <!--INIZIO NUCLEO FAMILIARE-->   
       
   <h4>Nucleo familiare</h4>
  <div class="form-table">         
    <table id="table_nucleo" class="table table-hover table-striped table-bordered">
  <thead>
  <tr>
    <th>nome </th>
    <th>cognome</th>
    <th>codice fiscale</th>
    <th>data nascita</th>
    <th>parentela</th>
    <th class="empty">&nbsp;</th>
  </tr>
  </thead>
   <tbody>
  <?php foreach ($nuclei as &$nucleo): ?>  
  <tr id="record_nucleo">                                                           
    <td>
       <input type="hidden" value="<? echo $nucleo->ID; ?>" name="nucleo_id[]" />
       <input type="text" class="form-control" id="nucleo_nome" value="<?php echo set_value('codicefiscale',$nucleo->NOME); ?>" name="nucleo_nome[]" placeholder="Nome" />
     </td>
    <td>
    <input type="text" class="form-control " id="nucleo_cognome" value="<?php echo $nucleo->COGNOME; ?>" name="nucleo_cognome[]" placeholder="Cognome" />
    </td>
    <td>
    <input type="text" maxlength="16" class="form-control" value="<?php echo $nucleo->CODICE_FISCALE; ?>" id="nucleo_codice_fiscale" name="nucleo_codice_fiscale[]" placeholder="Codice fiscale o Partita IVA" />
    </td>                                                                                                      
    <td class="">
      <div id="datepicker" class="input-daterange"><input type="text" class="form-control calendarioCustom" id="nucleo_data_nascita" value="<?php if ($nucleo->DATA_NASCITA) {echo set_value('datanascita',date('d/m/Y',strtotime($nucleo->DATA_NASCITA)));} ?>" name="nucleo_data_nascita[]" placeholder="Data nascita" /></div>
    </td>
    <td>
           
       <?php
            $attr='id="parentela" class="form-control"';                    
            echo form_dropdown('nucleo_parentela[]', $parentele, $nucleo->PARENTELA, $attr); 
       ?> 
    </td>
    
    <td class="remove_record_nucleo" >    
    <button type="button" class="btn btn-default"><span class="icon icon-remove"></span><span class="hidden">elimina</span></button>
</td>  

  </tr>
  <?php endforeach; ?>
  </tbody> 
</table>                                                   
      <!--FINE NUCLEO FAMILIARE-->
     <div class="col-sm-12">
     <div class="pull-right">
        <button type="button" id="button_nucleo" class="btn btn-primary"><span class="icon icon-add"></span> aggiungi</button>       
      </div>            
      </div>      
  </div>  
           <script type="text/javascript">
         $(document).ready(function() {
                 $(".calendario").live( "keyup",function(){
                    if ($(this).val().length == 2 || $(this).val().length == 5){
                       $(this).val($(this).val() + "/");
                    }
                 });

      });
                 
    
</script>
  <h4>Altri dati</h4>                                                  
<div class="form-group">     
    <label for="reddito_imponibile" class="col-sm-2 control-label">Somma dei redditi dei componenti del nucleo
     <div class="popover-control">
        <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
        <em class="popover-text">        
            Dato ricavabile da attestazione ISEE
        </em>
     </div>
      </label>
      <div class="col-sm-4">
        <input type="text" class="form-control  validate[required]"  id="reddito_imponibile" value="<?php echo set_value('reddito_imponibile',$REDDITO_IMPONIBILE_ISEE); ?>" name="reddito_imponibile" placeholder="Reddito imponibile" />
      </div>
      
     <label for="reddito_equivalente" class="col-sm-2 control-label">Reddito equivalente (ISEE)</label>
      <div class="col-sm-4">
        <input type="text" class="form-control validate[required]" id="reddito_equivalente" value="<?php echo set_value('reddito_equivalente',$REDDITO_EQUIVALENTE_ISEE); ?>" name="reddito_equivalente" placeholder="Reddito equivalente" />
      </div>
</div>
<div class="form-group">  

    <label for="datarilascioISEE" class="col-sm-2 control-label">Data di rilascio ISEE</label>
    <div class="col-sm-4 calendar-control">
        <input type="text" class="form-control calendarioCustom" value="<?php if ($DATA_RILASCIO_ISEE) {echo set_value('datarilascioISEE',date('d/m/Y',strtotime($DATA_RILASCIO_ISEE)));} ?>" name="datarilascioISEE" id="datarilascioISEE" placeholder="gg/mm/aaaa" />
    </div>
    
<label for="aiuti_economici" class="col-sm-2 control-label">Aiuti economici alla locazione non presenti nell'ISEE</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="aiuti_economici" name="aiuti_economici" value="<?php echo set_value('aiuti_economici',$AIUTI_ECON_NO_ISEE);?>"  placeholder="Aiuti economici alla locazione non presenti nell'ISEE" />
                </div>
</div>                
   
    <!--<label for="numero_nucleo" class="col-sm-2 control-label">Numero componenti nucleo</label>
      <div class="col-sm-2">
        <input type="text" class="form-control validate[required]" id="numero_nucleo" value="<?php echo set_value('numero_nucleo',$NUMERO_COMP_NUCLEO);?>" name="numero_nucleo" placeholder="Numero componenti nucleo" />
      </div>
     <label for="n_figli" class="col-sm-2 control-label">Numero figli</label>
      <div class="col-sm-2">
        <input type="text" class="form-control validate[required]" id="n_figli" value="<?php echo set_value('n_figli',$NUMERO_FIGLI);?>" name="n_figli" placeholder="Numero figli" />
      </div>  -->

    <div class="form-group form-group-check">        
         
                <div class="col-sm-offset-2 col-sm-10 control-label">
                <?php
                $attributes = 'id="assegnatario_erps"';
                echo form_checkbox('assegnatario_erps', '1', ($ASSEGNATARIO_ERPS =='1' ? '1' : '0'),$attributes ); ?> 
                <label for="assegnatario_erps">Assegnatario erps</label>               
                </div>                
   
                <div class="col-sm-offset-2 col-sm-10 control-label">
				        <?php 
                $attributes = 'id="permesso_soggiorno"';
                echo form_checkbox('permesso_soggiorno', '1', ($PERMESSO_SOGGIORNO =='1' ? '1' : '0'),$attributes ); ?> 
                <label for="permesso_soggiorno">Titolo di soggiorno valido</label>                
                </div>
    </div>   
    
              <div class="form-group">
                <!--no agenzie sociali-->
                <label for="provvedimento_sfratto" class="col-sm-2 control-label">Provvedimento di sfratto</label>
                <div class="col-sm-4"> 
                  <?php                   
                  //print_r($stati_sfratto);
                  $attr='id="provvedimento_sfratto" class="form-control"';                   
                  echo form_dropdown('provvedimento_sfratto', $stati_sfratto, $SCONTRINO, $attr); 
                  ?>  
                </div>
                <label for="motivazione_sfratto" class="col-sm-2 control-label">Motivazione provvedimento di sfratto</label>
                <div class="col-sm-4"> 
                  <?php                   
                  //print_r($stati_sfratto);
                  $attr='id="motivazione_sfratto" class="form-control"';                   
                  echo form_dropdown('motivazione_sfratto', $motivazione_sfratto, $MOTIVAZIONE_SCONTRINO, $attr); 
                  ?>  
                </div>
                                  
              </div>
    
                <div class="form-group form-group-check">      
                <div class="col-sm-offset-2 col-sm-10 control-label">
                <?php 
                $attributes = 'id="domanda_collegata_fondo"';                  
                echo form_checkbox('domanda_collegata_fondo', '1', ($DOMANDA_COLLEGATA_FONDO =='1' ? '1' : '0'),$attributes); ?> 
                <label for="domanda_collegata_fondo">Domanda collegata al Fondo morosità incolpevole</label>
                    <div class="popover-control">                
                      <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
                      <em class="popover-text">        
                      In caso di morosità incolpevole
                      </em>
                    </div>       
                </div>  
                </div>  
                
                
              <h4>Dati di residenza</h4>
              <div class="form-group">
                <label for="indirizzo_residenza" class="col-sm-2 control-label">Indirizzo </label>
                <div class="col-sm-8">
                  <input type="text" class="form-control validate[required]" id="indirizzo_residenza"  value="<?php echo set_value('indirizzo_residenza',$RESID_INDIRIZZO);?>" name="indirizzo_residenza" placeholder="Indirizzo" />
                </div>
                <div class="col-sm-2">                                      
                  <label for="civico_residenza">
                  <input type="text" class="form-control validate[required]" id="civico_residenza" value="<?php echo set_value('civico_residenza',$RESID_CIVICO);?>" name="civico_residenza" placeholder="Nr civico" />
                  </label>
                </div>
              </div>
              <!--/form-group-->
              <div class="form-group">
                <label for="CAP" class="col-sm-2 control-label ">CAP</label>
                <div class="col-sm-2">
                  <input type="text" class="form-control validate[required,custom[onlyNumberSp]]" value="<?php echo set_value('CAP',$RESID_CAP);?>" name="CAP" id="CAP" placeholder="CAP" />
                </div>
                <label for="provincia_residenza" class="col-sm-1 control-label">Prov.</label>
                <div  class="col-sm-2">
                  <div id="js-option_1" class="js-disabled">
                  <?php                   
                  $attr='id="provincia_residenza"  class="form-control validate[required]"';                   
                  echo form_dropdown('provincia_residenza', $province, $COD_PROVINCIA, $attr); 
                  ?>        
                  </div>
                  <!--/js-option-->
                </div>
                <label for="comune_residenza" class="col-sm-2 control-label">Comune</label>
                <div class="col-sm-3">
                  <div class="ui-widget">
                  <?php
                  $attr='id="comune_residenza" class="form-control validate[required]"';                    
                       echo form_dropdown('comune_residenza', $comuni, $RESID_COMUNE_ISTAT,$attr); 
                  ?>  
                  </div>
                </div>
              </div>
              <!--/form-group-->
              <h4>Nuovi dati di residenza</h4>
              <div class="form-group">
                <label for="indirizzo_n" class="col-sm-2  control-label">Indirizzo </label>
                <div class="col-sm-8">
                  <input type="text" class="form-control validate[required]" name="indirizzo_n" value="<?php echo set_value('indirizzo_n',$NUOVARESID_INDIRIZZO);?>" id="indirizzo_n" placeholder="Indirizzo" />
                </div>
                <div class="col-sm-2">
                  <label for="civico_n">
                  <input type="text" class="form-control validate[required]" name="civico_n" value="<?php echo set_value('civico_n',$NUOVARESID_CIVICO);?>" id="civico_n" placeholder="Nr civico" />
                  </label>
                </div>
              </div>
              <!--/form-group-->
              <div class="form-group">
                <label for="CAP_n" class="col-sm-2 control-label">CAP</label>
                <div class="col-sm-2">
                  <input type="text" class="form-control validate[required,custom[onlyNumberSp]]" value="<?php echo set_value('CAP_n',$NUOVARESID_CAP);?>" name="CAP_n" id="CAP_n" placeholder="CAP" />
                </div>
                <label for="provincia_nuova_residenza" class="col-sm-1 control-label">Prov.</label>
                <div  class="col-sm-2">
                  <div id="js-option_2" class="js-disabled">         
                   <?php                   
                    $attr='id="provincia_nuova_residenza" class="form-control"';                   
                    echo form_dropdown('provincia_nuova_residenza', $province, $NUOVO_COD_PROVINCIA, $attr); 
                  ?>    
                    <div class="button">
                      <button id="cerca_comune_2" class="btn btn-default" type="submit">cerca</button>
                    </div>
                  </div>
                  <!--/js-option-->
                </div>
                <label for="nuovo_comune_residenza" class="col-sm-2 control-label">Comune</label>
                <div class="col-sm-3">
                  <div class="ui-widget">
                  <?php
                  $attr='id="nuovo_comune_residenza" class="form-control"';                    
                       echo form_dropdown('nuovo_comune_residenza', $nuovo_comuni, $NUOVARESID_COMUNE_ISTAT,$attr); 
                  ?>  
                    
                  </div>
                </div>
              </div>
              <!--/form-group-->
         
          </div>
          <!--/.panel-body -->
        </div>
        <!-- /#tab -->
      </div>
      <!-- /.panel.panel-default -->
      
      
      
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title"> <a href="#tab2" data-toggle="collapse"> Contratto </a> </h4>
        </div>
        <div id="tab2" class="panel-collapse collapse in">
          <div class="panel-body">
        
   <div class="form-group">
   <label for="tipo_godimento_abitazione" class="col-sm-2 control-label">Titolo di godimento dell'abitazione attuale</label>
      <div class="col-sm-4">
      
         <?php
        //  echo $TIPO_CONTRATTO;
                $attr='id="tipo_godimento_abitazione" class="form-control"';                    
                echo form_dropdown('tipo_godimento_abitazione', $tipo_godimento_abitazione, $TIPO_GODIMENTO_ABITAZIONE,$attr); 
           ?>  
        
      
      </div>
       <label for="tipo_contratto_atto" class="col-sm-2 control-label">Tipologia del contratto in atto</label>
      <div class="col-sm-4">
        <?php
        //  echo $TIPO_CONTRATTO;
                $attr='id="tipo_contratto_atto" class="form-control"';                    
                echo form_dropdown('tipo_contratto_atto', $tipi_contratto_atto, $TIPO_CONTRATTO_ATTO,$attr); 
           ?>  
      </div>
      
      </div>
  
  
    <!--NO  AGENZIE-->
<div class="form-group">

	<label for="durata_nuovo_contratto" class="col-sm-2 control-label">Durata del nuovo contratto </label>
    <div class="col-sm-4">
   <?php
        //  echo $TIPO_CONTRATTO;
                $attr='id="durata_nuovo_contratto" class="form-control"';                    
                echo form_dropdown('durata_nuovo_contratto', $durata_nuovo_contratto, $DURATA_NUOVO_CONTRATTO,$attr); 
           ?>  
    </div>

    <label for="durata_contratto" class="col-sm-2 control-label">Durata del contratto in atto </label>
    <div class="col-sm-4">
     <?php
        //  echo $TIPO_CONTRATTO;
                $attr='id="durata_contratto" class="form-control"';                    
                echo form_dropdown('durata_contratto', $durata_contratto, $DURATA_CONTRATTO,$attr); 
           ?>  
    </div>
 </div><!--/form-group-->
  
  
<div class="form-group">
	<label for="data_nuovo_contratto" class="col-sm-2 control-label">Data di stipula del nuovo contratto 
        <div class="popover-control">
	        <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
	        <em class="popover-text">        
	        Inserire l'inizio della validit&agrave; del contratto in atto.
	        </em>
        </div>            
    </label>
    <div class="col-sm-4 calendar-control">
        <input type="text" class="form-control calendarioCustom" name="data_nuovo_contratto" id="data_nuovo_contratto" value="<?php if ($DATA_NUOVO_CONTRATTO) {echo set_value('data_nuovo_contratto',date('d/m/Y',strtotime($DATA_NUOVO_CONTRATTO)));} ?>" placeholder="gg/mm/aaaa" />
    </div>
    
        <label for="data_contratto" class="col-sm-2 control-label">Data di stipula del contratto in atto 
	        <div class="popover-control">
		        <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
		        <em class="popover-text">        
		        Inserire l'inizio della validit&agrave; del contratto in atto.
		        </em>
	        </div>    
          
    </label>
    <div class="col-sm-4 calendar-control">
        <input type="text" class="form-control calendarioCustom" name="data_contratto" id="data_contratto" value="<?php if ($DATA_CONTRATTO) {echo set_value('data_contratto',date('d/m/Y',strtotime($DATA_CONTRATTO)));} ?>" placeholder="gg/mm/aaaa" />
    </div>
</div>

<div class="form-group">
	 <label for="ammontare_nuovo_contratto" class="col-sm-2 control-label">Ammontare canone annuo nuovo contratto</label>
      <div class="col-sm-4">
        <input type="text" class="form-control validate[required]" name="ammontare_nuovo_contratto" value="<?php echo set_value('ammontare_nuovo_contratto',$AMMONTARE_NUOVO_CONTRATTO); ?>" id="ammontare_nuovo_contratto" placeholder="" />
      </div>
      
      <label for="ammontare_atto" class="col-sm-2 control-label">Ammontare canone annuo in atto</label>
      <div class="col-sm-4">
        <input type="text" class="form-control validate[required]" name="ammontare_atto" value="<?php echo set_value('ammontare_atto',$AMMONTARE_ATTO); ?>" id="ammontare_atto" placeholder="" />
      </div>                   
</div>


 <h4>Dati del Proprietario dell'Alloggio</h4>
    <div class="form-group">
      <label for="nome_proprietario" class="col-sm-2 control-label">Nome 
      </label>
      <div class="col-sm-4">
        <input type="text" class="form-control" value="<?php echo set_value('nome_proprietario',$NOME_PROPRIETARIO); ?>" id="nome_proprietario"  name="nome_proprietario" placeholder="Nome" />
      </div>
      <label for="cognome_proprietario" class="col-sm-2 control-label">Cognome </label>
      <div class="col-sm-4">
        <input type="text" class="form-control" value="<?php echo set_value('cognome_proprietario',$COGNOME_PROPRIETARIO); ?>" name="cognome_proprietario" id="cognome_proprietario" placeholder="Cognome" />
      </div>
    </div><!--/form-group-->
    <div class="form-group">
      <label for="codicefiscale_proprietario" class="col-sm-2 control-label">Codice fiscale o partita IVA</label>
      <div class="col-sm-4">
        <input type="text" maxlength="16" class="form-control validate[custom[onlyLetterNumber],maxSize[16]]" value="<?php echo set_value('codicefiscale_proprietario',$COD_FISCALE_PROPRIETARIO); ?>" id="codicefiscale_proprietario" name="codicefiscale_proprietario" placeholder="Codice fiscale o Partita IVA" />
      </div>
      <label for="datanascita" class="col-sm-2 control-label">Data di nascita 

      </label>
      <div class="col-sm-4 calendar-control">
        <input type="text" class="form-control calendarioCustom" value="<?php if ($DATA_NASCITA_PROPRIETARIO) {echo set_value('data_nascita_proprietario',date('d/m/Y',strtotime($DATA_NASCITA_PROPRIETARIO)));} ?>" name="data_nascita_proprietario" id="data_nascita_proprietario" placeholder="gg/mm/aaaa" />
      </div>                     
    </div><!--/form-group-->


 <h4>Dati dell'immobile</h4>
<div class="form-group">
      <label for="estremi_catastali_foglio" class="col-sm-3 control-label">Estremi catastali identificativi dell'unit&agrave; immobiliare
                            
      </label>
      
      <div class="col-sm-3">
        <label>Foglio:</label> <input type="text" class="form-control" id="estremi_catastali_foglio" value="<?php echo set_value('estremi_catastali_foglio',$ESTREMI_CATASTALI_FOGLIO); ?>" name="estremi_catastali_foglio" placeholder="" />
      </div>
      
      <div class="col-sm-3">
        <label>Particella</label>: <input type="text" class="form-control" id="estremi_catastali_particella" value="<?php echo set_value('estremi_catastali_particella',$ESTREMI_CATASTALI_PARTICELLA); ?>" name="estremi_catastali_particella" placeholder="" />
      </div>          
      <div class="col-sm-3">
        <label>Subalterno:</label> <input type="text" class="form-control" id="estremi_catastali_subalterno" value="<?php echo set_value('estremi_catastali_subalterno',$ESTREMI_CATASTALI_SUBALTERNO); ?>" name="estremi_catastali_subalterno" placeholder="" />
      </div>  
</div>     
<div class="form-group">
      <label for="numero_vani" class="col-sm-2 control-label">Numero vani catastali</label>     
      <div class="col-sm-4">
        <input type="text" class="form-control" id="numero_vani" value="<?php echo set_value('numero_vani',$NUMERO_VANI); ?>" name="numero_vani" placeholder="" />
      </div>
       <label for="categoria_catastale" class="col-sm-2 control-label">Categoria Catastale
      <div class="popover-control">
        <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
        <em class="popover-text">        
        Esclusione per A1,A7,A8,A9
        </em>
        </div>     
       </label>      
      <div class="col-sm-4">
        <input type="text" class="form-control" id="categoria_catastale" value="<?php echo set_value('categoria_catastale',$CATEGORIA_CATASTALE); ?>" name="categoria_catastale" placeholder="" />
      </div>
 
</div>
<div class="form-group">              
     
      
      <label for="stato_conservazione_fabbricato" class="col-sm-2 control-label">Stato conservazione fabbricato</label>      
      <div class="col-sm-4">  <?php
            $attr='id="stato_conservazione_fabbricato" class="form-control"';                    
            echo form_dropdown('stato_conservazione_fabbricato', $conservazione, $STATO_CONSERVAZIONE_FABBRICATO,$attr); 
       ?>  
       </div>
       
     
      <label for="stato_conservazione_alloggio" class="col-sm-2 control-label">Stato conservazione alloggio</label>      
      <div class="col-sm-4">  <?php
            $attr='id="stato_conservazione_alloggio" class="form-control"';                    
            echo form_dropdown('stato_conservazione_alloggio', $conservazione, $STATO_CONSERVAZIONE_ALLOGGIO,$attr); 
       ?>  
       </div>
  </div>            
      
<!--form-group-->
          </div>
          <!--/.panel-body -->
        </div>
        <!-- /#tab -->
      </div>
      <!-- /.panel.panel-default -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title"> <a href="#tab3" data-toggle="collapse"> Ammontare contributi e generale </a> </h4>
        </div>
        <div id="tab3" class="panel-collapse collapse in">
          <div class="panel-body">
<h4>Ammontare contributi</h4>                         
<div class="form-group">
      <label for="contributo_inquilino_ammesso" class="col-sm-3 control-label">Contributo inquilino ammesso
	      <div class="popover-control">
	        <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
	        <em class="popover-text">Contributo liquidato all'inquilino a valere solo sui fondi regionali</em>
	      </div>   
      </label>
      <div class="col-sm-3">
        <input type="text" class="form-control validate[required]" value="<?php echo set_value('contributo_inquilino_ammesso',$CONTRIBUTO_INQUILINO_AMMESSO); ?>" id="contributo_inquilino_ammesso" name="contributo_inquilino_ammesso" placeholder="" />
      </div>
      <label for="contributo_proprietario_ammesso" class="col-sm-3 control-label">Contributo proprietario ammesso
      	<div class="popover-control">
	        <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
	        <em class="popover-text">Contributo liquidato al proprietario a valere solo sui fondi regionali</em>
	    </div> 
      </label>
      <div class="col-sm-3">
        <input type="text" class="form-control validate[required]"  value="<?php echo set_value('contributo_proprietario_ammesso',$CONTRIBUTO_PROPRIETARIO_AMMESSO); ?>" name="contributo_proprietario_ammesso" id="contributo_proprietario_ammesso" placeholder="" />
      </div>            
</div>
<div class="form-group">
	<label for="cofinanziamento" class="col-sm-3 control-label">Cofinanziamento comunale</label>
      <div class="col-sm-3">
        <input type="text" class="form-control"  value="<?php echo set_value('cofinanziamento',$COFINANZIAMENTO); ?>" name="cofinanziamento" id="cofinanziamento" placeholder="" />
      </div>
    <label class="col-sm-3 control-label">
      <?php 
      $attributes = 'id="fondo_garanzia_proprietario"';
      echo form_checkbox('fondo_garanzia_proprietario', '1', ($FONDO_GARANZIA_PROPRIETARIO =='1' ? '1' : '0'), $attributes); ?> 
     Fondo di garanzia al proprietario               
    </label> 
</div>
<div class="form-group">      
	<label for="scadenza_fondo_garanzia_proprietario" class="col-sm-3 control-label">Scadenza Fondo di garanzia al proprietario</label>
  <div class="col-sm-3 calendar-control">                                             
    <input type="text" class="form-control calendarioCustom" value="<?php if ($SCADENZA_FONDO_GARANZIA_PROPRIETARIO) {echo set_value('scadenza_fondo_garanzia_proprietario',date('d/m/Y',strtotime($SCADENZA_FONDO_GARANZIA_PROPRIETARIO)));} ?>" id="scadenza_fondo_garanzia_proprietario" name="scadenza_fondo_garanzia_proprietario" placeholder="gg/mm/aaaa" />        
  </div>
        <label for="ammontare_fondo_garanzia_proprietario" class="col-sm-3 control-label">Ammontare Fondo di garanzia al proprietario</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" value="<?php echo set_value('ammontare_fondo_garanzia_proprietario',$AMMONTARE_FONDO_GARANZIA_PROPRIETARIO); ?>" id="ammontare_fondo_garanzia_proprietario" name="ammontare_fondo_garanzia_proprietario" placeholder="" />
      </div>                                
</div>    
<!-- LIQUIDAZIONE -->
<div class="form-group">
    <label for="data_liquidazione" class="col-sm-3 control-label">Data del provvedimento comunale di liquidazione</label>
    <div class="col-sm-3 calendar-control">    
        <input type="text" class="form-control calendarioCustom" name="data_liquidazione" value="<?php if ($DATA_LIQUIDAZIONE) {echo set_value('data_liquidazione', date('d/m/Y', strtotime($DATA_LIQUIDAZIONE)));} ?>"  id="data_liquidazione" placeholder="gg/mm/aaaa" />
    </div>
    
    <label for="numero_liquidazione" class="col-sm-3 control-label">Numero del provvedimento comunale di liquidazione</label>
    <div class="col-sm-3">
    <input type="text" class="form-control" value="<?php echo set_value('numero_liquidazione', $NUMERO_LIQUIDAZIONE); ?>" id="numero_liquidazione" name="numero_liquidazione" placeholder="" />
    </div>
</div>
<div class="form-group">
    <label for="annualita_liquidazione" class="col-sm-3 control-label">Annualità di riferimento</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" value="<?php echo set_value('annualita_liquidazione', $ANNUALITA_LIQUIDAZIONE); ?>"  id="annualita_liquidazione" name="annualita_liquidazione" placeholder="" />
    </div>  
</div>


<h4>Generale</h4>
    <div class="form-group">

    <label for="data_domanda" class="col-sm-2 control-label">Data della domanda</label>
    <div class="col-sm-2 calendar-control">    
        <input type="text" class="form-control calendarioCustom" name="data_domanda" value="<?php if ($DATA_DOMANDA) {echo set_value('data_domanda',date('d/m/Y',strtotime($DATA_DOMANDA)));} ?>"  id="data_domanda" placeholder="gg/mm/aaaa" <?php //if(!empty($ID)) {echo 'disabled="disabled" ';} ?> />
    </div>
    
    <label for="numero_protocollo" class="col-sm-2 control-label">Numero Protocollo</label>
    <div class="col-sm-2">
    <input type="text" class="form-control" value="<?php echo set_value('numero_protocollo',$NUMERO_PROTOCOLLO); ?>" id="numero_protocollo" name="numero_protocollo" placeholder="" />
    </div>

    <label for="data_protocollo" class="col-sm-2 control-label">Data protocollo</label>
    <div class="col-sm-2 calendar-control">
        <input type="text" class="form-control calendarioCustom" name="data_protocollo" value="<?php if ($DATA_PROTOCOLLO) {echo set_value('data_protocollo',date('d/m/Y',strtotime($DATA_PROTOCOLLO)));} ?>"  id="data_protocollo" placeholder="gg/mm/aaaa" />
    </div>
</div><!--/form-group-->
        
          </div>
          <!--/.panel-body -->
        </div>
        <!-- /#adjusthtml -->
      </div>
      <!-- /.panel.panel-default -->
      
      
   <div class="form-group puls-group">
          <div class="col-sm-12">          
        <?php if ($STATO_DOMANDA=='1'): ?>
        <button type="submit" name="submit" id="valida" class="btn btn-primary btn-lg" value="valida" ><span class="icon icon-ok"></span> Valida</button>             
        <?php endif; ?>
            <button type="submit" name="submit" id="btnsalvabozza" class="btn btn-primary btn-lg" value="salvabozza" ><span class="icon icon-save"></span> Salva Bozza</button>
			<button type="button" id="btnstampa" class="btn btn-primary btn-lg" value="stampa"><span class="icon icon-print"></span> Stampa</button>         
          </div>
        </div><!--/puls-group-->
    
       <span class="pull-right print-only">
        <br />
              <br />
        Data ____________ firma leggibile __________________________________
        </span>
  
  </div><!--panel-group-->
  
  
  </div><!--/container-->
  
</form>    

