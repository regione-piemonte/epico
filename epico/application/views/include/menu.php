<!--
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
-->

<?php $servizio = $this->uri->segment(1); ?>
<ul class="nav nav-pills no-print">
<li role="presentation">
	<a href="<?php echo base_url().'index.php/domande/'; ?>"><span class="icon icon-home"></span> Home</a>
</li>

<li <?php if ($servizio == 'domande' and ($_SESSION['tipo_domanda'] == 2 or $TIPO_DOMANDA == 2)) echo 'class="active"'; ?> role="presentation">
	<a href="<?php echo base_url().'index.php/domande/elenco/?tipo_domanda=2'; ?>">Agenzie Sociali per la Locazione</a>
</li>
<li <?php if ($servizio == 'domande' and ($_SESSION['tipo_domanda'] == 1 or $TIPO_DOMANDA == 1)) echo 'class="active"'; ?> role="presentation">
	<a href="<?php echo base_url().'index.php/domande/elenco/?tipo_domanda=1'; ?>">Fondo per la Morosit&agrave; Incolpevole</a>
</li>

<li role="presentation">
	<a href="#">Fondo sostegno alla locazione</a>
</li>
<?php if ($usertype == 'REG' || $usertype == 'SUP') : ?>
	<li <?php if ($servizio == 'importi') echo 'class="active"'; ?> role="presentation">
		<a href="<?php echo base_url().'index.php/importi'; ?>">Importi assegnati/liquidati</a>
	</li>
<?php endif; ?>
</ul>