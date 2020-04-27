<?php

/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

function createExcel($domande, $filtro) {

      require_once(APPPATH.'libraries/PHPExcel.php');
//         print_r($domande);
//         exit;<
      $CI =&get_instance();
      $CI->load->model('Nucleo');
      $CI->load->model('Domanda');
      $CI->load->model('Comune');
      /** Create a new PHPExcel Object **/ 
      $objPHPExcel = new PHPExcel();
      
      $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory; 
      PHPExcel_Settings::setCacheStorageMethod($cacheMethod);      
      $validLocale = PHPExcel_Settings::setLocale('it_it'); 
      if (!$validLocale) { echo 'Unable to set locale to '.$locale." - reverting to en_us<br />\n"; }
      $x = 1;
      $y_offset = 5;
      $objPHPExcel->createSheet();
      if($filtro){
	     $objPHPExcel->getActiveSheet()->setTitle('Domande'); 
      } else {
	      $objPHPExcel->getActiveSheet()->setTitle('Domande FMI'); 
	      $objPHPExcel->createSheet();
	      $objPHPExcel->setActiveSheetIndex(1);
	      $objPHPExcel->getActiveSheet()->setTitle('Domande Agenzie');
      }
       
     $cols = array('1' => 52, '2' => 45); //set the number of columns
    
    for ($j=1; $j < $objPHPExcel->getSheetCount(); $j++){
  	  if ($filtro) $nCols = $cols[$filtro];
  	  else {
  		  $objPHPExcel->setActiveSheetIndex($j-1);
  		  $nCols = $cols[$j];
  	  }
        foreach (range(0, $nCols) as $col) {
              $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);   
              //$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(15);             
        }
     }
  
       $type = PHPExcel_Cell_DataType::TYPE_STRING;
       $type_num = PHPExcel_Cell_DataType::TYPE_NUMERIC;
     
       $i= array('1' => 0, '2' => 0);
      
      foreach ($domande as &$domanda)  {
               //print_r($domanda);
            $tipo_domanda=$domanda->TIPO_DOMANDA;
            
            $x = 0;
            $y = $i[$tipo_domanda]+$y_offset;
               
            if(!$filtro) $objPHPExcel->setActiveSheetIndex($tipo_domanda - 1);
			
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($x,$y_offset-2)->setValueExplicit($domanda->DESCRIZIONE_TIPO_DOMANDA, $type);
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($x,$y)->setValueExplicit($domanda->ID_DOMANDA, $type);
            
            $data_domanda = ($domanda->DATA_DOMANDA) ? date("d/m/Y",strtotime($domanda->DATA_DOMANDA)) : '';
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($data_domanda, $type); 							//1
            
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->NOME, $type);							//2
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->COGNOME, $type);							//3
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->CODICE_FISCALE, $type);					//4
            
            $data_nascita = ($domanda->DATA_NASCITA) ? date("d/m/Y",strtotime($domanda->DATA_NASCITA)) : '';
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($data_nascita, $type);       						//5
            
            $cittadinanza = str_replace("-", "", reset($CI->Domanda->get_collegato('cittadinanza',$domanda->CITTADINANZA)));
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($cittadinanza, $type);								//6
            $titolo_studio = str_replace("-", "", reset($CI->Domanda->get_collegato('titolo_studio',$domanda->TITOLO_STUDIO)));		
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($titolo_studio, $type);							//7
            
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->REDDITO_IMPONIBILE_ISEE, $type_num);		//8
            
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->REDDITO_EQUIVALENTE_ISEE, $type_num);	//9
            
            $data_rilascio_isee = ($domanda->DATA_RILASCIO_ISEE) ? date("d/m/Y",strtotime($domanda->DATA_RILASCIO_ISEE)) : '';
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($data_rilascio_isee, $type);						//10
            
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->AIUTI_ECON_NO_ISEE, $type_num);			//11
            $permesso_soggiorno = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$domanda->PERMESSO_SOGGIORNO)));			
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($permesso_soggiorno, $type);            			//12
            $sfratto = str_replace("-", "", reset($CI->Domanda->get_collegato('sfratto',$domanda->SCONTRINO)));
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($sfratto, $type);									//13
            
            if ($tipo_domanda ==1) {   
	                
	        	$data_sfratto = ($domanda->DATA_SCONTRINO) ? date("d/m/Y",strtotime($domanda->DATA_SCONTRINO)) : '';
	            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($data_sfratto, $type);							//14 (1)
	            
	        } else {
		        
		        $motivazione_sfratto = str_replace("-", "", reset($CI->Domanda->get_collegato('motivazione_scontrino',$domanda->MOTIVAZIONE_SCONTRINO)));
	     		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($motivazione_sfratto, $type);					//14 (2)
	        }
	        
            $indirizzo = $domanda->RESID_INDIRIZZO.' '.$domanda->RESID_CIVICO.' Cap: '.$domanda->RESID_CAP. ', '.$domanda->DESCR_COMUNE;              
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($indirizzo, $type);       							//15
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->DESCR_COMUNE, $type);  					//16
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->RESID_COMUNE_ISTAT, $type);  			//17
            $nuovaresid_comune = $CI->Comune->getSingle($domanda->NUOVARESID_COMUNE_ISTAT);
            $nuova_residenza = $domanda->NUOVARESID_INDIRIZZO.' '.$domanda->NUOVARESID_CIVICO.' Cap: '.$domanda->NUOVARESID_CAP. ', '.$nuovaresid_comune[0]->DESCRIZIONE;              
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($nuova_residenza, $type);							//18
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($nuovaresid_comune[0]->DESCRIZIONE, $type);		//19
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->NUOVARESID_COMUNE_ISTAT, $type);			//20
            $tipo_godimento = str_replace("-", "", reset($CI->Domanda->get_collegato('tipo_godimento',$domanda->TIPO_GODIMENTO_ABITAZIONE)));
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($tipo_godimento, $type);							//21
            $tipo_contratto_atto = str_replace("-", "", reset($CI->Domanda->get_collegato('tipo_contratto_atto',$domanda->TIPO_CONTRATTO_ATTO)));
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($tipo_contratto_atto, $type);						//22
            $durata_contratto = str_replace("-", "", reset($CI->Domanda->get_collegato('durata_contratto',$domanda->DURATA_CONTRATTO)));
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($durata_contratto, $type);							//23
            
            $data_contratto = ($domanda->DATA_CONTRATTO) ? date("d/m/Y",strtotime($domanda->DATA_CONTRATTO)) : '';
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($data_contratto, $type);							//24
            
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->AMMONTARE_ATTO, $type_num);				//25
            $durata_nuovo_contratto = str_replace("-", "", reset($CI->Domanda->get_collegato('durata_nuovo_contratto',$domanda->DURATA_NUOVO_CONTRATTO)));
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($durata_nuovo_contratto, $type);					//26
            
            $data_nuovo_contratto = ($domanda->DATA_NUOVO_CONTRATTO) ? date("d/m/Y",strtotime($domanda->DATA_NUOVO_CONTRATTO)) : '';
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($data_nuovo_contratto, $type);						//27
            
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->AMMONTARE_NUOVO_CONTRATTO, $type_num);	//28
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->NOME_PROPRIETARIO, $type);				//29
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->COGNOME_PROPRIETARIO, $type);			//30
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->COD_FISCALE_PROPRIETARIO, $type);		//31
            
            $data_nascita_proprietario = ($domanda->DATA_NASCITA_PROPRIETARIO) ? date("d/m/Y",strtotime($domanda->DATA_NASCITA_PROPRIETARIO)) : '';
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($data_nascita_proprietario, $type);				//32
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->ESTREMI_CATASTALI_FOGLIO, $type);		//33
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->ESTREMI_CATASTALI_PARTICELLA, $type);	//34
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->ESTREMI_CATASTALI_SUBALTERNO, $type);	//35
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->NUMERO_VANI, $type);						//36
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->CATEGORIA_CATASTALE, $type);				//37
            $stato_alloggio = str_replace("-", "", reset($CI->Domanda->get_collegato('stato_conservazione',$domanda->STATO_CONSERVAZIONE_ALLOGGIO)));
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($stato_alloggio, $type);							//38
            $stato_fabbricato = str_replace("-", "", reset($CI->Domanda->get_collegato('stato_conservazione',$domanda->STATO_CONSERVAZIONE_FABBRICATO)));
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($stato_fabbricato, $type);							//39
            $erps = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$domanda->ASSEGNATARIO_ERPS)));
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($erps, $type);										//40
           if ($tipo_domanda ==1) {
              $minorenni = $CI->Nucleo->checkMinorenni($domanda->ID_DOMANDA);
      			  $minorenni = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$minorenni)));
      			  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($minorenni, $type);											//41
      			  $over70 = $CI->Nucleo->checkOver70($domanda->ID_DOMANDA);
      			  $over70 = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$over70)));
      			  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($over70, $type);												//42
      			  $invalidita = $CI->Domanda->checkInvalidita($domanda->ID_DOMANDA);				
      			  $invalidita = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$invalidita)));
      			  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($invalidita, $type);											//43
      			  $servizi_sociali = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$domanda->SERVIZI_SOCIALI)));
              $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($servizi_sociali, $type);									//44
              $rinuncia_esecuzione = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$domanda->RINUNCIA_ESECUZIONE)));
              $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($rinuncia_esecuzione, $type);								//45
              $differimento = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$domanda->DIFFERIMENTO)));
              $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($differimento, $type);										//46
              $durata_differimento = str_replace("-", "", reset($CI->Domanda->get_collegato('tipo_contratto',$domanda->TIPO_CONTRATTO)));
              $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($durata_differimento, $type);								//47
              $deposito_cauzionale = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$domanda->DEPOSITO_CAUZIONALE)));
              $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($deposito_cauzionale, $type);								//48
              $nuovo_contratto_agenzia = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$domanda->NUOVO_CONTRATTO_AGENZIA)));
              $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($nuovo_contratto_agenzia, $type);							//49
              $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->CONTRIBUTO_AMMESSO_COPERTURA, $type_num);			//50
              $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->CONTRIBUTO_AMMESSO_CAUZIONALE, $type_num);			//51
              $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->TOTALE_CONTRIBUTO, $type_num);						//52
           } else {
           	  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->CONTRIBUTO_INQUILINO_AMMESSO, $type_num);			//41
		   	  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->CONTRIBUTO_PROPRIETARIO_AMMESSO, $type_num);		//42
		   	  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->AMMONTARE_FONDO_GARANZIA_PROPRIETARIO, $type_num);	//43
		   	  
		   	  $scadenza_fondo_garanzia = ($domanda->SCADENZA_FONDO_GARANZIA_PROPRIETARIO) ? date("d/m/Y",strtotime($domanda->SCADENZA_FONDO_GARANZIA_PROPRIETARIO)) : '';
		   	  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($scadenza_fondo_garanzia, $type);							//44	
		   	  
		   	  $domanda_collegata_fondo = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$domanda->DOMANDA_COLLEGATA_FONDO)));
		   	  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda_collegata_fondo, $type);							//45
           }
           
          $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->DESCRIZIONE_STATO_DOMANDA, $type);							//46
          $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y)->setValueExplicit($domanda->DESCRIZIONE_COMUNE_INSERITORE, $type);							//47
           // 
               // DESCRIZIONE_COMUNE_INSERITORE
       
            $i[$tipo_domanda] = $i[$tipo_domanda] + 1; 
       }
       
    for ($j=1; $j < $objPHPExcel->getSheetCount(); $j++){
	    
	    $x = 0;
	   
  	   if(!$filtro){
  		   $tipo_domanda = $j;
  		   $objPHPExcel->setActiveSheetIndex($tipo_domanda - 1);
  	   }
	   
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$y_offset-4)->setValueExplicit('Bandi dell\'edilizia sociale', $type);    
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$y_offset-4)->setValueExplicit('Report generato il: ' .date("d/m/Y H:i:s",time()), $type);
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$y_offset-2)->setValueExplicit($user->comune_utente->DESCRIZIONE, $type);       
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($x,$y_offset-1)->setValueExplicit('Id', $type);
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Data domanda', $type);
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Nome', $type); 
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Cognome', $type);   
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Codice fiscale', $type); 
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Data di nascita', $type);
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Cittadinanza', $type);
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Titolo di studio', $type);
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Reddito imponibile', $type);
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Reddito equivalente', $type);
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Data rilascio ISEE', $type);
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Aiuti economici non presenti nell\'ISEE', $type);
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Titolo di soggiorno valido', $type);       
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Sfratto', $type);
	   if ($tipo_domanda ==1) {
		   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Data sfratto', $type);								//14 (1)
	   } else {
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Motivazione sfratto', $type);   						//14 (2)
	   }
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Residenza', $type); 
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Comune Residenza', $type);   
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('ISTAT Residenza', $type); 
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Nuova residenza', $type);
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Comune Nuova Residenza', $type); 
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('ISTAT Nuova Residenza', $type); 
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Tipo godimento abitazione', $type);
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Tipo contratto in atto', $type);
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Durata contratto in atto', $type);
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Data contratto in atto', $type);
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Canone annuo contratto in atto', $type);
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Durata nuovo contratto', $type);
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Data nuovo contratto', $type);
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Canone annuo nuovo contratto', $type);
	   $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Nome proprietario', $type); 
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Cognome proprietario', $type);   
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Codice fiscale proprietario', $type); 
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Data di nascita proprietario', $type); 
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Foglio', $type); 
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Particella', $type); 
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Subalterno', $type); 
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Vani catastali', $type); 
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Categoria catastale', $type); 
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Stato conservazione alloggio', $type); 
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Stato conservazione fabbricato', $type);
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Assegnatario ERPS', $type);								//40
       if ($tipo_domanda ==1) {         
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Presenza di minorenni nel nucleo famigliare', $type);	//41 (1)
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Presenza di ultra 70enni nel nucleo famigliare', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Presenza di invalidi nel nucleo famigliare', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Servizi sociali', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Rinuncia esecuzione sfratto', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Differimento sfratto', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Durata differimento', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Deposito cauzionale', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Nuovo contratto stipulato tramite Agenzia sociale locazione', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Contributo copertura morosità', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Contributo deposito cauzionale', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Totale contributo ammesso', $type);
       } else {
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Contributo inquilino ammesso', $type);					//41 (2)
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Contributo proprietario ammesso', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Fondo garanzia proprietario', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Scadenza fondo garanzia proprietario', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Domanda collegata al fondo morosità incolpevole', $type);
       }
       
       $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Stato domanda', $type);
         $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(++$x,$y_offset-1)->setValueExplicit('Comune inseritore', $type);
         
       
    }
    
    	$objPHPExcel->setActiveSheetIndex(0);
       
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        
        $nome_file = 'report.xls';
        
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$nome_file.'"');

        // Write file to the browser
		  $objWriter->save('php://output');
       
        exit;
}
      
