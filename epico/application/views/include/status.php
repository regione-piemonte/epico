
<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->
<div class="panel panel-user row-fluid no-print">
  <div class="span10">
    <dl class="dl-horizontal">
      <dt class="utente">&nbsp;Utente</dt>
      <dd><?php echo $identita_shib['nome']; ?> <?php echo $identita_shib['cognome']; ?> - <strong>
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
       ?>
      </strong> </dd>
    </dl>
  </div>
  <div class="span2"> <a class="btn btn-default" href="<?php echo base_url().'index.php/domande/logout'; ?>">esci&nbsp;</a> </div>
</div>
