<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--
    <title>SB Admin 2 - Bootstrap Admin Theme</title>
    -->
    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- MetisMenu CSS --><!--
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    -->
    <!-- DataTables CSS -->
    <link href="vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<?php include 'base.php' ?>
<style>.w3-bar-block a{text-decoration:none;}</style>
<?php startblock('content') ?>
    <script src="jquery-3.2.1.min.js"></script>
    <?php
        require_once('mysql_connect.php');
        
    ?>
    
    <div class="w3-container"  style="margin: 0 30px;" >
        <h1 style="text-align: center;">New Customer Registration</h1>
        <div class="w3-container" style="text-align:center;">
        <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Existing Addresses
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Primary Address</th>
                                        <th>Secondary Address</th>
                                        <th>District</th>
                                        <th>Postal Code</th>
                                        <th>Phone</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $addressRs = $dbc->query("SELECT ADDRESS_ID, ADDRESS, ADDRESS2, DISTRICT, POSTAL_CODE, PHONE FROM ADDRESS WHERE CITY_ID=".$_POST['city']." GROUP BY 2, 3, 4, 5 ORDER BY ADDRESS");
                                    if (!$addressRs) {
                                        echo mysqli_error($dbc);
                                    }
                                    while($row = $addressRs->fetch_assoc()){
                                        echo "
                                        <tr>
                                            <td>{$row['ADDRESS']}</td>
                                            <td>{$row['ADDRESS2']}</td>
                                            <td>{$row['DISTRICT']}</td>
                                            <td>{$row['POSTAL_CODE']}</td>
                                            <td>{$row['PHONE']}</td>  
                                            <td align='center'><button class=\"w3-button w3-teal w3-round\" onclick=\"dynamicSelect('existing.php', {$row['ADDRESS_ID']})\">SELECT</button></td>
                                        </tr>
                                        ";
                                    }
                                ?>
                            </tbody>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
        <!-- /#page-wrapper -->
        </div>
        </div>
        <div style="float:left; width: 60%;">
        <form action="#" method="post">
            <input type="hidden" name="firstname" value="<?php echo $_POST['firstname']; ?>">
            <input type="hidden" name="lastname" value="<?php echo $_POST['lastname']; ?>">
            <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
            <input type="hidden" name="city" value="<?php echo $_POST['city'] ?>">

            <div id="change">
                Primary Home Address: 
                <input list="address1" name="address1"  required>
                <datalist id="address1">
                    <?php
                        $query = 'SELECT ADDRESS_ID, ADDRESS FROM ADDRESS';
                        $result = mysqli_query($dbc, $query);
                        if (!$result){
                            echo mysqli_error($dbc);
                        }
                        
                        else {
                            while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                echo "<option value=\"{$row['ADDRESS']}\">";
                            }
                        }
                        
                    ?>
                </datalist><br>

                Secondary Home Address (leave blank if none): 
                <input list="address2" name="address2">
                <datalist id="address2">
                    <?php
                        $query = 'SELECT ADDRESS_ID, ADDRESS FROM ADDRESS';
                        $result = mysqli_query($dbc, $query);
                        if (!$result){
                            echo mysqli_error($dbc);
                        }
                        
                        else {
                            while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                echo "<option value=\"{$row['ADDRESS']}\">";
                            }
                        }
                        
                    ?>
                </datalist><br>
                
                Home District: <input type = 'text' name = 'district' required> <br>
            
                Postal Code: <input type = 'number' name = 'postal' min="1"> <br>
                
                Phone Number: <input type = 'number' name ='phone' min="1" required> <br>
                <input type="hidden" name="addressID" value="NULL">
            </div>
            <input type = 'submit' class="w3-button w3-teal w3-round" name="register" value = 'Register'><br>
        </form>
        </div>
        <?php
            if (isset($_POST['register'])){
                $message = NULL;
                
                $date = date("Y-m-d H:i:s");
                $addressID = (int) $_POST['addressID'];
                //var_dump($addressID);

                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];

                    if (!empty($_POST['email'])){        // email
                        $email = $_POST['email'];
                        // checking for email duplicates
                        $query = "SELECT EMAIL FROM CUSTOMER WHERE EMAIL='{$email}'";
                        $result=mysqli_query($dbc,$query);

                        if (!$result) {
                            echo mysqli_error($dbc);
                        }
                        if ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $message.="<b><p>Email {$email} already exists! Please input another!";
                        }
                    }
                    else{
                        $email = NULL;
                    }

                    $city = $_POST['city'];


                if($addressID == NULL){// IF ADDRESS NOT YET EXISTING
                    
                    $address1 = $_POST['address1'];
                    
                    if (!empty($_POST['address2'])){        // address 2
                        $address2 = $_POST['address2'];
                    }
                    else{
                        $address2 = NULL;
                    }

                    if (!empty($_POST['district'])){        // district
                        $district = $_POST['district'];
                    }
                    else{
                        $district = NULL;
                    }

                    if (!empty($_POST['postal'])){        // postal
                        $postal = $_POST['postal'];
                    }
                    else{
                        $postal = NULL;
                    }

                    $phone = $_POST['phone'];

                    if(!isset($message)){   
                        
                        $addressID = 0;

                        // INSERTING INTO ADDRESS TABLE
                        /*
                        if($existing){
                            $query = "UPDATE ADDRESS(ADDRESS_ID, ADDRESS, ADDRESS2, DISTRICT, CITY_ID, POSTAL_CODE, PHONE, LAST_UPDATE)
                                        SET     ADDRESS2 = '{$address2}',
                                                DISTRICT = '{$district}',
                                                CITY_ID = '{$city}',
                                                POSTAL_CODE = '{$postal}',
                                                PHONE = '{$phone}',
                                                LAST_UPDATE = '{$date}'
                                        WHERE   ADDRESS_ID = '{$addressID}'";
                                        
                            $result = mysqli_query($dbc, $query);
                            if (!$result) {
                                echo mysqli_error($dbc);
                            } 
                            else {
                                $message .= "<b><p>Existing address details updated! </b>";
                            }
                        }
                        else{*/
                        try{
                            $dbc->autocommit(FALSE); // i.e., start transaction


                            // INSERTING INTO ADDRESS TABLE
                            $query = "INSERT INTO ADDRESS(ADDRESS_ID, ADDRESS, ADDRESS2, DISTRICT, CITY_ID, POSTAL_CODE, PHONE, LAST_UPDATE)
                                        VALUES('{$addressID}', '{$address1}', '{$address2}', '{$district}', '{$city}', '{$postal}', '{$phone}', '{$date}')";
                            $result = $dbc->query($query);//$result = mysqli_query($dbc, $query);
                            if (!$result) {
                                //echo mysqli_error($dbc);
                                $result->free();
                                throw new Exception($dbc->error);
                            } 
                            else {
                                $message .= "<b><p>New address added! </b>";
                            }

                            // JUST READING FROM TABLE
                            $query = "SELECT ADDRESS_ID FROM ADDRESS ORDER BY ADDRESS_ID DESC LIMIT 1";
                            $result=mysqli_query($dbc,$query);
                            $result = $dbc->query($query);
                            if (!$result) {
                                echo mysqli_error($dbc);
                            }
                            $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
                            $addressID = $row['ADDRESS_ID'];

                            $dbc->commit();
                            $dbc->autocommit(TRUE); // i.e., end transaction
                        }
                        catch(Exception $e){
                            // before rolling back the transaction, you'd want
                            // to make sure that the exception was db-related
                            $dbc->rollback(); 
                            $dbc->autocommit(TRUE); // i.e., end transaction   
                        }
                    }
                }
                if(!isset($message)){   
                    try{
                        $dbc->autocommit(FALSE); // i.e., start transaction

                        // INSERTING INTO CUSTOMER TABLE
                        $query = "INSERT INTO CUSTOMER(CUSTOMER_ID, STORE_ID, FIRST_NAME, LAST_NAME, EMAIL, ADDRESS_ID, ACTIVE, CREATE_DATE, LAST_UPDATE)
                                    VALUES(0, '{$_SESSION['storeID']}', '{$firstname}', '{$lastname}', '{$email}', {$addressID}, '1', '{$date}', '{$date}')";
                        $result = $dbc->query($query);//$result = mysqli_query($dbc, $query);
                        if (!$result) {
                            echo mysqli_error($dbc);
                            $result->free();
                            throw new Exception($dbc->error);
                        } 
                        else {
                            $message .= "<b><p>Customer details added! </b>";
                        }

                        // our SQL queries have been successful. commit them
                        // and go back to non-transaction mode.

                        $dbc->commit();
                        $dbc->autocommit(TRUE); // i.e., end transaction
                    }
                    catch(Exception $e){
                        // before rolling back the transaction, you'd want
                        // to make sure that the exception was db-related
                        $dbc->rollback(); 
                        $dbc->autocommit(TRUE); // i.e., end transaction   
                    }
                
                }
                if(isset($message)){   
                    $message .= "<form method=\"post\" action=\"registration.php\">
                                        <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">
                                </form>";
                    echo '<div class="w3-grey w3-padding-16" style="margin: 0 0 20px 0; padding:20px; float:left; width:33%; border-radius: 10px;">';
                    echo '<p><b>'.$message. '</b></p>';
                    echo '</div>';
                }
            }
        ?>

    </div>
    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>


    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>
    <script>
    function dynamicSelect(ajaxPage, id){
    $.ajax({
        type: "POST",
        url: ajaxPage,
        data: "id=" + id,
        dataType: "html",
        success: function(result){
        $('#change').html(result);
       // $('#ajaxPostal').html(result);
        }
    });
    }
    
    </script>
<?php endblock() ?>