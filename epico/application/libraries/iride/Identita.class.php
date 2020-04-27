<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

//
// +---------------------------------------------------------------------------+
// | Identita   Ver. 1.20                       PHP 5                          |
// +---------------------------------------------------------------------------+
// | Copyright () 2007 GM/A.Battezzati                                         |
// +---------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or             |
// | modify it under the same terms as Perl itself.                            |
// |                                                                           |
// | Permission granted to use and modify this library so long as the          |
// | copyright above is maintained, modifications are documented, and          |
// | credit is given for any use of the library.                               |
// +---------------------------------------------------------------------------+
// | Author: GM                           |
//   Author: Alessandro Battezzati
// +---------------------------------------------------------------------------+
//

class Identita {

	private $nome;
	private $livelloAutenticazione;
	private $codFiscale;
	private $timestamp;
	private $mac;
	private $idProvider;
	private $rappresentazioneInterna;
	private $cognome;


	public function Identita(array $irideHashMap) {
		$this->nome = $irideHashMap['nome'];
		$this->livelloAutenticazione = $irideHashMap['livelloAutenticazione'];
		$this->codFiscale = $irideHashMap['codFiscale'];
		$this->timestamp = $irideHashMap['timestamp'];
		$this->mac = $irideHashMap['mac'];
		$this->idProvider = $irideHashMap['idProvider'];
		$this->rappresentazioneInterna = $irideHashMap['rappresentazioneInterna'];
		$this->cognome = $irideHashMap['cognome'];
	}


	public function setNome($nome) {
		$this->nome = $nome;
	}

	public function getNome() {
		return $this->nome;
	}

	public function setLivelloAutenticazione($livelloAutenticazione) {
		$this->livelloAutenticazione = $livelloAutenticazione;
	}

	public function getLivelloAutenticazione() {
		return $this->livelloAutenticazione;
	}

	public function setCodFiscale($codFiscale) {
		$this->codFiscale = $codFiscale;
	}

	public function getCodFiscale() {
		return $this->codFiscale;
	}

	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	public function getTimestamp() {
		return $this->timestamp;
	}

	public function setMac($mac) {
		$this->mac = $mac;
	}

	public function getMac() {
		return $this->mac;
	}

	public function setIdProvider($idProvider) {
		$this->idProvider = $idProvider;
	}

	public function getIdProvider() {
		return $this->idProvider;
	}

	public function setRappresentazioneInterna($rappresentazioneInterna) {
		$this->rappresentazioneInterna = $rappresentazioneInterna;
	}

	public function getRappresentazioneInterna() {
		return $this->rappresentazioneInterna;
	}

	public function setCognome($cognome) {
		$this->cognome = $cognome;
	}

	public function getCognome() {
		return $this->cognome;
	}

	public function toHashMap() {
		return array('nome' => $this->nome,
			'livelloAutenticazione' => $this->livelloAutenticazione,
			'codFiscale' => $this->codFiscale,
			'timestamp' => $this->timestamp,
			'mac' => $this->mac,
			'idProvider' => $this->idProvider,
			'rappresentazioneInterna' => $this->rappresentazioneInterna,
			'cognome' => $this->cognome);
	}

}

?>