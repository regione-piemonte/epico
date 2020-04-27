<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
class Nucleo extends CI_Model {
      function __construct()
      {
          // Call the Model constructor
          parent::__construct();
                    
      }
      function get($id_domanda)
      { 
             $this->db->select('*, n.ID as ID_NUCLEO, p.DESCRIZIONE as DESCR_PARENTELA');
             $this->db->join('parentela AS p', 'n.parentela = p.id', 'left');
             $query = $this->db->get_where('nucleo_famigliare as n', array('id_domanda' => $id_domanda));             
             $res = $query->result();
             return $res;   
      }
      function get_single($id_nucleo)
      { 
             $query = $this->db->get_where('nucleo_famigliare', array('id' => $id_nucleo));             
             $res = $query->result();
             return $res;   
      }
      function insert($nuclei,$id_domanda)
      {
             foreach ($nuclei as &$nucleo) {
                $this->id = $nucleo['id'];
                $this->id_domanda = $id_domanda;
                $this->nome = $nucleo['nucleo_nome'];
                $this->cognome = $nucleo['nucleo_cognome'];
                $this->codice_fiscale = $nucleo['nucleo_codice_fiscale'];
                if ($nucleo['nucleo_data_nascita']) {$this->data_nascita = DateTime::createFromFormat('d/m/Y', $nucleo['nucleo_data_nascita'])->format('Y-m-d');}
                $this->parentela = $nucleo['nucleo_parentela'];
                $this->data_inserimento    = date('Y-m-d H:i:s');                 
                $this->db->insert('nucleo_famigliare', $this);                
             }
             //exit;
                                
      }
      
      function delete($id_domanda)
      { 
         $this->db->where('ID_DOMANDA', $id_domanda);
         $this->db->delete('nucleo_famigliare');                  
      }
      public function numero_componenti_nucleo($id_domanda) {
           $this->db->where('id_domanda',$id_domanda);
           $this->db->where("nome !=''");
           $this->db->where("cognome !=''");
           $this->db->where("codice_fiscale !=''");
           $this->db->from('nucleo_famigliare');
           return $this->db->count_all_results();
      } 
       public function calcola_eta($id_domanda) {
            
           $res = $this->get($id_domanda);
           
           return $this->db->count_all_results();
      }   
      public function numero_figli($id_domanda) {
           $this->db->where('id_domanda',$id_domanda);
           $this->db->where('parentela','1');
           $this->db->where("nome !=''");
           $this->db->where("cognome !=''");
           $this->db->where("codice_fiscale !=''");
           $this->db->from('nucleo_famigliare');
           return $this->db->count_all_results();
      }
      
      function checkMinorenni($id_domanda)
      { 
         $this->db->where('ID_DOMANDA', $id_domanda);
         $this->db->where('nf.ETA < 18');         
         $this->db->from('nucleo_famigliare as nf');
             
         $res = 0;
         if ($this->db->count_all_results() > 0) {
            $res = 1;         
         }   
         //print_r($this->db->queries); exit;
             //echo $this->db->count_all_results(); exit; 
         return $res;              
      }
      function checkOver70($id_domanda)
      { 
         $this->db->where('ID_DOMANDA', $id_domanda);
         $this->db->where('(nf.ETA >=70 or d.ETA >=70)');
         
         $this->db->join('domanda AS d', 'd.ID = nf.ID_DOMANDA','left');
         $this->db->from('nucleo_famigliare as nf');
          //print_r($this->db->queries); exit;
         $res = 0;
         if ($this->db->count_all_results() > 0) {
            $res = 1;         
         }    
         return $res;              
      }

     
          
}
?>