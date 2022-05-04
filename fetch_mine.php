<?php

//fetch.php

$filename = "srpr_data.csv";
if(file_exists($filename))
{
 $file_data = fopen($filename, 'r');
 $column = fgetcsv($file_data);
 while($row = fgetcsv($file_data))
 {
  $row_data[] = array(
   'sr_no'  => $row[0],
   'student_phone'  => $row[1],
   'student_phone2'  => $row[2]
  );
 }
 $output = array(
  'column'  => $column,
  'row_data'  => $row_data
 );

 echo json_encode($output);

}

?>