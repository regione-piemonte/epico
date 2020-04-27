<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<script type="text/javascript" src="/servizi/epico/assets/js/epico.js"></script>

	  <?php
      $attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'form_list', 'method'=>'get');
      echo form_open('edilizia/elenco',$attributes);
    ?>

<div class="container">
<?php $this->load->view('include/menu_edilizia', $_ci_data['_ci_vars']);?>

     <h3>
     <?php if ($_SESSION['tipo_domanda_edilizia']):?>
    	Lettera <?php echo $_SESSION['tipo_domanda_edilizia']; ?>)
     <?php endif; ?>
     </h3>

      <?php
      if ($usertype != 'REG') { ?>
      <h4>Gestione domanda</h4>
      <?php } ?>


       <div class="alert alert-info col-sm-5">
      <p>Stai operando come: <strong>
               <?php
		if($_SESSION['atc']){
			echo $_SESSION['atc'];
		}
      elseif ($usertype == 'COM') {
        echo 'Comune di '.$comune_utente->DESCRIZIONE;
      } elseif ($usertype == 'REG') {
         echo 'Regione';
      } elseif ($usertype == 'SUP') {
         echo 'Superuser';
      }
       ?></strong></p>
        </div>

    <div class="col-sm-7">
     <div class="pull-right"> <!--form-group puls-group -->
    <?php if (FALSE): //($usertype == 'COM' or $usertype == 'SUP'): ?>
        <a href="<?php echo base_url().'index.php/edilizia/domanda/?tipo_domanda='.$_SESSION['tipo_domanda_edilizia']; ?>" class="btn btn-primary btn-lg"><span class="icon icon-plus"></span> nuova domanda</a>
    <?php endif; ?>
      </div>
      </div>

<div class="accordion panel-group box-ricerca">
</div>

<h4>ELENCO DOMANDE</h4>
        <?php if ($no_result): ?>
        <div class="alert alert-info">
          <p>Non sono presenti domande.</p>
        </div>
        <?php else: ?>
        <p>Trovate <strong><?php echo $num_domande; ?></strong> domande</p>
<table class="table table-hover table-striped table-bordered">
      <thead>

        <tr>
          <th>ID domanda</th>
          <!--th>Indirizzo</th-->
			 <?php if ($_SESSION['tipo_domanda_edilizia'] == 'b'): ?>
				 <th>Oggetto</th>
			 <?php endif; ?>
          <?php if ($usertype != 'REG'): ?>
          <th>Stato domanda</th>
          <?php endif; ?>
          <th>Richiedente</th>
			 <th>Punteggio</th>
          <th>Data domanda</th>
          <th class="no-print">Azione</th>
        </tr>
      </thead>
      <tbody>

      <?php foreach ($domande as &$domanda): ?>
      <?php //print_r($domanda); ?>
      <tr>
     <td><?php echo $domanda->ID_DOMANDA; ?></td>
     <!--td><?php echo $domanda->INDIRIZZO; ?> <?php echo $domanda->CIVICO; ?></td-->
	  <?php if ($_SESSION['tipo_domanda_edilizia'] == 'b'): ?>
		  <td><?php echo $domanda->OGGETTO; ?></td>
	 <?php endif; ?>
     <?php if ($usertype != 'REG'): ?>
     <td><span class="label label-<?php if ($domanda->STATO_DOMANDA=='2') {echo 'success';} else {echo 'warning';}?>">
      <?php echo $domanda->DESCRIZIONE_STATO_DOMANDA; ?></span>
      <?php if ($domanda->ASSEGNATARIO) { ?> 
      	<span class="label label-primary">Assegnata</span>
      <?php } ?> 
     </td>
       <?php endif; ?>
       <td><?php echo $domanda->DESCRIZIONE_RICHIEDENTE; ?></td>
		 <td><?php echo $domanda->PUNTEGGIO; ?></td>
          <?php  $usertype='COM'; ?>
     <td><?php echo dateFormatter($domanda->DATA_DOMANDA); ?></td>
           
       <td class="no-print">
          <?php if ($usertype == 'COM' or $usertype == 'SUP'): ?>
               <?php if ($domanda->STATO_DOMANDA!='2'): ?>
                <a href="<?php echo base_url().'index.php/edilizia/domanda/'.$domanda->ID_DOMANDA; ?>" title="modifica bozza"><span class="icon icon-edit"></span><span class="hidden">modifica bozza</span></a>
                <a href="<?php echo base_url().'index.php/edilizia/elimina/'.$domanda->ID_DOMANDA; ?>" title="elimina bozza" id="elimina_bozza"><span class="icon icon-remove"></span><span class="hidden">elimina bozza</span></a>
                <?php else: ?>
                  <a href="<?php echo base_url().'index.php/edilizia/visualizza/'.$domanda->ID_DOMANDA; ?>" title="visualizza dettaglio"><span class="icon <?php if ($domanda->ASSEGNATARIO) echo 'text-success'; ?> icon-file-text"></span><span class="hidden">visualizza dettaglio</span></a>
                <?php endif; ?>
            <?php else: ?>
              <a href="<?php echo base_url().'index.php/edilizia/visualizza/'.$domanda->ID_DOMANDA; ?>" title="visualizza dettaglio"><span class="icon <?php if ($domanda->ASSEGNATARIO) echo 'text-success'; ?> icon-file-text"></span><span class="hidden">visualizza dettaglio</span></a>
            <?php endif; ?>

          </td>
        </tr>
      <?php endforeach;?>
      </tbody>
    </table>
<?php endif; ?>
<div class="text-center">
    <ul class="pagination">
    <?php
      echo $pagination_link;
     ?>
    </ul>
</div>
   <div class="form-group puls-group">
    <div class="col-sm-12">
      <?php if(FALSE)://if ($usertype == 'REG'): ?>
        <a href="<?php echo base_url().'index.php/edilizia/export_domande'; ?>" type="reset" class="btn btn-default"><span class="icon icon-table"></span> Scarica tutto il report</a>
      <?php endif; ?>
       <div class="accordion panel-group box-ricerca">
          <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="#tab-ricerca_report"><span>Report</span></h4>
               </div>
              <div id="tab-ricerca_report" class="panel-collapse">
                <div class="panel-body">

             <?php if ($usertype == 'COM'): ?>
             <div class="col-sm-4">                                    
              <button type="submit" name="submit" id="export_domande_sintesi" class="btn btn-default" value="export_domande_sintesi" ><span class="icon icon-table"></span> Scarica il report di sintesi</button>
              </div>
            <?php endif; ?>
            </div>
           </div>
            </div>
      </div>
    </div>
</div--><!-- puls-group-->



</div><!--container-->
</form>
