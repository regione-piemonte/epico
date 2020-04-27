/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/


$(document).ready(function(){

    $('#reddito_imponibile').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#reddito_equivalente').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#aiuti_economici').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#ammontare_atto').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#ammontare_morosita').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#ammontare_nuovo_contratto').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});

    $('#ammontare_fondo_garanzia_proprietario').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#contributo_proprietario_ammesso').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#contributo_inquilino_ammesso').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#cofinanziamento').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});

    $('#contributo_ammesso_copertura').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#contributo_ammesso_cauzionale').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#contributo_ammesso_differimento').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#contributo_ammesso_nuovo_contratto').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});
    $('#totale_contributo').autoNumeric({aSign: ' €', pSign: 's', aSep: '.', aDec: ','});

    $('#CAP').autoNumeric({aSep: '', aDec: null, aPad: false, lZero: 'keep'});
    $('#CAP_n').autoNumeric({aSep: '', aDec: null, aPad: false, lZero: 'keep'});
    $('#numero_vani').autoNumeric({aSep: '', aDec: ',', aPad: false});
    $('#numero_protocollo').autoNumeric({aSep: '', aDec: null, aPad: false});
    $('#numero_liquidazione').autoNumeric({aSep: '', aDec: null, aPad: false});

    $("#categoria_catastale").blur(function(){
    	var cc = $("#categoria_catastale").val();
		if (cc == 'A1' || cc == 'A8' || cc =='A9') {
            alert('Le categorie A1, A8 e A9 non sono ammesse');
            $("#categoria_catastale").focus();
        }
	});
    	
    $("#rinuncia_esecuzione_si").click(function(){
        $("#differimento_si").prop("disabled",true);
        $("#differimento_no").prop("disabled",true);
        $("#tipo_contratto").prop("disabled",true); // ATTENZIONE! tipo_contratto = durata del differimento
        /*$("#data_nuovo_contratto").prop("disabled",false);
        $("#durata_nuovo_contratto").prop("disabled",false);
        $("#ammontare_nuovo_contratto").prop("disabled",false);
        $("#nuovo_contratto_agenzia_si").prop("disabled",false);
        $("#nuovo_contratto_agenzia_no").prop("disabled",false);*/
        
        $("#deposito_cauzionale_no").prop("disabled",false);
        $("#deposito_cauzionale_si").prop("disabled",false);
        $("#tipo_contratto").val("");
        $("input[name='differimento']").attr('checked', false);
    });
        
    $("#rinuncia_esecuzione_no").click(function(){
		$("#differimento_si").prop("disabled",false);
		$("#differimento_no").prop("disabled",false);
		$("#tipo_contratto").prop("disabled",false);
		$("#data_nuovo_contratto").val("");
		 
		/*$("#deposito_cauzionale_no").prop("disabled",true);
		$("#deposito_cauzionale_si").prop("disabled",true);
		$("#data_nuovo_contratto").prop("disabled",true);
		$("#durata_nuovo_contratto").prop("disabled",true);
		$("#ammontare_nuovo_contratto").prop("disabled",true);
		$("#nuovo_contratto_agenzia_si").prop("disabled",true);
		$("#nuovo_contratto_agenzia_no").prop("disabled",true);*/
		 
		$("input[name='deposito_cauzionale']").attr('checked', false);
		                               
		$('#durata_nuovo_contratto').find('option:first').attr('selected', 'selected');
		$('input[name="nuovo_contratto_agenzia"]').prop('checked', false);
		$("#ammontare_nuovo_contratto").val("");
    });

	$('#differimento_si').click(function () {
	    var differimentoSI = $('input[name=differimento]:checked').val();
	    var durata = $('#tipo_contratto').val(); 
	    if (differimentoSI === '1' && !durata) {
	    	alert('Se hai selezionato SI in "differimento dell\'esecuzione dello sfratto"  devi specificare la durata del differimento.');
	    }
	});
	
    // ripulisco il form al clik del reset
    $("button[type=reset]").click(function(e) {
	    e.preventDefault();
    	var $form = $(this).closest('form');
    	$(":input", $form).not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected').not(':checkbox, :radio, select').val('');
    	return false;
    });

    // stampo la bozza
    $("#btnstampa").click(function(){
		window.print();
		return false;
    });

    function getcomuni(id_provincia, nameofselect) {
    	$('#'+nameofselect).empty();
		$.getJSON( "/servizi/epico/index.php/domande/getcomuni/"+id_provincia, function(data) {
	    	$.each(data,function(id,city) {
	        	var opt = $('<option />'); // here we're creating a new select option with for each city
				opt.val(id);
				opt.text(city);
				$('#'+nameofselect).append(opt); //here we will append these new select options to a dropdown with the id 'cities'
	        });
    	});
    }
    $('#provincia_residenza').change(function() {
		id_provincia = $(this).val();
		getcomuni(id_provincia,'comune_residenza');
    });
    $('#provincia_nuova_residenza').change(function() {
    	id_provincia = $(this).val();
		getcomuni(id_provincia,'nuovo_comune_residenza');
    });

    $('#form_domanda').submit(function(e){
	    // evita submit premendo invio
	    return($(document.activeElement).attr('id') == 'btnsalvabozza' || $(document.activeElement).attr('id') == 'valida');
    });

    $("#btnsalvabozza").click(function(){
        $('#form_domanda').validationEngine('hideAll');
        $('#form_domanda').validationEngine('detach');
        //alert("Sebbene i campi siano tutti obbligatori il salvataggio in bozza è consentito ugualmente");
        return true;
    });

	//validazione attiva/disattiva
    $("#valida").click(function () {
        $('#form_domanda').validationEngine('hideAll');
        $('#form_domanda').validationEngine('detach');
        $('#form_domanda').validationEngine('attach');
        if($("#form_domanda").validationEngine('validate')) {
            var status = confirm("La validazione è un'operazione non reversibile! Vuoi continuare?");
            return status;
        } else return false;
    });
    
    // form assegnazione
    $('#form_assegnazione').validationEngine('attach');
    
    $("#sceltacomune").click(function () {
        $('#form_domanda').validationEngine('hideAll');
        $('#form_domanda').validationEngine('detach');
        $('#form_domanda').validationEngine('attach');
        if($("#form_domanda").validationEngine('validate')) {
        } else return false;
    });
    
    // alert sulla eliminazione della bozza
     $("#elimina_bozza").click(function () {
        var status = confirm("Stai per eliminare la bozza. Vuoi continuare?");
        return status;
     });
     
     // somma dei contributi
    $('.contributi').keyup(function() {
	    var totale = 0;
		var max = Number($('#totale_contributo').data('v-max'));
	    $('.contributi').each(function() {
        	totale += Number($(this).autoNumeric('get'));
    	});
    	if (totale > max) {
	    	totale = '';
	    	alert('Il totale contributo ammesso non può essere superiore a ' + max + ' €');
	    } $('#totale_contributo').autoNumeric('set', totale);
    });
    
    // gestione codice fiscale
    var dataNascita;
    var dataCF;

    $('#codicefiscale').focusout(function() {  
    	var cf = $(this).val();
		if (isNaN(cf)) {
        	var validCF = isValidCF(cf);
			if (!validCF && !!cf) {
				alert('Il codice fiscale non è valido');
				$(this).focus();
        	} else compareAge();
        } else {
			alert('Il codice fiscale non è valido');
			$(this).focus();
			/*var checkPiva = CtrPartIVA(cf);
			if (!checkPiva) {
			  alert('La partita IVA non è valida. Deve essere un numero di 11 cifre');
			}*/
        }             
    });
    
    // il proprietario può avere una p.iva
    $('#codicefiscale_proprietario').focusout(function() {  
	    var cf = $(this).val();
	    if (isNaN(cf)) {
	    	var validCF = isValidCF(cf);
	        if (!validCF && !!cf) {
	        	alert('Il codice fiscale del proprietario  non è valido');
	            $(this).focus();
	        } else compareAge();
        } else {
			var checkPiva = CtrPartIVA(cf);
			if (!checkPiva) {
			  alert('La partita IVA del proprietario non è valida. Deve essere un numero di 11 cifre');
			}
        }       
    });
      
    // gestone aggiungi righe nucleo
    var counter = 1;
    $( "#button_nucleo" ).on('click',function() {
    	new_elem = $("#record_nucleo").clone().appendTo("#table_nucleo tbody").show().attr("id", "addr" + counter);

        var inp = new_elem.find("input[type='text']");
        $.each(inp, function() {
        	$(this).val('');
        });

        counter += 1;
        $('#datepicker.input-daterange').datepicker({
            format: "dd/mm/yyyy",
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'it'
        });
    });

    // gestione rimozione del record di nucleo
    $(".remove_record_nucleo").live('click', function(event) {
        var rowCount = $('#table_nucleo tr').length;
        //console.log(rowCount);
        if  (rowCount > 2) {
           $(this).closest('tr').remove();
        }
    });
    //
    $("#export_domande_sintesi").live('click', function(event) {
        var data_da_report = $("#data_da_report").val();
        var data_a_report = $("#data_a_report").val();

        if (data_da_report && data_a_report) {
            //var diff = DateDiff(data_da_report,data_a_report);
            //console.log(diff);
        }
    });

}); // END document.ready()

