<?php
//index.php

$error = '';
$pr_id = '';
$customer_name = '';
$kb_available = '';
$issue_summary = '';

function jj_readcsv($filename, $header=false) {
    $handle = fopen($filename, "r");
    echo '<table>';
    //display header row if true
    if ($header) {
        $csvcontents = fgetcsv($handle);
        echo '<tr>';
        foreach ($csvcontents as $headercolumn) {
            echo "<th>$headercolumn</th>";
        }
        echo '</tr>';
    }
    // displaying contents
    while ($csvcontents = fgetcsv($handle)) {
        echo '<tr>';
        foreach ($csvcontents as $column) {
            echo "<td>$column</td>";
        }
        echo '</tr>';
    }
    echo '</table>';
    fclose($handle);
    }

function readSpecificRow($filepath, $prid ){
    $row = 1;
    if(($handle = fopen($filepath, "r")) !== FALSE){
        $flag = FALSE;
        while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
            $num = count($data);
            // echo "<p> $num fields in line $row: <br /></p>\n";
            // $csv[]=$data;
            $row++;
            if($data[1] == $prid){
                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
                $flag = TRUE;
            } else {
                $flag = FALSE;
            }
        }
        if ($flag == FALSE){
            echo "Entered PR Number [", $prid,"] is not available in the database";
        }
    }
    
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $num = $_POST['prnumber'];
    if(!preg_match("/^\d+$/",$num))
  // if(!preg_match("/^[a-zA-Z ]*$/",$pr_id))
    {
        $error .= '<p><label class="text-danger">Only Numerics are allowed in PR Number Field</label></p>';
    }
    if (empty($num)) {
        echo "PR Number is empty";
    } else {
        echo  $num . "<br />\n";
    }
}

// Method to check Retrieve data    
if(isset($_POST['prnumber']))
{
    // Getting the value of button
    $btnValue = $_POST['prnumber'];
    
    // Sending Response
    if (!empty($btnValue)) readSpecificRow("contact_data.csv", $btnValue);
}

function clean_text($string)
{
 $string = trim($string);
 $string = stripslashes($string);
 $string = htmlspecialchars($string);
 return $string;
}

if(isset($_POST["submit"]))
{
 if(empty($_POST["pr_id"]))
 {
  $error .= '<p><label class="text-danger">Please Enter your PR ID</label></p>';
 }
 else
 {
  $pr_id = clean_text($_POST["pr_id"]);
  if(!preg_match("/^\d+$/",$pr_id))
  // if(!preg_match("/^[a-zA-Z ]*$/",$pr_id))
  {
   $error .= '<p><label class="text-danger">Only Numerics are allowed in PR ID Field</label></p>';
  }
 }
 if(empty($_POST["customer_name"]))
 {
  $error .= '<p><label class="text-danger">Customer name is required</label></p>';
 }
 else
 {
  $customer_name = clean_text($_POST["customer_name"]);
 }
 if(empty($_POST["escalation"]))
 {
  $error .= '<p><label class="text-danger">Please select escalation colour</label></p>';
 }
 else
 {
  $escalation = clean_text($_POST["escalation"]);
 }
 if(empty($_POST["analysis_quarter"]))
 {
  $error .= '<p><label class="text-danger">Please select analysis quarter</label></p>';
 }
 else
 {
  $analysis_quarter = clean_text($_POST["analysis_quarter"]);
 }
 if(empty($_POST["issue_summary"]))
 {
  $error .= '<p><label class="text-danger">Issue Summary is required</label></p>';
 }
 else
 {
  $issue_summary = clean_text($_POST["issue_summary"]);
 }
 if(!empty($_POST["kb_available"])) 
 {
  $kb_available = clean_text($_POST["kb_available"]);
 }


 if($error == '')
 {
  $file_open = fopen("contact_data.csv", "a");
  $no_rows = count(file("contact_data.csv"));
  if($no_rows > 1)
  {
   $no_rows = ($no_rows - 1) + 1;
  }
  $form_data = array(
   'sr_no'  => $no_rows,
   'pr_id'  => $pr_id,
   'customer'  => $customer_name,
   'escalation' => $escalation,
   'analysis quarter' => $analysis_quarter,
   'issue summary' => $issue_summary,
   'kb available' => $kb_available
  );

  fputcsv($file_open, $form_data);
  $error = '<label class="text-success">Thank you for submitting your response.</label>';
  $pr_id = '';
  $customer_name = '';
  $escalation = '';
  $analysis_quarter = '';
  $kb_available = '';
  $issue_summary = '';
 }
}


