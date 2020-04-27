<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

function getUser($codice_fiscale) {
    $CI =&get_instance();
    $CI->load->database();
    $CI->load->model('utente');    
    $res = new stdClass();     
    
    //se c'è il  ruolo di iride lo uso per sovrascrivere quello su db
    // (lo spostamento della gestione ruoli è avvenuto da db ad iride durante lo  del progetto)
     if ($_SESSION['ruolo']) {
            //$CI->db->flush_cache();
            $ruolo = $_SESSION['ruolo'];                        
            $CI->db->where('ruolo_iride',$ruolo);
            $query = $CI->db->get('utenti_ruolo_iride');
            $res_iride = $query->result();
            $res_iride = $res_iride[0];
            $res->TIPO_UTENTE =  $res_iride->TIPO_UTENTE;
          }
     
    $res->CODICE_FISCALE =  $_SESSION['identita_shib']['cod_fiscale'];
    $res->NOME = $_SESSION['identita_shib']['nome'];
    $res->COGNOME = $_SESSION['identita_shib']['cognome'];
    //print_r($res);
    return $res;
}

function build_array_identita($string_identita){      
        // Recover Data
        $array_identita = explode('/', $string_identita);                          
        if (count($array_identita) > 7) {                          
            $array_identita_bis = $array_identita;            
            for ($q = 1; $q <= 6; $q++) {
                 array_shift($array_identita_bis);
            }         
            $mac = implode('/', $array_identita_bis);            
            $array_identita[6] = $mac;
        }
        return $array_identita;
}

