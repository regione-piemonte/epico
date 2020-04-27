<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
  class Assegnatario extends CI_Model {
  
      //var $id   = '';
      
      function __construct()
      {
          // Call the Model constructor
          parent::__construct();
                    
      }
      function get($id_domanda)
      { 
	      	 $query = $this->db->select('*, ts.DESCRIZIONE as DESCRIZIONE_TITOLO_STUDIO');
	      	 $this->db->join('titolo_studio AS ts', 'ts.id = titolo_studio', 'left');
             $query = $this->db->get_where('assegnatari', array('id_domanda' => $id_domanda));             
             $res = $query->result();
             if(!empty($res)) {
	             $res = $res[0];
	             $res->CITTADINANZA = $this->get_cittadinanza($res->CITTADINANZA);
	         }
             return (object) $res;   
      }
      function insert()
      {
	      if($this->nome && $this->cognome && $this->data_nascita && $this->codice_fiscale && $this->titolo_studio && $this->cittadinanza){
            $this->data_nascita = DateTime::createFromFormat('d/m/Y', $this->data_nascita)->format('Y-m-d');
            //$this->data_inserimento    = date('Y-m-d H:i:s'); 
            $this->db->insert('assegnatari', $this);     
          }                                          
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
      function get_cittadinanza($id){
	      $cittadinanza = array('itue'=>'Italiana/UE', 'exue'=>'Extra UE');
	      return $cittadinanza[$id];
      }              
}
  
?>