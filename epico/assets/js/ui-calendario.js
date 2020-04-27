/*
 * Copyright (c) 2009 Massimiliano Balestrieri
 * 
 * $Date: 2010-02-26 14:37:16 +0100 (ven, 26 feb 2010) $
 * $Rev: 314 $
 * @requires jQuery v1.3.2
 * 
 * Copyright (c) 2008 Massimiliano Balestrieri
 * Examples and docs at: http://maxb.net/blog/
 * Licensed GPL licenses:
 * http://www.gnu.org/licenses/gpl.html
 */

(function($){
////////////////////////////////////////////////////////////////////////////////
//*********** DATEPICKER IT ****************************************************
//Italian initialisation for the $ UI date picker plugin.
//Written by Apaella (apaella@gmail.com).
////////////////////////////////////////////////////////////////////////////////
$(function($){
    $.datepicker.regional['it'] = {
        clearText: 'Svuota', clearStatus: 'Annulla',
        closeText: 'Chiudi', closeStatus: 'Chiudere senza modificare',
        prevText: '&#x3c;Prec', prevStatus: 'Mese precedente',
        prevBigText: '&#x3c;&#x3c;', prevBigStatus: 'Mostra l\'anno precedente',
        nextText: 'Succ&#x3e;', nextStatus: 'Mese successivo',
        nextBigText: '&#x3e;&#x3e;', nextBigStatus: 'Mostra l\'anno successivo',
        currentText: 'Oggi', currentStatus: 'Mese corrente',
        monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',
        'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
        monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu',
        'Lug','Ago','Set','Ott','Nov','Dic'],
        monthStatus: 'Seleziona un altro mese', yearStatus: 'Seleziona un altro anno',
        weekHeader: 'Sm', weekStatus: 'Settimana dell\'anno',
        dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'],
        dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'],
        dayNamesMin: ['Do','Lu','Ma','Me','Gio','Ve','Sa'],
        dayStatus: 'Usa DD come primo giorno della settimana', dateStatus: 'Seleziona D, M d',
        dateFormat: 'dd/mm/yy', firstDay: 1, 
        initStatus: 'Scegliere una data', isRTL: false};
    $.datepicker.setDefaults($.datepicker.regional['it']);
});

var MyCalendario = function(options){

	var _options = $.extend({},options);

	return this.each(function(){
		
		if(!this.disabled){
			
			var that = this;

			if($.metadata)
				_options = $.extend(_options,$(that).metadata());

			$(that).datepicker(
		        $.extend(
					_options,
					$.datepicker.regional["it"],
					{
		            	//showOn: "button", 
			            //buttonImage: '/ris/css/generaliV3/jquery/im/ico_calendario.gif',  
			            //buttonImageOnly: true
			        })
	    	);
			var _trigger = $('<span class="ui-datepicker-trigger" title="clicca sul calendario per selezionare la data">&nbsp;</span>').click(function(){
				$(that).datepicker("show");
			});
			$(that).after(_trigger);
				
		}else{
			$(this).after('<span class="ui-datepicker-trigger-disabled" title="calendario disabilitato">&nbsp;</span>');	
			//$(this).after('<img class="ui-datepicker-trigger" src="/ris/css/generaliV3/jquery/im/ico_calendario_disabled.gif" alt="..." title="..."/>');	
		}
	});
};
$.fn.calendario = MyCalendario;

var MyCalendarioDivisoInTre = function(){
	return this.each(function(){
		
		var that = this;
		var _hidden = $('.ui-datepicker-hidden', that);
		var _gg = $('.ui-datepicker-gg',that);
		var _mm = $('.ui-datepicker-mm',that);
		var _aa = $('.ui-datepicker-aa',that);
		var _aaaa = $('.ui-datepicker-aaaa',that);
		
		$('.ui-datepicker-gg, .ui-datepicker-mm, .ui-datepicker-aa, .ui-datepicker-aaaa',that).change(function(){
			var _data = _get_diviso_dateformat();
			_hidden.datepicker("setDate", _data);
			_data = _hidden.datepicker("getDate");
			_set_diviso_dateformat(_data);
		});
		
		function _get_diviso(){
			var _a = _aa.length > 0 ? _aa.val() : _aaaa.val();
			var _ret = _gg.val() + '/' + _mm.val() + '/' + _a;
			//console.log(_ret);
			return _ret;
		}
		function _get_diviso_dateformat(){
			var _ret = new Date();
			var _a = _aa.length > 0 ? _aa.val() : _aaaa.val();
			_a = _a.length == 2 ? (_a > 20 ? '19' + _a  : '20' + _a) : _a;
			_ret.setFullYear(_a);
			_ret.setMonth((_mm.val() - 1));
			_ret.setDate(_gg.val());
			return _ret;
		}
		function _set_diviso(dateText){
			var _sp = dateText.split("/");
			_gg.val(_sp[0]);
			_mm.val(_sp[1]);
			_aaaa.val(_sp[2]);
			_aa.val(_sp[2].substr(2,4));
		}	
		function _set_diviso_dateformat(dateformat){
			var _g = dateformat.getDate();
			var _m = dateformat.getMonth() + 1;
			if(_g.toString().length == 1) _g = '0' + _g;
			if(_m.toString().length == 1) _m = '0' + _m;
			_gg.val(_g);
			_mm.val(_m);
			_aaaa.val(dateformat.getFullYear());
			_aa.val((dateformat.getFullYear().toString().substr(2,4)));
		}		
		var _options = $.extend({
			onSelect : function(dateText, inst){
				_set_diviso(dateText);
				_hidden.css('z-index', '-100');
			}
		},$.datepicker.regional["it"]);
		
		_hidden.datepicker(_options);
		//_hidden.datepicker("setDate", _get_diviso_dateformat());
		
		var _trigger = $('.ui-datepicker-trigger', that).click(function(){
			_hidden.css('z-index', '1');
			_hidden.datepicker("show");
		});
	});
};
$.fn.calendario_diviso = MyCalendarioDivisoInTre;

$('link[media="javascript-screen"]').attr("media", "screen");
////////////////////////////////////////////////////////////////////////////////
//*********** DATEPICKER AUTOLOAD **********************************************
////////////////////////////////////////////////////////////////////////////////
$(document).bind("ready", function(ev){
    var _target = ev.target || document;
    $('div.calendario', _target).datepicker(
        $.extend({},$.datepicker.regional["it"],{
            defaultDate : 0
        })
     );
	 
	
	
	$(':input.calendario', _target).calendario();
	$('span.calendario_diviso', _target).calendario_diviso();	
});

})(jQuery);