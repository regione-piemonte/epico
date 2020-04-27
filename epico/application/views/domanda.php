<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<script type="text/javascript" src="/servizi/epico/assets/js/epico.js"></script>
    
 <?php 
       $attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'form_domanda');
        echo form_open('domande/submit',$attributes); 
?>                
    <div class="container">
     <div class="row"><div class="col-md-12">
     
<?php $this->load->view('include/menu', $_ci_data['_ci_vars']);?>    
      <h3>Fondo per la Morosit&agrave; Incolpevole</h3>
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
           <a href="<?php echo base_url().'index.php/domande/domanda/?tipo_domanda=1'; ?>" class="btn btn-primary btn-lg"><span class="icon icon-plus"></span> nuova domanda</a>          
           <a href="<?php echo base_url().'index.php/domande/elenco/?tipo_domanda=1'; ?>" class="btn btn-primary btn-lg"><span class="icon icon-list"></span> elenco domande</a>
          </div>
          </div>
      </div></div>
    </div>                      
    
 <div class="container fixedScroll">  
  <?php if ($msg=='bozzaok'): ?>
        <!--div class="alert alert-success">
              <p>La bozza della domanda è stata salvata correttamente.<br />
              Sebbene i campi siano tutti obbligatori il salvataggio in bozza è consentito ugualmente</p>
        </div-->
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
      <label for="codicefiscale" class="col-sm-2 control-label">Codice fiscale</label>
      <div class="col-sm-4">
        <input type="text" class="form-control validate[required]" id="codicefiscale" value="<?php echo set_value('codicefiscale',$CODICE_FISCALE); ?>" name="codicefiscale" placeholder="Codice fiscale" />
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
  $attributes = 'id="cittadinanza-ue" class="from-control validate[required]"';  
  echo form_radio('cittadinanza', 'itue', ($CITTADINANZA =='itue' ? '1' : '0'),$attributes); ?> Italiana/UE
</label>               

<label for="cittadinanza-extra" class="checkbox-inline">
  <?php 
  $attributes = 'id="cittadinanza-extra" class="from-control validate[required]"';  
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
    <input type="text" maxlength="16" class="form-control" value="<?php echo $nucleo->CODICE_FISCALE; ?>" id="nucleo_codice_fiscale" name="nucleo_codice_fiscale[]" placeholder="Codice fiscale" />
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
                                                                   

  <h4>Altri dati</h4>                                                  
