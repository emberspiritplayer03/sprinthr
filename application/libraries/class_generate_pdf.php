<?php
class Generate_Pdf {

    public static function generateCoe($fName = NULL, $e, $d, $reason = '', $other_info = array())
    {
        $company = G_Company_Info_Finder::findById(1);

        if( strtolower($d['gender']) == 'female' ){
            $person_title = 'MS.';
        }elseif( strtolower($d['gender']) == 'male' ){
            $person_title = 'MR.';
        }else{
            $person_title = 'MR/MS.';
        }

        $hired_date = date_format(date_create($e->getHiredDate()),"F j, Y");
        if($e->getTerminatedDate() != '0000-00-00') {
          $end_date = date_format(date_create($e->getTerminatedDate()),"F j, Y");
        }elseif($e->getResignationDate() != '0000-00-00') {
          $end_date = date_format(date_create($e->getResignationDate()),"F j, Y");
        }elseif($e->getEndoDate() != '0000-00-00') {
          $end_date = date_format(date_create($e->getEndoDate()),"F j, Y");
        } else {
          $end_date = date("F j, Y");    
        }
        $full_name = strtoupper($e->getFirstName() . ' ' . $e->getMiddleName() . ' ' . $e->getLastName());
        $employee_name = strtr(utf8_decode($full_name), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $last_name = strtoupper($e->getLastName());        

        if( trim($reason) == '' ){
          $reason = 'whatever legal purpose it may serve';
        }

        $msg = '
            <div style="width:700px; margin:0 auto;">
                <p style="text-align:center; font-size: 16pt; font-weight:bold;">CERTIFICATE OF EMPLOYMENT</p><br />
            </div>
            <div style="width:700px; margin:0 auto;">
                <p style="text-align:left;">This is to certify the <strong>' . $person_title . ' ' . $employee_name .' </strong> has been hired by ' . $d['company_name'] . ' as <b>' . $d['position'] . '</b> from <b>' . $hired_date . '</b> &nbsp;up to <b>' . $end_date . '</b>.</p>
            </div><br />
            <div style="width:700px; margin:0 auto;">
                <p style="text-align:left;">This certification is being issued upon the request of <strong>' . $person_title . ' ' . $last_name . '</strong> for ' . trim($reason) . '.</p>
            </div><br />
            <div style="width:700px; margin:0 auto;">
                <p style="text-align:left;">Done in ' . $company->getAddress() . ' this ' . date('d') . date('S') . ' day of ' . date("F") . ', ' . date("Y") . '</p>
            </div><br /><br /><br /><br />';

        if( $other_info['coe_signatory'] != '' && $other_info['coe_position'] != '' ){
            $msg .= '
                <div style="width:700px; margin:0 auto;">                
                    <p style="text-align:left;">' . ucwords($other_info['coe_signatory']) . '<br />' . ucwords($other_info['coe_position']) . '</p><br />
                </div>       
            ';
        }else{
            $msg .= '
                <div style="width:700px; margin:0 auto;">                
                    <p style="text-align:left;">Charina Williams<br />Asst. Manager HR/GA</p><br />
                </div>       
            ';
        }        

        $html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', array(15, 15, 15, 5)); //HTML2PDF('P','A4','en');
        $html2pdf->WriteHTML($msg, false);      
        $pName = $fName; 
        $html2pdf->Output(BASE_PATH."files/coe/$pName", 'F');   
        return BASE_PATH."files/coe/$pName";            
    }
                
    public static function testPdf()
    {
        $msg = '
            <div style="width:700px; margin:0 auto;">
                <p style="text-align:center; font-size: 16pt; font-weight:bold;">CERTIFICATE OF EMPLOYMENT</p><br />
            </div>
            <div style="width:700px; margin:0 auto;">
                <p style="text-align:left;">This is to certify the MR/MS. JEFFREY REYES AMBROCIO has been hired by Artnature Manufacturing Philippines, Inc. as Operator from January 3, 2011 up to present.</p>
            </div><br />
            <div style="width:700px; margin:0 auto;">
                <p style="text-align:left;">This certification is being issued upon the request of MR/MS. AMBROCIO for whatever legal purpose it may serve.</p>
            </div><br />
            <div style="width:700px; margin:0 auto;">
                <p style="text-align:left;">Given this 03rd day of November 2015 at First Philippine Industrial Park Barangay Sta. Anastacia, Sto. Tomas, Batangas.</p>
            </div><br /><br />
            <div style="width:700px; margin:0 auto;">
                <p style="text-align:left;">HRAD Manager</p><br />
            </div>       
        ';

        $html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', array(15, 15, 15, 5)); //HTML2PDF('P','A4','en');
        $html2pdf->WriteHTML($msg, false);      
        $pName = "certificate_of_employment.pdf"; 
        $html2pdf->Output(BASE_PATH."files/coe/$pName", 'F');   
        return BASE_PATH."files/coe/$pName";        
    }
    
    
}
?>