function createExcelSintesi($domande,$user,$bozze='') {
	require_once(APPPATH.'libraries/PHPExcel.php');
	//require_once(APPPATH.'libraries/tcpdf/tcpdf.php');
	
	$CI =&get_instance();      
	
	$CI->load->model('Nucleo');
	$CI->load->model('Domanda');
	    
	/** Create a new PHPExcel Object **/ 
	$objPHPExcel = new PHPExcel();
	
	
	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory; 
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod);      
	$validLocale = PHPExcel_Settings::setLocale('it_it'); 
	if (!$validLocale) { echo 'Unable to set locale to '.$locale." - reverting to en_us<br />\n"; }
	$x = 1;
	$y_offset = 5;
	$objPHPExcel->createSheet();
       
    $nCols = 9; //set the number of columns
    
    foreach (range(0, $nCols) as $col) {   
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(15);             
    }

	$objPHPExcel->getActiveSheet()->setTitle('Domande');
	
	$type = PHPExcel_Cell_DataType::TYPE_STRING;
	$type_num = PHPExcel_Cell_DataType::TYPE_NUMERIC;

	$i=0;
	                       
	foreach ($domande as &$domanda)  {
	    $y = $i+$y_offset;
	       //print_r($domanda);
	    $tipo_domanda=$domanda->TIPO_DOMANDA;
	    $minorenni = $CI->Nucleo->checkMinorenni($domanda->ID_DOMANDA);            
	    $minorenni = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$minorenni)));
	    
	    $over70 = $CI->Nucleo->checkOver70($domanda->ID_DOMANDA);
	    $over70 = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$over70)));
	    $invalidita = $CI->Domanda->checkInvalidita($domanda->ID_DOMANDA);
	    $invalidita = str_replace("-", "", reset($CI->Domanda->get_collegato('sino',$invalidita))) ;
	 
	    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$y_offset-2)->setValueExplicit($domanda->DESCRIZIONE_TIPO_DOMANDA, $type);
	    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$y)->setValueExplicit($domanda->ID_DOMANDA, $type);
	    
	    $data_domanda = ($domanda->DATA_DOMANDA) ? date("d/m/Y",strtotime($domanda->DATA_DOMANDA)) : '';
	    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$y)->setValueExplicit($data_domanda, $type);
	    
	    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$y)->setValueExplicit($domanda->NOME, $type);
	    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$y)->setValueExplicit($domanda->COGNOME, $type);
	    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$y)->setValueExplicit($domanda->CODICE_FISCALE, $type);
	    $indirizzo = $domanda->RESID_INDIRIZZO.' '.$domanda->RESID_CIVICO.' Cap: '.$domanda->RESID_CAP. ', '.$domanda->DESCR_COMUNE;              
	    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5,$y)->setValueExplicit($indirizzo, $type);
		
		if ($tipo_domanda ==1) {
	    	if (!$bozze) {
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6,$y)->setValueExplicit($domanda->TOTALE_CONTRIBUTO, $type_num);
	    	}
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7,$y)->setValueExplicit($minorenni, $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8,$y)->setValueExplicit($over70, $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9,$y)->setValueExplicit($invalidita, $type);
		} else {
	        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6,$y)->setValueExplicit($domanda->CONTRIBUTO_INQUILINO_AMMESSO, $type_num);
	        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7,$y)->setValueExplicit($domanda->CONTRIBUTO_PROPRIETARIO_AMMESSO, $type_num);
	        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8,$y)->setValueExplicit($domanda->AMMONTARE_FONDO_GARANZIA_PROPRIETARIO, $type_num);
		}
		$data_nuovo_contratto = ($domanda->DATA_NUOVO_CONTRATTO) ? date("d/m/Y",strtotime($domanda->DATA_NUOVO_CONTRATTO)) : '';
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10,$y)->setValueExplicit($data_nuovo_contratto, $type);
	    $i++; 
	}
       
	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$y_offset-4)->setValueExplicit('Bandi dell\'edilizia sociale', $type);    
	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$y_offset-4)->setValueExplicit('Report generato il: ' .date("d/m/Y H:i:s",time()), $type);
	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$y_offset-2)->setValueExplicit($user->comune_utente->DESCRIZIONE, $type);       
	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$y_offset-1)->setValueExplicit('ID_DOMANDA', $type);
	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$y_offset-1)->setValueExplicit('Data in cui il cittadino ha fatto la domanda ', $type);
	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$y_offset-1)->setValueExplicit('NOME', $type); 
	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$y_offset-1)->setValueExplicit('COGNOME', $type);   
	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$y_offset-1)->setValueExplicit('Codice fiscale', $type);  
	$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5,$y_offset-1)->setValueExplicit('Indirizzo', $type);
      
    if ($tipo_domanda ==1) {
    	if (!$bozze) {
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6,$y_offset-1)->setValueExplicit('Totale contributo ammesso', $type);
        }
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7,$y_offset-1)->setValueExplicit('Presenza di minorenni nel nucleo famigliare', $type);
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8,$y_offset-1)->setValueExplicit('Presenza di ultra 70enni nel nucleo famigliare', $type);
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9,$y_offset-1)->setValueExplicit('Presenza di invalidi nel nucleo famigliare', $type);
    } else {
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6,$y_offset-1)->setValueExplicit('Contributo inquilino ammesso', $type);
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7,$y_offset-1)->setValueExplicit('Contributo proprietario ammesso', $type);
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8,$y_offset-1)->setValueExplicit('Fondo garanzia proprietario', $type);         
    }
       
    if (!$bozze) {
        $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10,$y_offset-1)->setValueExplicit('Data nuovo contratto', $type);
    }
    

  	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          
  	$nome_file = 'elenco.xls';
          
    // We'll be outputting an excel file
    header('Content-type: application/vnd.ms-excel');

    header('Content-Disposition: attachment; filename="'.$nome_file.'"');

    // Write file to the browser
    $objWriter->save('php://output');
       
    exit;
  
}