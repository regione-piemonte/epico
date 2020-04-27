<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
  class Comune extends CI_Model {

      var $id   = '';
      function __construct()
      {
          // Call the Model constructor
          parent::__construct();

      }
      function getComuni(){
	      $query = $this->db->get('comuni');
          return $query->result();
      }
      function getArrayComuni()
      {
          $res = $this->getComuni();
          $arr_comune = array('-' => '-');
          foreach ($res as &$comune) {
            $arr_comune[$comune->ISTAT]=$comune->DESCRIZIONE;
          }
          return $arr_comune;
      }
      function getArrayList($id_provincia)
      {
          $res = $this->getList($id_provincia);
          $arr_comune = array('-' => '-');
          foreach ($res as &$comune) {
            $arr_comune[$comune->ISTAT]=$comune->DESCRIZIONE;
          }
          return $arr_comune;
      }
      function getList($id_provincia)
      {
          $query = $this->db->get_where('comuni', array('COD_PROVINCIA' => $id_provincia));
          $res = $query->result();
          return $res;
      }
      function getSingle($istat)
      {
          $query = $this->db->get_where('comuni', array('ISTAT' => $istat));
          return $query->result();
      }
      function getListProvince()
      {
          $query = $this->db->order_by('SIGLA');
          $query = $this->db->get('province');
          return $query->result();
      }
      function getArrayListProvince()
      {
          $res = $this->getListProvince();
          $arr_province = array('-' => '-');
          foreach ($res as &$provincia) {
            $arr_province[$provincia->COD_PROVINCIA]=$provincia->SIGLA;
          }
          return $arr_province;
      }
      function getSingleProvincia($cod_provincia)
      {
          $query = $this->db->get_where('province', array('COD_PROVINCIA' => $cod_provincia));
          return $query->result();
      }
      function getProvinciaFromComune()
      {
          $query = $this->db->get_where('province', array('id' => $this->id));
          return $query->result();
      }
       function getComuniDomande()
      {
          $this->db->select('COMUNE_INSERITORE, c.DESCRIZIONE');
          $this->db->distinct('COMUNE_INSERITORE');
          $this->db->join('comuni AS c', 'c.istat = d.COMUNE_INSERITORE');
          $query = $this->db->get_where('domanda AS d');

          $res = $query->result();
           $arr_comune = array('' => '-');
           foreach ($res as &$comune) {
            $arr_comune[$comune->COMUNE_INSERITORE]=$comune->DESCRIZIONE;
          }

          //print_r($res);

          return $arr_comune;
      }
}

?>
