<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

// il controller dell'applicazione
class domande extends CI_Controller {
  function __construct()
  {
        // Call the Model constructor
        parent::__construct();
         //$this->output->enable_profiler(TRUE);
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
          
         if ($_SESSION['tipo_domanda']) {
          $this->tipo_domanda = $_SESSION['tipo_domanda'];
         }
        $this->data['usertype'] = $this->user->TIPO_UTENTE;

        //print_r($this->user);
  }

  // pagina di intro dell'applicativo, se è un comune deve scegliere per qualche comune operare
  public function index()
	{
       $comune = $this->input->post('comune');
       $tipo_domanda = $this->input->post('tipo_domanda');
       //echo "$tipo_domanda"; exit;
       $this->displayHeader();
       $this->load->model('Utente');
       $data->COMUNI = $this->Utente->get_comuni_utente($this->user->ID);

       // se è un comune ma non è stato ancora scelto il comune lo faccio scegliere
       if ($this->user->TIPO_UTENTE == 'COM' and !$comune) {
           if ($_SESSION['comune']) {$data->COMUNE = $_SESSION['comune'];}
           $data->msg = "E' necessario scegliere un comune";
           $this->load->view('index',$data);
      // oppure se ha già scelto il comune lo mando al tipo di domanda
       } elseif ($comune) {
           if (array_key_exists($comune,$data->COMUNI)) {
               $_SESSION['comune']  = $comune;
               $_SESSION['tipo_domanda']  = $tipo_domanda;
               redirect('domande/elenco/');
            }
        // se è superuser metto un istat fittizio       
       } else {
           redirect('domande/elenco');
       }
       $this->displayFooter();
  }

