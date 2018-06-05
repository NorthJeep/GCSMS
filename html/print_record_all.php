<?php
require('fpdf.php');
$acadOpt = '';
$semOpt = '';
$monthOpt = '';
$dayOpt = '';
$courseOpt = '';
$result = '';
class PDF extends FPDF
{

// Colored table
    public function Header()
    {
    

    // Select Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
    
        // Framed title
    
        $this->Image('images/PUPLogo.png', 98, 10, 20);
        $this->Ln(30);
        $this->Cell(80);
        $this->Cell(40, 10, 'Polytechnic University of the Philippines', 0, 0, 'C');
        // Line break
        $this->Ln(20);
    }
    // Colored table
    public function FancyTable($body)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(50, 50, 50, 40);
        for ($i=0;$i<count($body);$i++) {
            $this->Cell($w[$i], 7, $body[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
    
        $fill = false;
        $conn = mysqli_connect("localhost", "root", "", "pupqcdb");

        // Check connection
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        if (isset($_REQUEST['view'])) {
            $acadOpt = $_REQUEST['acadOpt'];
            $semOpt = $_REQUEST['semOpt'];
            $monthOpt = $_REQUEST['monthOpt'];
            $dayOpt = $_REQUEST['dayOpt'];
            $courseOpt = $_REQUEST['courseOpt'];

            $actualQuery = " SELECT
  `s`.`Stud_NO` AS `STUD_NO`,
  CONCAT(`s`.`Stud_FNAME`, ' ', `s`.`Stud_LNAME`) AS `STUD_NAME`,
  `c`.`Couns_COUNSELING_TYPE` AS `COUNSELING_TYPE`,
  DATE_FORMAT(`c`.`Couns_DATE`, '%M %d %Y') AS `COUNSELING_DATE`,
  (
    SELECT
      GROUP_CONCAT(`a`.`Couns_APPROACH` SEPARATOR ', ')
    FROM
      `t_couns_approach` `a`
    WHERE
      (
        `a`.`Couns_ID_REFERENCE` = `c`.`Couns_ID`
      )
  ) AS `COUNSELING_APPROACH`,
  `c`.`Couns_BACKGROUND` AS `COUNSELING_BG`,
  `c`.`Couns_GOALS` AS `GOALS`,
  `c`.`Couns_COMMENT` AS `COUNS_COMMENT`,
  `c`.`Couns_RECOMMENDATION` AS `RECOMMENDATION`
FROM
  `t_counseling` `c`
  JOIN `t_couns_details` `cd` ON `c`.`Couns_ID` = `cd`.`Couns_ID_REFERENCE`
  JOIN `r_stud_profile` `s` ON `s`.`Stud_NO` = `cd`.`Stud_NO`
  JOIN `r_courses` `cr` ON `s`.`Stud_COURSE` = `cr`.`Course_CODE` ";
    
    $options = array();
    
    if ($acadOpt != 'All') {
        $options[] = "cr.Course_CURR_YEAR = '$acadOpt'";
    }

    if ($semOpt != 'All') {
        $options[] = "";
    }

    if ($monthOpt != 'All') {
        $options[] = "MONTH(c.Couns_DATE) = '$monthOpt'";
    }

    if ($dayOpt != 'All') {
        $options[] = "DAY(c.Couns_DATE) = '$dayOpt'";
    }

    if ($courseOpt != 'All') {
        $options[] = "s.Stud_COURSE = '$courseOpt'";
    }

    $query = $actualQuery;
    if (count($options)>0) {
        $query .= " WHERE ". implode(' AND ', $options);
    }

    $result = mysqli_query($conn, $query);
    } else {
        $result =  mysqli_query($conn, " SELECT
 `s`.`Stud_NO` AS `STUD_NO`,
 CONCAT(`s`.`Stud_FNAME`, ' ', `s`.`Stud_LNAME`) AS `STUD_NAME`,
 `c`.`Couns_COUNSELING_TYPE` AS `COUNSELING_TYPE`,
  DATE_FORMAT(`c`.`Couns_DATE`, '%M %d %Y') AS `COUNSELING_DATE`,
  
 
  
  (
  SELECT
      GROUP_CONCAT(`a`.`Couns_APPROACH` SEPARATOR ', ')
  FROM
      `t_couns_approach` `a`
  WHERE
      (
          `a`.`Couns_ID_REFERENCE` = `c`.`Couns_ID`
      )
) AS `COUNSELING_APPROACH`,
`c`.`Couns_BACKGROUND` AS `COUNSELING_BG`,
`c`.`Couns_GOALS` AS `GOALS`,
`c`.`Couns_COMMENT` AS `COUNS_COMMENT`,
`c`.`Couns_RECOMMENDATION` AS `RECOMMENDATION`
FROM
  (
      (
          `t_counseling` `c`
      JOIN `t_couns_details` `cd` ON
          (
              (
                  `c`.`Couns_ID` = `cd`.`Couns_ID_REFERENCE`
              )
          )
      )
  JOIN `r_stud_profile` `s` ON
      ((`s`.`Stud_NO` = `cd`.`Stud_NO`))
  )  ");
    }

        
        while ($row = mysqli_fetch_array($result)) {
            {
        $this->Cell($w[0], 7, $row[0], 'LR', 0, 'L', $fill);
        $this->Cell($w[1], 7, $row[1], 'LR', 0, 'L', $fill);
        $this->Cell($w[2], 7, $row[2], 'LR', 0, 'L', $fill);
        $this->Cell($w[3], 7, $row[3], 'LR', 0, 'L', $fill);
        $this->Ln();
        $fill = !$fill;
    }
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

$pdf = new PDF();
// Column headings
$body = array('Student Number', 'Student name', 'Type', 'Date');
// Data loading
$pdf->SetFont('Arial', '', 14);
$pdf->AddPage();
$pdf->FancyTable($body);
$pdf->Output('I', 'Counseling Report');
