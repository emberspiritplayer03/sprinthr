<?php
$doc = new DOMDocument();
$doc->loadXML(include 'kapi.xml');
echo $doc->saveXML();
?>
<?php 
include 'kpi.xml';
?>
<div id="div_xml"></div>
<script>
load_employee_performance();
</script>