<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

class edilizia extends CI_Controller {
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
      $atc = $this->input->post('atc');
      
      $comune = $this->input->post('comune');      
      
      $atc = 'ATC02';
      $_SESSION['is_atc'] = '1';
      
      $tipo_domanda_edilizia = $this->input->post('tipo_domanda_edilizia', TRUE);
      //echo "$tipo_domanda"; exit;
      if(empty($tipo_domanda_edilizia)) $tipo_domanda_edilizia = 'a';
      $_SESSION['tipo_domanda_edilizia']  = $tipo_domanda_edilizia;
      $this->displayHeader();
      $this->load->model('Utente');
      $data->COMUNI = $this->Utente->get_comuni_utente($this->user->ID);

      if($atc) $_SESSION['atc']  = $atc;

      if($_SESSION['is_atc']){
         if($_SESSION['atc']){
            //$_SESSION['tipo_domanda_edilizia']  = $tipo_domanda_edilizia;
            redirect('edilizia/elenco/');
         } else {
            $data->ATC = $this->Utente->get_atc();
            $data->msg = "E' necessario scegliere una ATC";
            $this->load->view('index_edilizia',$data);
         }
      } else {
         // se è un comune ma non è stato ancora scelto il comune lo faccio scegliere
         if ($this->user->TIPO_UTENTE == 'COM' and !$comune) {
             if ($_SESSION['comune']) {$data->COMUNE = $_SESSION['comune'];}
             $data->msg = "E' necessario scegliere un comune";
             $this->load->view('index_edilizia',$data);
        // oppure se ha già scelto il comune lo mando al tipo di domanda
         } elseif ($comune) {
             if (array_key_exists($comune,$data->COMUNI)) {
                $_SESSION['comune']  = $comune;
                //$_SESSION['tipo_domanda_edilizia']  = $tipo_domanda_edilizia;
                redirect('edilizia/elenco/');
              }
         // oppure direttamente all'elenco
         } else {
             redirect('edilizia/elenco');
         }
      }