/*
function sumContributo() {
	var copertura = $('#contributo_ammesso_copertura').val();
	if (!copertura) {
		copertura = 0;
	} else {
		copertura = (typeof copertura === 'undefined') ? 0 : copertura;
		copertura = copertura.replace(/[$.]+/g,"");
		copertura = copertura.replace(/[$,]+/g,".");
		copertura = parseFloat(copertura);
	}
	
	var cauzionale = $('#contributo_ammesso_cauzionale').val();
	if (!cauzionale) {
	  cauzionale = 0;
	} else {
		cauzionale = (typeof cauzionale === 'undefined') ? 0 : cauzionale;
		cauzionale = cauzionale.replace(/[$.]+/g,"");
		cauzionale = cauzionale.replace(/[$,]+/g,".");
		cauzionale = parseFloat(cauzionale);
	}
	// controllo che la somma dei contributi non superi una certa soglia
	var totale = copertura + cauzionale;
	if (totale > 8000) {
		alert('Il totale contributo ammesso non può essere superiore ad 8000');
		totale = 0;
	}  else {
		totale = totale.toFixed(2).toString().replace(/[$.]+/g,",");
	}
	
	return totale;
}
*/

function compareAge() {
	var cf = $('#codicefiscale').val();
	dataCF = getDataByCF(cf);
	var dNascitaCF = Date.parse(dataCF);
	var datanascita =  $('#datanascita').val();
	datanascita = Date.parse(datanascita);
	
	//console.log(datanascita);
	//console.log(dNascitaCF);
	if (!isNaN(datanascita) && !isNaN(dNascitaCF) && datanascita !='' && dNascitaCF !='') {
	  if (datanascita != dNascitaCF) {
	  alert("La data di nascita non è coerente rispetto a quella del codice fiscale");
	  }
	}
}

