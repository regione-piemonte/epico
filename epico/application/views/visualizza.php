<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<script type="text/javascript" src="/servizi/epico/assets/js/epico.js"></script>    
    <?php $this->load->view('include/status', $_ci_data['_ci_vars']);?>
 <?php 
       $attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'form_domanda');
        echo form_open('domande/submit',$attributes); 
?>                
    <div class="container">
     <div class="row">
     <div class="col-md-12">
     
<?php $this->load->view('include/menu', $_ci_data['_ci_vars']);?>    
	 <?php if ($TIPO_DOMANDA=='1'): ?>
      <h3>Fondo per la Morosit&agrave; Incolpevole</h3>
     <?php elseif ($TIPO_DOMANDA=='2'): ?>
     <h3>Agenzie sociali per la locazione</h3>
     <?php endif; ?>
      <h4 class="no-print">Visualizza domanda</h4>
     
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
          
          <a href="javascript:window.print()" class="btn btn-primary btn-lg"><span class="icon icon-print"></span>stampa</a>
          
           <?php  if ($usertype != 'REG') { ?>
           <a href="<?php echo base_url().'index.php/domande/domanda/?tipo_domanda=1'; ?>" class="btn btn-primary btn-lg"><span class="icon icon-plus"></span> nuova domanda</a>
           <?php  } ?>
           <a href="<?php echo base_url().'index.php/domande/elenco/?tipo_domanda='.$TIPO_DOMANDA; ?>" class="btn btn-primary btn-lg"><span class="icon icon-list"></span> elenco domande</a>
             
          </div></div>
      </div></div>
    </div>
    
