<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
 * mithra62 - CT Admin
 *
 * @package		mithra62:Ct_admin
 * @author		Eric Lamb
 * @copyright	Copyright (c) 2011, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/ct-admin/
 * @version		1.3.2
 * @filesource 	./system/expressionengine/third_party/ct_admin/
 */
 
 /**
 * CT Admin - JS methods
 *
 * JavaScript Class
 *
 * @package 	mithra62:Ct_admin
 * @author		Eric Lamb
 * @filesource 	./system/expressionengine/third_party/ct_admin/libraries/ct_admin_js.php
 */
class Flag_master_js
{
	
	public function __construct()
	{
		$this->EE =& get_instance();
	}
	
	public function get_accordian_css()
	{	
		return ' $("#my_accordion").accordion({autoHeight: false,header: "h3"}); ';
	}
	
	public function get_sortable()
	{
		return array(
			'
			var time = new Date().getTime();
			var fixHelper = function(e, ui) {
					ui.children().each(function() {
						$(this).width($(this).width());
					});
					return ui;
				};				
				
				$( "#flag_options tbody" ).sortable({
				placeholder: "ui-state-highlight",
				helper: fixHelper
				
			}).disableSelection();
				
			$( "#flag_options tbody" ).bind( "sortstop", function(event, ui) {
				var ids = $("#flag_options tbody").sortable("serialize");
				ids = ids+ "&" +$.param({ "XID": EE.XID});
				
				
				request = $.ajax({
				  url: EE.BASE+"&C=addons_modules&M=show_module_cp&module=flag_master&method=order_options&time=" + time,
				  type: "POST",
				  data: ids
				});			

				request.done(function(msg) {
				$("#flag_options table").tablesorter({ 
				    widgets: ["zebra"] 
				    }); 				  
				});
				
				request.fail(function(jqXHR, textStatus) {
				  console.debug(jqXHR);
				})				
			});

			'	
				
		);
		
	}
	