function getDataByCF(cf) {

	var tabellamesi = {
		"A" : "01",
		"B" : "02",
		"C" : "03",
		"D" : "04",
		"E" : "05",
		"H" : "06",
		"L" : "07",
		"M" : "08",
		"P" : "09",
		"R" : "10",
		"S" : "11",
		"T" : "12"
	};
	
	return cf.replace(/^(?:\w{6})(\d{2})(\w)(\d{2}).+$/, function(data, aa, mm, gg) {

		var anno = parseInt(aa, 10);
		var secolo = (anno < 9)? '20':'19';
		anno = [secolo, aa].join('');
		
		var giorno = parseInt(gg, 10);
		var sesso = (giorno > 31)? 'F' : 'M';
		if (sesso === 'F') giorno -= 40;
		
		var mese = mm.toUpperCase();
		mese = tabellamesi[mese];
		
		return [giorno, mese, anno].join("/");
	});
};

function etaFromCf(cf) {
	var codicefiscale = cf;
	var tabellamesi = {
		"A" : "01",
		"B" : "02",
		"C" : "03",
		"D" : "04",
		"E" : "05",
		"H" : "06",
		"L" : "07",
		"M" : "08",
		"P" : "09",
		"R" : "10",
		"S" : "11",
		"T" : "12"
	};

    var datanascita = codicefiscale.replace(/^(?:\w{6})(\d{2})(\w)(\d{2}).+$/, function(data, aa, mm, gg) {
	    var anno = parseInt(aa, 10);
	    var secolo = (anno < 9)? '20':'19';
	    anno = [secolo, aa].join('');
	
	    var giorno = parseInt(gg, 10);
	    if (giorno > 71 || ((31 < giorno) && (giorno < 41))) return 'giorno di nascita errato.';
	
	    var sesso = (giorno > 31)? 'F' : 'M';
	    if (sesso === 'F') giorno -= 40;
	
	    var mese = mm.toUpperCase();
	    if (!tabellamesi[mese]) return 'mese di nascita errato';
	    mese = tabellamesi[mese];
	    var datacf = new Date(parseInt(anno), parseInt(mese)-1, parseInt(giorno));
	
	    return datacf;
	});
    return datanascita;
}

