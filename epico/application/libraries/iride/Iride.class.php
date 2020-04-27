<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

//
// +---------------------------------------------------------------------------+
// | Iride   Ver. 1.00                        PHP 5                            |
// +---------------------------------------------------------------------------+
// | Copyright () 
// +---------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or             |
// | modify it under the same terms as Perl itself.                            |
// |                                                                           |
// | Permission granted to use and modify this library so long as the          |
// | copyright above is maintained, modifications are documented, and          |
// | credit is given for any use of the library.                               |
// +---------------------------------------------------------------------------+
// | Author: GM, Alessandro Battezzati                            |
// +---------------------------------------------------------------------------+
//

//require_once('nusoap.php');

/*
require_once('Actor.class.php');
require_once('Application.class.php');
require_once('Identita.class.php');
require_once('Ruolo.class.php');
require_once('UseCase.class.php');
*/
require_once('Ruolo.class.php');
require_once('IrideException.class.php');


class Iride {
	
	// -------------------------------------------------------------------------
	// Costanti
	// -------------------------------------------------------------------------
	
	const ERROR_INVALID_WSDL		= 'L\' endpoint WSDL fornito non e\' valido';
	const ERROR_INVALID_USERNAME	= 'Lo username fornito non e\' valido';
	const ERROR_INVALID_PASSWORD	= 'La password fornito non e\' valido';
	const ERROR_INVALID_PIN			= 'Il pin fornito non e\' valido';
	const ERROR_INVALID_CERT		= 'Il certificato fornito non e\' valido';
	const ERROR_INVALID_IDENTITA	= 'L\'identita\' fornita non e\' valida';
	const ERROR_INVALID_USECASE		= 'Lo usecase fornito non e\' valido';
	const ERROR_INVALID_APPLICATION	= 'L\'applicazione fornita non e\' valida';
	const ERROR_INVALID_RUOLO		= 'Il ruolo fornito non e\' valido';
	
	
	// -------------------------------------------------------------------------
	// Properties
	// -------------------------------------------------------------------------
	
	private $client;
	private $irideErrorCode;
	private $irideErrorMessage;
	
	
	// -------------------------------------------------------------------------
	// Costruttore
	// -------------------------------------------------------------------------
	
	public function __construct($wsdlIride = null) {
		if (is_null($wsdlIride) or ($wsdlIride == '')) {
			throw new IrideException('Iride::construct', self::ERROR_INVALID_WSDL);
		}
		
/*
 * customizzato nusoap.php per problemi di compilazione
 * ridenominata la classe soapclient in mysoapclient
 */
		$this->client = new mysoapclient($wsdlIride, true);
		if ($this->isSoapError($this->client)) {
			throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
		}
	}


	// -------------------------------------------------------------------------
	// Metodi per l'autenticazione
	// -------------------------------------------------------------------------

	/**
     * Autentica un utente che ha fornito USERNAME e PASSWORD.
     *
     * @param $username
     * @param $password
     * @return
     */
	public function identificaUserPassword($username = null, $password = null){
		if (is_null($username) or ($username == '')) {
			throw new IrideException('Iride::identificaUserPassword-001', self::ERROR_INVALID_USERNAME);
		} elseif (is_null($password) or ($password == '')) {
			throw new IrideException('Iride::identificaUserPassword-002', self::ERROR_INVALID_PASSWORD);
		} else {
			$identitaHashMap = $this->client->call('identificaUserPassword', array('in0' => $username, 'in1' => $password));
		}

		if ($this->isSoapError($identitaHashMap)) {
    		throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
    	} else {
    		return new Identita($identitaHashMap);
    	}
	}

	/**
     * Autentica un utente che ha fornito un CERTIFICATO DIGITALE.
     *
     * @param $certificato
     * @return
     */
    public function identificaCertificato($certificato = null) {
		
		if (is_null($certificato) or ($certificato == '')) {
    		throw new IrideException('Iride::identificaCertificato', self::ERROR_INVALID_CERT);
    	} else {
    		$identitaHashMap = $this->client->call('identificaCertificato', array(new soapval('in0', 'xsd:base64Binary', base64_encode($certificato))));
    	}

    	if ($this->isSoapError($identitaHashMap)) {
    		throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
    	} else {
    		return new Identita($identitaHashMap);
    	}
    }

    /**
     * Autentica un utente che ha fornito USERNAME, PASSWORD e PIN.
     *
     * @param $username
     * @param $password
     * @param $pin
     * @return
     */
    public function identificaUserPasswordPin($username = null, $password = null, $pin = null) {
		
		if (is_null($username) or ($username == '')) {
    		throw new IrideException('Iride::identificaUserPasswordPin-001', self::ERROR_INVALID_USERNAME);
		} elseif (is_null($password) or ($password == '')) {
    		throw new IrideException('Iride::identificaUserPasswordPin-002', self::ERROR_INVALID_PASSWORD);
		} elseif (is_null($pin) or ($pin == '')) {
    		throw new IrideException('Iride::identificaUserPasswordPin-003', self::ERROR_INVALID_PIN);
		} else {
			$identitaHashMap = $this->client->call('identificaUserPasswordPIN', array('in0' => $username, 'in1' => $password, 'in2' => $pin));
		}

		if ($this->isSoapError($identitaHashMap)) {
    		throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
    	} else {
    		return new Identita($identitaHashMap);
    	}
    }


