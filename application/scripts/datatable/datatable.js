

function createDataTable(id, path, columns, fields, height, width) {	
	this.id = id;
	this.columns = columns;
	this.fields = fields;
	this.height = (height == undefined) ? 500 : height ;
	this.width = (width == undefined) ? 500 : width ;	
	this.path = path;
}

var dt = createDataTable;

dt.prototype.show = function() {
		

	var datatable_element = this.id;
	var datatable_my_columns =	this.columns;	
	var datatable_fields = this.fields ;
	var datatable_sorted_by = '';
	var datatable_height = this.height;
	var datatable_width = this.width;
	var path = this.path;
	
	var row_per_page = (this.row_per_page == undefined) ? 20 : this.row_per_page ;
	
	//this is for Paginator Label
	var first_page_link_label = (this.first_page_link_label == undefined) ? '' : this.first_page_link_label ;
	var next_page_link_label = (this.next_page_link_label == undefined) ? '' : this.next_page_link_label ;
	var previous_page_link_label = (this.previous_page_link_label == undefined) ? '' : this.previous_page_link_label ;
	var last_page_link_label = (this.last_page_link_label == undefined) ? '' : this.last_page_link_label ;
	

	YAHOO.example.MultipleFeatures = function() {
		
		var myColumnDefs = datatable_my_columns;
		var myDataSource = new YAHOO.util.DataSource(base_url + path);	
	
		myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;

		myDataSource.responseSchema = {
			resultsList: 'records',
			fields: datatable_fields,
			 metaFields: { totalRecords: 'totalRecords' }
		};
		var myConfigs = {
			initialRequest:'startIndex=0&results='+row_per_page,
			dynamicData: true,
			height:datatable_height,
			scrollable:true,
			width:datatable_width,
			paginator: new YAHOO.widget.Paginator({
				rowsPerPage: row_per_page,
				nextPageLinkLabel: next_page_link_label,
				previousPageLinkLabel: previous_page_link_label,
				firstPageLinkLabel: first_page_link_label,
				lastPageLinkLabel: last_page_link_label,
			  
			  	//customize
				// use a custom layout for pagination controls
				//template: "{FirstPageLink} {PreviousPageLink} {PageLinks} {NextPageLink} {LastPageLink}  &nbsp;&nbsp; Show:{RowsPerPageDropdown}per page {CurrentPageReport}",
				
								
				template: "{FirstPageLink} {PreviousPageLink} {PageLinks} {NextPageLink} {LastPageLink}  &nbsp;&nbsp; Show:{RowsPerPageDropdown}per page ",
	        	pageReportTemplate : "<b>Total Record(s): {totalRecords}</b>",
				
				// show all links
				pageLinks: 4,//YAHOO.widget.Paginator.VALUE_UNLIMITED,
			 
				// use these in the rows-per-page dropdown
				rowsPerPageOptions: [10,20,30,40,50,60,70,80,90,100,200]
				
			})
		}

		var myDataTable = new YAHOO.widget.DataTable(datatable_element, myColumnDefs, myDataSource, myConfigs);
		myDataTable.subscribe('rowClickEvent',myDataTable.onEventSelectRow);
		myDataTable.subscribe('cellDblclickEvent',myDataTable.onEventShowCellEditor);
		myDataTable.subscribe('editorBlurEvent', myDataTable.onEventSaveCellEditor);
		myDataTable.subscribe('rowMouseoverEvent', myDataTable.onEventHighlightRow); 
		myDataTable.subscribe('rowMouseoutEvent', myDataTable.onEventUnhighlightRow);

		 myDataTable.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {   
			 oPayload.totalRecords = oResponse.meta.totalRecords;  
			 return oPayload;   
		 } 
		return {
			ds: myDataSource,
			dt: myDataTable
		};

	}();	
	
}

dt.prototype.rowPerPage = function(row_per_page) {
	this.row_per_page = row_per_page;
}

dt.prototype.pageLinkLabel = function(first,previous,next,last) {
	
	var first_page_link_label = (this.first_page_link_label == undefined) ? '' : this.first_page_link_label ;
	var next_page_link_label = (this.next_page_link_label == undefined) ? '' : this.next_page_link_label ;
	var previous_page_link_label = (this.previous_page_link_label == undefined) ? '' : this.previous_page_link_label ;
	var last_page_link_label = (this.last_page_link_label == undefined) ? '' : this.last_page_link_label ;
	
	
	this.first_page_link_label  = (first == undefined) ? '|<' : first;
	this.next_page_link_label 	= (next == undefined) ? '>' : next;
	this.previous_page_link_label = (previous == undefined) ? '<' : previous;
	this.last_page_link_label = (last == undefined) ? '>|' : last;
}

//dt.prototype.innerHtml = function(inner_html) {
	//this.inner_html = inner_html;
	
//}

function x () {

rowsPerPage = (rowsPerPage == undefined) ? 20 : rowsPerPage ;

// Override the built-in formatter 
YAHOO.widget.DataTable.formatLink1 = function(elCell, oRecord, oColumn, oData) { 
	var id = oRecord.getData("id");
	elCell.innerHTML = innerHtml;
	//"<div align=center><a href='javascript:editCategory(" + id + ")' >Edit</a> | <a href='javascript:removeCategory(" + id + ")' >Remove</a></div>"; 
};		

	//"canvassCategoryDT";
var datatable_element = id;

	var datatable_my_columns =	datatable_column
				
				
			
				
				var datatable_fields = fields ;
				//['id','category','description','total_records'];
				var datatable_sorted_by = sorted_by;
				var datatable_height = height;
				var datatable_width = width;

//$.post(base_url + 'approvals/_get_request_list', {}, function(data){
	YAHOO.example.MultipleFeatures = function() {
		var myColumnDefs = datatable_my_columns;

			var myDataSource = new YAHOO.util.DataSource(base_url + controller + '?' );	

	
		myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;

		myDataSource.responseSchema = {
			resultsList: 'records',
			fields: datatable_fields,
			 metaFields: { totalRecords: 'totalRecords' }
		};
		var myConfigs = {
			initialRequest: initialRequest,
			//'startIndex=0&results=20'
			dynamicData: true,
			height:datatable_height,
			scrollable:true,
			width:datatable_width,
			paginator: new YAHOO.widget.Paginator({
				rowsPerPage: rowsPerPage,
				nextPageLinkLabel: '',
				previousPageLinkLabel: '',
				firstPageLinkLabel: '',
				lastPageLinkLabel: ''
			})
		}

		var myDataTable = new YAHOO.widget.DataTable(datatable_element, myColumnDefs, myDataSource, myConfigs);
		myDataTable.subscribe('rowClickEvent',myDataTable.onEventSelectRow);
		myDataTable.subscribe('cellDblclickEvent',myDataTable.onEventShowCellEditor);
		myDataTable.subscribe('editorBlurEvent', myDataTable.onEventSaveCellEditor);
		myDataTable.subscribe('rowMouseoverEvent', myDataTable.onEventHighlightRow); 
		myDataTable.subscribe('rowMouseoutEvent', myDataTable.onEventUnhighlightRow);

		 myDataTable.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {   
			 oPayload.totalRecords = oResponse.meta.totalRecords;  
			 return oPayload;   
		 } 
		return {
			ds: myDataSource,
			dt: myDataTable
		};

	}();
//}, 'json');	
}
