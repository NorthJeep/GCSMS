<?php
require('fpdf.php');

class PDF extends FPDF
{


// Colored table
function Header()
{
    

    // Select Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    
    // Framed title
    
    $this->Image('images\PUPLogo.png',110,10,20);
    $this->Ln(30);
    $this->Cell(90);
    $this->Cell(30,10,'Polytechnic University of the Philippines',0,0,'C');
    // Line break
    $this->Ln(20);
}
// Colored table
function FancyTable($body)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(50, 50, 30, 50);
    for($i=0;$i<count($body);$i++)
        $this->Cell($w[$i],7,$body[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data

   $fill = false;
   $conn = mysqli_connect("localhost","root","","g&csms_db");

// Check connection
if (mysqli_connect_errno())
{
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
  $sql =  mysqli_query ($conn," SELECT `STUD_NO`,`STUD_NAME`,`COUNSELING_TYPE_CODE`,`COUNS_DATE` FROM `t_counseling`");
while ($row = mysqli_fetch_array($sql)){
    {
        $this->Cell($w[0],7,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],7,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],7,$row[2],'LR',0,'L',$fill);
        $this->Cell($w[3],7,$row[3],'LR',0,'L',$fill);
        $this->Ln();
        $fill = !$fill;
    }
}
    // Closing line
    $this->Cell(array_sum($w),0,'','T');

}

}

$pdf = new PDF();
// Column headings
$body = array('Student Number', 'Student name', 'Type', 'Date');
// Data loading
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->FancyTable($body);
$pdf->Output('I','Counseling Report');
?>