function isValidCF(codicefiscale) {
	var cf;
	var reCF = /^[a-z]{6}(\d{2})(A|B|C|D|E|H|L|M|P|R|S|T)([1256]\d|[04][1-9]|[37][01])[a-z]\d{3}[a-z]$/i;
	
	if (!(cf = codicefiscale.toUpperCase().match(reCF))) return false;
	else {
	
		var isLeapYear = function(year) {
			/* is a leap year ? */
			var year = parseInt(year, 10);
			return (!(year % 100))? (!(year % 400)) : (!(year % 4));
		};
		
		/* controllo tutti i mesi 31 giorni */
		if (/(31|71)/.test(cf[3]) && !(/[ACELMRT]/).test(cf[2])) return false;
		
		/* controllo febbraio non superiore 29 giorni */
		if (/([37][01])/.test(cf[3]) && cf[2] === 'B') return false;
		
		/* controllo anni bisestili */
		cf[1] = parseInt(cf[1], 10);
		cf[1] = [(((new Date).getFullYear() % 100) < cf[1])? '19':'20', cf[1]].join('');
		if (/([26]9)/.test(cf[3]) && cf[2] === 'B' && !isLeapYear(cf[1])) return false;
	};
	
	return true;
}

function CtrPartIVA(PIVA) {
    if (PIVA.length!=11) { 
        return false;
    } else {
        for (i=0;i<PIVA.length;i++) {
            if ((PIVA.charAt(i)<"0")||(PIVA.charAt(i)>"9"))
                return false;
        } 
    }
    return true; 
}

function calculateAge(birthDate, otherDate) {

     birthDate=parseDate(birthDate);
     otherDate=parseDate(otherDate);

    birthDate = new Date(birthDate);
    otherDate = new Date(otherDate);

    var years = (otherDate.getFullYear() - birthDate.getFullYear());

    if (otherDate.getMonth() < birthDate.getMonth() ||
        otherDate.getMonth() == birthDate.getMonth() && otherDate.getDate() < birthDate.getDate()) {
        years--;
    }

    return years;
}

function  parseDate(s)  {
	var re = /^(\d\d)\/(\d\d)\/(\d{4})$/;
	var m = re.exec(s);
	return m ? new Date(m[3], m[2]-1, m[1]) : null;
};

var DateDiff = {

    inDays: function(d1, d2) {
        var t2 = d2.getTime();
        var t1 = d1.getTime();

        return parseInt((t2-t1)/(24*3600*1000));
    },

    inWeeks: function(d1, d2) {
        var t2 = d2.getTime();
        var t1 = d1.getTime();

        return parseInt((t2-t1)/(24*3600*1000*7));
    },

    inMonths: function(d1, d2) {
        var d1Y = d1.getFullYear();
        var d2Y = d2.getFullYear();
        var d1M = d1.getMonth();
        var d2M = d2.getMonth();

        return (d2M+12*d2Y)-(d1M+12*d1Y);
    },

    inYears: function(d1, d2) {
        return d2.getFullYear()-d1.getFullYear();
    }
}
