var wg = {

	version: "1.0",

	won: new Array(), //windows on load functions

	isFunction: function(fn) {

		return !!fn && typeof fn != "string" && !fn.nodeName && 

			fn.constructor != Array && /function/i.test( fn + "" );

	},

	

	getId: function(element){

		return document.getElementById(element);

	},

	

	getClass: function(element){

		var arr = new Array();

		var elems = document.getElementsByTagName("*");

		for(var i = 0; i < elems.length; i++)

		{

			var elem = elems[i];

			var id = elem.getAttribute("id");

			var cls = elem.getAttribute("class");

			if(cls == element)

			{

				arr[arr.length] = id;

			}

		}

		return arr;	

	},

	

	onReady: function(fn){

		wg.won.push(fn);

		window.onload = wg._execWon;

	},

	

	_execWon: function(){

	  for(var i = 0; i < wg.won.length; i++)

		wg.won[i]();		

	},

	

	each: function(obj, fn) {

		if (typeof obj.length == 'undefined' ){

			for (var i in obj){

				fn.call(obj[i], i, obj[i]);

			}			

		}else

			for ( var i = 0, ol = obj.length, val = obj[0]; 

				i < ol && fn.call(val,i,val) !== false; val = obj[++i] ){}

			

		return obj;

	}

}



wg.ajax = {

	getXhr: function(){

		try{

			xhr = new XMLHttpRequest()

		}

		catch(e){

			try{

				xhr = new ActiveXObject("Msxml2.XMLHTTP")

			}

			catch(e){

				try{

					xhr = new ActiveXObject("Microsoft.XMLHTTP")

				}

				catch(E){

					xhr = false

				}

			}

		}

		return xhr;

	},

	

	get: function(url, callback){

		var xhr = this.getXhr();

		if (wg.isFunction(callback)){

			xhr.onreadystatechange = function(){

				if (xhr.readyState == 4 || xhr.readyState == "complete"){

					callback(xhr.responseText);

				}

			}

		}

		xhr.open("GET", url, true);

		xhr.send(null);		

	},

	

	update: function(id, url){

		var xhr = this.getXhr();

		xhr.onreadystatechange = function(){

			if (xhr.readyState == 4 || xhr.readyState == "complete"){

				wg.getId(id).innerHTML = xhr.responseText;

			}

		}

		xhr.open("GET", url, true);

		xhr.send(null);

	},

	

	request: function(url, params){

		var xhr = this.getXhr();

		if (params){

			xhr.onreadystatechange = function(){

				if (wg.isFunction(params.process))

					if (xhr.readyState == 1 || xhr.readyState == 2 || xhr.readyState == 3)

						params.process();	

				if (wg.isFunction(params.complete))

					if (xhr.readyState == 4 || xhr.readyState == "complete")

						params.complete(xhr.responseText);

			}

		}

		

		var method = 'GET';

		var async = true;

		var data = '';

		if (params){

			method = (params.type) ? params.type : method;

			async = (params.async == false) ? false : async;

			data = (params.data) ? '?' + params.data : data;

		}

		xhr.open(method, url + data, async);

		xhr.send(null);

	}

}