    // -------------------------------------------------------------------------
	// Metodi di inquiry verso Iride
	// -------------------------------------------------------------------------

	/**
	 * Verifica l'accesso ad uno UseCase.
	 *
	 * @param $identita
	 * @param $useCase
	 * @return
	 */
	public function isPersonaAutorizzataInUseCase(Identita $identita = null, UseCase $useCase = null) {
		
		if (is_null($identita)) {
                    throw new IrideException('Iride::isPersonaAutorizzataInUseCase-001', self::ERROR_INVALID_IDENTITA);
                } elseif (is_null($useCase)) {
                        throw new IrideException('Iride::isPersonaAutorizzataInUseCase-002', self::ERROR_INVALID_USECASE);
                } else {
                        $result = $this->client->call('isPersonaAutorizzataInUseCase', array('in0' => $identita->toHashMap(), 'in1' => $useCase->toHashMap()));
                }

                if ($this->isSoapError($result)) {
                        throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
                } else {
                        return $result;
                }
	}

	/**
	 * Restituisce l'elenco degli UseCase associati ad una Application.
	 *
	 * @param $identita
	 * @param $applicazione
	 * @return
	 */
	public function findUseCasesForPersonaInApplication(Identita $identita = null, Application $application = null) {
		
		if (is_null($identita)) {
    		throw new IrideException('Iride::findUseCasesForPersonaInApplication-001', self::ERROR_INVALID_IDENTITA);
    	} elseif (is_null($application)) {
    		throw new IrideException('Iride::findUseCasesForPersonaInApplication-002', self::ERROR_INVALID_APPLICATION);
    	} else {
			$useCases = $this->client->call('findUseCasesForPersonaInApplication', array('in0' => $identita->toHashMap(), 'in1' => $application->toHashMap()));
    	}

    	if ($this->isSoapError($useCases)) {
    		throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
    	} else {
    		$result = array();
    		foreach ($useCases as $useCase) {
    			array_push($result, new UseCase($useCase));
    		}
    		
    		if (count($result) == 0) {
    			return null;
    		} else {
    			return $result;
    		}
    	}
	}

	/**
	 * Verifica la validit� dell'identit� digitale.
	 *
	 * @param $identita
	 * @return
	 */
	public function isIdentitaAutentica(Identita $identita = null) {
		
		if (is_null($identita)) {
    		throw new IrideException('Iride::isIdentitaAutentica', self::ERROR_INVALID_IDENTITA);
    	} else {                
		  	$result = $this->client->call('isIdentitaAutentica', array('in0' => $identita->toHashMap()) );
    	
    
        // Check for errors
        $err = $this->client->getError();
        if ($err) {
        // Display the error
        echo '<h2>Error</h2><pre>' . $err . '</pre>';
    }
    
      
      
      
      }
    	if ($this->isSoapError($result)) {
    		throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
    	} else {
    		return $result;
    	}
	}
  
  public function findApplications( Application $application = null) {
		
		if (is_null($application)) {
    		throw new IrideException('Iride::findApplications', self::ERROR_INVALID_IDENTITA);
    	} else {
		  	$result = $this->client->call('findApplications', array('in0' => $application->toHashMap()));
    	}

    	if ($this->isSoapError($result)) {
    		throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
    	} else {
    		return $result;
    	}
	}

	/**
	 * Restituisce l'XML con le informazioni aggiuntive.
	 *
	 * @param $identita
	 * @param $useCase
	 * @return
	 */
	public function getInfoPersonaInUseCase(Identita $identita = null, UseCase $useCase = null) {		
			$info = $this->client->call('getInfoPersonaInUseCase', array('in0' => $identita->toHashMap(), 'in1' => $useCase->toHashMap() ));
    	return $info;
	}

	/**
	 * Restituisce l'elenco dei Ruoli associati ad uno UseCase.
	 *
	 * @param $identita
	 * @param $useCase
	 * @return
	 */
	public function findRuoliForPersonaInUseCase(Identita $identita = null, UseCase $useCase = null) {
		
		if (is_null($identita)) {
    		throw new IrideException('Iride::findRuoliForPersonaInUseCase-001', self::ERROR_INVALID_IDENTITA);
    	} elseif (is_null($useCase)) {
    		throw new IrideException('Iride::findRuoliForPersonaInUseCase-002', self::ERROR_INVALID_USECASE);
    	} else {
    		$ruoli = $this->client->call('findRuoliForPersonaInUseCase', array('in0' => $identita->toHashMap(), 'in1' => $useCase->toHashMap()));
    	}

    	if ($this->isSoapError($ruoli)) {
    		throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
    	} else {
    		$result = array();
    		foreach ($ruoli as $ruolo) {
    			array_push($result, new Ruolo($ruolo));
    		}
    		
    		if (count($result) == 0) {
    			return null;
    		} else {
    			return $result;
    		}
    	}
	}

