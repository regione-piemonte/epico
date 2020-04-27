<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

 <?php if($vincoli->VALIDABILE != 1) : ?>
 <script type="text/javascript" src="/servizi/epico/assets/js/epico.js"></script>
 <div class="container">
     <div class="row"><div class="col-md-12">
<div class="panel panel-default">
 <div class="panel-heading">
    <h4 class="panel-title"> Assegnatario </h4>
 </div>

    <div class="panel-body">
 <?php 
	 if ($vincoli->ASSEGNABILE && empty($CODICE_FISCALE) && $usertype != 'REG') { 
	       $attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'form_assegnazione');
		   echo form_open('edilizia/assegna',$attributes); 
  ?> 
		<input type="hidden" id="id_domanda" name="id_domanda" value="<?php echo set_value('id_domanda',$ID_DOMANDA); ?>" />
        <div class="form-group">
	      <label for="nome" class="col-sm-2 control-label">Nome 
	      </label>
	      <div class="col-sm-4">
	        <input type="text" class="form-control validate[required]" id="nome" name="nome" placeholder="Nome" value="<?php echo set_value('nome',$NOME); ?>" />        
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
	      <label for="datanascita" class="col-sm-2 control-label">Data di nascita </label>
	      <div class="col-sm-4 calendar-control">
	        <input type="text" class="form-control  validate[required] calendarioCustom" value="<?php if ($DATA_NASCITA) {echo set_value('datanascita',date('d/m/Y',strtotime($DATA_NASCITA)));} ?>" id="datanascita" name="datanascita" placeholder="gg/mm/aaaa" />      
	      </div>
	    </div><!--/form-group-->
	    
	    <div class="form-group">
	      <label for="titolostudio" class="col-sm-2 control-label">Titolo di studio </label>
	      <div class="col-sm-4">     
	      <?php
	            $attr='id="titolostudio" class="form-control validate[required]"';                    
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
    
     <div class="form-group puls-group">
          <div class="col-sm-12">
		  	<button type="submit" name="submit" id="assegna" class="btn btn-primary btn-lg pull-right" value="assegna" <?php if($usertype == 'REG') echo 'disabled="disabled"'?> ><span class="icon icon-ok"></span> Assegna</button>
          </div>
        </div><!--/puls-group-->

        
  </div><!--panel-group-->
  
<?php 
	form_close();
	} elseif(!empty($CODICE_FISCALE)) { 
?>
      <ul>
          <li>Nome: <?php echo $NOME; ?></li>
          <li>Cognome:  <?php echo $COGNOME; ?></li>
          <li>Codice Fiscale: <?php echo $CODICE_FISCALE; ?></li>
          <li>Data di nascita: <?php if ($DATA_NASCITA) {echo date("d/m/Y",strtotime($DATA_NASCITA));} ?> </li>
          <li>Titolo di studio: <?php echo $DESCRIZIONE_TITOLO_STUDIO; ?></li>
          <li>Cittadinanza: <?php echo $CITTADINANZA; ?></li>
        </ul>
        
        <?php 
	} else echo 'Non assegnato';
?> 

        
    </div>
         <!--/.panel-body -->

 </div>
         <!-- /.panel.panel-default -->

  </div><!--panel-group-->

  </div><!--/container-->
<?php endif; ?>