<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

  class Domanda extends CI_Model {
  
      var $id   = '';
      
      function __construct()
      {
          // Call the Model constructor
          parent::__construct();
                    
      }
      function get_entries($filter,$id='',$for_elenco='')
      {   
          if ($for_elenco) {
             $this->db->select('d.NOME as NOME, d.COGNOME as COGNOME,d.DATA_DOMANDA,d.CODICE_FISCALE_USER,d.DESCRIZIONE_COMUNE_INSERITORE, 
                            d.ID as ID_DOMANDA,d.OPERATORE,s.DESCRIZIONE as DESCRIZIONE_STATO_DOMANDA, c.DESCRIZIONE AS DESCR_COMUNE,
                            td.DESCRIZIONE as DESCRIZIONE_TIPO_DOMANDA,ts.DESCRIZIONE as DESCRIZIONE_TITOLO_STUDIO, d.STATO_DOMANDA, d.ETA ');
          } else {
              $this->db->select('*,d.ID as ID_DOMANDA, s.DESCRIZIONE as DESCRIZIONE_STATO_DOMANDA, c.DESCRIZIONE AS DESCR_COMUNE,
                            td.DESCRIZIONE as DESCRIZIONE_TIPO_DOMANDA,ts.DESCRIZIONE as DESCRIZIONE_TITOLO_STUDIO');
          }          
          $this->db->join('stato_domanda AS s', 's.id = d.stato_domanda', 'left');
          $this->db->join('tipo_domanda AS td', 'td.id = d.tipo_domanda', 'left');
          $this->db->join('titolo_studio AS ts', 'ts.id = d.titolo_studio', 'left');
          
          $this->db->join('comuni AS c', 'c.ISTAT = d.RESID_COMUNE_ISTAT','left');          
          if ($filter['nome']) {$this->db->like('UPPER(d.NOME)', strtoupper($filter['nome']));}
          if ($filter['cognome']) {$this->db->like('UPPER(d.COGNOME)', strtoupper($filter['cognome']));}
          if ($filter['operatore']) {$this->db->like('UPPER(d.OPERATORE)', strtoupper($filter['operatore']));}
          if ($filter['id_domanda']) {$this->db->where('d.ID', $filter['id_domanda']); }
          if ($filter['stato_domanda']) {$this->db->where('d.STATO_DOMANDA', $filter['stato_domanda']); }          
          if ($filter['comune_inseritore']) {$this->db->where('d.COMUNE_INSERITORE', $filter['comune_inseritore']); }
          if ($filter['tipo_domanda']) {$this->db->where('d.TIPO_DOMANDA', $filter['tipo_domanda']); }
  
          if ($filter['tipo_utente'] == 'REG' and $filter['export_regione'] != '1') {
              $this->db->where('d.stato_domanda', 2);
          }
          if ($filter['invalidita'] == '1') {$this->db->where('d.FCT', 1); }
          if ($filter['minorenne'] == '1') {
              $this->db->join('nucleo_famigliare AS nf', 'nf.ID_DOMANDA = d.ID','left');
              $this->db->group_by('nf.ID_DOMANDA');
              $this->db->having('MIN(nf.ETA) < ', 18, null); 
              //$this->db->where('nf.ETA < ', 18); 
          }
          if ($filter['over70'] == '1') {
              $this->db->join('nucleo_famigliare AS nf2', 'nf2.ID_DOMANDA = d.ID','left');
              $this->db->group_by('nf2.ID_DOMANDA');
              $this->db->having('(MAX(nf2.ETA) >= 70 OR d.ETA >= 70)', null, null);
          }
          
          if ($filter['data_da']) {
              $data_da =date('Y-m-d',strtotime(strtr($filter['data_da'],"/","-")));
              $this->db->where('d.data_domanda >=',  $data_da ); 
          }
           if ($filter['data_a']) {
              $data_a =date('Y-m-d',strtotime(strtr($filter['data_a'],"/","-")));
              $this->db->where('d.data_domanda <=', $data_a);     
          }
          
          if ($filter['data_da_report']) {
              $data_da =date('Y-m-d',strtotime(strtr($filter['data_da_report'],"/","-")));
              $this->db->where('d.data_domanda >=',  $data_da ); 
          }
           if ($filter['data_a_report']) {
              $data_a =date('Y-m-d',strtotime(strtr($filter['data_a_report'],"/","-")));
              $this->db->where('d.data_domanda <=', $data_a);     
          }
      
          if ($id) {$this->db->where('d.ID', $id); }
            
          $this->db->order_by('DATA_SALVATAGGIO_BOZZA','desc');
          $query = $this->db->get('domanda AS d');
          
          $res = $query->result();
             
          return $res;
      }
      function get_single_entry()
      {
          
          $query = $this->db->get_where('domanda', array('id' => $this->id));
          $res = $query->result();
          $res = $res[0];
          $istat = $res->RESID_COMUNE_ISTAT;
          $nuovo_istat = $res->NUOVARESID_COMUNE_ISTAT;
          //print   $istat;
          
          $this->load->model('Comune');
          $comune = $this->Comune->getSingle($istat);
          $cod_provincia = $comune[0]->COD_PROVINCIA;          
          $res->COD_PROVINCIA = $cod_provincia;
          
          $nuovo_comune = $this->Comune->getSingle($nuovo_istat);
          $nuovo_cod_provincia = $nuovo_comune[0]->COD_PROVINCIA; 
          $res->NUOVO_COD_PROVINCIA = $nuovo_cod_provincia;
          return $res;
      }
     
      function insert_or_update_entry($id)
      {             
          $this->data_salvataggio_bozza    = date('Y-m-d H:i:s');
                    
          $this->data_nascita = ($this->data_nascita) ? DateTime::createFromFormat('d/m/Y', $this->data_nascita)->format('Y-m-d') : null;
          $this->data_rilascio_isee = ($this->data_rilascio_isee) ? $this->data_rilascio_isee = DateTime::createFromFormat('d/m/Y', $this->data_rilascio_isee)->format('Y-m-d') : null;
          $this->data_contratto = ($this->data_contratto) ? DateTime::createFromFormat('d/m/Y', $this->data_contratto)->format('Y-m-d') : null;
          $this->data_nuovo_contratto = ($this->data_nuovo_contratto) ? DateTime::createFromFormat('d/m/Y', $this->data_nuovo_contratto)->format('Y-m-d') : null;          
          $this->data_nascita_garante = ($this->data_nascita_garante) ? DateTime::createFromFormat('d/m/Y', $this->data_nascita_garante)->format('Y-m-d') : null;
          $this->data_nascita_proprietario = ($this->data_nascita_proprietario) ? DateTime::createFromFormat('d/m/Y', $this->data_nascita_proprietario)->format('Y-m-d') : null;
          $this->data_scontrino = ($this->data_scontrino) ? DateTime::createFromFormat('d/m/Y', $this->data_scontrino)->format('Y-m-d') : null;
          $this->data_protocollo = ($this->data_protocollo) ? DateTime::createFromFormat('d/m/Y', $this->data_protocollo)->format('Y-m-d') : null;          
          $this->scadenza_fondo_garanzia_proprietario = ($this->scadenza_fondo_garanzia_proprietario) ?  DateTime::createFromFormat('d/m/Y', $this->scadenza_fondo_garanzia_proprietario)->format('Y-m-d') : null;
          $this->data_liquidazione = ($this->data_liquidazione) ? DateTime::createFromFormat('d/m/Y', $this->data_liquidazione)->format('Y-m-d') : null;
          $this->data_registrazione_contratto = ($this->data_registrazione_contratto) ? DateTime::createFromFormat('d/m/Y', $this->data_registrazione_contratto)->format('Y-m-d') : null;
          $this->data_registrazione_nuovo_contratto = ($this->data_registrazione_nuovo_contratto) ? DateTime::createFromFormat('d/m/Y', $this->data_registrazione_nuovo_contratto)->format('Y-m-d') : null;   

          $this->data_domanda = ($this->data_domanda) ? DateTime::createFromFormat('d/m/Y', $this->data_domanda)->format('Y-m-d') : null;

          if (!$id) {           
            $this->db->insert('domanda', $this);
            $last_id = $this->db->insert_id();
          } else {
            $this->db->update('domanda', $this, array('ID' => $id));
            $last_id = $id;
           }
              
          return $last_id;
      }      
      
      function delete_single_entry($id)
      {     
          $this->db->delete('domanda', array('id' => $id));
      }
      
      // calcola a livello serve l'età dei famigliari del nucleo proponente
      public function aggiorna_eta_nuclei_e_proponente($id_domanda) {
        $this->load->model('Nucleo'); 
        $this->load->helper('varie');
        $n_figli=$this->Nucleo->numero_figli($id_domanda);
        $n_nucleo=$this->Nucleo->numero_componenti_nucleo($id_domanda);    
          //calcolo l'età dei nuclei famigliari                
        $nuclei = $this->Nucleo->get($id_domanda);
        foreach ($nuclei as &$nucleo) {
            $eta_nucleo = calcutateAge($nucleo->DATA_NASCITA,$this->data_domanda);
            $nucleo->ID;
            $array_nucleo=array('ETA'=>$eta_nucleo); 
             
            $this->db->where('id',$nucleo->ID_NUCLEO);
            $this->db->update('nucleo_famigliare',$array_nucleo);
        }
        // calcolo età proponente domanda
         $this->load->model('Domanda');
        $domanda = $this->get_entries('',$id_domanda);
        $eta = calcutateAge($this->data_nascita,$this->data_domanda);
        $array_update= array( 'ETA'=>$eta,
                              'NUMERO_COMPONENTI_NUCLEO'=>$n_nucleo,
                              'NUMERO_FIGLI_NUCLEO'=>$n_figli
                                 );
        $this->db->where('id',$id_domanda);
        $this->db->update('domanda',$array_update);
      }
      public function valida_domanda($id_domanda) {     
                       
        $this->load->model('Domanda');
        $domanda = $this->get_entries('',$id_domanda);
        $domanda = $domanda[0];
 

        $array_update= array('stato_domanda'=>2,
                             'DATA_SALVATAGGIO_VALIDAZIONE'=>date('Y-m-d H:i:s')                             
                             );
        $this->db->where('id',$id_domanda);
        $this->db->update('domanda',$array_update);
      }
                                    
      public function check_if_validata($id_domanda) { 
        $this->db->where('ID', $id_domanda); 
        $this->db->where('STATO_DOMANDA', '2');       
       
        $query = $this->db->get('domanda');
        $res = $query->result();
        return !empty($res); // 1 se validata, 0 se nn validata 
      }
      function get_collegato($tabella='',$id='')
      {
          $this->db->order_by('ID','asc');
          if ($id !='') {
               
              $query = $this->db->get_where($tabella, array('id' => "$id"));
          } else {
               $arr_collegato= array('' => '-'); 
               $query = $this->db->get($tabella);     
          }
          $res = $query->result();   
        
          foreach ($res as &$collegato) {
            $arr_collegato[$collegato->ID]=$collegato->DESCRIZIONE;
          }
          return  $arr_collegato;    
      }
      function checkInvalidita($id_domanda)
      { 
         $this->db->where('ID', $id_domanda);
         $this->db->where('FCT',1);
         $this->db->from('domanda');
         return $this->db->count_all_results();              
      }                    
}
  
?>