  public function elenco()
	{  
        $button = $this->input->get('submit');
       if ($this->user->TIPO_UTENTE == 'COM' and !$this->user->comune_utente->ISTAT) {
          redirect('domande/');
       }

       $this->displayHeader();
       $this->load->model('Domanda');
       // legge eventuali filtri di ricerca
       $filter['nome'] = $this->input->get_post('nome_richiedente') ? $this->input->get_post('nome_richiedente') : NULL;
       $filter['cognome'] = $this->input->get_post('cognome_richiedente') ? $this->input->get_post('cognome_richiedente') : NULL;
       $filter['id_domanda'] = $this->input->get_post('id_domanda') ? $this->input->get_post('id_domanda') : NULL;
       if ($button=='export_domande_sintesi') {
        $filter['stato_domanda'] = '2'; // in caso di report estraggo solo le domande convalidate       
       } elseif ($button=='export_domande_sintesi_prefetture') {
        $filter['stato_domanda'] = '1'; // in caso di report FMI per prefetture estraggo solo le domande bozza       
       } else {
        $filter['stato_domanda'] = $this->input->get_post('stato_domanda') ? $this->input->get_post('stato_domanda') : NULL;
       }

       // se l'utente ha scelto un comune filtro sul comune
       if ($this->user->comune_utente->ISTAT) {
         $filter['comune_inseritore'] = $this->user->comune_utente->ISTAT;
       } else {                         
         $filter['comune_inseritore'] = $this->input->get_post('comune_inseritore') ? $this->input->get_post('comune_inseritore') : NULL;
       }

       $filter['invalidita'] = $this->input->get_post('invalidita') ? $this->input->get_post('invalidita') : NULL;
       $filter['operatore'] = $this->input->get_post('operatore') ? $this->input->get_post('operatore') : NULL;
       $filter['minorenne'] = $this->input->get_post('minorenne') ? $this->input->get_post('minorenne') : NULL;
       $filter['over70'] = $this->input->get_post('over70') ? $this->input->get_post('over70') : NULL;
       if ($this->input->get_post('tipo_domanda')) {
          $filter['tipo_domanda'] = $this->input->get_post('tipo_domanda') ? $this->input->get_post('tipo_domanda') : NULL;
       }  else {
          $filter['tipo_domanda'] =  $this->tipo_domanda;
       }
       if ($filter['tipo_domanda']) {$_SESSION['tipo_domanda']=$filter['tipo_domanda'];}
       $filter['data_da'] = $this->input->get_post('data_da') ? $this->input->get_post('data_da') : NULL;
       $filter['data_a'] = $this->input->get_post('data_a') ? $this->input->get_post('data_a') : NULL;

       // filtri date per il report excel
       $filter['data_da_report'] = $this->input->get_post('data_da_report') ? $this->input->get_post('data_da_report') : NULL;
       $filter['data_a_report'] = $this->input->get_post('data_a_report') ? $this->input->get_post('data_a_report') : NULL;

       $filter['tipo_utente'] = $this->user->TIPO_UTENTE;

       if ($filter['id_domanda'] or $filter['cognome'] or $filter['nome'] or $filter['stato_domanda']
            or ($filter['tipo_domanda'] && $this->user->TIPO_UTENTE != 'COM') or $filter['data_da']  or $filter['data_a']) {
          $filter['active'] = 1; //tiene aperto l'accordion delle ricerca
       }
       $data['filter'] = $filter;

       $this->load->library('pagination');
       $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

      if ($button == '') { 
         $domande = $this->Domanda->get_entries($filter,'',1);
       } else {
        
         $domande = $this->Domanda->get_entries($filter,'');
       }
  
       
     if ($button=='export_domande') {

         $this->load->helper('excel');
         $filter['export_regione'] = '1';         
	   	   createExcel($this->Domanda->get_entries(array('tipo_utente'=>$filter['tipo_utente'], 'tipo_domanda'=>$filter['tipo_domanda'], 'export_regione'=>$filter['export_regione'])), $filter['tipo_domanda']);
 	   }

       if ($button=='export_domande_sintesi') {       
           $this->load->model('Nucleo');
           $this->load->helper('excel');                      
           createExcelSintesi($domande,$this->user);
       }
       
      if ($button=='export_domande_sintesi_prefetture') {       
           $this->load->model('Nucleo');
           $this->load->helper('excel');                      
           createExcelSintesi($domande,$this->user,1);
       }
       

       $_SESSION['domande']=$domande;
       $this->load->model('Comune');
       $comuni = $this->Comune->getComuniDomande();

       $data['comuni_inseritori'] = $comuni;
 

       $data['tipo_domande'] = $this->Domanda->get_collegato('tipo_domanda');
       $data['stato_domande'] = $this->Domanda->get_collegato('stato_domanda');
       $num_domande = count($domande);
       $data['num_domande'] = $num_domande;
       //carico la configurazione della paginazione
       $this->load->helper('varie');
       $config = configPagination($num_domande);

       // se ha trovato domande, le mostro paginate
       if ($num_domande > 0) {
         $domande = array_slice($domande,$page,$config['per_page']);
         $data['domande'] = $domande;
         $this->pagination->initialize($config);
         $data['pagination_link'] = $this->pagination->create_links();
        } else {
        // diversamente, messaggio di risultati 0
         $data['no_result'] = 1;
        }
            
       $this->load->view('elenco',$data);
       $this->displayFooter();
  }

