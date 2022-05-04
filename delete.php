<?php
    error_reporting( E_ALL );

    include("index1_test.php");

    $val = 6;

    /* What is the name of the $connection object ? */
    //$result = mysqli_query( $conn, "Select * from `test` where `testid`='$val'" );
   // $name=( $result ) ? mysqli_fetch_assoc( $result ) : false;
   $number=array(343435,343455);
   $customer=array('EMC','Barclays');

?>  
<html>
    <head>
        <title>Test Page</title>
    </head>
    <body>
        <?php
            if( !empty( $number ) ){
                echo "
                    <form>
                        <input type='text' name='pr_id' id='pr_id' placeholder='Enter PR ID' class='form-control' value='{$number[0]}'/>
                        <input type='text' name='pr_id' id='pr_id' placeholder='Enter PR ID' class='form-control' value='{$customer[1]}'/>
                    </form>";
            } else {
                echo "No such name exists";
            }
        ?>