?>
<!DOCTYPE html>
<html>
 <head>
  <title>VCF RRT SR PR ANALYSIS FORM</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <br />
  <div class="container">
   <h2 align="center">VCF RRT SR PR ANALYSIS FORM</h2>
   <br />
   <div class="col-md-6" style="margin:0 auto; float:none;">
    <form method="post">
     <!-- <h3 align="center">Analysis Form</h3> -->
     <br />
     <?php echo $error; ?>
     <div class="form-group">
      <label>PR ID</label>
      <input type="text" name="pr_id" placeholder="Enter PR ID" class="form-control" required="" value="<?php echo $pr_id; ?>" />
     </div>
     <div class="form-group">
      <label>Customer Name</label>
      <input type="text" name="customer_name" class="form-control" placeholder="Enter Customer Name" value="<?php echo $customer_name; ?>"/>
     </div>
     <div class="form-group">
     <label>Escalation Colour</label>   
     <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="escalation" id="yellow" value="Yellow">
                                    <label class="form-check-label" for="yellow">Yellow</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="escalation" id="orange" value="Orange">
                                    <label class="form-check-label" for="orange">Orange</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="escalation" id="purple" value="Purple">
                                    <label class="form-check-label" for="purple">Purple</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="escalation" id="red" value="Red">
                                    <label class="form-check-label" for="red">Red</label>
                                </div><div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="escalation" id="none" value="None">
                                    <label class="form-check-label" for="none">None</label>
                                </div>
      </div>

      <div class="form-group">
      <label>SR PR Analysis Quarter</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="analysis_quarter" id="fy23q1" value="FY23 Q1">
                                    <label class="form-check-label" for="fy23q1">FY23 Q1</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="analysis_quarter" id="fy23q1" value="FY23 Q2">
                                    <label class="form-check-label" for="fy23q2">FY23 Q2</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="analysis_quarter" id="fy23q1" value="FY23 Q3">
                                    <label class="form-check-label" for="fy23q3">FY23 Q3</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="analysis_quarter" id="fy23q1" value="FY23 Q4">
                                    <label class="form-check-label" for="fy23q4">FY23 Q4</label>
                                </div>
        </div> 

    <div class="form-row">
     
     <div class="form-group">
      <label>Issue Summary</label>
      <textarea name="issue_summary" class="form-control"  placeholder="Enter Issue Summary"><?php echo $issue_summary; ?></textarea>
     </div>
     <div class="form-group">
      <label>KB Available</label>
      <input type="text" name="kb_available" class="form-control" placeholder="Enter KB Details" value="<?php echo $kb_available; ?>" />
     </div>
     </div>
     <p>
        <div class="form-group" align="center">
            <input type="submit" name="submit" class="btn btn-info" value="Submit" />
            <input type="reset" class="btn btn-info" Value="Reset" />
            <input type="button" class="btn btn-info" onclick="window.location.href = 'http://127.0.0.1:8000/getdata.php';" value="Database"/>
            <!--a href='index1.php?retrieve=true' class="btn btn-info">Retrieve Data</a-->           
        </div>
     </p>
    </form>
   </div>
  </div>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  PR NUMBER: <input type="text" name="prnumber">
  <input type="submit" class="btn btn-info">

 </body>
</html>