wg.table = {

	row_per_page: 20,

	show_page: 1,

	row_list: [10, 20, 30],

	

	show: function(params){

		if (params) {

			url = params.url;

			this.table_id = params.table_id;

			this.pager_id = params.pager_id;

			this.row_per_page = (params.row_per_page) ? params.row_per_page : this.row_per_page;

			this.show_page = (params.show_page) ? params.show_page : this.show_page;

			this.fields = (params.fields) ? params.fields : null;

			this.row_list_id = (params.row_list_id) ? params.row_list_id : this.row_list_id;

			this.row_list = (params.row_list) ? params.row_list : this.row_list;			

			//this.style = (params.style) ? params.style : null;

		}

		wg.ajax.request(url,

		{

			data: "page=" + this.show_page + "&row_per_page=" + this.row_per_page,

			complete: function(response){

				

				response = eval('(' + response + ')');

				wg.table.records = response['records'];

				

				wg.table.total_record = response.total_record;

				wg.table.total_page = wg.table.getTotalPage()

				

				wg.table.showTable();

				wg.table.showPager();

				(wg.table.row_list_id) ? wg.table.showPerPageSelector() : '';

			},

			process: function(){

				

			}

		});

	},

	

	showPerPageSelector: function(){

		var str = "<select id='pager-per-page-selector-box'>";

			wg.each(wg.table.row_list, function(i, v){

				var selected = (wg.table.row_per_page == v) ? 'selected="selected"' : '';

				str += "<option value='" + v + "' " + selected + ">" + v + "</option>";		   

			});

		str += "</select>";

		

		wg.getId(wg.table.row_list_id).innerHTML = str;

		wg.getId('pager-per-page-selector-box').onchange = this.changePerPage;

	},

	

	changePerPage: function(){

		wg.table.row_per_page = wg.getId('pager-per-page-selector-box').value;

		//if (wg.table.show_page > wg.table.total_page){

			wg.table.show_page = 1;	

		//}

		wg.table.show();

	},	



	showTable: function(){

		var tbl = "<table>";

		tbl += wg.table.getTitle();

		tbl += wg.table.getRecords();

		tbl += "</table>";

		

		wg.getId(this.table_id).innerHTML = tbl;

	},

	

	getTitle: function(){

		var str = '<thead><tr>';

		wg.each(wg.table.fields, function(field_key){

			str += '<th>';

			str += wg.table.fields[field_key].title;

			str += '</th>';

		});

		str += '</tr></thead>';

		return str;

	},

	

	getRecords: function(){

		//alert(this.style.column[1]);

		var str = '<tbody>';

		var ctr = 0;

		var row_type = 'even';

		wg.each(wg.table.records, function(i){

			row_type = ((ctr % 2) == 0) ? 'even' : 'odd' ;

			str += '<tr class="row-' + row_type + '">';

			

			wg.each(wg.table.fields, function(field_key, field_val){

				wg.each(wg.table.fields[field_key], function(field, db_field){

					wg.each(wg.table.records[i], function(key, val){

						if (key == db_field){

							str += '<td class="column-' + (field_key + 1) + '">';

							str += val;

							str += '</td>';

						}

					});					

				});

			});

			ctr++;

		});

		str += '</tr>';

		str += '</tbody>';

		return str;

	},

	

	getTotalPage: function(){

		return Math.ceil(wg.table.total_record / wg.table.row_per_page);

	},

	

	showPager: function(){

		var pager = '';

		pager += "<span id='table-start-arrow' style='cursor:pointer; font-weight:bold; font-size:20px'>|&laquo;</span> &nbsp;&nbsp;";

		pager += "<span id='table-previous-arrow' style='cursor:pointer; font-weight:bold; font-size:20px'>&laquo;</span> ";

		//pager += "<input id='table-page-box' type='text' size='1' value='"+ this.show_page +"'/> /" + wg.table.total_page;

		

		pager += "<select id='table-page-box'>";

		for (ctr = 1; ctr <= wg.table.total_page; ctr++){

			var selected = (wg.table.show_page == ctr) ? 'selected="selected"' : '';

			pager += "<option value="+ ctr +" " + selected + ">" + ctr  + "</option>";

		}

		pager += "</select>";

		pager += " of " + wg.table.total_page;

		pager += " <span id='table-next-arrow' style='cursor:pointer; font-weight:bold; font-size:20px'>&raquo;</span>";

		pager += "&nbsp;&nbsp; <span id='table-end-arrow' style='cursor:pointer; font-weight:bold; font-size:20px'>&raquo;|</span>";

		

		wg.getId(this.pager_id).innerHTML = pager;

		

		wg.getId('table-start-arrow').onclick = this.showStart;

		wg.getId('table-end-arrow').onclick = this.showEnd;

		wg.getId('table-previous-arrow').onclick = this.clickLeftArrow;

		wg.getId('table-next-arrow').onclick = this.clickRightArrow;

		wg.getId('table-page-box').onchange = this.changePageBox;		

	},

	

	changePageBox: function(){

		var input_number = Number(this.value);

		if (input_number >= 1 && input_number <= wg.table.total_page){

			wg.table.show_page = input_number;

			wg.table.show();

		} else {

			wg.getId('table-page-box').value = wg.table.show_page;

		}

	},

	

	showStart: function(){

		if (wg.table.show_page > 1){

			wg.table.show_page = 1;

			wg.table.show();

		}

	},

	

	showEnd: function(){

		if (wg.table.show_page < wg.table.total_page) {

			wg.table.show_page = wg.table.total_page;

			wg.table.show();

		}

	},

	

	clickLeftArrow: function(){

		if (wg.table.show_page > 1){

			wg.table.show_page--;

			wg.table.show();

		}

	},

	

	clickRightArrow: function(){

		if (wg.table.show_page < wg.table.total_page) {

			wg.table.show_page++;

			wg.table.show();

		}

	}

}



