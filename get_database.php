
<!DOCTYPE html>
<html>
 <head>
  <title>SR PR Analysis Database</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <div class="container">
   <div class="table-responsive">
    <h1 align="center">SR PR Analysis Database</h1>
    <br />
    <div align="center">
     <button type="button" name="load_data" id="load_data" class="btn btn-info">Load Data</button>
     <input type="button" name="back" id="back_index1_test" class="btn btn-info" onclick="window.location.href = 'index1_test.php';" value="Home Page"/>
     <button class="btn btn-info" onclick="window.location.href = 'get_database.php';">Refresh</button>
    </div>
    <br />
    <div id="employee_table">
    </div>
   </div>
  </div>
 </body>
</html>

<script>
$(document).ready(function(){
 $('#load_data').click(function(){
  $.ajax({
   url:"srpr_data.csv",
   dataType:"text",
   success:function(data)
   {
    var pr_data = data.split(/\r?\n|\r/);
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
 
});
</script>