  public function submit()
	{

    $this->load->model('Domanda');

    $button = $this->input->post('submit');
    $id = $this->input->post('id');
    if ($id) {
        $this->checkPermessiSuDomanda($id);
    }
     if ($this->Domanda->check_if_validata($id)) {
         redirect('domande/domanda/'.$id.'/giavalidata');
     }
      $this->load->helper('codicefiscale');
      //$test = $this->db->get('test');
      $this->Domanda->nome = $this->input->post('nome');
      $this->Domanda->cognome = $this->input->post('cognome');
      $this->Domanda->codice_fiscale = $this->input->post('codicefiscale');
      $this->Domanda->titolo_studio = $this->input->post('titolostudio');
      $this->Domanda->data_nascita = $this->input->post('datanascita');
      $this->Domanda->reddito_imponibile_isee = priceToSQL($this->input->post('reddito_imponibile'));
      $this->Domanda->reddito_equivalente_isee = priceToSQL($this->input->post('reddito_equivalente'));
      $this->Domanda->data_rilascio_isee = $this->input->post('datarilascioISEE');
      //$this->Domanda->numero_figli = $this->input->post('n_figli');
      $this->Domanda->fct = $this->input->post('invalidita');
     // $this->Domanda->numero_comp_nucleo = $this->input->post('numero_nucleo');
      $this->Domanda->aiuti_econ_no_isee = priceToSQL($this->input->post('aiuti_economici'));
      $this->Domanda->assegnatario_erps = $this->input->post('assegnatario_erps');
      $this->Domanda->permesso_soggiorno = $this->input->post('permesso_soggiorno');
      $this->Domanda->scontrino = $this->input->post('provvedimento_sfratto');
      $this->Domanda->data_scontrino = $this->input->post('data_provvedimento_sfratto');
      $this->Domanda->motivazione_scontrino = $this->input->post('motivazione_sfratto');
      $this->Domanda->domanda_collegata_fondo = $this->input->post('domanda_collegata_fondo');

      $this->Domanda->rinuncia_esecuzione = $this->input->post('rinuncia_esecuzione');
      $this->Domanda->nuovo_contratto_agenzia = $this->input->post('nuovo_contratto_agenzia');
      $this->Domanda->differimento = $this->input->post('differimento');
      $this->Domanda->deposito_cauzionale = $this->input->post('deposito_cauzionale');

      $this->Domanda->resid_indirizzo = $this->input->post('indirizzo_residenza');
      $this->Domanda->resid_civico = $this->input->post('civico_residenza');
      $this->Domanda->resid_cap = $this->input->post('CAP');
      $this->Domanda->tipo_godimento_abitazione = $this->input->post('tipo_godimento_abitazione');
      $this->Domanda->tipo_contratto = $this->input->post('tipo_contratto');
      $this->Domanda->tipo_contratto_atto = $this->input->post('tipo_contratto_atto');
      $this->Domanda->resid_comune_istat = $this->input->post('comune_residenza');
      $this->Domanda->resid_provincia = $this->input->post('provincia_residenza');
      $this->Domanda->nouvaresid_provincia = $this->input->post('provincia_nuova_residenza');
      $this->Domanda->nuovaresid_comune_istat = $this->input->post('nuovo_comune_residenza');
      $this->Domanda->nuovaresid_indirizzo = $this->input->post('indirizzo_n');
      $this->Domanda->nuovaresid_civico = $this->input->post('civico_n');
      $this->Domanda->nuovaresid_cap = $this->input->post('CAP_n');
      $this->Domanda->ammontare_nuovo_contratto  = priceToSQL($this->input->post('ammontare_nuovo_contratto'));
      $this->Domanda->ammontare_atto  = priceToSQL($this->input->post('ammontare_atto'));
      $this->Domanda->ammontare_morosita  = priceToSQL($this->input->post('ammontare_morosita'));
      $this->Domanda->durata_contratto = $this->input->post('durata_contratto');
      $this->Domanda->durata_nuovo_contratto = $this->input->post('durata_nuovo_contratto');
      $this->Domanda->data_contratto = $this->input->post('data_contratto');
      $this->Domanda->data_nuovo_contratto = $this->input->post('data_nuovo_contratto');
      $this->Domanda->nome_proprietario = $this->input->post('nome_proprietario');
      $this->Domanda->cognome_proprietario = $this->input->post('cognome_proprietario');
      $this->Domanda->cod_fiscale_proprietario = $this->input->post('codicefiscale_proprietario');
      $this->Domanda->data_nascita_proprietario = $this->input->post('data_nascita_proprietario');
      $this->Domanda->nome_garante = $this->input->post('nome_garante');
      $this->Domanda->cognome_garante = $this->input->post('cognome_garante');
      $this->Domanda->cod_fiscale_garante = $this->input->post('codicefiscale_garante');
      $this->Domanda->data_nascita_garante = $this->input->post('data_nascita_garante');
      $this->Domanda->estremi_catastali_foglio = $this->input->post('estremi_catastali_foglio');
      $this->Domanda->estremi_catastali_particella = $this->input->post('estremi_catastali_particella');
      $this->Domanda->estremi_catastali_subalterno = $this->input->post('estremi_catastali_subalterno');
      $this->Domanda->numero_vani = $this->input->post('numero_vani');
      $this->Domanda->categoria_catastale = $this->input->post('categoria_catastale');
      $this->Domanda->rendita_catastale = $this->input->post('rendita_catastale');
      $this->Domanda->stato_conservazione_fabbricato = $this->input->post('stato_conservazione_fabbricato');
      $this->Domanda->stato_conservazione_alloggio = $this->input->post('stato_conservazione_alloggio');

      $this->Domanda->cittadinanza = $this->input->post('cittadinanza');
      $this->Domanda->servizi_sociali = $this->input->post('servizi_sociali');

      $this->Domanda->fondo_garanzia_proprietario = $this->input->post('fondo_garanzia_proprietario');
      $this->Domanda->numero_protocollo = $this->input->post('numero_protocollo');
      $this->Domanda->data_protocollo = $this->input->post('data_protocollo');

      $this->Domanda->contributo_ammesso_copertura = priceToSQL($this->input->post('contributo_ammesso_copertura'));
      $this->Domanda->contributo_ammesso_cauzionale = priceToSQL($this->input->post('contributo_ammesso_cauzionale'));
      $this->Domanda->totale_contributo = priceToSQL($this->input->post('totale_contributo'));

      $this->Domanda->contributo_inquilino_ammesso = priceToSQL($this->input->post('contributo_inquilino_ammesso'));
      $this->Domanda->contributo_proprietario_ammesso = priceToSQL($this->input->post('contributo_proprietario_ammesso'));
      $this->Domanda->ammontare_fondo_garanzia_proprietario = priceToSQL($this->input->post('ammontare_fondo_garanzia_proprietario'));
      $this->Domanda->scadenza_fondo_garanzia_proprietario = $this->input->post('scadenza_fondo_garanzia_proprietario');
      
      $this->Domanda->cofinanziamento = priceToSQL($this->input->post('cofinanziamento'));
      $this->Domanda->data_liquidazione = $this->input->post('data_liquidazione');
      $this->Domanda->numero_liquidazione = $this->input->post('numero_liquidazione');
      $this->Domanda->annualita_liquidazione = $this->input->post('annualita_liquidazione');
      
      $this->Domanda->data_registrazione_contratto = $this->input->post('data_registrazione_contratto');
      $this->Domanda->data_registrazione_nuovo_contratto = $this->input->post('data_registrazione_nuovo_contratto');
      $this->Domanda->contributo_ammesso_differimento = priceToSQL($this->input->post('contributo_ammesso_differimento'));
      $this->Domanda->contributo_ammesso_nuovo_contratto = priceToSQL($this->input->post('contributo_ammesso_nuovo_contratto'));
      

      $nucleo['nucleo_id'] = $this->input->post('nucleo_id');
      $nucleo['nucleo_nome'] = $this->input->post('nucleo_nome');
      $nucleo['nucleo_cognome'] =  $this->input->post('nucleo_cognome');
      $nucleo['nucleo_codice_fiscale'] =  $this->input->post('nucleo_codice_fiscale');
      $nucleo['nucleo_data_nascita'] =  $this->input->post('nucleo_data_nascita');
      $nucleo['nucleo_parentela'] =  $this->input->post('nucleo_parentela');

      $nucleo_famigliare = $this->readNucleo($nucleo);

      $this->Domanda->data_domanda = $this->input->post('data_domanda');
      $this->Domanda->stato_domanda = 1;
      
      if ($this->user->TIPO_UTENTE != 'SUP') {
      
        $this->Domanda->COMUNE_INSERITORE = $this->user->comune_utente->ISTAT;
        $this->Domanda->DESCRIZIONE_COMUNE_INSERITORE = $this->user->comune_utente->DESCRIZIONE;
      } else {
      
        $this->Domanda->COMUNE_INSERITORE = '999999';
        $this->Domanda->DESCRIZIONE_COMUNE_INSERITORE = 'Comune di Superuser';
      } 
      
      $this->Domanda->CODICE_FISCALE_USER = $this->user->CODICE_FISCALE;

      $this->Domanda->OPERATORE = $this->user->NOME.' '.$this->user->COGNOME;
      $this->Domanda->tipo_domanda = $this->input->post('tipo_domanda');
      // se ho l'id aggiorno la domanda sennò nuova domanda
      if ($id) {
        $this->Domanda->id = $id;
        $this->Domanda->insert_or_update_entry($id);
      }  else {
        // è una domanda nuova quindi va salvata e faccio la insert
        $id = $this->Domanda->insert_or_update_entry();
      }
      // il nucleo viene salvato cancellando quello esistente e ricaricando
      $this->load->model('Nucleo');
      $this->Nucleo->delete($id);
      $this->Nucleo->insert($nucleo_famigliare,$id);

      $this->Domanda->aggiorna_eta_nuclei_e_proponente($id);

       if ($button == 'salvabozza') {
          redirect('domande/domanda/'.$id.'/bozzaok');
       } elseif ($button=='valida') {
          $this->displayHeader();
          $this->Domanda->valida_domanda($id);
          redirect('domande/visualizza/'.$id.'/validata');
          $this->displayFooter();
       }
  }
 public function elimina($id='')
	{
    $this->checkPermessiSuDomanda($id);
    $this->load->model('Domanda');
    if ($this->Domanda->check_if_validata($id)) {
       $this->visualizza($id);
       exit;
   }

    if ($id) {
      $domanda = $this->Domanda->delete_single_entry($id);
    }

    redirect('domande/elenco/');
    exit;

  }
  // mostra la form di sola visualizzazione (per regione e per domande validate)
  public function visualizza($id='',$msg='')
	{
    $this->checkPermessiSuDomanda($id);
    $this->displayHeader();
    $this->load->model('Domanda');
    $this->load->model('Comune');

    if ($id) {
      // se mi passano l'id l'id estraggo e visualizzo la domanda
          $this->Domanda->id = $id;
          $domanda = $this->Domanda->get_entries('',$id);
          if (isset($domanda)) {
            $data=$domanda[0];
          }
    }
    $data->descr_nuovaresid_comune = $this->Comune->getSingle($data->NUOVARESID_COMUNE_ISTAT);
    $data->descr_nuovaresid_comune = $data->descr_nuovaresid_comune[0]->DESCRIZIONE;
    $data->descr_resid_provincia = $this->Comune->getSingleProvincia($data->RESID_PROVINCIA);
    $data->descr_resid_provincia = $data->descr_resid_provincia[0]->DESCRIZIONE;
    $data->descr_nuovaresid_provincia = $this->Comune->getSingleProvincia($data->NOUVARESID_PROVINCIA);
    $data->descr_nuovaresid_provincia = $data->descr_nuovaresid_provincia[0]->DESCRIZIONE;
    $data->descr_tipo_contratto = reset($this->Domanda->get_collegato('tipo_contratto',$data->TIPO_CONTRATTO));
    $data->descr_stato_conservazione_alloggio = reset($this->Domanda->get_collegato('stato_conservazione',$data->STATO_CONSERVAZIONE_ALLOGGIO));
    $data->descr_stato_conservazione_fabbricato = reset($this->Domanda->get_collegato('stato_conservazione',$data->STATO_CONSERVAZIONE_FABBRICATO));
    $data->descr_cittadinanza = reset($this->Domanda->get_collegato('cittadinanza',$data->CITTADINANZA));
    $data->descr_servizi_sociali = reset($this->Domanda->get_collegato('sino',$data->SERVIZI_SOCIALI));
    $data->descr_invalidita = reset($this->Domanda->get_collegato('sino',$data->FCT));
    $data->descr_assegnatario_erps = reset($this->Domanda->get_collegato('sino',$data->ASSEGNATARIO_ERPS));
    $data->descr_fondo_garanzia_proprietario = reset($this->Domanda->get_collegato('sino',$data->FONDO_GARANZIA_PROPRIETARIO));
    $data->descr_permesso_soggiorno = reset($this->Domanda->get_collegato('sino',$data->PERMESSO_SOGGIORNO));
    $data->descr_provvedimento_sfratto = reset($this->Domanda->get_collegato('sfratto',$data->SCONTRINO));
    $data->descr_tipo_contratto_atto = reset($this->Domanda->get_collegato('tipo_contratto_atto',$data->TIPO_CONTRATTO_ATTO));
    $data->descr_durata_contratto = reset($this->Domanda->get_collegato('durata_contratto',$data->DURATA_CONTRATTO));
    $data->descr_durata_nuovo_contratto = reset($this->Domanda->get_collegato('durata_nuovo_contratto',$data->DURATA_NUOVO_CONTRATTO));

    $this->load->model('Nucleo');
    $nuclei = $this->Nucleo->get($id);

    $data->nuclei = $nuclei;
    $data->msg=$msg;
    //print_r($data);
    $this->load->view('visualizza',$data);
    $this->displayFooter();
    
  }
 // mostra la form di inserimento vuota od in editing
  public function domanda($id='',$msg='')
	{
       $this->load->model('Domanda');
       if ($id) {
           $this->checkPermessiSuDomanda($id);
       }
       if ( $this->input->get('tipo_domanda', TRUE) ) {
          $tipo_domanda =$this->input->get('tipo_domanda', TRUE);
       } else {
         $tipo_domanda = $_SESSION['tipo_domanda'];
       }

       $data->TIPO_DOMANDA= $tipo_domanda;
       if ($this->Domanda->check_if_validata($id)) {
           $this->visualizza($id);
       }
      if ($id) {
      // se mi passano l'id l'id estraggo e visualizzo la domanda
          $this->Domanda->id = $id;
          $domanda = $this->Domanda->get_single_entry($id);
          if (isset($domanda)) {
            $data=$domanda;
          }
      } else {
        $data->DATA_DOMANDA = date('Y/m/d',time());
      }

      $this->load->model('Nucleo');
      $nuclei = $this->Nucleo->get($id);

      $data->nuclei = $nuclei;

      // mi serve almeno un record vuoto per popolare la prima riga di input
      if (!$data->nuclei){
           $data->nuclei = array(array('nucleo_id'=>'null'));
      }

     // estraggo i dati per popolare le combo di comuni e province
     $this->load->model('Comune');
     $province = $this->Comune->getArrayListProvince();
     $data->province = $province;

     if ($data->COD_PROVINCIA) {
      $comuni = $this->Comune->getArrayList($data->COD_PROVINCIA);
      $data->comuni = $comuni;
     }

     if ($data->NUOVO_COD_PROVINCIA) {
      $nuovo_comuni = $this->Comune->getArrayList($data->NUOVO_COD_PROVINCIA);
      $data->nuovo_comuni = $nuovo_comuni;
     }

     $stati_sfratto = $this->Domanda->get_collegato('sfratto');
     $data->stati_sfratto = $stati_sfratto;

     $titoli_studio = $this->Domanda->get_collegato('titolo_studio');
     $data->titoli_studio = $titoli_studio;

     $tipi_contratto =  $this->Domanda->get_collegato('tipo_contratto');
     $data->tipi_contratto = $tipi_contratto;

     $tipi_contratto_atto = $this->Domanda->get_collegato('tipo_contratto_atto');
     $data->tipi_contratto_atto = $tipi_contratto_atto;

     $durata_contratto = $this->Domanda->get_collegato('durata_contratto');
     $data->durata_contratto = $durata_contratto;

     $durata_nuovo_contratto = $this->Domanda->get_collegato('durata_nuovo_contratto');
     $data->durata_nuovo_contratto = $durata_nuovo_contratto;

     $parentele =  $this->Domanda->get_collegato('parentela');
     $data->parentele = $parentele;

     $conservazione = $this->Domanda->get_collegato('stato_conservazione');
     $data->conservazione = $conservazione;

     $motivazione_sfratto = $this->Domanda->get_collegato('motivazione_scontrino');
     $data->motivazione_sfratto = $motivazione_sfratto;

     $tipo_godimento_abitazione= $this->Domanda->get_collegato('tipo_godimento');
     $data->tipo_godimento_abitazione = $tipo_godimento_abitazione;

     if ($msg) {
        $data->msg=$msg;
     }
     $this->displayHeader();
     if ($tipo_domanda == 1) {   //print_r($data);
     $this->load->view('domanda',$data);
     } else {
       $this->load->view('domanda2',$data);
     }
     $this->displayFooter();

	}
  public function readNucleo($nucleo) {
       $arr_nucleo = array();
       $i=0;

       foreach ($nucleo['nucleo_id'] as $itemid) {
                if ($nucleo['nucleo_nome'][$i] and $nucleo['nucleo_cognome'][$i]) {
                  $arr_item_nucleo['nucleo_nome']=$nucleo['nucleo_nome'][$i];
                  $arr_item_nucleo['nucleo_cognome']=$nucleo['nucleo_cognome'][$i];
                  $arr_item_nucleo['nucleo_codice_fiscale']=$nucleo['nucleo_codice_fiscale'][$i];
                  $arr_item_nucleo['nucleo_data_nascita']=$nucleo['nucleo_data_nascita'][$i];
                  $arr_item_nucleo['nucleo_parentela']=$nucleo['nucleo_parentela'][$i];
                  $arr_nucleo[] =  $arr_item_nucleo;
                }
               $i++;
       }
       return $arr_nucleo;
  }

