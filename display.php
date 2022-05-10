<!DOCTYPE html>
<html>
<head>
    <title>SR PR ANALYSIS DATABASE</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
                    
<style>
table {
border-collapse: collapse;
width: 100%;
color: #588c7e;
font-family: monospace;
font-size: 25px;
text-align: left;
}
th {
background-color: #588c7e;
color: white;
}
tr:nth-child(even) {background-color: #f2f2f2}
</style>
</head>
<body>
<h1 align="center"><span  style="color:#588c7e">SR PR ANALYSIS DATABASE</span></h1>
<br>
<div align="center">
     <input type="button" name="back" id="back_home" class="btn btn-info" onclick="window.location.href = 'index_sql.php';" value="Home Page"/>
     <button class="btn btn-info" onclick="window.location.href = 'display.php';">Refresh</button>
     <button name="export" id="export" class="btn btn-success">Export</button>               
    </div>
</br>
<div class="table-responsive" id="pr_table">
<table class="table table-bordered">
    <tr>
        <th>bug_id</th>
        <th>customer</th>
        <th>escalation_colour</th>
        <th>quarter</th>
        <th>issue_summary</th>
        <th>kb_available</th>
        <th>issue_type</th>
    </tr>
<?php
$host = "localhost";
$dbUserName = "root";
$dbpassword = "";
$dbname = "vcfhawkeye";
$conn = new mysqli($host, $dbUserName, $dbpassword, $dbname);
if (mysqli_connect_error()) {
    die("Connect Error(" . mysqli_conect_errno() . ")" . mysqli_connect());
} else {
    $select_query = "SELECT * FROM prdata";
    $result = mysqli_query($conn, $select_query);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" .
                $row["bug_id"] .
                "</td><td>" .
                $row["customer"] .
                "</td><td>" .
                $row["escalation_colour"] .
                "</td><td>" .
                $row["quarter"] .
                "</td><td>" .
                $row["issue_summary"] .
                "</td><td>" .
                $row["kb_available"] .
                "</td><td>" .
                $row["issue_type"] .
                "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 Results";
    }
}

$conn-> close();

?>
</table>
</body>
</html>

<script>  
 $(document).ready(function(){  
      $('#export').click(function(){  
           var excel_data = $('#pr_table').html();  
           var page = "export.php?data=" + excel_data;  
           window.location = page;  
      });  
 }); 
 </script>
