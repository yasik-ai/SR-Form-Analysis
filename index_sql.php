<?php
$error = "";
$pr_id = "";
$customer_name = "";
$escalation_color = "";
$quarter = "";
$issue_summary = "";
$kb_available = "";
$issue_type = "";
function function_alert($message) {
    // Display the alert box
    echo "<script>alert('$message');</script>";
}
function clean_text($string) {
    $string = trim($string);
    $string = stripslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}
function create_sql_connection() {
    $host = "localhost";
    $dbUserName = "root";
    $dbpassword = "";
    $dbname = "vcfhawkeye";
    $conn = new mysqli($host, $dbUserName, $dbpassword, $dbname);
    return $conn;
}
if (isset($_POST["retrieve"])) {
    // collect value of input field
    $num = $_POST["prnumber"];
    if (empty($_POST["prnumber"])) {
        $error.= '<p><label class="text-danger">Please Enter PR Number to Retrieve the details.</label></p>';
        echo "PR Number is empty";
    } elseif (!preg_match("/^\d+$/", $num)) {
        $error.= '<p><label class="text-danger">Only Numerics are allowed in PR Number Field</label></p>';
    } else {
        if (!empty($num)) {
            $pr_id = $num;
            echo "Entered PR Num : ", $num . "<br />\n";
            //Create SQL connection and query the data
            $conn = create_sql_connection();
            $select_query = "SELECT * FROM prdata WHERE bug_id='$pr_id';";
            $result = mysqli_query($conn, $select_query);
            $num_rows = mysqli_num_rows($result);
            if ($num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $customer_name = $row["customer"];
                    $escalation_color = $row["escalation_colour"];
                    $quarter = $row["quarter"];
                    $issue_summary = $row["issue_summary"];
                    $kb_available = $row["kb_available"];
                    $issue_type = $row["issue_type"];
                }
            } else {
                $error.= '<p><label class="text-danger">Record not found for PR #' . $pr_id . ' in database.</label></p>';
            }
        }
    }
}
if (isset($_POST["submit"])) {
    // Creating a MySQL connection
    if (empty($_POST["pr_id"])) {
        $error.= '<p><label class="text-danger">1.Please Enter your PR ID</label></p>';
    } else {
        $pr_id = clean_text($_POST["pr_id"]);
        if (!preg_match("/^\d+$/", $pr_id)) {
            $error.= '<p><label class="text-danger">Only Numerics are allowed in PR ID Field</label></p>';
        }
    }
    if (empty($_POST["customer_name"])) {
        $error.= '<p><label class="text-danger">2.Customer name is required</label></p>';
    } else {
        $customer_name = clean_text($_POST["customer_name"]);
    }
    if (empty($_POST["escalation"])) {
        $error.= '<p><label class="text-danger">3.Please select escalation colour</label></p>';
    } else {
        $escalation_color = clean_text($_POST["escalation"]);
    }
    if (empty($_POST["analysis_quarter"])) {
        $error.= '<p><label class="text-danger">4.Please select analysis quarter</label></p>';
    } else {
        $quarter = clean_text($_POST["analysis_quarter"]);
    }
    if (empty($_POST["issue_summary"])) {
        $error.= '<p><label class="text-danger">5.Issue Summary is required</label></p>';
    } else {
        $issue_summary = clean_text($_POST["issue_summary"]);
    }
    if (!empty($_POST["kb_available"])) {
        $kb_available = clean_text($_POST["kb_available"]);
    }
    $conn = create_sql_connection();
    if (mysqli_connect_error()) {
        die('Connect Error(' . mysqli_conect_errno() . ')' . mysqli_connect());
    } else {
        $insert_query = "INSERT INTO prdata(bug_id, customer, escalation_colour, quarter,
            issue_summary, kb_available, issue_type) VALUES ('$pr_id', '$customer_name', '$escalation_color', '$quarter',
            '$issue_summary', '$kb_available', '$issue_type');";
        $result = mysqli_query($conn, "SELECT * FROM prdata WHERE bug_id='$pr_id'");
        $num_rows = mysqli_num_rows($result);
        $update_query = "UPDATE prdata SET customer = '$customer_name', escalation_colour = '$escalation_color', quarter = '$quarter',
        issue_summary = '$issue_summary', kb_available = '$kb_available', issue_type = '$issue_type'
WHERE bug_id = '$pr_id';";
        if ($num_rows > 0) {
            //Update Record
            if (mysqli_query($conn, $update_query)) {
                echo 'Record Updated.';
            } else {
                echo 'error';
            }
        } else {
            if (mysqli_query($conn, $insert_query)) {
                echo 'New Record Inserted!.';
            } else {
                echo 'error';
            }
        }
        mysqli_close($conn);
        $error = '<label class="text-success">Thank you for submitting your response.</label>';
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
            <form method="post" action='index_sql.php'>
               <br />
               <?php echo $error; ?>
               <div class="form-group">
                  <label>1. PR ID<span  style="color:red"> *</span></label>
                  <input type="text" name="pr_id" id ="pr_id" placeholder="Enter PR ID" class="form-control" required="" value="<?php echo $pr_id; ?>" />
                </div>
               <div class="form-group">
                  <label>2. Customer Name<span  style="color:red"> *</span></label>
                  <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Enter Customer Name" value="<?php echo $customer_name; ?>"/>
               </div>
               <div class="form-group">
                  <label>3. Escalation Colour<span  style="color:red"> *</span></label>   
                  <div class="form-check form-check-inline">

                     <input class="form-check-input" type="radio" name="escalation" id="yellow" value="Yellow"<?php if ($escalation_color == "Yellow") echo "checked"; ?>/>
                     <label class="form-check-label" for="yellow">Yellow</label>
                  </div>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="radio" name="escalation" id="orange" value="Orange"<?php if ($escalation_color == "Orange") echo "checked"; ?>/>
                     <label class="form-check-label" for="orange">Orange</label>
                  </div>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="radio" name="escalation" id="purple" value="Purple"<?php if ($escalation_color == "Purple") echo "checked"; ?>/>
                     <label class="form-check-label" for="purple">Purple</label>
                  </div>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="radio" name="escalation" id="red" value="Red"<?php if ($escalation_color == "Red") echo "checked"; ?>/>
                     <label class="form-check-label" for="red">Red</label>
                  </div>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="radio" name="escalation" id="none" value="None"<?php if ($escalation_color == "None") echo "checked"; ?>/>
                     <label class="form-check-label" for="none">None</label>
                  </div>
               </div>
               <div class="form-group">
                  <label>4. SR PR Analysis Quarter<span  style="color:red"> *</span></label>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="radio" name="analysis_quarter" id="fy23q1" value="FY23 Q1"<?php if ($quarter == "FY23 Q1") echo "checked"; ?>/>
                     <label class="form-check-label" for="fy23q1">FY23 Q1</label>
                  </div>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="radio" name="analysis_quarter" id="fy23q1" value="FY23 Q2"<?php if ($quarter == "FY23 Q2") echo "checked"; ?>/>
                     <label class="form-check-label" for="fy23q2">FY23 Q2</label>
                  </div>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="radio" name="analysis_quarter" id="fy23q1" value="FY23 Q3"<?php if ($quarter == "FY23 Q3") echo "checked"; ?>/>
                     <label class="form-check-label" for="fy23q3">FY23 Q3</label>
                  </div>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="radio" name="analysis_quarter" id="fy23q1" value="FY23 Q4"<?php if ($quarter == "FY23 Q4") echo "checked"; ?>/>
                     <label class="form-check-label" for="fy23q4">FY23 Q4</label>
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group">
                     <label>5. Issue Summary<span  style="color:red"> *</span></label>
                     <textarea name="issue_summary" id="issue_summary" class="form-control"  placeholder="Enter Issue Summary"><?php echo $issue_summary; ?></textarea>
                  </div>
                  <div class="form-group">
                     <label>6. KB Available</label>
                     <input type="text" name="kb_available" id="kb_available" class="form-control" placeholder="Enter KB Details" value="<?php echo $kb_available; ?>" />
                  </div>
               </div>
               <p>
               <div class="form-group" align="center">
                  <input type="submit" name="submit" class="btn btn-info" value="Submit"/>
                  <input type="reset" class="btn btn-info" Value="Reset" />
                  <input type="button" class="btn btn-info" onclick="window.location.href = 'display.php';" value="Database"/>
                  <button class="btn btn-info" onclick="window.location.href = 'index_sql.php';">Refresh</button>         
              </p>          
            </form>
           <!--form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>"-->
            <form method="post" action="#">
                 PR NUMBER : <input type="text" name="prnumber">
                 <input type="submit" class="btn btn-info" name ="retrieve" id="retrieve" value="Retrieve Data">
            </form>
   </body>
</html>

<script>

 $('#retrieve').click(function(){
  $.ajax({
   url:"get_bugdata.php",
   type: "get"
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