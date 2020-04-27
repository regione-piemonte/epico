<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<script type="text/javascript" src="/servizi/epico/assets/js/epico.js"></script>

	  <?php 
      $attributes = array('class' => 'form-horizontal validateForm', 'role' => 'form', 'id'=>'form_list', 'method'=>'get');
      echo form_open('domande/elenco',$attributes);
    ?>  

<div class="container">

<?php $this->load->view('include/menu', $_ci_data['_ci_vars']);?>

     <h3>
     <?php if ($usertype == 'COM' && $filter['tipo_domanda']=='1'):?> 
     Fondo per la Morosit&agrave; Incolpevole
      <?php elseif ($usertype == 'COM' && $filter['tipo_domanda']=='2'): ?>
      Agenzie sociali per la locazione
       <?php endif; ?>
     </h3>
   
      <?php 
      if ($usertype != 'REG') { ?>
      <h4>Gestione domanda</h4>
      <?php } ?>
      
      
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
     <div class="pull-right"> <!--form-group puls-group -->
    <?php if ($usertype == 'COM' or $usertype == 'SUP'): ?>      
        <a href="<?php echo base_url().'index.php/domande/domanda/?tipo_domanda='.$_SESSION['tipo_domanda']; ?>" class="btn btn-primary btn-lg"><span class="icon icon-plus"></span> nuova domanda</a>
    <?php endif; ?>
      </div>
    </div>
 <div class="accordion panel-group box-ricerca">    
    <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="#tab-ricerca"><span> Ricerca </span>
    </h4>
 </div>
       
 <div id="tab-ricerca" class="panel-collapse <?php if ($filter['active']) {echo 'in';} ?>">
 <div class="panel-body">
  <div class="form-group"> 
    <label for="id_domanda" class="col-sm-2 control-label">ID domanda</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" id="id_domanda" name="id_domanda" value="<?php echo set_value('id_domanda',$filter['id_domanda']); ?>" placeholder="inserire l'id della domanda" />
      </div>
      <?php if ($usertype != 'COM'): ?>
      <label for="tipo_domanda" class="col-sm-2 control-label">Tipo domanda</label>
      <div class="col-sm-4">
         <?php
            $attr='id="tipo_domanda" class="form-control"';                    
            echo form_dropdown('tipo_domanda', $tipo_domande, $filter['tipo_domanda'],$attr); 
       ?>
      </div>
      <?php else: ?>
      <label for="stato_domanda" class="col-sm-2 control-label">Stato domanda</label>
      <div class="col-sm-4">
         <?php                                                                
            $attr='id="stato_domanda" class="form-control"';                    
            echo form_dropdown('stato_domanda', $stato_domande, $filter['stato_domanda'],$attr); 
       ?>
      </div> 
      
      <?php endif; ?>
      
 </div><!-- class="form-group"-->  
 <?php //print_r($filter); ?>
  <div class="form-group"> 
    <label for="nome_richiedente" class="col-sm-2 control-label">Nome richiedente</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" value="<?php echo set_value('nome_richiedente',$filter['nome']); ?>" name="nome_richiedente" id="nome_richiedente" placeholder="Inserire nome richiedente" />
      </div>
      
    <label for="cognome_richiedente" class="col-sm-2 control-label">Cognome richiedente</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" id="cognome_richiedente" value="<?php echo set_value('cognome_richiedente',$filter['cognome']); ?>"  name="cognome_richiedente"  placeholder="Inserire cognome richiedente" />
      </div>          
 </div><!-- class="form-group"-->  
  <div class="form-group"> 
 
      <?php if ($usertype != 'COM'): ?>     
      <label for="comune_inseritore" class="col-sm-2 control-label">Comune operatore</label>
      <div class="col-sm-4">
      <?php      
            $attr='id="comune_inseritore" class="form-control"';                    
            echo form_dropdown('comune_inseritore', $comuni_inseritori, $filter['comune_inseritore'],$attr); 
        ?>     
           
      </div>
 
      <label for="operatore" class="col-sm-2 control-label">Operatore</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" value="<?php echo set_value('operatore',$filter['operatore']); ?>" name="operatore" id="operatore" placeholder="Inserire nome operatore" />
      </div>
        <?php endif; ?> 
        
   </div>     