<div class="container">
  <h3 class="no-print">Domanda  <?php echo $ID_DOMANDA; ?> </h3>
    <?php if ($msg=='validata'): ?>
     <div class="alert alert-success">
              <p>La domanda è stata validata correttamente</p>
        </div>
     <?php endif; ?>    
    
    <div class="row col-lg-12">
    <?php  //echo $DATA_SALVATAGGIO_VALIDAZIONE; ?>
    Domanda numero: <?php echo $ID_DOMANDA; ?> - Data validazione <?php if ($DATA_SALVATAGGIO_VALIDAZIONE) {echo date("d/m/Y h:i:s",strtotime($DATA_SALVATAGGIO_VALIDAZIONE));} ?>  </div>
  <div class="panel-group">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">Nucleo richiedente</h4>
      </div>
      <div class="panel-body">
        <h4>Dati anagrafici del richiedente</h4>
        <ul>
          <li>Nome: <?php echo $NOME; ?></li>
          <li>Cognome:  <?php echo $COGNOME; ?></li>
          <li>Codice Fiscale: <?php echo $CODICE_FISCALE; ?></li>
          <li>Data di nascita: <?php if ($DATA_NASCITA) {echo date("d/m/Y",strtotime($DATA_NASCITA));} ?> </li>
          <li>Età: <?php echo $ETA; ?></li>
          <li>Titolo di studio: <?php echo $DESCRIZIONE_TITOLO_STUDIO; ?></li>
        </ul>
      <h4>Nucleo famigliare</h4> 
       
      <?php foreach ($nuclei as &$nucleo): ?>
      <ul> 
       <li>Nome: <?php echo $nucleo->NOME; ?></li>
          <li>Cognome:  <?php echo $nucleo->COGNOME; ?></li>
          <li>Codice Fiscale: <?php echo $nucleo->CODICE_FISCALE; ?></li>
          <li>Data di nascita: <?php if ($DATA_NASCITA) {echo date("d/m/Y",strtotime($nucleo->DATA_NASCITA));} ?> </li>
          <li>Età: <?php echo $nucleo->ETA; ?> </li>
          <li>Parentela: <?php echo $nucleo->DESCR_PARENTELA; ?></li>
       </ul>   
      <?php endforeach; ?>  
      
        <h4>Altri dati</h4>
        <ul>
          <li>Reddito imponibile:  <?php echo $REDDITO_IMPONIBILE_ISEE; ?></li>
          <li>Reddito equivalente (ISEE): <?php echo $REDDITO_EQUIVALENTE_ISEE; ?></li>
          
          <?php if ($DATA_RILASCIO_ISEE): ?>
          <li>Data di rilascio ISEE:  <?php echo date("d/m/Y",strtotime($DATA_RILASCIO_ISEE)); ?> </li><br>
          <?php endif; ?>
          <li>Numero componenti nucleo: <?php echo $NUMERO_COMPONENTI_NUCLEO; ?></li>
          <li>Numero figli: <?php echo $NUMERO_FIGLI_NUCLEO; ?></li>
          <li>Invalidit&agrave;: <?php echo $descr_invalidita; ?></li>
          <li>Servizi sociali: <?php echo $descr_servizi_sociali; ?></li>
          
          <li>Aiuti economici alla locazione non presenti nell'ISEE: <?php echo $AIUTI_ECON_NO_ISEE; ?></li>
          <li>Assegnatario erps: <?php echo $descr_assegnatario_erps; ?></li>
          <li>Indicatore Cittadinanza: <?php echo $descr_cittadinanza; ?></li>         
          <li>Permesso di soggiorno: <?php echo $descr_permesso_soggiorno; ?></li>
          <li>Provvedimento di sfratto per morosità: <?php echo $descr_provvedimento_sfratto; ?> </li>   
          <?php if ($TIPO_DOMANDA=='1'): ?>
            <?php if ($DATA_SCONTRINO): ?> 
              <li>Convalida provvedimento di sfratto per morosità: <?php echo date("d/m/Y",strtotime($DATA_SCONTRINO)); ?></li>
            <?php endif; ?>
          <?php endif; ?>     
        </ul>
        <h4>Dati di residenza</h4>                
        <ul>
          <li>Indirizzo:  <?php echo $RESID_INDIRIZZO; ?> <?php echo $RESID_CIVICO; ?></li>
          <li>CAP:  <?php echo $RESID_CAP; ?></li>
          <li>Prov.:  <?php echo $descr_resid_provincia; ?></li>
          <li>Comune:  <?php echo $DESCR_COMUNE; ?></li>
        </ul>
        <h4>Nuovi dati di residenza</h4>
        <ul>
          <li>Indirizzo: <?php echo $NUOVARESID_INDIRIZZO; ?>  <?php echo $NUOVARESID_CIVICO; ?> </li>
          <li>CAP: <?php echo $NUOVARESID_CAP; ?> </li>
          <li>Prov.: <?php echo $descr_nuovaresid_provincia; ?> </li>
          <li>Comune: <?php echo $descr_nuovaresid_comune; ?> </li>
        </ul>
      </div>
    </div>
    <!-- /panel panel-default-->
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">Contratto</h4>
      </div>
      <div class="panel-body">
        <ul>
          <li>Durata del differimento: <?php echo $descr_tipo_contratto; ?></li>
          <li>Tipologia del contratto in atto: <?php echo $descr_tipo_contratto_atto; ?></li>
          <li>Durata del contratto in atto: <?php echo $descr_durata_contratto; ?></li>
          
          <?php if ($DATA_CONTRATTO): ?>
            <li>Data di stipula del contratto in atto: <?php echo date("d/m/Y",strtotime($DATA_CONTRATTO)); ?></li>
          <?php endif; ?>
          <?php if ($DATA_REGISTRAZIONE_CONTRATTO): ?>
            <li>Data di registrazione del contratto: <?php echo date("d/m/Y",strtotime($DATA_REGISTRAZIONE_CONTRATTO)); ?></li>
          <?php endif; ?>
          <li>Durata del nuovo contratto: <?php echo $descr_durata_nuovo_contratto; ?></li>
          <?php if ($DATA_NUOVO_CONTRATTO): ?>
          <li>Data di stipula del nuovo contratto: <?php echo date("d/m/Y",strtotime($DATA_NUOVO_CONTRATTO)); ?></li>
          <?php endif; ?>        
          <?php if ($DATA_REGISTRAZIONE_NUOVO_CONTRATTO): ?>
            <li>Data di registrazione del nuovo contratto: <?php echo date("d/m/Y",strtotime($DATA_REGISTRAZIONE_NUOVO_CONTRATTO)); ?></li>
          <?php endif; ?>
          <li>Ammontare canone annuo nuovo contratto: <?php echo $AMMONTARE_NUOVO_CONTRATTO; ?></li>
          <li>Ammontare canone annuo in atto: <?php echo $AMMONTARE_ATTO; ?></li>
          <?php if ($TIPO_DOMANDA=='1'): ?>
           <li>Ammontare morosit&agrave;  dichiarata nel provvedimento di sfratto: <?php echo $AMMONTARE_MOROSITA; ?></li>
          <?php endif; ?>          
        </ul>
        <h4>Dati del Proprietario dell'Alloggio</h4>
        <ul>
          <li>Nome: <?php echo $NOME_PROPRIETARIO; ?></li>
          <li>Cognome: <?php echo $COGNOME_PROPRIETARIO; ?></li>
          <li>Codice fiscale: <?php echo $COD_FISCALE_PROPRIETARIO; ?></li>
          <?php if ($DATA_NASCITA_PROPRIETARIO): ?>
          <li>Data di nascita: <?php echo date("d/m/Y",strtotime($DATA_NASCITA_PROPRIETARIO));  ?> </li>
          <?php endif; ?>
        </ul>
  
        <h4>Dati dell'immobile</h4>
        <ul>                
          <li>Estremi catastali identificativi dell'unit&agrave, immobiliare: <ul><li> foglio: <?php echo $ESTREMI_CATASTALI_FOGLIO; ?> </li> 
                                                                              <li>    particella: <?php echo $ESTREMI_CATASTALI_PARTICELLA; ?> </li> 
                                                                              <li> subalterno: <?php echo $ESTREMI_CATASTALI_SUBALTERNO; ?></li></ul>
                                                                              </li>
          <li>Numero vani catastali: <?php echo $NUMERO_VANI; ?></li>
          <li>Categoria Catastale: <?php echo $CATEGORIA_CATASTALE; ?></li>
          <li>Indicatore stato conservazione fabbricato: <?php echo $descr_stato_conservazione_fabbricato; ?></li>
          <li>Indicatore stato conservazione alloggio: <?php echo $descr_stato_conservazione_alloggio; ?> </li>
        </ul>
      </div>
    </div>
    <!-- /panel panel-default-->
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">Ammontare contributi e generale </h4>
      </div>
      <div class="panel-body">
        <h4> Ammontare contributi</h4>
        <ul>
        
        <?php if ($TIPO_DOMANDA=='1'): ?>
          <li>Contributo ammesso a copertura della morosità: <?php echo $CONTRIBUTO_AMMESSO_COPERTURA; ?></li>
          <li>Contributo ammesso per deposito cauzionale: <?php echo $CONTRIBUTO_AMMESSO_CAUZIONALE; ?></li>
          <li>Contributo ammesso per le mensilità di differimento: <?php echo $CONTRIBUTO_AMMESSO_DIFFERIMENTO; ?></li>
          <li>Contributo ammesso per le mensilità del nuovo contratto a canone concordato: <?php echo $CONTRIBUTO_AMMESSO_NUOVO_CONTRATTO; ?></li>
           <li>Totale contributo ammesso: <?php echo $TOTALE_CONTRIBUTO; ?></li>
         <?php endif; ?>  
        <?php if ($TIPO_DOMANDA=='2'): ?>                                        
          <li>Contributo inquilino ammesso: <?php echo $CONTRIBUTO_INQUILINO_AMMESSO; ?></li>
         <!-- <li>Contributo inquilino erogato: <?php echo $CONTRIBUTO_INQUILINO_EROGATO; ?></li> -->         
          <li>Contributo proprietario ammesso:  <?php echo $CONTRIBUTO_PROPRIETARIO_AMMESSO; ?> </li>
          <li>Cofinanziamento comunale:  <?php echo $COFINANZIAMENTO; ?> </li>
          <li>Fondo di garanzia al proprietario: <?php echo $descr_fondo_garanzia_proprietario; ?> </li>
          <li>Ammontare fondo di garanzia al proprietario: <?php echo $AMMONTARE_FONDO_GARANZIA_PROPRIETARIO; ?> </li>
             <?php if ($SCADENZA_FONDO_GARANZIA_PROPRIETARIO): ?>
                 <li>Scadenza Fondo di garanzia al proprietario: <?php echo date("d/m/Y",strtotime($SCADENZA_FONDO_GARANZIA_PROPRIETARIO));  ?> </li>
             <?php endif; ?>
        <?php endif; ?> 
        
        <?php if ($DATA_LIQUIDAZIONE): ?>
                 <li>Data del provvedimento comunale di liquidazione: <?php echo date("d/m/Y",strtotime($DATA_LIQUIDAZIONE));  ?> </li>
        <?php endif; ?>  
        <li>Numero del provvedimento comunale di liquidazione: <?php echo $NUMERO_LIQUIDAZIONE; ?> </li>
        <li>Annualità di riferimento: <?php echo $ANNUALITA_LIQUIDAZIONE; ?> </li>
                                                                                         
        </ul>
        <h4>Generale</h4>
        <ul>
        <?php if ($DATA_DOMANDA): ?>
          <li>Data della domanda:  <?php echo date("d/m/Y",strtotime($DATA_DOMANDA));  ?> </li>
        <?php endif; ?>  
          <li>Numero Protocollo Domanda:  <?php echo $NUMERO_PROTOCOLLO; ?></li>
          <?php if ($DATA_PROTOCOLLO): ?>
          <li>Data protocollo domanda:  <?php echo date("d/m/Y",strtotime($DATA_PROTOCOLLO));  ?></li>
          <?php endif; ?>
          
        </ul>
        
        <span class="pull-right print-only">
        <br />
              <br />
        Data ____________ firma leggibile __________________________________
        </span>
        
      </div>
      
       
      
    </div>
    <!-- /panel panel-default-->
    

  </div>
  <!-- /panel-group-->
 
  
</div>