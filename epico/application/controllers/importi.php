<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

class importi extends CI_Controller {
  function __construct()
  {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('varie');
        //$this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        session_start();
        //assegno l'identità shibboleth a data
        $this->data['identita_shib']=$_SESSION['identita_shib'];
         //print_r($this->data);
        $this->load->helper('utenti');
        // recuper le info aggiuntive dell'uente
        $this->user = getUser($this->data['identita_shib']['cod_fiscale']);

        // controllo se è un utente valido
        $this->checkUser($this->user);
        //recupero il comune per cui può lavorare  dalla sessione
        if  ($_SESSION['comune']) {
            $this->load->model('Comune');
            $comune_utente = $this->Comune->getSingle($_SESSION['comune']);
            // lo assegno sia alla variabile data per le view sia alla variabile di classe
            $this->data['comune_utente'] = $comune_utente[0];
            $this->user->comune_utente = $comune_utente[0];
         }
        $this->data['usertype'] = $this->user->TIPO_UTENTE;
   }

   public function index()
   {
	   $this->displayHeader();
	   $this->load->model('Utente');
	   $this->load->model('Comune');
	   
	   if ($this->user->TIPO_UTENTE == 'REG' || $this->user->TIPO_UTENTE == 'SUP'){
		$this->load->model('Risorse');
	   	$data->COMUNI = $this->Comune->getArrayComuni();
	   	$comune = $this->input->post('comune');
	   	$data->comune = $comune;
	   	if (!empty($comune)){
			if (!empty($this->input->post('submit'))) {
		   		$this->Risorse->comune = $comune;
		   		$this->Risorse->annualita = $this->input->post('annualita');
		   		$this->Risorse->importo = priceToSQL($this->input->post('importo'));
		   		$this->Risorse->data_determina = $this->input->post('data_determina');
		   		$this->Risorse->num_determina = $this->input->post('num_determina');
		   		$this->Risorse->al = $this->input->post('al');
		   		$this->Risorse->data_al = $this->input->post('data_al');
		   		$this->Risorse->capitolo = $this->input->post('capitolo');
		   		$this->Risorse->anno_capitolo = $this->input->post('anno_capitolo');
		   		$this->Risorse->num_impegno = $this->input->post('num_impegno');
		   		$this->Risorse->tipologia = $this->input->post('submit');
		   		$this->Risorse->insert();
	   		}
	   		$data->residue = $this->Risorse->getResidue($comune);
	   		$data->assegnate = $this->Risorse->get($comune, 'assegnati');
		   	$data->liquidate = $this->Risorse->get($comune, 'liquidati');
		   	$data->economie = $this->Risorse->get($comune, 'economie');
		   	$data->tot_assegnate = $this->Risorse->getTotale($comune, 'assegnati');
		   	$data->tot_liquidate = $this->Risorse->getTotale($comune, 'liquidati');
	   	}	   	
	   	$this->load->view('importi',$data);
	   } else redirect('domande/');
	
	   $this->displayFooter();
    }
    
    public function elimina($id)
	{
	   $this->load->model('Risorse');
	   $this->Risorse->delete($id);
    }
    public function displayFooter() {        
        $this->load->view('include/footerSP07');
    }
    public function displayHeader() {
          $this->load->view('include/head');          
          $this->load->view('include/header');
          $this->load->view('include/status',$this->data);
    }
    public function checkUser($user) {
         if (!$user->TIPO_UTENTE or !$_SESSION['identita_shib']) {
          //faccio un redirect in modo da passare al controller welcome il parametro che forza il redirect su questo controller dopo la profilazione
          header('Location: /servizi/epico/index.php?servizio=importi');
          exit;
         }
    }
    public function getcomuni($id_provincia)
    {
          $this->load->model('Comune');
          //header('Content-Type: application/x-json; charset=utf-8');
          echo(json_encode($this->Comune->getArrayList($id_provincia)));
    }
    public function getprovince()
    {
         $this->load->model('Comune');
         //header('Content-Type: application/x-json; charset=utf-8');
         echo(json_encode($this->Comune->getArrayListProvince()));
    }
  
    
}
