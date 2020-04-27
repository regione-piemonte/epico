<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
class Ristrutturazione extends CI_Model {

    var $id   = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

    }

    function set_punteggio($interventi)
    {
         $fmt = numfmt_create( 'it_IT', NumberFormatter::DECIMAL );
         if ($_SESSION['tipo_domanda_edilizia'] == 'a'){
            // graduatoria = 30pt
            $punteggio = $this->graduatoria * 30;
            // almeno 5 interventi = 20pt
            if(count($interventi) >= 5) $punteggio += 20;
            // se presenti interventi su impianti 30pt
            $impianti = array_intersect(array('6','7','8'), $interventi);
            if(!empty($impianti)) $punteggio += 30;

         } elseif ($_SESSION['tipo_domanda_edilizia'] == 'b'){
            // graduatoria = 5pt
            if ($this->graduatoria) {
              $punteggio += 5;
            }            
            // manutenzione = 20pt
            if ($this->manutenzione) {
              $punteggio += 20;
            }
            if ($this->collegata) {
              $punteggio += 20;
            }
            // se presenti interventi strutturali 5pt
            $strutturali = array_intersect(array('2','4','6'), $interventi);
            if(!empty($strutturali)) $punteggio += 5;
            // efficentamento energetico 25pt
            if(in_array('1', $interventi)) $punteggio += 25;
            // rimozione materiali nocivi e pericolosi 10pt
            if(in_array('3', $interventi)) $punteggio += 10;
            // mautenzione straordinaria parti comuni 7,5pt
            if(in_array('5', $interventi)) $punteggio +=  7.5;
         }
     
      $this->punteggio = $punteggio;
    }

    function get_entries($filter,$id='')
    {
      $this->db->select('*, e.ID as ID_DOMANDA, s.DESCRIZIONE as DESCRIZIONE_STATO_DOMANDA, c.DESCRIZIONE AS DESCR_COMUNE, p.SIGLA AS SIGLA_PROV, m.DESCRIZIONE AS OGGETTO, a.CODICE_FISCALE as ASSEGNATARIO, e.GIORNI_PREVISTI ');
      $this->db->join('assegnatari AS a', 'a.id_domanda = e.ID', 'left');
      $this->db->join('stato_domanda AS s', 's.id = e.stato_domanda', 'left');
      $this->db->join('comuni AS c', 'c.ISTAT = e.COMUNE','left');
      $this->db->join('province AS p', 'p.COD_PROVINCIA = e.PROVINCIA','left');
      $this->db->join('modalita_edilizia AS m', 'm.ID = e.MODALITA','left');

      if ($filter['comune_inseritore']) {$this->db->where('e.COMUNE_INSERITORE', $filter['comune_inseritore']); }
      if ($filter['stato_domanda']) {$this->db->where('e.STATO_DOMANDA', $filter['stato_domanda']); }
      
      if ($filter['data_domanda']) {$this->db->where("e.DATA_DOMANDA  BETWEEN '".$filter['data_domanda']."-01-01' AND '".$filter['data_domanda']."-12-31' "); }
      
      if ($filter['tipo_domanda_edilizia']) {$this->db->where('e.TIPO_DOMANDA', $filter['tipo_domanda_edilizia']); }
      if ($filter['tipo_utente']) {
         switch ($filter['tipo_utente']) {
            case 'SUP':
               break;
            case 'REG':
               $this->db->where('e.stato_domanda', 2);
               break;
            default:
               $this->db->where('e.richiedente', $filter['tipo_utente']);
               break;
            }
      }

      $this->db->order_by('e.ID', 'DESC');

      if ($id) {$this->db->where('e.ID', $id); }

      $query = $this->db->get('edilizia AS e');

      $res = $query->result();
      //print_r($this->db->queries);     exit;
      return $res;
    }

    public function valida_domanda($id_domanda) {

     $this->load->model('Ristrutturazione');
     $domanda = $this->get_entries('',$id_domanda);
     $domanda = $domanda[0];

     $array_update= array('stato_domanda'=>2);
     $this->db->where('id',$id_domanda);
     $this->db->update('edilizia',$array_update);
    }

    function get_single_entry()
    {

        $query = $this->db->get_where('edilizia', array('id' => $this->id));
        $res = $query->result();
        $res = $res[0];

        $istat = $res->COMUNE;

        $this->load->model('Comune');
        $comune = $this->Comune->getSingle($istat);
        $cod_provincia = $comune[0]->COD_PROVINCIA;
        $res->COD_PROVINCIA = $cod_provincia;

        return $res;
    }

    function insert_or_update_entry($id=false)
    {    
      //$this->data_domanda = ($this->data_domanda) ? DateTime::createFromFormat('d/m/Y', $this->data_domanda)->format('Y-m-d') : null;
      
      $this->costo_mq = str_replace(',','.',$this->costo_mq);        
      $this->iva_stimata = str_replace(',','.',$this->iva_stimata);
      $this->costo_intervento = str_replace(',','.',$this->costo_intervento);
      $this->costo_totale = str_replace(',','.',$this->costo_totale);

        if (!$id) {
          $this->db->insert('edilizia', $this);
          $last_id = $this->db->insert_id();
        } else {
          $this->db->update('edilizia', $this, array('ID' => $id));
          $last_id = $id;
         }

        return $last_id;
    }

    function delete_single_entry($id)
    {
        $this->db->delete('edilizia', array('id' => $id));
    }

    public function check_if_validata($id) {
     $this->db->where('ID', $id);
     $this->db->where('STATO_DOMANDA', 2);

     $query = $this->db->get('edilizia');
     $res = $query->result();
     return !empty($res); // 1 se validata, 0 se nn validata
    }

    public function getTipoDomandaEdilizia($codice){
      $this->db->select('INIZIO, FINE, (now() > INIZIO AND now() < FINE) AS VALIDABILE, INIZIO_ASSEGNAZIONE, FINE_ASSEGNAZIONE, (now() > INIZIO_ASSEGNAZIONE AND now() < FINE_ASSEGNAZIONE) AS ASSEGNABILE');
      $this->db->where('CODICE', $codice);
      $query = $this->db->get('tipo_domanda_edilizia');
      $res = $query->result();
     return $res[0];
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
}

?>
