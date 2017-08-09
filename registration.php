<?php include 'base.php' ?>

<?php startblock('content') ?>
    <script src="jquery-3.2.1.min.js"></script>
    <?php
        require_once('mysql_connect.php');
        //echo $_SESSION['user'];
        //echo $_SESSION['storeID'];
    ?>
    
    <div class="w3-container"  style="margin: 0 30px;" >
        <h1 style="text-align: center;">New Customer Registration</h1>
        <div style="float:left; width: 60%;">
        <form action="#" method="post">
            First Name: <input type = 'text' name = 'firstname' required> <br>
            Last Name: <input type = 'text' name = 'lastname' required> <br>
            Email Address: <input type ='text' name = 'email'> <br>

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
            
            City: <select name="city" required onchange="dynamicSelect('district.php', this.value)">
                    <?php
                        $query = 'SELECT CITY_ID, CITY FROM CITY';
                        $result = mysqli_query($dbc, $query);
                        if (!$result){
                            echo mysqli_error($dbc);
                        }
                        
                        else {
                            while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                echo "<option value ="."{$row['CITY_ID']}".">"."{$row['CITY']}"."</option>";
                            }
                        }
                        
                    ?>
                </select><br>
            <div id="change">
            Home District: <!--<input type = 'text' name = 'district' required> <br>-->
            <select name="district" class="form-control" required>
            </select><br>
        
            Postal Code: <!--<input type = 'number' name = 'postal' min="1"> <br>-->
            <select name="postal" class="form-control" required>
            </select><br>
            </div>

            Phone Number: <input type = 'number' name ='phone' min="1" required> <br>
            <input type = 'submit' class="w3-button w3-teal w3-round" name="register" value = 'Register'><br>

        </form>
        </div>
        <?php
            if (isset($_POST['register'])){
                $message = NULL;

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

                $address1 = $_POST['address1'];
                /*
                // checking for existing address
                    $query = "SELECT ADDRESS_ID, ADDRESS FROM ADDRESS WHERE ADDRESS='{$address1}'";
                    $result=mysqli_query($dbc,$query);
                    $existing = 0;
                    if (!$result) {
                        echo mysqli_error($dbc);
                    }
                    if ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $existing = 1;
                        $addressID = $row['ADDRESS_ID'];
                    }
                    else{
                        $query = "SELECT ADDRESS_ID FROM ADDRESS ORDER BY ADDRESS_ID DESC LIMIT 1";
                        $result=mysqli_query($dbc,$query);
                        if (!$result) {
                            echo mysqli_error($dbc);
                        }
                        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
                        $addresID = $row['ADDRESS_ID'] + 1;
                    }
                    */
                if (!empty($_POST['address2'])){        // address 2
                    $address2 = $_POST['address2'];
                }
                else{
                    $address2 = NULL;
                }

                $city = $_POST['city'];

                if (!empty($_POST['district'])){        // postal
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
                    $date = date("Y-m-d H:i:s");
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
                            $message .= "<b><p>New address details added! </b>";
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
                        

                        // INSERTING INTO CUSTOMER TABLE
                        $query = "INSERT INTO CUSTOMER(CUSTOMER_ID, STORE_ID, FIRST_NAME, LAST_NAME, EMAIL, ADDRESS_ID, ACTIVE, CREATE_DATE, LAST_UPDATE)
                                    VALUES('0', '{$_SESSION['storeID']}', '{$firstname}', '{$lastname}', '{$email}', '{$addressID}', '1', '{$date}', '{$date}')";
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
    <script>
    function dynamicSelect(ajaxPage, city){
    $.ajax({
        type: "GET",
        url: ajaxPage,
        data: "id=" + city,
        dataType: "html",
        success: function(result){
        $('#change').html(result);
       // $('#ajaxPostal').html(result);
        }
    });
    }
    
    </script>
<?php endblock() ?>