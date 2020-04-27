<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

class Interventi extends CI_Model {
      function __construct()
      {
          // Call the Model constructor
          parent::__construct();

      }

      function get($id_edilizia, $tipo_domanda_edilizia)
      {
        if ($id_edilizia) {
           $this->db->select('id, descrizione, (id_edilizia IS NOT NULL) AS attivo');
           $this->db->join("(SELECT * FROM `interventi_edilizia` WHERE id_edilizia = $id_edilizia) AS ie", 'ie.id_intervento = i.id', 'left');
        } else {
           $this->db->select('id, descrizione');
        }

        $this->db->where('tipo_domanda', $tipo_domanda_edilizia);
        $this->db->order_by('id');
        $query = $this->db->get('interventi AS i');
        $res = $query->result();

        return $res;
      }

      function insert($interventi, $id_edilizia)
      {
         if(!empty($interventi)){
            //var_dump($interventi); die();
             foreach ($interventi as &$intervento) {
                $this->id_intervento = $intervento;
                $this->id_edilizia = $id_edilizia;
                $this->db->insert('interventi_edilizia', $this);                
             }
         }
      }

      function delete($id_edilizia)
      {
         $this->db->where('id_edilizia', $id_edilizia);
         $this->db->delete('interventi_edilizia');
      }

}
?>
