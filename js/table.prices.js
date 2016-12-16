
/*
 * Editor client script for DB table prices
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'php/table.prices.php',
		table: '#prices',
		fields: [
			{
				"label": "perkm:",
				"name": "perkm"
			},
			{
				"label": "permin:",
				"name": "permin"
			},
			{
				"label": "startTime:",
				"name": "starttime",
				"type": "datetime",
				"format": "h:mm a"
			},
			{
				"label": "endTime:",
				"name": "endtime",
				"type": "datetime",
				"format": "h:mm a"
			}
		]
	} );

	var table = $('#prices').DataTable( {
		ajax: 'php/table.prices.php',
		columns: [
			{
				"data": "perkm"
			},
			{
				"data": "permin"
			},
			{
				"data": "starttime"
			},
			{
				"data": "endtime"
			}
		],
		select: true,
		lengthChange: false
	} );

	new $.fn.dataTable.Buttons( table, [
		{ extend: "create", editor: editor },
		{ extend: "edit",   editor: editor },
		{ extend: "remove", editor: editor }
	] );

	table.buttons().container()
		.appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );
} );

}(jQuery));

