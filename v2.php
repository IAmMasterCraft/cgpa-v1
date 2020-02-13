<?php
// Include Composer autoloader if not already done for pdf manipulation.
include_once './pdfparser-master/vendor/autoload.php';

// include pdfhtmltodom for scraping
include_once './simplehtmldom/simple_html_dom.php';

// Parse pdf file and build necessary objects.

function pdfToText($filename)
{
    $parser = new \Smalot\PdfParser\Parser();
    // $pdf    = $parser->parseFile('document.pdf');
    $pdf    = $parser->parseFile($filename);
    $text = $pdf->getText();
    // echo $text;
    return $text;
}

// print_r($_FILES);
if (!empty($_FILES)) {
    // echo "q";
    // print_r($_POST);
    // print_r($_FILES);

    $sn = array();
    $batch = array();
    $semester = array();
    $course = array();
    $title = array();
    $grade = array();
    $i_type = array();
    $remarks = array();

    $all_c_file = pdfToText($_FILES['res']['tmp_name']);
    $c_files = explode("NOU191043410", $all_c_file);
    $c_files_temp = explode("Remarks", $c_files[1]);
    $c_files[1] = $c_files_temp[1];
    // echo $c_files[1];

    foreach ($c_files as $key => $value) {
        $c_file[$key] = (explode(" ", $value));
    }
    $index = 0;
    $index2 = 0;
    foreach ($c_file as $key => $value) {
        if ($key == 0 || $key == 26) {
            continue;
        }
        // else echo ($value[$index])."<hr>";
        else {
            // echo "$index2 of $key is " . ($c_file[$key][$index2])." and size is = " . sizeof($c_file[$key]) . "<hr>";
            // if ($key < 10) {
            //     $sn[$key] = trim(substr($c_file[$key][$index2], 0, 3), " ");
            // } else {
            //     $sn[$key] = trim(substr($c_file[$key][$index2], 0, 4), " ");
            // }

            switch ($key) {
                case ($key >= 0 && $key <= 9):
                    $sn[$key] = trim(substr($c_file[$key][$index2], 2, 1), " ");
                    $batch[$key] = trim(substr($c_file[$key][$index2], 4, 6), " ");
                    $semester[$key] = trim(substr($c_file[$key][$index2], 11, 3), " ");
                    $course[$key] = trim(substr($c_file[$key][$index2], 15, 6), " ");

                    foreach ($c_file[$key] as $id => $data) {
                        // echo $id . " = " . $data . "<br>";
                        if ($id === 0) {
                            $temp_title = substr($data, 21, strlen($data));
                        } else {
                            $temp_title .= " " . $data;
                        }
                    }
                    $temp_title = explode("OK", $temp_title);
                    $title[$key] = substr($temp_title[0], 0, -2);
                    $grade[$key] = substr($temp_title[0], strlen(substr($temp_title[0], 0, -2)));
                    break;
                case ($key >= 10 && $key <= 99):
                    $sn[$key] = trim(substr($c_file[$key][$index2], 2, 2), " ");
                    $batch[$key] = trim(substr($c_file[$key][$index2], 5, 6), " ");
                    $semester[$key] = trim(substr($c_file[$key][$index2], 12, 3), " ");
                    $course[$key] = trim(substr($c_file[$key][$index2], 16, 6), " ");

                    foreach ($c_file[$key] as $id => $data) {
                        // echo $id . " = " . $data . "<br>";
                        if ($id === 0) {
                            $temp_title = substr($data, 23, strlen($data));
                        } else {
                            $temp_title .= " " . $data;
                        }
                    }
                    $temp_title = explode("OK", $temp_title);
                    $title[$key] = substr($temp_title[0], 0, -2);
                    $grade[$key] = substr($temp_title[0], strlen(substr($temp_title[0], 0, -2)));
                    break;
                case ($key >= 100 && $key <= 999):
                    $sn[$key] = trim(substr($c_file[$key][$index2], 2, 3), " ");
                    $batch[$key] = trim(substr($c_file[$key][$index2], 7, 6), " ");
                    $semester[$key] = trim(substr($c_file[$key][$index2], 13, 3), " ");
                    $course[$key] = trim(substr($c_file[$key][$index2], 17, 6), " ");

                    foreach ($c_file[$key] as $id => $data) {
                        // echo $id . " = " . $data . "<br>";
                        if ($id === 0) {
                            $temp_title = substr($data, 25, strlen($data));
                        } else {
                            $temp_title .= " " . $data;
                        }
                    }
                    $temp_title = explode("OK", $temp_title);
                    $title[$key] = substr($temp_title[0], 0, -2);
                    $grade[$key] = substr($temp_title[0], strlen(substr($temp_title[0], 0, -2)));
                    break;
            }
            // echo trim(substr($c_file[$key][3], 0, ((strpos($c_file[$key][3], "OK")) - 5)), " ") . "<hr>";
            // while ($index2 < sizeof($c_file[$key])) {
            //     switch ($key) {
            //         case ($key >= 0 && $key <= 9):
            //             // $title[$key] .= trim(substr($c_file[$key][$index2], 23), " ");
            //             $title[$key] .= $c_file[$key][$index2];
            //             break;
            //         case ($key >= 10 && $key <= 99):
            //             $title[$key] .= $c_file[$key][$index2];
            //             break;
            //         case ($key >= 100 && $key <= 999):
            //             $title[$key] .= $c_file[$key][$index2];
            //             break;
            //     }
            //     $index2 ++;
            // }
            // $index2 = 0;
        }
    }
    // echo strlen($sn[1]);
    // echo trim(substr($sn[1], 0, 3), " ");
    // print_r($grade);


}
$faculty = urlencode("Sciences");
// scraping credit units
// Create DOM from URL or file
$html = file_get_html('https://nou.edu.ng/courses?field_faculty_name_value=' . $faculty . '&field_level__value=All&field_semester_value=All');

