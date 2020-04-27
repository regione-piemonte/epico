/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/


$(document).ready(function(){
						   		
$('#datepicker, .calendar-control').append( '&nbsp;<span class="icon-calendar"></span>&nbsp;');
								
$("#js-option_1,#js-option_2").toggleClass('js-disabled js-enable');


$('#btnstampa').click(function() {
window.print();
return false;
});

	
/* COLLAPSE  */

var $span = $(".accordion h4 span");
$span.replaceWith(function () {	
							
	var id = $(this).parent().attr("class");
							
    return $('<a/>', {
        href: id,		
		class: 'attrCollapse',
        html: this.innerHTML
		
    });
	
});

$('.attrCollapse').attr('data-toggle', 'collapse');
$('.attrCollapse').attr('data-parent', '.accordion');
$('.panel-collapse').addClass('collapse');
$('.attrCollapse').append( '&nbsp;<span class="icon-chevron-down"></span>&nbsp;');

$("[data-toggle='collapse']").click(function(e) {
var $this = $(this);
var $icon = $this.find("span[class^='icon-chevron']");
 
	if ($icon.hasClass('icon-chevron-down')) {
	$icon.removeClass('icon-chevron-down').addClass('icon-chevron-up');
	} else {
	$icon.removeClass('icon-chevron-up').addClass('icon-chevron-down');
	}
	
});


/*FINE ----  COLLAPSE ----  */	
	

//	$(".calendarioCustom").calendario({changeYear: true });//,yearRange: "1900:+0"		   

	$("<div id='dialog'></div>").dialog({
		autoOpen: false,					  
		width: 650,
		height: 600,
		modal: false,
		bgiframe: true,
		resizable: true,
		draggable: true,
		chiudi :true
	});	

	$.fn.openDialogHelp = function() {
		var title = $(this).attr("title");	
		page_url = $(this).attr("href");
		$(".ui-dialog span.ui-dialog-title").html(title);							   
		$('.ui-dialog-content').load(page_url + ' #dialog_target[role="main"]');	  							   
		$( "#dialog" ).dialog( "open" );
		return false;
		};
	$( ".dialog-help" ).click($.fn.openDialogHelp); 


	$( '.popover-text ul li' ).prepend( '<span class="icon-chevron-right"></span>&nbsp;');
	$('.popover-help').each(function() {
	 var $this = $(this);	
		$this.popover({
		trigger: 'hover',
		placement: 'top',
		container: $this,
		html: true,
		content: function() {
			return $this.parents('.control-label').find('.popover-text').html();
		}
		});    
	});
	$(".popover-control").css("display","inline-block"); 
	

	$("a.esterno",this).apri({toolbar : "yes", location : "yes", directories : "yes", status : "yes", menuBar : "yes", scrollbars : "yes", resizable : "yes", width : 800, height : 600, top : 0, left : 0});	

	$( "dt.utente" ).prepend( '<i class="icon-user"></i>' );
	$( ".panel-user .btn-default" ).append( '<i class="icon-signout"></i>' );
	$( ".btn-print" ).prepend( '<span class="icon-print"></span>&nbsp;');
	$(".popover-error, .popover-text").hide();
	
	$('.popover-error ul li').each(function() {
	var $this = $(this);
	var txt='<span class="icon-exclamation"></span>';
	$this.prepend( '<span class="icon-exclamation"></span>' );	
	});
	

	/*  TOGLIERE DA QUI
	$(".checkbox.error label em").css("color","#B94A48"); 
	$(".error.radio-group .radio label").css("color","#B94A48"); 
	
	$('.error .form-control, .error .custom-combobox-input').each(function() {
																		  															   
	 var $this = $(this);
	 
		$this.popover({
		  trigger: 'hover, focus',
		  placement: 'top',
		  html: true,
		  content: $this.parents('.error').find('.popover-error').html()
		});	

	});
	
	$('.error.checkbox label, .error.radio-group label').each(function() {
																		  															   
	 var $this = $(this);
	 
		$this.popover({
		  trigger: 'hover',
		  placement: 'top',
		  html: true,
		  content: $this.parents('.error').find('.popover-error').html()
		});	

	});
	
	$( '<span class="icon-exclamation form-control-feedback"></span>' ).insertAfter( '.error input.form-control' );
	
	$( '<span class="icon-exclamation form-control-feedback select-control"></span>' ).insertAfter( '.error select.form-control, .error .custom-combobox-input' );
	$( ".calendarioCustom" ).closest( '.error' ).addClass('calendar-control');
	
	$( ".custom-combobox-toggle" ) .attr( "title", "" )
		return false;
	
	 */
			  
			  
}); 