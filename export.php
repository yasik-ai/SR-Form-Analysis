<?php  
 //export.php  
 header('Content-Type: application/xls');  
 header('Content-disposition: attachment; filename=SR_PR_Analysis.xls');  
 echo $_GET["data"];  
 ?>