wg.pager = {

	page: 1,

	row_per_page: 10,

	row_list: [10, 20, 30],

	

	show: function(params){

		if (params){

			this.page = (params.page) ? params.page : this.page;

			this.row_per_page = (params.row_per_page) ? params.row_per_page : this.row_per_page;

			this.total_records = params.total_records;

			this.id = (params.id) ? params.id : this.id;

			this.action = (params.action) ? params.action : this.action;

			this.pager_id = (params.pager_id) ? params.pager_id : this.pager_id;

			this.row_list_id = (params.row_list_id) ? params.row_list_id : this.row_list_id;

			this.row_list = (params.row_list) ? params.row_list : this.row_list;

		}

		wg.ajax.update(this.id, this.action + '?page=' + this.page + '&row_per_page=' + this.row_per_page);

		this.showPageSelector();

		(wg.pager.row_list_id) ? this.showPerPageSelector() : '';

	},

	

	showPageSelector: function(){

		var total_pages = this.getTotalPages();

		

		var str = "<span id='pager-previous-arrow' style='cursor:pointer; font-size:18px'>&laquo;</span> ";

		str += "<select id='pager-page-selector-box'>";

		for (var ctr = 1; ctr <= total_pages; ctr++){

			var selected = (wg.pager.page == ctr) ? 'selected="selected"' : '';

			str += "<option value='" + ctr + "' " + selected + ">" + ctr + "</option>";

		}

		str += "</select>";

		

		str += " of " + total_pages;

		

		str += " <span id='pager-next-arrow' style='cursor:pointer; font-size:18px'>&raquo;</span>";

		

		wg.getId(wg.pager.pager_id).innerHTML = str;

		wg.getId('pager-page-selector-box').onchange = this.changePageBox;

		wg.getId('pager-previous-arrow').onclick = this.showPrevious;

		wg.getId('pager-next-arrow').onclick = this.showNext;

	},

	

	showPerPageSelector: function(){

		var str = "<select id='pager-per-page-selector-box'>";

			wg.each(wg.pager.row_list, function(i, v){

				var selected = (wg.pager.row_per_page == v) ? 'selected="selected"' : '';

				str += "<option value='" + v + "' " + selected + ">" + v + "</option>";		   

			});

		str += "</select>";

		

		wg.getId(wg.pager.row_list_id).innerHTML = str;

		wg.getId('pager-per-page-selector-box').onchange = this.changePerPage;

	},

	

	changePerPage: function(){

		wg.pager.row_per_page = wg.getId('pager-per-page-selector-box').value;

		if (wg.pager.page >wg.pager.getTotalPages()){

			wg.pager.page = 1;	

		}

		wg.pager.show();

	},

	

	changePageBox: function(){

		wg.pager.page = wg.getId('pager-page-selector-box').value;

		wg.pager.show();

	},

	

	showPrevious: function(){

		if (wg.pager.page > 1){

			wg.pager.page--;

			wg.pager.show();

		}

	},

	

	showNext: function(){

		if (wg.pager.page < wg.pager.getTotalPages()) {

			wg.pager.page++;

			wg.pager.show();

		}

	},

	

	getTotalPages: function(){

		return Math.ceil(this.total_records / this.row_per_page);	

	}

}



wg.cookie = {

	set: function(c_name, c_value, c_days) {

 		var today = new Date();

 		var expire = new Date();

 		if (c_days == null || c_days==0) c_days=1;

 		expire.setTime(today.getTime() + 3600000*24*c_days);

 		document.cookie = c_name+"="+escape(c_value)

                 + ";expires="+expire.toGMTString();

	},

	

	get: function(c_name) {

		if (document.cookie.length>0) {

			c_start=document.cookie.indexOf(c_name + "=")

		  	if (c_start!=-1){ 

				c_start=c_start + c_name.length+1 

				c_end=document.cookie.indexOf(";",c_start)

				if (c_end==-1) c_end=document.cookie.length

				return unescape(document.cookie.substring(c_start,c_end))

		  	} 

		}

		return false;

	}

}