	/**
     * Restituisce l'elenco degli Actor associati ad una Application.
     *
     * @param $identita
     * @param $application
     * @return
     */
    public function findRuoliForPersonaInApplication(Identita $identita = null, Application $application = null) {
		
		if (is_null($identita)) {
    		throw new IrideException('Iride::findRuoliForPersonaInApplication-001', self::ERROR_INVALID_IDENTITA);
    	} elseif (is_null($application)) {
    		throw new IrideException('Iride::findRuoliForPersonaInApplication-002', self::ERROR_INVALID_APPLICATION);
    	} else {
    		$ruoli = $this->client->call('findRuoliForPersonaInApplication', array('in0' => $identita->toHashMap(), 'in1' => $application->toHashMap()));
        }
        
        //print_r($ruoli);

    	if ($this->isSoapError($ruoli)) {
    		throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
    	} else {
        return $ruoli;
    	}
	}

	/**
	 * Restituisce lo schema XML delle informazioni aggiuntive.
	 *
	 * @param $ruolo
	 * @return
	 */
	public function getInfoPersonaSchema(Ruolo $ruolo = null) {		        
           
                                      
		
       print_r($ruolo);
			 $info = $this->client->call('getInfoPersonaSchema', array('in0' => $ruolo->toHashMap()));
       print_r($info);
       exit;
       return $info;
     //  echo '<pre>' . htmlspecialchars($this->client->request, ENT_QUOTES) . '</pre>';
    
      
       
	}

	/**
	 * Restituisce l'elenco dei Ruoli associati ad una Application.
	 *
	 * @param $identita
	 * @param $application
	 * @return
	 */
	public function findActorsForPersonaInApplication(Identita $identita = null, Application $application = null) {
		
		if (is_null($identita)) {
    		throw new IrideException('Iride::findActorsForPersonaInApplication-001', self::ERROR_INVALID_IDENTITA);
    	} elseif (is_null($application)) {
    		throw new IrideException('Iride::findActorsForPersonaInApplication-002', self::ERROR_INVALID_APPLICATION);
    	} else {
			$actors = $this->client->call('findActorsForPersonaInApplication', array('in0' => $identita->toHashMap(), 'in1' => $application->toHashMap()));
      
    	}

    	if ($this->isSoapError($actors)) {
    		throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
    	} else {
    		$result = array();
    		foreach ($actors as $actor) {
    			array_push($result, new Actor($actor));
    		}
    		
    		if (count($result) == 0) {
    			return null;
    		} else {
    			return $result;
    		}
    	}
	}

	/**
	 * Restituisce l'elenco degli Actor associati ad uno UseCase.
	 *
	 * @param $identita
	 * @param $useCase
	 * @return
	 */
	public function findActorsForPersonaInUseCase(Identita $identita = null, UseCase $useCase = null) {
		
		if (is_null($identita)) {
    		throw new IrideException('Iride::findActorsForPersonaInUseCase-001', self::ERROR_INVALID_IDENTITA);
    	} elseif (is_null($useCase)) {
    		throw new IrideException('Iride::findActorsForPersonaInUseCase-002', self::ERROR_INVALID_USECASE);
    	} else {
    		$actors = $this->client->call('findActorsForPersonaInUseCase', array('in0' => $identita->toHashMap(), 'in1' => $useCase->toHashMap()));
    	}

    	if ($this->isSoapError($actors)) {
    		throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
    	} else {
    		$result = array();
    		foreach ($actors as $actor) {
    			array_push($result, new Actor($actor));
    		}
    		
    		if (count($result) == 0) {
    			return null;
    		} else {
    			return $result;
    		}
    	}
	}

	/**
	 * Verifica l'appartenenza ad un Ruolo.
	 *
	 * @param $identita
	 * @param $ruolo
	 * @return
	 */
	public function isPersonaInRuolo(Identita $identita = null, Ruolo $ruolo = null){
		
		if (is_null($identita)) {
    		throw new IrideException('Iride::isPersonaInRuolo-001', self::ERROR_INVALID_IDENTITA);
    	} elseif (is_null($ruolo)) {
    		throw new IrideException('Iride::isPersonaInRuolo-002', self::ERROR_INVALID_RUOLO);
    	} else {
    		$result = $this->client->call('isPersonaInRuolo', array('in0' => $identita->toHashMap(), 'in1' => $ruolo->toHashMap()));
    	}

    	if ($this->isSoapError($result)) {
    		throw new IrideException($this->irideErrorCode, $this->irideErrorMessage);
    	} else {
    		return $result;
    	}
	}


	// -------------------------------------------------------------------------
	// Metodi ausiliari
	// -------------------------------------------------------------------------
	
	private function isSoapError($data) {
		if ($this->client->getError()) {
			$this->irideErrorCode = $data['faultcode'];
			$this->irideErrorMessage = $data['faultstring'];
			return true;
    	} else {
    		return false;
    	}
	}
	
}

?>