	public function get_check_toggle()
	{
		return array(
						'$(".toggle_all").toggle(
							function(){
								$("input.toggle").each(function() {
									this.checked = true;
								});
							}, function (){
								var checked_status = this.checked;
								$("input.toggle").each(function() {
									this.checked = false;
								});
							}
						);'
					);		
	}
	
	public function get_user_defined_dialogs($data)
	{
		$return = '';
		foreach($data AS $item)
		{
			$return .= '
					$( "#user_defined_'.$item['id'].'" ).dialog({
						autoOpen: false,
						show: "blind",
						modal:true,
						hide: "explode"
					});
			
					$( "#user_defined_'.$item['id'].'_opener" ).click(function() {
						$( "#user_defined_'.$item['id'].'" ).dialog( "open" );
						return false;
					});					
			';
		}
		
		return array($return);		
	}
	
	public function get_form_profile()
	{
		
	}
	
	public function get_orders_datatables($ajax_method, $cols, $piplength, $perpage, $extra = FALSE, $last_sort = FALSE)
	{
				
		$js = '
		var oCache = {
			iCacheLower: -1
		};
		
		function fnSetKey( aoData, sKey, mValue )
		{
			for ( var i=0, iLen=aoData.length ; i<iLen ; i++ )
			{
				if ( aoData[i].name == sKey )
				{
					aoData[i].value = mValue;
				}
			}
		}
		
		function fnGetKey( aoData, sKey )
		{
			for ( var i=0, iLen=aoData.length ; i<iLen ; i++ )
			{
				if ( aoData[i].name == sKey )
				{
					return aoData[i].value;
				}
			}
			return null;
		}
		
		function fnDataTablesPipeline ( sSource, aoData, fnCallback ) {
			var iPipe = '.$piplength.',
				bNeedServer = false,
				sEcho = fnGetKey(aoData, "sEcho"),
				iRequestStart = fnGetKey(aoData, "iDisplayStart"),
				iRequestLength = fnGetKey(aoData, "iDisplayLength"),
				iRequestEnd = iRequestStart + iRequestLength,
				k_search    = document.getElementById("order_keywords"),
				f_status       = document.getElementById("f_status"),
				date_range       = document.getElementById("date_range");
				f_perpage       = document.getElementById("f_perpage");
				total_range	= document.getElementById("total_range");
		
			function k_search_value() {
				if ($(k_search).data("order_data") == "n") {
					return "";
				}
				
				return k_search.value;
			}		
			aoData.push( 
				{ "name": "k_search", "value": k_search_value() },
				{ "name": "f_status", "value": f_status.value },
				{ "name": "date_range", "value": date_range.value },
				{ "name": "f_perpage", "value": f_perpage.value },
				{ "name": "total_range", "value": total_range.value }
			 );
			
			oCache.iDisplayStart = iRequestStart;
			
			/* outside pipeline? */
			if ( oCache.iCacheLower < 0 || iRequestStart < oCache.iCacheLower || iRequestEnd > oCache.iCacheUpper )
			{
				bNeedServer = true;
			}
			
			/* sorting etc changed? */
			if ( oCache.lastRequest && !bNeedServer )
			{
				for( var i=0, iLen=aoData.length ; i<iLen ; i++ )
				{
					if ( aoData[i].name != "iDisplayStart" && aoData[i].name != "iDisplayLength" && aoData[i].name != "sEcho" )
					{
						if ( aoData[i].value != oCache.lastRequest[i].value )
						{
							bNeedServer = true;
							break;
						}
					}
				}
			}
			
			/* Store the request for checking next time around */
			oCache.lastRequest = aoData.slice();
			
			if ( bNeedServer )
			{
				if ( iRequestStart < oCache.iCacheLower )
				{
					iRequestStart = iRequestStart - (iRequestLength*(iPipe-1));
					if ( iRequestStart < 0 )
					{
						iRequestStart = 0;
					}
				}
				
				oCache.iCacheLower = iRequestStart;
				oCache.iCacheUpper = iRequestStart + (iRequestLength * iPipe);
				oCache.iDisplayLength = fnGetKey( aoData, "iDisplayLength" );
				fnSetKey( aoData, "iDisplayStart", iRequestStart );
				fnSetKey( aoData, "iDisplayLength", iRequestLength*iPipe );
				
					aoData.push( 
						{ "name": "k_search", "value": k_search_value() },
						{ "name": "f_status", "value": f_status.value },
						{ "name": "date_range", "value": date_range.value },
						{ "name": "f_perpage", "value": f_perpage.value },
						{ "name": "total_range", "value": total_range.value }
					 );
		
				$.getJSON( sSource, aoData, function (json) { 
					/* Callback processing */
					oCache.lastJson = jQuery.extend(true, {}, json);
		 			
					if ( oCache.iCacheLower != oCache.iDisplayStart )
					{
						json.aaData.splice( 0, oCache.iDisplayStart-oCache.iCacheLower );
					}
					json.aaData.splice( oCache.iDisplayLength, json.aaData.length );
					
					
					fnCallback(json)
				} );
			}
			else
			{
				json = jQuery.extend(true, {}, oCache.lastJson);
				json.sEcho = sEcho; /* Update the echo for each response */
				json.aaData.splice( 0, iRequestStart-oCache.iCacheLower );
				json.aaData.splice( iRequestLength, json.aaData.length );
				fnCallback(json);
				return;
			}
		}
		var time = new Date().getTime();
	
		oTable = $(".mainTable").dataTable( {	
				"sPaginationType": "full_numbers",
				"bLengthChange": false,
				"bFilter": false,
				"sWrapper": false,
				"sInfo": false,
				"bAutoWidth": false,
				"iDisplayLength": '.$perpage.', 
				'.$extra.'
				
				"aoColumns": [null, null, null, null, null, { "bSortable" : false } ],
				
				
			"oLanguage": {
				"sZeroRecords": "'.lang('no_matching_orders').'",
				
				"oPaginate": {
					"sFirst": "<img src=\"'.$this->EE->cp->cp_theme_url.'images/pagination_first_button.gif\" width=\"13\" height=\"13\" alt=\"&lt; &lt;\" />",
					"sPrevious": "<img src=\"'.$this->EE->cp->cp_theme_url.'images/pagination_prev_button.gif\" width=\"13\" height=\"13\" alt=\"&lt; &lt;\" />",
					"sNext": "<img src=\"'.$this->EE->cp->cp_theme_url.'images/pagination_next_button.gif\" width=\"13\" height=\"13\" alt=\"&lt; &lt;\" />", 
					"sLast": "<img src=\"'.$this->EE->cp->cp_theme_url.'images/pagination_last_button.gif\" width=\"13\" height=\"13\" alt=\"&lt; &lt;\" />"
				}
			},
			
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": EE.BASE+"&C=addons_modules&M=show_module_cp&module=ct_admin&method='.$ajax_method.'&time=" + time,
				"fnServerData": fnDataTablesPipeline
		} );

		$("#order_keywords").bind("keydown blur paste", function (e) {
			/* Filter on the column (the index) of this element */
	    	setTimeout(function(){oTable.fnDraw();}, 1);
		});

		$("#export_submit").click(function() {
			var date_range = $("#date_range").val();
			var f_status = $("#f_status").val();
			var order_keywords = $("#order_keywords").val();
			var total_range = $("#total_range").val();
			var dataString = "date_range="+ date_range + "&f_status=" + f_status + "&order_keywords=" + order_keywords + "&total_range="+total_range;
			
			window.location.replace(EE.BASE+"&C=addons_modules&M=show_module_cp&module=ct_admin&method=export&"+dataString);	
			return false;
		});
		
		$("#order_form").submit(function() {
			oTable.fnDraw();
  			return false;
		});
		
		$("select#date_range").change(function () {
				
				if($(this).val() == "custom_date")
				{
					function date_range_dt(dateText)
					{

					}
					
					$("#custom_date_picker").slideDown();
					$("#custom_date_end_span").datepicker({
											   altField: "#custom_date_end",
											   altFormat: "yy-mm-dd",
											   dateFormat: "yy-mm-dd",
											   maxDate: new Date,
											   minDate: new Date($("#default_start_date").val() != "" ? $("#default_start_date").val() : ""),
											   onSelect: function(dateText, inst) {
													var start_date = $("#custom_date_start").val();
													var check = $("#custom_date_option").remove();
													$("#date_range").append(\'<option id="custom_date_option" selected="selected">\'+start_date+" to "+dateText+"</option>");
													oTable.fnDraw();
											   }
					});
					$("#custom_date_start_span").datepicker({
											   altField: "#custom_date_start",
											   altFormat: "yy-mm-dd",
											   dateFormat: "yy-mm-dd",
											   maxDate: new Date,
											   minDate: new Date($("#default_start_date").val() != "" ? $("#default_start_date").val() : ""), 
											   defaultDate: new Date($("#custom_date_start").val() != "yy-mm-dd" ? $("#default_start_date").val() : $("#custom_date_start").val()),
											   onSelect: function(dateText, inst) {
													var end_date = $("#custom_date_end").val();
													var check = $("#custom_date_option").remove();
													$("#date_range").append(\'<option id="custom_date_option" selected="selected">\'+dateText+" to "+end_date+"</option>");
													oTable.fnDraw();
											   }								   
					});	

				}
				else
				{
					$("#custom_date_picker").slideUp();
					oTable.fnDraw();
				}
		});		
	
		$("#f_perpage").change(function () {
				oTable.fnDraw();
			});
					
		$("select#f_status").change(function () {
				oTable.fnDraw();
			});

		$("#custom_date_picker").mouseleave(function() {
			$("#custom_date_picker").slideUp();
		});	

		$("#total_range_picker").mouseleave(function() {
			
			if($("#custom_total_range").val().trim() == "to")
			{
				$("#custom_total_range").remove();
			}
			
			oTable.fnDraw();
			$("#total_range_picker").slideUp();
		});			

		$("select#total_range").change(function () {
		
			
			if($(this).val() == "select_range")
			{	
						
				$("#total_range_picker").slideDown();
				var check = $("#custom_total_range").remove();
				var min_value = $("#filter_min_order").val();
				var max_value = $("#filter_max_order").val();

				$("#total_range").append(\'<option id="custom_total_range" selected="selected">\'+" to </option>");
				$( "#slider-range" ).slider({
					range: true,
					min: 0,
					max: max_value,
					values: [ 0, max_value-- ],
					slide: function( event, ui ) {
						$("#custom_total_range").remove();
						$( "#custom_total_range, #total_range_val" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
						$("#total_range").append(\'<option id="custom_total_range" selected="selected" value="\'+ ui.values[ 0 ] + \' - \' + ui.values[ 1 ] + \'">Total Range: $\'+ui.values[ 0 ] + " - $" + ui.values[ 1 ] +" </option>");
					}
				});
			}
			else
			{
				$("#total_range_picker").slideUp();
			}
			
			oTable.fnDraw();

		});	
		';
		
		return $js;
	}

}