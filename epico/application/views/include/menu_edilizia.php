<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<ul class="nav nav-pills">
<li role="presentation"><a href="<?php echo base_url().'index.php/edilizia/'; ?>"><span class="icon icon-home"></span> Home</a></li>
 
<li role="presentation" <?php if ($_SESSION['tipo_domanda_edilizia']=='a' or $TIPO_DOMANDA_EDILIZIA=='a'){echo 'class="active"';} ?> ><a href="<?php echo base_url().'index.php/edilizia/elenco/?tipo_domanda_edilizia=a'; ?>">Lettera a)</a></li>
<li role="presentation" <?php if ($_SESSION['tipo_domanda_edilizia']=='b' or $TIPO_DOMANDA_EDILIZIA=='b'){echo 'class="active"';} ?>><a href="<?php echo base_url().'index.php/edilizia/elenco/?tipo_domanda_edilizia=b'; ?>">Lettera b)</a></li>

</ul>
