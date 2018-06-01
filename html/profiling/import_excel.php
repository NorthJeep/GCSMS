<?php
/**
 * Created by PhpStorm.
 * User: Aravinth
 * Date: 10-09-2017
 * Time: 12:30 PM
 */

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

require_once ('../config.php');
require_once ('Spout/Autoloader/autoload.php');

if(!empty($_FILES['excelfile']['name']))
{
    // Get File extension eg. 'xlsx' to check file is excel sheet
    $pathinfo = pathinfo($_FILES['excelfile']['name']);

    // check file has extension xlsx, xls and also check
    // file is not empty
    if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls')
        && $_FILES['excelfile']['size'] > 0 )
    {
        $file = $_FILES['excelfile']['tmp_name'];

        // Read excel file by using ReadFactory object.
        $reader = ReaderFactory::create(Type::XLSX);

        // Open file
        $reader->open($file);
        $count = 0;

        // Number of sheet in excel file
        foreach ($reader->getSheetIterator() as $sheet)
        {

            // Number of Rows in Excel sheet
            foreach ($sheet->getRowIterator() as $row)
            {

                // It reads data after header. In the my excel sheet,
                // header is in the first row.
                if ($count > 0) {

                    // Data of excel sheet
                    $stud_no = $row[0];
                    $stud_firstname = $row[1];
                    $stud_mname = $row[2];
                    $stud_lname = $row[3];
                    $stud_gender = $row[4];
                    $stud_course = $row[5];
                    $stud_yr_lvl = $row[6];
                    $stud_section = $row[7];
                    $stud_bday = $row[8];
                    $stud_address = $row[9];
                    $stud_prov_add = $row[10];
                    $stud_tel_no = $row[11];
                    $stud_cp_no = $row[12];
                    $stud_email = $row[13];
                    $stud_birthplace = $row[14];
                    $stud_status = $row[15];

                    //Here, You can insert data into database.
                    $qry = "call stud_profile_add('$stud_no','$stud_firstname','$stud_mname', '$stud_lname','$stud_gender','$stud_course','$stud_yr_lvl','$stud_section','$stud_bday', '$stud_address', '$stud_prov_add','$stud_tel_no', '$stud_cp_no', '$stud_email', '$stud_birthplace', '$stud_status')";
                    $res = mysqli_query($db,$qry);

                }
                $count++;
            }
        }

        if($res)
        {
            echo "Your file Uploaded Successfull";
        }
        else
        {
            echo "Your file Uploaded Failed";
        }

        // Close excel file
        $reader->close();
    }
    else
    {
        echo "Please Choose only Excel file";
    }
}
else
{
    echo "File is Empty"."<br>";
    echo "Please Choose Excel file";
}

?>