<div class="form-group">     
    <label for="reddito_imponibile" class="col-sm-2 control-label">Reddito ISE
    	<div class="popover-control">
            <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
            <em class="popover-text">        
            <?php
	    		$reddito_imponibile_max = '';
				echo 'Massimo consentito: ' . number_format($reddito_imponibile_max, 2, ',', '.') . ' €';
			?>
            </em>
        </div>      
    </label> 
      <div class="col-sm-4">
        <input type="text" class="form-control  validate[required]" data-v-max="<?php echo $reddito_imponibile_max; ?>"  id="reddito_imponibile" value="<?php echo set_value('reddito_imponibile',$REDDITO_IMPONIBILE_ISEE); ?>" name="reddito_imponibile" placeholder="Reddito imponibile" />
      </div>
     <label for="reddito_equivalente" class="col-sm-2 control-label">Reddito equivalente (ISEE)
     	<div class="popover-control">
            <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
            <em class="popover-text">        
            <?php
	    		$reddito_equivalente_max = '';
				echo 'Massimo consentito: ' . number_format($reddito_equivalente_max, 2, ',', '.') . ' €';
			?>
            </em>
        </div>   
     </label>
      <div class="col-sm-4">
        <input type="text" class="form-control validate[required]" data-v-max="<?php echo $reddito_equivalente_max; ?>" id="reddito_equivalente" value="<?php echo set_value('reddito_equivalente',$REDDITO_EQUIVALENTE_ISEE); ?>" name="reddito_equivalente" placeholder="Reddito equivalente" />
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

    <div class="form-group form-group-check">        
       <div class="col-sm-offset-2 col-sm-10 control-label"> 
			 <?php 
        $attributes = 'id="invalidita"';
       echo form_checkbox('invalidita', '1', ($FCT =='1' ? '1' : '0'),$attributes ); ?> 
            <label for="invalidita">Invalidit&agrave; accertata maggiore o uguale al 74%</label>
            <div class="popover-control">
                <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
                <em class="popover-text">        
                   Anche solo di un componente del nucleo
                </em>
             </div>                            
        </div>            
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
                 <label for="permesso_soggiorno"> Titolo di soggiorno valido</label>
                  </div>
                                    
                <div class="col-sm-offset-2 col-sm-10 control-label">
                <?php
                $attributes = 'id="servizi_sociali"'; 
                echo form_checkbox('servizi_sociali', '1', ($SERVIZI_SOCIALI=='1' ? '1' : '0'),$attributes  ); ?>  
                <label for="servizi_sociali">Servizi sociali</label>
                    <div class="popover-control">
                    <a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
                    <em class="popover-text">        
                       In carico ai servizi sociali o ASL per l'attuazione di un progetto assistenziale individuale
                    </em>
                    </div>               
                </div>
                 
    </div>   
              
              <div class="form-group">
                <!--no agenzie sociali-->
                <label for="provvedimento_sfratto" class="col-sm-2 control-label">Provvedimento di sfratto per morosità</label>
                <div class="col-sm-4"> 
                  <?php  
	              // elimina opzioni non più utilizzabili                 
                  unset($stati_sfratto[1]);
                  unset($stati_sfratto[2]);
                  unset($stati_sfratto[3]);
                  $attr='id="provvedimento_sfratto" class="form-control"';                   
                  echo form_dropdown('provvedimento_sfratto', $stati_sfratto, $SCONTRINO, $attr); 
                  ?>  
                </div>
                <label for="data_provvedimento_sfratto" class="col-sm-2 control-label">Data provvedimento di sfratto per morosità</label>
                <div class="col-sm-4 calendar-control">    
                  <input type="text" class="form-control calendarioCustom" value="<?php if ($DATA_SCONTRINO) {echo set_value('data_provvedimento_sfratto',date('d/m/Y',strtotime($DATA_SCONTRINO)));} ?>" name="data_provvedimento_sfratto" id="data_provvedimento_sfratto" placeholder="gg/mm/aaaa" />
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
                  //print_r($province);                   
                  $attr=' id="provincia_residenza"  class="form-control validate[required]" ';                                     
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
		  		<label for="tipo_contratto_atto" class="col-sm-2 control-label">Tipologia del contratto in atto</label>
		  		<div class="col-sm-4">
		        <?php
	                $attr='id="tipo_contratto_atto" class="form-control"';                    
	                echo form_dropdown('tipo_contratto_atto', $tipi_contratto_atto, $TIPO_CONTRATTO_ATTO,$attr); 
		        ?>  
		    	</div>
		    	
		    	<label for="durata_contratto" class="col-sm-2 control-label">Durata del contratto in atto </label>
			    <div class="col-sm-4">
			    <?php
	                $attr='id="durata_contratto" class="form-control"';                    
	                echo form_dropdown('durata_contratto', $durata_contratto, $DURATA_CONTRATTO,$attr); 
			    ?>  
			    </div>
    		</div><!--/form-group-->
			
			<div class="form-group">
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
			    
			    <label for="data_registrazione_contratto" class="col-sm-2 control-label">Data di registrazione del contratto in atto</label>
			    <div class="col-sm-4 calendar-control">
			        <input type="text" class="form-control calendarioCustom" name="data_registrazione_contratto" id="data_registrazione_contratto" value="<?php if ($DATA_REGISTRAZIONE_CONTRATTO) {echo set_value('data_registrazione_contratto',date('d/m/Y',strtotime($DATA_REGISTRAZIONE_CONTRATTO)));} ?>" placeholder="gg/mm/aaaa" />
			    </div>
      		</div><!--/form-group-->
  
	  		<div class="form-group">

		      <label for="ammontare_atto" class="col-sm-2 control-label">Ammontare canone annuo in atto</label>
		      <div class="col-sm-4">
		        <input type="text" class="form-control validate[required]" name="ammontare_atto" value="<?php echo set_value('ammontare_atto',$AMMONTARE_ATTO); ?>" id="ammontare_atto" placeholder="" />
		      </div>
		      <label for="ammontare_morosita" class="col-sm-2 control-label">Ammontare morosità accertata dal comune</label>     
		      <div class="col-sm-4">
		         <input type="text" class="form-control validate[required]" name="ammontare_morosita" value="<?php echo set_value('ammontare_morosita',$AMMONTARE_MOROSITA); ?>" id="ammontare_morosita" placeholder="" />
		      </div>                                     
			</div><!--/form-group-->

			<div class="form-group">

				<label class="col-sm-4 control-label">Rinuncia del proprietario all'esecuzione dello sfratto  </label>
				<div class="col-sm-2">
					<label for="rinuncia_esecuzione_si" class="checkbox-inline">
					<?php
					$attributes = 'id="rinuncia_esecuzione_si"'; 
					echo form_radio('rinuncia_esecuzione', '1', ($RINUNCIA_ESECUZIONE =='1' ? '1' : '0'),$attributes); ?> SI
					</label>
					<label for="rinuncia_esecuzione_no"  class="checkbox-inline">
					<?php
					$attributes = 'id="rinuncia_esecuzione_no"';  
					echo form_radio('rinuncia_esecuzione', '0', ($RINUNCIA_ESECUZIONE =='0' ? '1' : '0'),$attributes); ?> NO
					</label>
				</div>			
			</div><!--/form-group-->

			<div class="form-group">

				<label class="col-sm-4 control-label">Differimento dell'esecuzione dello sfratto</label>
				<div class="col-sm-2">
					<label for="differimento_si" class="checkbox-inline">           
					<?php 
					$attributes = 'id="differimento_si"'; 
					echo form_radio('differimento', '1', ($DIFFERIMENTO =='1' ? '1' : '0'),$attributes); ?> SI 
					</label>
					<label for="differimento_no"  class="checkbox-inline">
					<?php
					$attributes = 'id="differimento_no"'; 
					echo form_radio('differimento', '0', ($DIFFERIMENTO =='0' ? '1' : '0'),$attributes); ?>  NO 
					</label>
				</div> 
				
				<label for="tipo_contratto" class="col-sm-2 control-label">Durata del differimento</label>
				<div class="col-sm-4">
				<?php
			        $attr='id="tipo_contratto" class="form-control"';                    
			        echo form_dropdown('tipo_contratto', $tipi_contratto, $TIPO_CONTRATTO,$attr); 
				?>  
				</div>
			</div><!--/form-group-->

			<div class="form-group">   
		    	<label class="col-sm-4 control-label">Deposito cauzionale per nuovo contratto</label>
				<div class="col-sm-2">
					<label for="deposito_cauzionale_si"  class="checkbox-inline">           
					<?php
			        	$attributes = 'id="deposito_cauzionale_si"';  
						echo form_radio('deposito_cauzionale', '1', ($DEPOSITO_CAUZIONALE =='1' ? '1' : '0'),$attributes); ?> SI 
					</label>
					<label for="deposito_cauzionale_no"  class="checkbox-inline">
					<?php
						$attributes = 'id="deposito_cauzionale_no"';  
						echo form_radio('deposito_cauzionale', '0', ($DEPOSITO_CAUZIONALE =='0' ? '1' : '0'),$attributes); ?> NO
					</label>
		    	</div>  
		    	
		    	<label for="durata_nuovo_contratto" class="col-sm-2 control-label">Durata del nuovo contratto </label>
			    <div class="col-sm-4">
			   <?php
	                $attr='id="durata_nuovo_contratto" class="form-control"';       
	                unset($durata_nuovo_contratto[9]); // rimuovo opzione 4+4             
	                echo form_dropdown('durata_nuovo_contratto', $durata_nuovo_contratto, $DURATA_NUOVO_CONTRATTO, $attr); 
			    ?>  
			    </div>
			</div><!--/form-group-->      
    
			<div class="form-group">      
			    <label for="data_nuovo_contratto" class="col-sm-2 control-label">Data nuovo contratto concordato </label>
			    <div class="col-sm-4 calendar-control">
			        <input type="text" class="form-control calendarioCustom" name="data_nuovo_contratto" id="data_nuovo_contratto" value="<?php  if ($DATA_NUOVO_CONTRATTO) {echo set_value('data_nuovo_contratto',date('d/m/Y',strtotime($DATA_NUOVO_CONTRATTO)));} ?>" placeholder="gg/mm/aaaa" />
			    </div>
			    
			    <label for="data_registrazione_nuovo_contratto" class="col-sm-2 control-label">Data di registrazione del nuovo contratto </label>
			    <div class="col-sm-4 calendar-control">
			        <input type="text" class="form-control calendarioCustom" name="data_registrazione_nuovo_contratto" id="data_registrazione_nuovo_contratto" value="<?php  if ($DATA_REGISTRAZIONE_NUOVO_CONTRATTO) {echo set_value('data_registrazione_nuovo_contratto',date('d/m/Y',strtotime($DATA_REGISTRAZIONE_NUOVO_CONTRATTO)));} ?>" placeholder="gg/mm/aaaa" />
			    </div>
 			</div><!--/form-group--> 
 			
 			<div class="form-group">
	 			
	 			<label class="col-sm-4 control-label">Nuovo contratto stipulato tramite Agenzia sociale locazione </label>
				<div class="col-sm-2">
					<label for="nuovo_contratto_agenzia_si" class="checkbox-inline"> 
					<?php 
						$attributes = 'id="nuovo_contratto_agenzia_si"';
						echo form_radio('nuovo_contratto_agenzia', '1', ($NUOVO_CONTRATTO_AGENZIA =='1' ? '1' : '0'),'id="nuovo_contratto_agenzia_si"'); ?>  SI 
					</label>
					<label for="nuovo_contratto_agenzia_no" class="checkbox-inline">
					<?php 
						$attributes = 'id="nuovo_contratto_agenzia_no"';
						echo form_radio('nuovo_contratto_agenzia', '0', ($NUOVO_CONTRATTO_AGENZIA =='0' ? '1' : '0'),'id="nuovo_contratto_agenzia_no"'); ?> NO  
					</label>
				</div>
	 			   
				<label for="ammontare_nuovo_contratto" class="col-sm-2 control-label">Ammontare canone annuo nuovo contratto</label>
				<div class="col-sm-4">
					<input type="text" class="form-control validate[required]" name="ammontare_nuovo_contratto" value="<?php echo set_value('ammontare_nuovo_contratto',$AMMONTARE_NUOVO_CONTRATTO); ?>" id="ammontare_nuovo_contratto" placeholder="" />
				</div>
			</div><!--/form-group--> 
     
     
			<h4>Dati del Proprietario dell'Alloggio</h4>
		    <div class="form-group">
				<label for="nome_proprietario" class="col-sm-2 control-label">Nome </label>
				<div class="col-sm-4">
					<input type="text" class="form-control" value="<?php echo set_value('nome_proprietario',$NOME_PROPRIETARIO); ?>" id="nome_proprietario"  name="nome_proprietario" placeholder="Nome" />
				</div>
				<label for="cognome_proprietario" class="col-sm-2 control-label">Cognome </label>
				<div class="col-sm-4">
					<input type="text" class="form-control" value="<?php echo set_value('cognome_proprietario',$COGNOME_PROPRIETARIO); ?>" name="cognome_proprietario" id="cognome_proprietario" placeholder="Cognome" />
				</div>
		    </div><!--/form-group-->
		    
		    <div class="form-group">
				<label for="codicefiscale_proprietario" class="col-sm-2 control-label">Codice fiscale o Partita IVA</label>
				<div class="col-sm-4">
					<input type="text" maxlength="16" class="form-control validate[custom[onlyLetterNumber],maxSize[16]]" value="<?php echo set_value('codicefiscale_proprietario',$COD_FISCALE_PROPRIETARIO); ?>" id="codicefiscale_proprietario" name="codicefiscale_proprietario" placeholder="Codice fiscale o  Partita IVA" />
				</div>
				<label for="datanascita" class="col-sm-2 control-label">Data di nascita 
				
				</label>
				<div class="col-sm-4 calendar-control">
					<input type="text" class="form-control calendarioCustom" value="<?php if ($DATA_NASCITA_PROPRIETARIO) {echo set_value('data_nascita_proprietario',date('d/m/Y',strtotime($DATA_NASCITA_PROPRIETARIO)));} ?>" name="data_nascita_proprietario" id="data_nascita_proprietario" placeholder="gg/mm/aaaa" />
				</div>                     
		    </div><!--/form-group-->


			<h4>Dati dell'immobile</h4>
			<div class="form-group">
				<label for="estremi_catastali_foglio" class="col-sm-3 control-label">Estremi catastali identificativi dell'unit&agrave; immobiliare</label>
				<div class="col-sm-3">
				<label>Foglio:</label> <input type="text" class="form-control" id="estremi_catastali_foglio" value="<?php echo set_value('estremi_catastali_foglio',$ESTREMI_CATASTALI_FOGLIO); ?>" name="estremi_catastali_foglio" placeholder="" />
				</div>
				<div class="col-sm-3">
				<label>Particella:</label> <input type="text" class="form-control" id="estremi_catastali_particella" value="<?php echo set_value('estremi_catastali_particella',$ESTREMI_CATASTALI_PARTICELLA); ?>" name="estremi_catastali_particella" placeholder="" />
				</div>          
				<div class="col-sm-3">
				<label> Subalterno:</label> <input type="text" class="form-control" id="estremi_catastali_subalterno" value="<?php echo set_value('estremi_catastali_subalterno',$ESTREMI_CATASTALI_SUBALTERNO); ?>" name="estremi_catastali_subalterno" placeholder="" />
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
						Esclusione per A1, A8, A9
						</em>
					</div>     
				</label> 
				<div class="col-sm-4">
					<input type="text" class="form-control" id="categoria_catastale" value="<?php echo set_value('categoria_catastale',$CATEGORIA_CATASTALE); ?>" name="categoria_catastale" placeholder="" />
				</div>
			   
			</div><!--/form-group-->
			
			<div class="form-group">              
				<label for="stato_conservazione_fabbricato" class="col-sm-2 control-label">Stato conservazione fabbricato</label>      
				<div class="col-sm-4">  
				<?php
				    $attr='id="stato_conservazione_fabbricato" class="form-control"';                    
				    echo form_dropdown('stato_conservazione_fabbricato', $conservazione, $STATO_CONSERVAZIONE_FABBRICATO,$attr); 
				?>  
				</div>
				
				
				<label for="stato_conservazione_alloggio" class="col-sm-2 control-label">Stato conservazione alloggio</label>      
				<div class="col-sm-4">  
				<?php
				    $attr='id="stato_conservazione_alloggio" class="form-control"';                    
				    echo form_dropdown('stato_conservazione_alloggio', $conservazione, $STATO_CONSERVAZIONE_ALLOGGIO,$attr); 
				?>  
				</div>
			</div><!--/form-group-->            
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
			    <label for="contributo_ammesso_copertura" class="col-sm-3 control-label">Contributo ammesso a copertura della morosità
			    	<div class="popover-control">
						<a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
						<em class="popover-text">        
						Decreto 30 marzo 2016, art.5 comma 1.a)<br>
						<?php
				    		$copertura_max = 8000;
							echo 'Massimo consentito: ' . number_format($copertura_max, 2, ',', '.') . ' €';
						?>
						</em>
					</div>    
			    </label>
			    <div class="col-sm-3">
			        <input type="text" class="form-control validate[required] contributi" data-v-max="<?php echo $copertura_max; ?>" value="<?php echo set_value('contributo_ammesso_copertura',$CONTRIBUTO_AMMESSO_COPERTURA); ?>" id="contributo_ammesso_copertura" name="contributo_ammesso_copertura" placeholder="" />
			    </div>
			    <label for="contributo_ammesso_copertura" class="col-sm-3 control-label">Contributo ammesso per le mensilità di differimento
			    	<div class="popover-control">
						<a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
						<em class="popover-text">        
						Decreto 30 marzo 2016, art.5 comma 1.b)<br>
						<?php
				    		$differimento_max = 6000;
							echo 'Massimo consentito: ' . number_format($differimento_max, 2, ',', '.') . ' €';
						?>
						</em>
					</div>    
			    </label>
			    <div class="col-sm-3">
			        <input type="text" class="form-control validate[required] contributi" data-v-max="<?php echo $differimento_max; ?>" value="<?php echo set_value('contributo_ammesso_differimento',$CONTRIBUTO_AMMESSO_DIFFERIMENTO); ?>" id="contributo_ammesso_differimento" name="contributo_ammesso_differimento" placeholder="" />
			    </div>
			</div><!--/form-group-->
			<div class="form-group">
			    <label for="contributo_ammesso_cauzionale" class="col-sm-3 control-label">Contributo ammesso per deposito cauzionale
			    	<div class="popover-control">
						<a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
						<em class="popover-text">        
						Decreto 30 marzo 2016, art.5 comma 1.c)
						</em>
					</div> 
			    </label>
			    <div class="col-sm-3">
			        <input type="text" class="form-control validate[required] contributi"  value="<?php echo set_value('contributo_ammesso_cauzionale',$CONTRIBUTO_AMMESSO_CAUZIONALE); ?>" name="contributo_ammesso_cauzionale" id="contributo_ammesso_cauzionale" placeholder="" />
			    </div>
			    <label for="contributo_ammesso_cauzionale" class="col-sm-3 control-label">Contributo ammesso per le mensilità del nuovo contratto a canone concordato
			    	<div class="popover-control">
						<a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
						<em class="popover-text">        
						Decreto 30 marzo 2016, art.5 comma 1.d)
						</em>
					</div> 
			    </label>
			    <div class="col-sm-3">
			        <input type="text" class="form-control validate[required] contributi"  value="<?php echo set_value('contributo_ammesso_nuovo_contratto',$CONTRIBUTO_AMMESSO_NUOVO_CONTRATTO); ?>" name="contributo_ammesso_nuovo_contratto" id="contributo_ammesso_nuovo_contratto" placeholder="" />
			    </div>
			</div><!--/form-group-->
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
			</div><!--/form-group-->
			<div class="form-group">
			    <label for="annualita_liquidazione" class="col-sm-3 control-label">Annualità di riferimento</label>
			    <div class="col-sm-3">
			        <input type="text" class="form-control" value="<?php echo set_value('annualita_liquidazione', $ANNUALITA_LIQUIDAZIONE); ?>"  id="annualita_liquidazione" name="annualita_liquidazione" placeholder="" />
			    </div>
			    <label for="totale_contributo" class="col-sm-3 control-label">Totale contributo ammesso
			    	<div class="popover-control">
						<a href="javascript:void(0)" class="popover-help icon-info"><span>[info]</span></a>
						<em class="popover-text">        
						<?php
				    		$tot_max = 12000;
							echo 'Massimo consentito: ' . number_format($tot_max, 2, ',', '.') . ' €';
						?>
						</em>
					</div> 
			    </label>
			    <div class="col-sm-3">
			        <input type="text" class="form-control validate[required]"  data-v-max="<?php echo $tot_max; ?>" readonly="readonly"  value="<?php echo set_value('totale_contributo',$TOTALE_CONTRIBUTO); ?>" name="totale_contributo" id="totale_contributo" placeholder="" />
			    </div>
			</div>
			<!--<div class="form-group">
			      <label for="contributo_proprietario_erogato" class="col-sm-2 control-label">Contributo proprietario erogato</label>
			      <div class="col-sm-4">
			        <input type="text" class="form-control validate[required]" value="<?php echo set_value('contributo_proprietario_erogato',$CONTRIBUTO_PROPRIETARIO_EROGATO); ?>" name="contributo_proprietario_erogato" id="contributo_proprietario_erogato" placeholder="" />
			      </div>
			      <label for="fondo_garanzia_proprietario" class="col-sm-2 control-label">Fondo di garanzia al proprietario</label>
			      <div class="col-sm-4">
			        <input type="text" class="form-control" value="<?php echo set_value('fondo_garanzia_proprietario',$FONDO_GARANZIA_PROPRIETARIO); ?>" name="fondo_garanzia_proprietario" id="fondo_garanzia_proprietario" placeholder="" />
			      </div>
			
			</div>-->
			<h4>Generale</h4>
			<div class="form-group">

				<label for="data_domanda" class="col-sm-2 control-label">Data della domanda</label>
				<div class="col-sm-2 calendar-control">    
				    <input type="text" class="form-control calendarioCustom" name="data_domanda" value="<?php if ($DATA_DOMANDA) {echo set_value('data_domanda',date('d/m/Y',strtotime($DATA_DOMANDA)));} ?>"  id="data_domanda" placeholder="gg/mm/aaaa" <?php //if(!empty($ID)) {echo 'disabled="disabled" ';} ?>/>
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

