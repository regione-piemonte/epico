<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

//
// +---------------------------------------------------------------------------+
// | Ruolo   Ver. 1.00                        PHP 5                            |
// +---------------------------------------------------------------------------+
// | Copyright () 2007 GM                                        |
// +---------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or             |
// | modify it under the same terms as Perl itself.                            |
// |                                                                           |
// | Permission granted to use and modify this library so long as the          |
// | copyright above is maintained, modifications are documented, and          |
// | credit is given for any use of the library.                               |
// +---------------------------------------------------------------------------+
// | Author: GM\ Alessandro Battezzati                           |
// +---------------------------------------------------------------------------+
//

class Ruolo {

	private $codiceRuolo;
	private $mnemonico;
	private $codiceDominio;


	public function Ruolo(array $ruoloHashMap) {
		$this->codiceRuolo = $ruoloHashMap['codiceRuolo'];
		$this->mnemonico = $ruoloHashMap['mnemonico'];
		$this->codiceDominio = $ruoloHashMap['codiceDominio'];
	}


	public function setCodiceRuolo($codiceRuolo) {
		$this->codiceRuolo = $codiceRuolo;
	}

	public function getCodiceRuolo() {
		return $this->codiceRuolo;
	}

	public function setMnemonico($mnemonico) {
		$this->mnemonico = $mnemonico;
	}

	public function getMnemonico() {
		return $this->mnemonico;
	}

	public function setCodiceDominio($codiceDominio) {
		$this->codiceDominio = $codiceDominio;
	}

	public function getCodiceDominio() {
		return $this->codiceDominio;
	}

	public function toHashMap() {
		return array('codiceRuolo' => $this->codiceRuolo,
			'mnemonico' => $this->mnemonico,
			'codiceDominio' => $this->codiceDominio);
	}

}

?>