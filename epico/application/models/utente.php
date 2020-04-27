<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
  class Utente extends CI_Model {

      var $id   = '';
      var $codice_fiscale   = '';
      var $tipo_utente   = '';

      function __construct()
      {
          // Call the Model constructor
          parent::__construct();

      }
      function get_entry($codice_fiscale)
      {
          $this->db->where('codice_fiscale',$codice_fiscale);
          $query = $this->db->get('utenti');
          //print_r($this->db->queries);
          $res = $query->result();
          $res = $res[0];

          //se c'è il  ruolo di iride lo uso per sovrascrivere quello su db
          // (lo spostamento della gestione ruoli è avvenuto da db ad iride durante lo sviluppo del progetto)
          if ($_SESSION['ruolo']) {
            $this->db->flush_cache();
            $ruolo = $_SESSION['ruolo'];
            $this->db->where('ruolo_iride',$ruolo);
            $query = $this->db->get('utenti_ruolo_iride');
            $res_iride = $query->result();
            $res_iride = $res_iride[0];
            $res->TIPO_UTENTE =  $res_iride->TIPO_UTENTE;
          }

          return $res;
      }
      function get_comuni_utente($id)
      {
          $arr_istat = $_SESSION['istat_comuni'];
          if(!empty($arr_istat)){
             //print_r($arr_comuni);
             $arr_comuni = array('' => '-');
             foreach ($arr_istat as $istat) {
                 $istat = str_pad($istat, 6, "0", STR_PAD_LEFT );
                 $this->db->select('c.descrizione');
                 $this->db->where('istat',$istat);
                 $query = $this->db->get('comuni AS c');
                 //print_r($this->db->queries);
                 $res = $query->result();
                 $res = $res[0];


                $arr_comuni[$istat]=$res->descrizione;
             }
          } else $arr_comuni = array();
          return $arr_comuni;
      }
      function get_atc(){
         $arr_atc = $_SESSION['codici_atc'];
         if(!empty($arr_atc)){
            //$arr_atc[''] = '-';
            $arr_atc = array_merge(array('' => '-'), array_combine($arr_atc, $arr_atc));
         } else $arr_atc = array();
         return $arr_atc;
      }
}

?>