// Find all needed content
$n = 0;
foreach ($html->find('tr') as $element) {
    foreach ($element->find('td.views-field-field-course-code') as $info) {
        $noun[trim($info->plaintext)] = "";
    }
    foreach ($element->find('td.views-field-field-credit-unit') as $info) {
        $temp[$n] = trim($info->plaintext);
    }
    // $noun[$n] = trim($element->find('td.views-field-field-credit-unit')->plaintext);
    $n++;
}
// print_r($temp);
$n = 1;
foreach ($noun as $code => $unit) {
    $cc = substr(str_replace(" ", "", $code), 0, 6);
    $units[$cc] = $temp[$n];
    $n++;
}
// print_r($units);

// scraping credit units for gst
// Create DOM from URL or file
$html = file_get_html('https://nou.edu.ng/courseware?field_faculty_name_value=Centre+for+Entrepreneurship+and+General+Studies+%28CE%26GS%29&field_level__value=All&field_semester_value=All');

// Find all needed content
$n = 0;
foreach ($html->find('tr') as $element) {
    foreach ($element->find('td.views-field-field-course-code') as $info) {
        $noun2[trim($info->plaintext)] = "";
    }
    foreach ($element->find('td.views-field-field-credit-unit') as $info) {
        $temp2[$n] = trim($info->plaintext);
    }
    // $noun[$n] = trim($element->find('td.views-field-field-credit-unit')->plaintext);
    $n++;
}
$n = 1;
foreach ($noun2 as $code => $unit) {
    $cc = substr(str_replace(" ", "", $code), 0, 6);
    $units[$cc] = $temp2[$n];
    $n++;
}

?>
<!-- table for verifying and calculating cgpa -->
<?php
if (!empty($sn) && !empty($batch) && !empty($semester) && !empty($course) && !empty($title) && !empty($grade) && !empty($units)) {
?>
<div class="table-responsive">
    <form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>BATCH</th>
                <th>SEMESTER</th>
                <th>COURSE</th>
                <th>TITLE</th>
                <th>UNIT</th>
                <th>GRADE</th>
                <th>REMARKS</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#</th>
                <th>BATCH</th>
                <th>SEMESTER</th>
                <th>COURSE</th>
                <th>TITLE</th>
                <th>UNIT</th>
                <th>GRADE</th>
                <th>REMARKS</th>
            </tr>
        </tfoot>
        <tbody id="table-body">
            <?php
            $totalGradeByUnit = "";
            $totalUnit = "";
            $i = 1;
            while ($i < sizeof($sn)) {
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php
                    if (!array_key_exists($i, $batch)) {$batch[$i] = "UNKNOWN";}
                    echo $batch[$i];
                    ?></td>
                    <td><?php
                    if (!array_key_exists($i, $semester)) {$semester[$i] = "UNKNOWN";}
                    echo $semester[$i];
                    ?></td>
                    <td><?php
                    if (!array_key_exists($i, $course)) {$course[$i] = "UNKNOWN";}
                    echo $course[$i]; 
                    ?></td>
                    <td><?php
                    if (!array_key_exists($i, $title)) {$title[$i] = "UNKNOWN";}
                    echo $title[$i];
                    ?></td>
                    <td><input name="units<?php echo $i; ?>" type="number" value="<?php
                    if (!array_key_exists($course[$i], $units)) {$units[$course[$i]] = "UNKNOWN"; $nan = 1;}
                    echo $units[$course[$i]];
                    $totalUnit .= $units[$course[$i]];
                    ?>"></td>
                    <td><?php
                    if (!array_key_exists($i, $grade)) {$grade[$i] = "UNKNOWN";}
                    switch ($grade[$i]) {
                        case "A":
                            $gradeUnit = 5 * (int)$units[$course[$i]];
                        break;
                        case "B":
                            $gradeUnit = 4 * (int)$units[$course[$i]];
                        break;
                        case "C":
                            $gradeUnit = 3 * (int)$units[$course[$i]];
                        break;
                        case "D":
                            $gradeUnit = 2 * (int)$units[$course[$i]];
                        break;
                        case "E":
                            $gradeUnit = 1 * (int)$units[$course[$i]];
                        break;
                        case "F":
                            $gradeUnit = 0 * (int)$units[$course[$i]];
                        break;
                    }
                    $totalGradeByUnit .= $gradeUnit;
                    echo $grade[$i];
                    ?></td>
                    <!-- <td><?php echo $i; ?></td> -->
                    <td><?php echo $i; ?></td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
    </form>
</div>
<button>CALCULATE CGPA!</button>
<br><hr>
<?php
echo $totalGradeByUnit;
echo $totalUnit;
if ($nan === 1) {echo "<script>alert('Can not fetch credit unit of one or more course(s) on your result please edit it manually and click on calculate CGPA button')</script>";}
} ?>

<form enctype="multipart/form-data" action="v2.php" method="POST">
    <label>Upload Result</label>
    <input type="file" name="res"><br>
    <label>ID</label>
    <input type="text" name="id">
    <button id="" class="btn btn-danger" type="submit">SUBMIT</button>
</form>