      $this->displayFooter();
   }

   public function elenco()
    {
      $button = $this->input->get('submit');
         if ($this->user->TIPO_UTENTE == 'COM' and !$this->user->comune_utente->ISTAT) {
            //redirect('domande/'); //ritorna al controller di default
         }
         $this->displayHeader();
         $this->load->model('Ristrutturazione');
         $this->load->library('pagination');
         $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

         if ($this->input->get_post('tipo_domanda_edilizia')) {
           $filter['tipo_domanda_edilizia'] = $this->input->get_post('tipo_domanda_edilizia') ? $this->input->get_post('tipo_domanda_edilizia') : NULL;
           if (!empty($filter['tipo_domanda_edilizia']))$_SESSION['tipo_domanda_edilizia'] = $filter['tipo_domanda_edilizia'];
        }  else {
           $filter['tipo_domanda_edilizia'] =  $_SESSION['tipo_domanda_edilizia'];
        }

        // se l'utente ha scelto un comune filtro sul comune
        if ($this->user->comune_utente->ISTAT) {
         $filter['comune_inseritore'] = $this->user->comune_utente->ISTAT;
       } else {
         $filter['comune_inseritore'] = $this->input->get_post('comune_inseritore') ? $this->input->get_post('comune_inseritore') : NULL;
       }

       if($_SESSION['atc']) $filter['tipo_utente'] = $_SESSION['atc'];
       else $filter['tipo_utente'] = $this->user->TIPO_UTENTE;

       if ($button=='export_domande_sintesi') {
          $filter['stato_domanda'] = 2;
          $edilizia = $this->Ristrutturazione->get_entries($filter);
          
          $this->reportPdf($edilizia);
       } else {

         $edilizia = $this->Ristrutturazione->get_entries($filter);

         $_SESSION['edilizia']=$edilizia;

         $num_domande = count($edilizia);
         $data['num_domande'] = $num_domande;
         //carico la configurazione della paginazione
         $this->load->helper('varie');
         $config = configPagination($num_domande, 'index.php/edilizia/elenco');

         // se ha trovato domande, le mostro paginate
         if ($num_domande > 0) {
           $edilizia = array_slice($edilizia,$page,$config['per_page']);
           $data['domande'] = $edilizia;
           $this->pagination->initialize($config);
           $data['pagination_link'] = $this->pagination->create_links();
          } else {
          // diversamente, messaggio di risultati 0
           $data['no_result'] = 1;
          }

         $this->load->view('elenco_edilizia',$data);

         $this->displayFooter();
      }
    }

    // mostra la form di sola visualizzazione (per regione e per domande validate)
    public function visualizza($id='',$msg='')
  	{
      //$this->checkPermessiSuDomanda($id);
      $this->displayHeader();
      $this->load->model('Ristrutturazione');
      $this->load->model('Comune');

      if ($id) {
        // se mi passano l'id l'id estraggo e visualizzo la domanda
            $this->Ristrutturazione->id = $id;
            $domanda = $this->Ristrutturazione->get_entries('',$id);
            if (isset($domanda)) {
              $data=$domanda[0];
            }
            $this->load->model('Interventi');
            $interventi = $this->Interventi->get($id, $data->TIPO_DOMANDA);
            $data->interventi = $interventi;
            
            $this->load->model('Assegnatario');
            $assegnatario = $this->Assegnatario->get($id);
            //if(empty($assegnatario)) $assegnatario->domanda = $id;
            $assegnatario->vincoli = $this->Ristrutturazione->getTipoDomandaEdilizia($data->TIPO_DOMANDA);
            $assegnatario->titoli_studio = $this->Assegnatario->get_collegato('titolo_studio');
      }


      $data->msg=$msg;
      //print_r($data);
      $this->load->view('visualizza_edilizia',$data);
      if ($data->TIPO_DOMANDA == 'a') {
	   	$this->load->view('assegnatario',$assegnatario);   
      }  
      $this->displayFooter();
   }
   
   // assegna domanda
   public function assegna($id='',$msg='')
  	{
	  	$this->load->model('Assegnatario');
	  	$this->Assegnatario->id_domanda = $this->input->post('id_domanda');
	  	$this->Assegnatario->nome = $this->input->post('nome');
	  	$this->Assegnatario->cognome = $this->input->post('cognome');
	  	$this->Assegnatario->codice_fiscale = $this->input->post('codicefiscale');
	  	$this->Assegnatario->data_nascita = $this->input->post('datanascita');
	  	$this->Assegnatario->titolo_studio = $this->input->post('titolostudio');
	  	$this->Assegnatario->cittadinanza = $this->input->post('cittadinanza');
	  	$this->Assegnatario->insert();
	  	
	  	redirect('edilizia/visualizza/'.$this->Assegnatario->id_domanda);
   }
                        
   function reportPdf($edilizia){
      $this->load->library('Pdf');
      $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      // set default font subsetting mode
      $pdf->setFontSubsetting(true);
      // remove default header/footer
      $pdf->setPrintHeader(false);
      $pdf->setPrintFooter(false);
      // Set font
      $pdf->SetFont('helvetica', '', 10, '', true);

      // Add a page
      // This method has several options, check the source code documentation for more information.
      $pdf->AddPage('L');

      $html = '<h1>Elenco analitico - Lettera ' . strtoupper($_SESSION['tipo_domanda_edilizia']) . '</h1>';
      $html .= '<p><strong>';
      if ($_SESSION['atc']){
        $html .= $_SESSION['atc'];
      } elseif ($usertype == 'COM') {
        $html .= 'Comune di '.$comune_utente->DESCRIZIONE;
      }
      $html .= '</strong></p>';

      $html .= '<table cellspacing="0" cellpadding="2" border="1" width="100%">
                  <tr>
                     <th>ID Domanda</th>
                     <th>Data della Domanda</th>
                     <th>Indirizzo</th>
                     <th>N. civico</th>
                     <th>CAP</th>
                     <th>Prov.</th>
                     <th>Comune</th>
                     <th>Giorni previsti</th>
                     <th>Costo complessivo</th>';
      if ($_SESSION['tipo_domanda_edilizia'] == 'b') {
         $html .=    '<th>Risorse aggiuntive al finanziamento statale</th>';
      }
      $html .=    '</tr>';

      $alloggi = 0;
      $immobili = 0;
      $totale = 0;

      foreach ($edilizia as $e){
         $totale += $e->COSTO_TOTALE;
         if ($e->MODALITA == 2) $immobili++;
         else $alloggi++;
         $html .= '<tr>'.
                     '<td>'.$e->ID_DOMANDA.'</td>'.
                     '<td>'.dateFormatter($e->DATA_DOMANDA).'</td>'.
                     '<td>'.$e->INDIRIZZO.'</td>'.
                     '<td>'.$e->CIVICO.'</td>'.
                     '<td>'.$e->CAP.'</td>'.
                     '<td>'.$e->SIGLA_PROV.'</td>'.
                     '<td>'.$e->DESCR_COMUNE.'</td>'.
                     '<td>'.$e->GIORNI_PREVISTI.'</td>'.
                     '<td align="right">'.money_format('%.2n',$e->COSTO_TOTALE).'</td>';
         if ($_SESSION['tipo_domanda_edilizia'] == 'b') {
            $html .= '<td align="right">'.money_format('%.2n',$e->RISORSE_AGGIUNTIVE).'</td>';
         }
         $html .=  '</tr>';
      }

      $html .= '<tr>
                  <td colspan="5"></td>
                  <td colspan="3"><strong>TOTALE ALLOGGI</strong></td>
                  <td><strong>'.$alloggi.'</strong></td>
               </tr>';

      if ($_SESSION['tipo_domanda_edilizia'] == 'b') {
         $html .= '<tr>
                     <td colspan="5"></td>
                     <td colspan="3"><strong>TOTALE IMMOBILI</strong></td>
                     <td><strong>'.$immobili.'</strong></td>
                  </tr>';
      }

      $html .= '<tr>
                  <td colspan="5"></td>
                  <td colspan="3"><strong>TOTALE COSTO INTERVENTI</strong></td>
                  <td align="right"><strong>'.money_format('%.2n',$totale).'</strong></td>
               </tr>';

      $html .= '</table>';

      // Print text using writeHTMLCell()
      $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

      $pdf->SetMargins(30, 0, 80, true);

      $pdf->Write(10, 'Data:', '', 0, 'R', true, 0, false, false, 0);
      $pdf->Write(10, 'Firma:', '', 0, 'R', true, 0, false, false, 0);

      $pdf->Output('report_lettera_'.$_SESSION['tipo_domanda_edilizia'].'.pdf', 'I');
   }

    // mostra la form di inserimento vuota od in editing
     public function domanda($id='',$msg='')
   	{
          $this->load->model('Ristrutturazione');  
          if ( $this->input->get('tipo_domanda_edilizia', TRUE) ) {
             $tipo_domanda_edilizia =$this->input->get('tipo_domanda_edilizia', TRUE);
          } else {
            $tipo_domanda_edilizia = $_SESSION['tipo_domanda_edilizia'];
          }

          $data->TIPO_DOMANDA = $tipo_domanda_edilizia;
          if ($this->Ristrutturazione->check_if_validata($id)) {
             redirect('edilizia/visualizza/'.$id);
          }
         if ($id) {
         // se mi passano l'id l'id estraggo e visualizzo la domanda
             $this->Ristrutturazione->id = $id;
             $domanda = $this->Ristrutturazione->get_single_entry($id);
             if (isset($domanda)) {
               $data=$domanda;
             }
         } else {
           $data->DATA_DOMANDA = date('Y-m-d',time());
         }

         // estraggo i dati per popolare le combo di comuni e province
         $this->load->model('Comune');
         $province = $this->Comune->getArrayListProvince();
         $data->province = $province;

         if ($data->COD_PROVINCIA) {
          $comuni = $this->Comune->getArrayList($data->COD_PROVINCIA);
          $data->comuni = $comuni;
         }

         $proprietari = $this->Ristrutturazione->get_collegato('proprietario_alloggio');
         $data->proprietari = $proprietari;

         if ($tipo_domanda_edilizia == 'b'){
            $modalita = $this->Ristrutturazione->get_collegato('modalita_edilizia');
            $data->modalita = $modalita;
            $tipo_intervento = $this->Ristrutturazione->get_collegato('tipo_intervento');
            $data->tipo_intervento = $tipo_intervento;
            $quote_proprieta = $this->Ristrutturazione->get_collegato('quote_proprieta');
            $data->quote_proprieta = $quote_proprieta;
         }

         $this->load->model('Interventi');
         $interventi = $this->Interventi->get($id, $tipo_domanda_edilizia);
         $data->interventi = $interventi;

         $data->vincoli = $this->Ristrutturazione->getTipoDomandaEdilizia($tipo_domanda_edilizia);

         if ($msg) {
            $data->msg=$msg;
         }
         $this->displayHeader();

         $this->load->view('domanda_edilizia',$data);

         $this->displayFooter();
      }

      public function submit()
    	{

        $this->load->model('Ristrutturazione');

        $button = $this->input->post('submit');
        $id = $this->input->post('id');
        
        $this->Ristrutturazione->indirizzo = $this->input->post('indirizzo');
        $this->Ristrutturazione->civico = $this->input->post('civico');
        $this->Ristrutturazione->cap = $this->input->post('cap');
        $this->Ristrutturazione->provincia = $this->input->post('provincia');
        $this->Ristrutturazione->comune = $this->input->post('comune');
        $this->Ristrutturazione->foglio = $this->input->post('foglio');
        $this->Ristrutturazione->particella = $this->input->post('particella');
        $this->Ristrutturazione->proprieta = $this->input->post('proprieta');
        $this->Ristrutturazione->graduatoria = $this->input->post('graduatoria');
        $this->Ristrutturazione->manutenzione = $this->input->post('manutenzione');
        $this->Ristrutturazione->collegata = $this->input->post('collegata');
        $this->Ristrutturazione->modalita = $this->input->post('modalita');
        $this->Ristrutturazione->tipo_intervento = $this->input->post('tipo_intervento');
        $this->Ristrutturazione->quote_proprieta = $this->input->post('quote_proprieta');

        $scala = $this->input->post('scala');
        $this->Ristrutturazione->scala = (empty($scala)) ? NULL : $scala;
        $piano_ft = $this->input->post('piano_ft');
        $this->Ristrutturazione->piano_ft = (empty($piano_ft)) ? NULL : $piano_ft;
        $subalterno = $this->input->post('subalterno');
        $this->Ristrutturazione->subalterno = (empty($subalterno)) ? NULL : $subalterno;
        $alloggi_edificio = $this->input->post('alloggi_edificio');
        $this->Ristrutturazione->alloggi_edificio = (empty($alloggi_edificio)) ? NULL : $alloggi_edificio;
        $alloggi_sfitti = $this->input->post('alloggi_sfitti');
        $this->Ristrutturazione->alloggi_sfitti = (empty($alloggi_sfitti)) ? NULL : $alloggi_sfitti;
        $anno_ristrutturazione = $this->input->post('anno_ristrutturazione');
        $this->Ristrutturazione->anno_ristrutturazione = (empty($anno_ristrutturazione)) ? NULL : $anno_ristrutturazione;
        $giorni_previsti = $this->input->post('giorni_previsti');
        $this->Ristrutturazione->giorni_previsti = (empty($giorni_previsti)) ? NULL : $giorni_previsti;
        $alloggi_coinvolti = $this->input->post('alloggi_coinvolti');
        $alloggi_coinvolti = (empty($alloggi_coinvolti)) ? 1 : $alloggi_coinvolti;
        $this->Ristrutturazione->alloggi_coinvolti = $alloggi_coinvolti;
        $superficie = $this->input->post('superficie');
        $superficie = (empty($superficie)) ? NULL : $superficie;
        $this->Ristrutturazione->superficie = $superficie;
        
        $fmt = numfmt_create( 'it_IT', NumberFormatter::DECIMAL );
        
        $costo_mq = $this->input->post('costo_mq');
        $costo_mq = (empty($costo_mq)) ? NULL : str_replace('€','',$costo_mq);           
        $costo_mq = numfmt_parse($fmt, $costo_mq);
    
        $this->Ristrutturazione->costo_mq = $costo_mq;
        $iva_stimata = $this->input->post('iva_stimata');
        $iva_stimata = (empty($iva_stimata)) ? NULL : str_replace('€','',$iva_stimata);
        $iva_stimata = numfmt_parse($fmt, $iva_stimata);
        
        $this->Ristrutturazione->iva_stimata = $iva_stimata;
        $risorse_aggiuntive = $this->input->post('risorse_aggiuntive');
        $risorse_aggiuntive = (empty($risorse_aggiuntive)) ? NULL : str_replace('€','',$risorse_aggiuntive);
        $risorse_aggiuntive = numfmt_parse($fmt, $risorse_aggiuntive);
        
        $this->Ristrutturazione->risorse_aggiuntive = $risorse_aggiuntive;

        // verifica calcoli
        $costo_intervento = $this->input->post('costo_intervento');
        $costo_intervento = (empty($costo_intervento)) ? NULL : $superficie * $costo_mq;

        $this->Ristrutturazione->costo_intervento = $costo_intervento;
        $costo_totale = $this->input->post('costo_totale');
        $costo_totale = (empty($costo_totale)) ? NULL : $costo_intervento + $iva_stimata;
        $this->Ristrutturazione->costo_totale = $costo_totale;
        $costo_alloggio = $this->input->post('costo_alloggio');
        $costo_alloggio = (empty($costo_alloggio)) ? NULL : $costo_totale / $alloggi_coinvolti;
        $this->Ristrutturazione->costo_alloggio = $costo_alloggio;
        $costo_ammissibile = $this->input->post('costo_ammissibile');
        $costo_ammissibile = (empty($costo_ammissibile)) ? NULL : $costo_alloggio - $risorse_aggiuntive;
        $this->Ristrutturazione->costo_ammissibile = $costo_ammissibile;

        // determina punteggio
        $interventi = $this->input->post('interventi');
        $this->Ristrutturazione->set_punteggio($interventi);

        $this->Ristrutturazione->data_domanda = $this->input->post('data_domanda');

        $this->Ristrutturazione->stato_domanda = 1;
        if($_SESSION['atc']){
           $this->Ristrutturazione->RICHIEDENTE = $_SESSION['atc'];
           $this->Ristrutturazione->DESCRIZIONE_RICHIEDENTE = $_SESSION['atc'];
        } else {
           $this->Ristrutturazione->RICHIEDENTE = 'COM';
           $this->Ristrutturazione->COMUNE_INSERITORE = $this->user->comune_utente->ISTAT;
           $this->Ristrutturazione->DESCRIZIONE_RICHIEDENTE = $this->user->comune_utente->DESCRIZIONE;
        }
        $this->Ristrutturazione->CF_OPERATORE = $this->user->CODICE_FISCALE;

       $this->Ristrutturazione->OPERATORE = $this->user->NOME.' '.$this->user->COGNOME;
       $this->Ristrutturazione->tipo_domanda = $this->input->post('tipo_domanda');
         if ($this->Ristrutturazione->check_if_validata($id)) {
             redirect('edilizia/domanda/'.$id.'/giavalidata');
         }
         // se ho l'id aggiorno la domanda sennò nuova domanda
         if ($id) {
           $this->Ristrutturazione->id = $id;
           $this->Ristrutturazione->insert_or_update_entry($id);
         }  else {
           // è una domanda nuova quindi va salvata e faccio la insert
           $id = $this->Ristrutturazione->insert_or_update_entry();
         }

         // l'elenco interventi viene salvato cancellando quello esistente e ricaricando
         $this->load->model('Interventi');
         $this->Interventi->delete($id);
         $this->Interventi->insert($interventi, $id);

          if ($button == 'salvabozza') {
             redirect('edilizia/domanda/'.$id.'/bozzaok');
          } elseif ($button=='valida') {
             $this->displayHeader();
             $this->Ristrutturazione->valida_domanda($id);
             redirect('edilizia/visualizza/'.$id.'/validata');
             $this->displayFooter();
          }
    }

    public function elimina($id='')
   	{         
       $this->load->model('Ristrutturazione');
       if ($this->Ristrutturazione->check_if_validata($id)) {
          $this->visualizza($id);
          exit;
      }

       if ($id) {
         $domanda = $this->Ristrutturazione->delete_single_entry($id);
       }

       redirect('edilizia/elenco/');
       exit;

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
          header('Location: /epico/index.php?servizio=edilizia');
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