<div class="form-group"> 

      <label for="data_da" class="col-sm-2 control-label">Data domanda da</label>
      <div class="col-sm-4 calendar-control">
          <input type="text" class="form-control calendarioCustom calendar-control" id="data_da" value="<?php echo set_value('data_da',$filter['data_da']); ?>"  name="data_da"  placeholder="" />
      </div>
      
      <label for="data_a" class="col-sm-2 control-label">Data domanda a</label>
      <div class="col-sm-4 calendar-control">
          <input type="text" class="form-control calendarioCustom calendar-control" id="data_a" value="<?php echo set_value('data_a',$filter['data_a']); ?>"  name="data_a"  placeholder="" />
      </div> 
      
    <div class="col-sm-offset-2 col-sm-4 form-group-check"><!-- -->
    
            <?php if ($filter['tipo_domanda']=='1' or $usertype != 'COM'):?>           
           <label for="invalidita"  class="control-label"> 
                 <?php 
                 $attributes = 'id=invalidita';
                 echo form_checkbox('invalidita', '1', ($filter['invalidita'] =='1' ? '1' : '0'),$attributes ); ?> 
                 Invalidit&agrave; accertata maggiore o uguale al 74%
            </label>
             <?php endif; ?> 
            <label for="minorenne"  class="control-label"> 
                 <?php
                 $attributes = 'id=minorenne'; 
                 echo form_checkbox('minorenne', '1', ($filter['minorenne'] =='1' ? '1' : '0'),$attributes ); ?> 
                 Minorenni nel nucleo familiare
            </label>
            <label for="over70"  class="control-label"> 
                 <?php
                   $attributes = 'id=over70'; 
                 echo form_checkbox('over70', '1', ($filter['over70'] =='1' ? '1' : '0'),$attributes ); ?> 
                 Anziani con più di 70 anni
            </label>
    </div>  
    
    
      <?php if ($usertype == 'COM'): ?>  

      <label for="operatore" class="col-sm-2 control-label">Operatore</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" value="<?php echo set_value('operatore',$filter['operatore']); ?>" name="operatore" id="operatore" placeholder="Inserire nome operatore" />
      </div>
      
     <?php endif; ?>      
      
    </div><!--form-group-->
    
 
    <div class="form-group puls-group"> 
      <div class="col-sm-offset-2 col-sm-10">
        <button type="reset" class="btn btn-default">pulisci campi</button>
        <button type="submit" class="btn btn-primary">cerca</button>
      </div>
    </div>

</div>
          <!--/.panel-body -->
        </div>
        <!-- /#tab-ricerca -->
      </div>
      <!-- /.panel.panel-default -->
      
  </div><!-- /accordion-->
<h4>ELENCO DOMANDE</h4>
        <?php if ($no_result): ?>
        <div class="alert alert-info">
          <p>Non sono presenti risultati per questa ricerca.</p>
        </div>
        <?php else: ?>
        <p>Trovate <strong><?php echo $num_domande; ?></strong> domande</p>
