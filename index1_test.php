<?php
//index.php

$error = "";
$pr_id = "";
$customer_name = "";
$kb_available = "";
$issue_summary = "";

function jj_readcsv($filename, $header = false)
{
    $handle = fopen($filename, "r");
    echo "<table>";
    //display header row if true
    if ($header) {
        $csvcontents = fgetcsv($handle);
        echo "<tr>";
        foreach ($csvcontents as $headercolumn) {
            echo "<th>$headercolumn</th>";
        }
        echo "</tr>";
    }
    // displaying contents
    while ($csvcontents = fgetcsv($handle)) {
        echo "<tr>";
        foreach ($csvcontents as $column) {
            echo "<td>$column</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    fclose($handle);
}

function readSpecificRow($filepath, $prid)
{
    $row = 1;
    if (($handle = fopen($filepath, "r")) !== false) {
        $flag = false;
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $num = count($data);
            // echo "<p> $num fields in line $row: <br /></p>\n";
            // $csv[]=$data;
            $row++;
            if ($data[1] == $prid) {
                $flag = true;
                echo "<table>";
                for ($c = 0; $c < $num; $c++) {
                    echo  "<tr><td>". $data[$c] ."</td><td>" ; //"<br />\n";
                }
                echo "</table>";
            } 
           
        }
        if ($flag == false) {
            echo "Entered PR Number #".
                $prid.
                " is not available in the database";
        }
    }
}


// if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["retrieve"])) {
    // collect value of input field
    $num = $_POST["prnumber"];
    if (empty($_POST["prnumber"])) {
        $error .=
            '<p><label class="text-danger">Please Enter PR Number to Retrieve the details</label></p>';
    }
    elseif (!preg_match("/^\d+$/", $num)) {
        // if(!preg_match("/^[a-zA-Z ]*$/",$pr_id))
        $error .=
            '<p><label class="text-danger">Only Numerics are allowed in PR Number Field</label></p>';
    }
    if (empty($num)) {
        echo "PR Number is empty";
    } else {
        echo "Entered PR Num : ", $num . "<br />\n";
        // Method to check Retrieve data
        if (isset($_POST["prnumber"])) {
            // Getting the value of button
            $btnValue = $_POST["prnumber"];

            // Sending Response
            if (!empty($btnValue)) {
            readSpecificRow("srpr_data.csv", $btnValue);
             }
        }
    }
}

function function_alert($message) {
      
    // Display the alert box 
    echo "<script>alert('$message');</script>";
}

function clean_text($string)
{
    $string = trim($string);
    $string = stripslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}

