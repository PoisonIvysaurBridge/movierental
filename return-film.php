<?php include 'base.php' ?>

<?php startblock('content') ?>
		<?php
			require_once('mysql_connect.php');
            $query = "SELECT C.CUSTOMER_ID, FIRST_NAME, LAST_NAME 
                      FROM CUSTOMER C
                      JOIN RENTAL R ON R.CUSTOMER_ID = C.CUSTOMER_ID
                      WHERE R.RETURN_DATE IS NULL ORDER BY 1";
            $result = mysqli_query($dbc, $query);
            if (!$result) {
                echo mysqli_error($dbc);
            }
            $message = NULL;
            if (isset($_POST['done'])){
                // insert payment stuffs
                $date = date("Y-m-d H:i:s");
                foreach ($_SESSION['inventoryIDs'] as $key => $value) {
                    $amount = $_SESSION['penalties'][$key];
                    if($amount > 0){
                        $query = "SELECT RENTAL_ID  FROM RENTAL WHERE INVENTORY_ID = '".$value."' ORDER BY RETURN_DATE DESC LIMIT 1";
                        $result = mysqli_query($dbc, $query);
                        if (!$result) {
                            echo mysqli_error($dbc);
                        } 
                        else {
                            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            $rental_id = $row['RENTAL_ID'];
                            $customer_id = $_SESSION['customerID'];

                            $query = "INSERT INTO PAYMENT(PAYMENT_ID, CUSTOMER_ID, STAFF_ID, RENTAL_ID, AMOUNT, PAYMENT_DATE, LAST_UPDATE)
                                        VALUES('0', '{$customer_id}', '{$_SESSION['user']}', '{$rental_id}', '{$amount}', '{$date}', '{$date}')";
                            $result = mysqli_query($dbc, $query);
                            if (!$result) {
                                echo mysqli_error($dbc);
                            } 
                            else{
                                $message .= "<p> Penalty payment for Rental #{$rental_id} acknowledged!</p>";
                                $paymentReady = 1;
                            }
                        }
                    }
                    
                }
                
            }
            //if (isset($_POST['done'])){
                unset($_SESSION['film']);
                unset($_SESSION['filmID']);
                unset($_SESSION['customerID']);
                unset($_SESSION['inventoryIDs']);
                unset($_SESSION['penalties']);
                $_SESSION['filmctr'] = 0;
        ?>
		<div class="w3-container"  style="margin: 0 30px;" >
            <h1 style="text-align: center;">Return Movie Film</h1>
            
            <div id="custSelect" class="w3-half">
            <form action="return-film-details.php" method="post">
                Customer: <select name='customer'> 
                            <?php
                            
                                    while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                        echo "
                                            <option value=\"{$row['CUSTOMER_ID']}\">ID #{$row['CUSTOMER_ID']} - {$row['LAST_NAME']}, {$row['FIRST_NAME']}</option>
                                        ";
                                    }
                                
                            ?>
                        </select> <br>
                <input type = 'submit' class="w3-button w3-teal w3-round" name='submit' style="margin: 50px 0 0 0" value = 'Submit'><br>
            </form>
            </div>
		</div>
        
<?php endblock() ?>