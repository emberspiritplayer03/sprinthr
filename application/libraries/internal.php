<?php
function internal_autocomplete() {
	Loader::appLibrary('yui');
	Loader::appStyle('jquery/autocomplete/jquery.autocomplete.css');
	Loader::appStyle('jquery/autocomplete/thickbox.css');
	yui_datatable();
	Loader::appStyle('internal/internal.autocomplete.css');
}

?>