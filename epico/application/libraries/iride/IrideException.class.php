<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

//
// +---------------------------------------------------------------------------+
// | IrideException   Ver. 1.00                    PHP 5                       |
// +---------------------------------------------------------------------------+
// | Copyright (©) 2007 GM                                         |
// +---------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or             |
// | modify it under the same terms as Perl itself.                            |
// |                                                                           |
// | Permission granted to use and modify this library so long as the          |
// | copyright above is maintained, modifications are documented, and          |
// | credit is given for any use of the library.                               |
// +---------------------------------------------------------------------------+
// | Author: GM                            |
// | Author: Alessandro Battezzati
// +---------------------------------------------------------------------------+
//

class IrideException extends Exception {

	// -------------------------------------------------------------------------
	// Properties
	// -------------------------------------------------------------------------

	private $irideErrorCode = null;
	private $irideErrorMessage = null;
	private $exceptionType = null;
	private $exceptionMessage = null;

	private $exceptions = array(
		'AuthException'					=> 'Username e/o password risultano non corretti.',
		'BadRuoloException'				=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'CertException'					=> 'Si e\' verificato un problema con il certificato.',
		'CertOutsideValidityException'	=> 'Il certificato digitale non puo\' essere utilizzato perche\' e\' scaduto.',
		'CertRevokedException'			=> 'Il certificato digitale non puo\' essere utilizzato perche\' e\' stato revocato.',
		'IdentitaNonAutenticaException'	=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'IdProviderNotFoundException'	=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'InactiveAccountException'		=> 'L\'account utilizzato risulta disabilitato.',
		'InternalException'				=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'MalformedIdTokenException'		=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'MalformedRuoloException'		=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'MalformedUsernameException'	=> 'Username e/o password risultano non corretti.',
		'NoSuchApplicationException'	=> 'L\'application specificata non esiste.',
		'NoSuchUseCaseException'		=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'PasswordExpiredException'		=> 'Username e password sono corretti, ma la password è scaduta.',
		'BadIdentitaException'			=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'BadPasswordException'			=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'CSIException'					=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'NoSuchActorException'			=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'SystemException'				=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'UnrecoverableException'		=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'UserException'					=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.',
		'SAXException'					=> 'Si e\' verificato un errore. Ripetere l\'operazione o contattare il servizio di assistenza.'
	);


	// -------------------------------------------------------------------------
	// Costruttore
	// -------------------------------------------------------------------------

	function __construct($iec = null, $iem = null) {

		$this->irideErrorCode = $iec;
		$this->irideErrorMessage = $iem;

		if (! is_null($this->irideErrorMessage)) {
			foreach (array_keys($this->exceptions) as $exceptionKey) {
				if (preg_match("/$exceptionKey/i", $this->irideErrorMessage)) {
					$this->exceptionType = $exceptionKey;
					$this->exceptionMessage = $this->exceptions[$exceptionKey];
					break;
				}
			}
		}

		if (is_null($this->exceptionType)) {
			$this->exceptionType = $this->irideErrorCode;
			$this->exceptionMessage = $this->irideErrorMessage;
		}

		parent::__construct($this->exceptionType, 0);
	}


	// -------------------------------------------------------------------------
	// Getters
	// -------------------------------------------------------------------------

	function getIrideErrorCode() {
		return $this->irideErrorCode;
	}

	function getIrideErrorMessage() {
		return $this->irideErrorMessage;
	}

	function getExceptionType() {
		return $this->exceptionType;
	}

	function getExceptionMessage() {
		return $this->exceptionMessage;
	}

}

?>