<table class="table table-hover table-striped table-bordered">
      <thead>
        <tr>
          <th>ID domanda</th>
          <th>Nome richiedente</th>
          <?php if ($usertype != 'REG'): ?>
          <th>Stato domanda</th>
          <?php endif; ?>
          <?php if ($usertype != 'COM'): ?>      
            <th>Tipo domanda</th>   
            <th>Comune operatore</th>
           <?php endif; ?>
          <?php if ($usertype != 'REG'): ?>
          <th>Operatore</th>                       
           <?php endif; ?>      
          <th>Data domanda</th>
          <th class="no-print">Azione</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($domande as &$domanda): ?>
      <?php //print_r($domanda); ?>
      <tr>
     <td><?php echo $domanda->ID_DOMANDA; ?></td>
     <td><?php echo $domanda->NOME; ?> <?php echo $domanda->COGNOME; ?></td>
     <?php if ($usertype != 'REG'): ?> 
     <td><span class="label label-<?php if ($domanda->STATO_DOMANDA=='2') {echo 'success';} else {echo 'warning';}?>">
      <?php echo $domanda->DESCRIZIONE_STATO_DOMANDA; ?></span>
     </td>       
       <?php endif; ?>        
      <?php if ($usertype != 'COM'): ?>  
       <td><?php echo $domanda->DESCRIZIONE_TIPO_DOMANDA; ?></td>      
       <td><?php echo $domanda->DESCRIZIONE_COMUNE_INSERITORE; ?></td>
     <?php endif; ?>                                      
      <?php if ($usertype != 'REG'): ?>
        <td><?php echo $domanda->OPERATORE; ?> </td>
      <?php endif; ?>
     <td><?php echo dateFormatter($domanda->DATA_DOMANDA); ?></td>
     
       <td class="no-print">
       
          <?php if ($usertype == 'COM' or $usertype == 'SUP'): ?>
               <?php if ($domanda->STATO_DOMANDA!='2'): ?>
                <a href="<?php echo base_url().'index.php/domande/domanda/'.$domanda->ID_DOMANDA; ?>" title="modifica bozza"><span class="icon icon-edit"></span><span class="hidden">modifica bozza</span></a>          
                <a href="<?php echo base_url().'index.php/domande/elimina/'.$domanda->ID_DOMANDA; ?>" title="elimina bozza" id="elimina_bozza"><span class="icon icon-remove"></span><span class="hidden">elimina bozza</span></a>              
                <?php else: ?>
                  <a href="<?php echo base_url().'index.php/domande/visualizza/'.$domanda->ID_DOMANDA; ?>" title="visualizza dettaglio"><span class="icon icon-file-text"></span><span class="hidden">visualizza dettaglio</span></a>         
                <?php endif; ?>   
            <?php else: ?>
              <a href="<?php echo base_url().'index.php/domande/visualizza/'.$domanda->ID_DOMANDA; ?>" title="visualizza dettaglio"><span class="icon icon-file-text"></span><span class="hidden">visualizza dettaglio</span></a>     
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
       <div class="accordion panel-group box-ricerca">    
          <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="#tab-ricerca_report"><span>Report</span></h4>
               </div>
               <div id="tab-ricerca_report" class="panel-collapse">
	               <div class="panel-body">
				   <?php if ($usertype == 'COM'): ?>
				   <label for="data_da_report" class="col-sm-2 control-label">Data domanda da</label>
				   <div class="col-sm-2 calendar-control">
					   <input type="text" class="form-control calendarioCustom calendar-control" id="data_da_report" value="<?php echo set_value('data_da',$filter['data_da_report']); ?>"  name="data_da_report"  placeholder="" />
					</div>                        
					<label for="data_a_report" class="col-sm-2 control-label">Data domanda a</label>
					<div class="col-sm-2 calendar-control">
                	    <input type="text" class="form-control calendarioCustom calendar-control" id="data_a_report" value="<?php echo set_value('data_da',$filter['data_a_report']); ?>"  name="data_a_report"  placeholder="" />
          </div>       
          <div class="col-sm-12">           
  					<button type="submit" name="submit" id="export_domande_sintesi" class="btn btn-default" value="export_domande_sintesi" ><span class="icon icon-table"></span> Scarica il report di sintesi</button>
            <?php if ($filter['tipo_domanda']=='1'): ?>
              <button type="submit" name="submit" id="export_domande_sintesi_prefetture" class="btn btn-default" value="export_domande_sintesi_prefetture" ><span class="icon icon-table"></span> Scarica il report di sintesi per prefetture</button>
            <?php endif; ?>
          
					<?php endif; ?>
					<?php if ($usertype == 'REG'): ?>
					<button type="submit" name="submit" id="export_domande" class="btn btn-default" value="export_domande" >
						<span class="icon icon-table"></span> 
						<?php 
							if ($filter['tipo_domanda']=='1') echo 'Scarica il report Fondo per la Morosit&agrave; Incolpevole';
							elseif ($filter['tipo_domanda']=='2') echo 'Scarica il report Agenzie sociali per la locazione';
							else echo 'Scarica il report totale';
						?>
					</button>
					<?php endif; ?>
          	</div>
        			</div>
           		</div>
            </div> 
     
      </div>
    </div>      
</div><!-- puls-group-->



</div><!--container-->
</form>