if (isset($_POST["submit"])) {
    if (empty($_POST["pr_id"])) {
        $error .=
            '<p><label class="text-danger">1.Please Enter your PR ID</label></p>';
    } else {
        $pr_id = clean_text($_POST["pr_id"]);
        if (!preg_match("/^\d+$/", $pr_id)) {
            // if(!preg_match("/^[a-zA-Z ]*$/",$pr_id))
            $error .=
                '<p><label class="text-danger">Only Numerics are allowed in PR ID Field</label></p>';
        }
    }
    if (empty($_POST["customer_name"])) {
        $error .=
            '<p><label class="text-danger">2.Customer name is required</label></p>';
    } else {
        $customer_name = clean_text($_POST["customer_name"]);
    }
    if (empty($_POST["escalation"])) {
        $error .=
            '<p><label class="text-danger">3.Please select escalation colour</label></p>';
    } else {
        $escalation = clean_text($_POST["escalation"]);
    }
    if (empty($_POST["analysis_quarter"])) {
        $error .=
            '<p><label class="text-danger">4.Please select analysis quarter</label></p>';
    } else {
        $analysis_quarter = clean_text($_POST["analysis_quarter"]);
    }
    if (empty($_POST["issue_summary"])) {
        $error .=
            '<p><label class="text-danger">5.Issue Summary is required</label></p>';
    } else {
        $issue_summary = clean_text($_POST["issue_summary"]);
    }
    if (!empty($_POST["kb_available"])) {
        $kb_available = clean_text($_POST["kb_available"]);
    }

    if ($error == "") {
        $file_open = fopen("srpr_data.csv", "a");
        $no_rows = count(file("srpr_data.csv"));
        if ($no_rows > 1) {
            $no_rows = $no_rows - 1 + 1;
        }
        $form_data = [
            "sr_no" => $no_rows,
            "pr_id" => $pr_id,
            "customer" => $customer_name,
            "escalation" => $escalation,
            "analysis quarter" => $analysis_quarter,
            "issue summary" => $issue_summary,
            "kb available" => $kb_available,
        ];

        fputcsv($file_open, $form_data);
        $error =
            '<label class="text-success">Thank you for submitting your response.</label>';
        $pr_id = "";
        $customer_name = "";
        $escalation = "";
        $analysis_quarter = "";
        $kb_available = "";
        $issue_summary = "";
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
                  <label>1. PR ID<span  style="color:red"> *</span></label>
                  <input type="text" name="pr_id" placeholder="Enter PR ID" class="form-control" required="" value="<?php echo $pr_id; ?>" />
                </div>
               <div class="form-group">
                  <label>2. Customer Name<span  style="color:red"> *</span></label>
                  <input type="text" name="customer_name" class="form-control" placeholder="Enter Customer Name" value="<?php echo $customer_name; ?>"/>
               </div>
               <div class="form-group">
                  <label>3. Escalation Colour<span  style="color:red"> *</span></label>   
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
                  </div>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="radio" name="escalation" id="none" value="None">
                     <label class="form-check-label" for="none">None</label>
                  </div>
               </div>
               <div class="form-group">
                  <label>4. SR PR Analysis Quarter<span  style="color:red"> *</span></label>
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
                     <label>5. Issue Summary<span  style="color:red"> *</span></label>
                     <textarea name="issue_summary" class="form-control"  placeholder="Enter Issue Summary"><?php echo $issue_summary; ?></textarea>
                  </div>
                  <div class="form-group">
                     <label>6. KB Available</label>
                     <input type="text" name="kb_available" class="form-control" placeholder="Enter KB Details" value="<?php echo $kb_available; ?>" />
                  </div>
               </div>
               <p>
               <div class="form-group" align="center">
                  <input type="submit" name="submit" class="btn btn-info" value="Submit"/>
                  <input type="reset" class="btn btn-info" Value="Reset" />
                  <input type="button" class="btn btn-info" onclick="window.location.href = 'http://127.0.0.1:8000/get_database.php';" value="Database"/>
                  <button class="btn btn-info" onclick="window.location.href = 'http://127.0.0.1:8000/index1_test.php';">Refresh</button>
                  <!--a href='index1.php?retrieve=true' class="btn btn-info">Retrieve Data</a-->           
              </p>          
            </form>
           <!--form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>"-->
            <form method="post" action="#">
                 PR NUMBER : <input type="text" name="prnumber">
                 <input type="submit" class="btn btn-info" name ="retrieve"  value="Retrieve Data">
            </form>
   </body>
</html>

<script>

 $('#load_data').click(function(){
  $.ajax({
   url:"srpr_data.csv",
   dataType:"text",
   success:function(data)
   {
    var employee_data = data.split(/\r?\n|\r/);
    var table_data = '<table class="table table-bordered table-striped">';
    for(var count = 0; count<pr_data.length; count++)
    {
     var cell_data = pr_data[count].split(",");
     table_data += '<tr>';
     for(var cell_count=0; cell_count<cell_data.length; cell_count++)
     {
      if(count === 0)
      {
       table_data += '<th>'+cell_data[cell_count]+'</th>';
      }
      else
      {
       table_data += '<td>'+cell_data[cell_count]+'</td>';
      }
     }
     table_data += '</tr>';
    }
    table_data += '</table>';
    $('#employee_table').html(table_data);
   }
  });

 
});
</script>