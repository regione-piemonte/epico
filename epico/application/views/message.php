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
     <div class="row"><div class="col-md-12">
     
<?php $this->load->view('include/menu', $_ci_data['_ci_vars']);?>    
       
     <div class="alert alert-info col-sm-5">
      <p>Stai operando come: <strong>
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
           <a href="<?php echo base_url().'index.php/domande/domanda/?tipo_domanda='.$TIPO_DOMANDA; ?>" class="btn btn-primary btn-lg"><span class="icon icon-plus"></span> nuova domanda</a>
           <a href="<?php echo base_url().'index.php/domande/elenco/?tipo_domanda='.$TIPO_DOMANDA; ?>" class="btn btn-primary btn-lg"><span class="icon icon-list"></span> elenco domande</a>
            
          </div></div>
      </div></div>
    </div>
<div class="container">
      <div class="alert alert-<?php echo $error_type;?> col-sm-12">
          <?php echo $msg;?> 
      </div>
</div>