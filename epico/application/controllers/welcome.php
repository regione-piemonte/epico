<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */


  //welcome legge le info dell'utente da shibboleth e iride e le schiaffo in sessione

	public function index()
	{
	    	    
	    //echo $_SERVER['HTTP_SHIB_IRIDE_IDENTITADIGITALE']; exit;
	    if ($_SERVER["HTTP_SHIB_IDENTITA_CODICEFISCALE"]) {
	      //valorizza e mette in sessione i dati dell'utente
	      $identita_shib['cod_fiscale'] = $_SERVER["HTTP_SHIB_IDENTITA_CODICEFISCALE"];
	      $identita_shib['nome'] = $_SERVER["HTTP_SHIB_IDENTITA_NOME"];
	      $identita_shib['cognome'] = $_SERVER["HTTP_SHIB_IDENTITA_COGNOME"];
	      session_start();
	      $_SESSION['identita_shib'] = $identita_shib;
	      $_SESSION['comune'] = ''; //azzero il comune tanto viene impostato in index del controller domanda
	
	      // chiama la profilazione iride
	      //$this->irideProfile();
  		  $servizio = $this->input->get('servizio', TRUE);
        $servizio ='edilizia';
  		  switch($servizio){
  			  case 'importi':
  			  case 'edilizia':
  			  	header('Location: ./index.php/' . $servizio . '/');
  			  	break;
  			  default:
  			  	header('Location: ./index.php/domande/');
  		  }
  	      die();
  	    } else {
  	      echo "non autenticato con shibboleth";
  	      die();
  	    }
	}

  private function irideProfile()
	{
      require_once(APPPATH.'libraries/iride/nusoap.php');
      require_once(APPPATH.'libraries/iride/Iride.class.php');
      require_once(APPPATH.'libraries/iride/Identita.class.php');
      require_once(APPPATH.'libraries/iride/Application.class.php');
      require_once(APPPATH.'libraries/iride/Ruolo.class.php');
      require_once(APPPATH.'libraries/iride/UseCase.class.php');

      $this->load->helper('utenti');

      //recupera l'identità digitale utente
      $shib_iride_identitadigitale  =  $_SERVER['HTTP_SHIB_IRIDE_IDENTITADIGITALE'];

      //costruisce l'array come piace alle classi iride prese da gestgcred joomla
      $array_identita = build_array_identita($shib_iride_identitadigitale);

      $iride = new Iride(WSDL_IRIDE);      
      // build iride array hash map
      $irideHashMap['codFiscale']              = $array_identita[0];
      $irideHashMap['nome']                    = $array_identita[1];
      $irideHashMap['cognome']                 = $array_identita[2];
      $irideHashMap['idProvider']              = $array_identita[3];
      $irideHashMap['timestamp']               = $array_identita[4];
      $irideHashMap['livelloAutenticazione']   = $array_identita[5];
      $mac = $array_identita[6];
      $irideHashMap['mac']                     = $mac;
      $irideHashMap['rappresentazioneInterna'] = $shib_iride_identitadigitale;

       // costruttore per la classe Identita
      $identita = new Identita($irideHashMap);
      $application = new Application(array('id' => CODICE_APP_IRIDE));

      $usecaseHashMap['appId']=CODICE_APP_IRIDE;
      $usecaseHashMap['id']='GESTORE'; // id del caso l'uso
      $usecase = new UseCase($usecaseHashMap);

      // verifica che l'identità sia autentica
      $identitaOk = $iride->isIdentitaAutentica($identita);


     if (is_null($identitaOk)) {
              // sarebbe meglio una pagina vestita
              echo "Errore: identità Iride non valida. Contattare l'amministratore";
              exit();
      } else {

       // cerca gli attori del ruolo
       $attori = $iride->findRuoliForPersonaInApplication($identita,$application);

       $hashRuolo = $attori[0];
              
       $objRuolo = new Ruolo($hashRuolo);
       $ruolo = $attori[0]['codiceRuolo'];

        // cerca i casi l'uso
       $usecases = $iride->findUseCasesForPersonaInApplication($identita,$application);
       $usecase=$usecases[0];
              
        // se è un gestore controllo quali comuni può gestire
        // se non è un gestore è un admin od un superuser e non serve  leggere i comuni (che tanto non ci sono)
        if ($ruolo == 'EPICO_GESTORE') {
           // legge i comuni aggiuntivi
           $infopersona = $iride->getInfoPersonaInUseCase($identita,$usecase);
            //$Objxml = simplexml_load_string($infopersona);
            // mi arriva un XML
            $Objxml = new SimpleXMLElement($infopersona);

       foreach ($Objxml as $item) {
					if($item['ruolo'] == 'EPICO_GESTORE@COMUNE'){
							$cod_comuni = $item->COD_COMUNE[0];
							$arr_istat_comuni= explode( ";",$cod_comuni);
              
							$_SESSION['istat_comuni'] = $arr_istat_comuni;
							break;
					} elseif ($item['ruolo'] == 'EPICO_GESTORE@ATC'){
            
						$cod_atc = $item->COD_ATC[0];
						$arr_atc= explode( ";",$cod_atc);
						if(count($arr_atc) > 1) $_SESSION['codici_atc'] = $arr_atc;
						else $_SESSION['atc'] = $arr_atc[0];
						$_SESSION['is_atc'] = true;
					}
            }

        }
       if (!$ruolo) {
             echo "Errore: l'utente ".$irideHashMap['codFiscale'].", ".$irideHashMap['nome']." ".$irideHashMap['cognome'].
             " non è collegato ad un ruolo valido";
             exit;
       }
       // metto il sessione il ruolo iride.
        $_SESSION['ruolo']=$ruolo;
      }
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
