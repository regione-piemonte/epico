<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/


function configPagination($num_of_record, $url = "index.php/domande/elenco") {
       $config['base_url'] = base_url() . $url;       
       $config['per_page'] = 10;
       $config['num_links'] = 5;
       $config["uri_segment"] = 3;
       $config['reuse_query_string'] = TRUE;
       $config['num_tag_open'] = '<li>';
       $config['num_tag_close'] = '</li>';
       $config['cur_tag_open'] = '<li class="active"><a>';
       $config['cur_tag_close'] = '</a></li>';
       $config['last_tag_open'] = '<li class="end">';
       $config['last_tag_close'] = '</li>';
       $config['first_tag_open'] = '<li class="start">';
       $config['first_tag_close'] = '</li>';
       $config['next_tag_open'] = '<li class="succ">';
       $config['next_tag_close'] = '</li>';
       $config['prev_tag_open'] = '<li class="prec">';
       $config['prev_tag_close'] = '</li>';
       $config['next_link'] = 'Succ';
       $config['prev_link'] = 'Prec';
       $config['last_link'] = 'Fine';
       $config['first_link'] = 'Inizio';
       $config['total_rows'] = $num_of_record;
       return $config;

}

function dateFormatter($date) {
       if ($date) {
          $formatted_date=DateTime::createFromFormat('Y-m-d', $date)->format('d/m/Y');
       }
       return $formatted_date;
}
function parseDateIt($date) {
       if ($date) {
          $date=DateTime::createFromFormat('Y-m-d', $date);
       }
       return $date;
}
function calcutateAge($data_nascita,$data_domanda){

          if ($data_nascita and $data_domanda) {
           $data_nascita = date_create_from_format('Y-m-d', $data_nascita);
           $data_domanda = date_create_from_format('Y-m-d', $data_domanda);
           $diff = $data_nascita->diff($data_domanda);
           return $diff->y;
         }  else {
            return '';
         }


}

function priceToSQL($price)
{
	$price = preg_replace('/[^0-9\.,]*/i', '', $price);
	if(!empty($price)){
	    $price = str_replace(',', '.', $price);

	    if(substr($price, -3, 1) == '.')
	    {
	        $price = explode('.', $price);
	        $last = array_pop($price);
	        $price = join($price, '').'.'.$last;
	    }
	    else
	    {
	        $price = str_replace('.', '', $price);
	    }
	} else $price = null;
    return $price;
}