  public function export_domande($tipo_domanda='',$filter) {
       $domande = $_SESSION['domande'];
       var_dump($domande); die();
       $this->load->helper('excel');
       createExcel($domande);
  }

  public function displayFooter() {
      // $this->load->view('include/footer');
      $this->load->view('include/footerSP07');
  }
  public function displayHeader() {
        $this->load->view('include/head');
        //$this->load->view('include/portal_header');
        $this->load->view('include/header');
        $this->load->view('include/status',$this->data);
  }

  public function logout() {
    // logout paranoico
    // distruggo la sessione CODEIGNITER nel caso non si appoggi a quella PHP
    $this->session->set_userdata('identita_shib','');
    // distruggo la sessione PHP
    $_SESSION['identita_shib']='';
    session_destroy();
    // cancello tutti i cookie disponibili
    $past = time() - 3600;
    foreach ( $_COOKIE as $key => $value )
    {
        setcookie( $key, $value, $past, '/' );
    }

    #redirigo al logout di shibboleth
    redirect($this->config->item('logout_url'));

  }

  // controlla che l'operatore comune stia lavorando su una domanda su cui ha i permessi
  public function checkPermessiSuDomanda($id_domanda) {

        $tipo_utente = $this->user->TIPO_UTENTE;
        if ($tipo_utente == 'COM') {
              $istat_utente = $this->user->comune_utente->ISTAT;

                if ($id_domanda) {
                   $this->load->model('Domanda');
                // se mi passano l'id l'id estraggo e visualizzo la domanda
                    $this->Domanda->id = $id_domanda;
                    $domanda = $this->Domanda->get_entries('',$id_domanda);
                    if (isset($domanda)) {
                      $domanda=$domanda[0];
                    }
              }
           //print_r($domanda->COMUNE_INSERITORE); print_r($istat_utente);
          if ($domanda->COMUNE_INSERITORE != $istat_utente) {
             $data->msg = "Non hai i permessi per operare sulla domanda: ".$id_domanda;
             $data->error_type = "danger";
            $this->displayHeader();
            $this->load->view('message',$data);
            $this->displayFooter();
            echo $this->output->get_output();
             exit;
        }
     }
  }
  public function checkUser($user) {
       if (!$user->TIPO_UTENTE or !$_SESSION['identita_shib']) {
         echo "sessione scaduta od utente non valido";
         echo "<br>Tipo utente: ";
         print_r($user->TIPO_UTENTE);
         echo "<br>Identità Shibbolet: ";
         print_r($_SESSION['identita_shib']);
         echo "<br><a href=\"/servizi/epico\">Rieffettua il login</a>";
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
