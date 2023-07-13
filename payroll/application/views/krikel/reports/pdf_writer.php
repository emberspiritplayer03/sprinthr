 <p>&nbsp;</p>
<form id="form1" name="form1" method="post" action="<?php echo url('source/_load_pdf_output'); ?>">
  <input  type="submit" name="Submit" id="button" value="Load PDF Sample" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Loader::appLibrary('class_pdf_writer'); <br />
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);<br />
  $pdf-&gt;SetFont('dejavusans', '', 10);<br />
  $pdf-&gt;AddPage();<br />
  $html = 'This is a test';<br />
  $pdf-&gt;writeHTML($html, true, false, true, false, '');<br />
  $pdf-&gt;lastPage();<br />
  $pdf-&gt;Output('payslip.pdf', 'D');</p>
  
