<?php include 'base.php' ?>

<?php startblock('content') ?>
		<?php
			require_once('mysql_connect.php');
            $query = "SELECT C.CUSTOMER_ID, FIRST_NAME, LAST_NAME 
                      FROM CUSTOMER C
                      JOIN RENTAL R ON R.CUSTOMER_ID = C.CUSTOMER_ID
                      WHERE R.RETURN_DATE IS NULL 
                      GROUP BY 1
                      ORDER BY 3,2";
            $rscust = mysqli_query($dbc, $query);
            if (!$rscust) {
                echo mysqli_error($dbc);
            }
            $message = NULL;
            if (isset($_POST['done'])){
                // insert payment stuffs
                $date = date("Y-m-d H:i:s");
                foreach ($_SESSION['inventoryIDs'] as $key => $value) {
                    $amount = $_SESSION['penalties'][$key];
                    if($amount > 0){        // ONLY INSERTS TO PAYMENT TABLE WHEN THERE ARE PENALTIES
                        $query = "SELECT RENTAL_ID  FROM RENTAL WHERE INVENTORY_ID = '".$value."' ORDER BY RETURN_DATE DESC LIMIT 1";
                        $result = mysqli_query($dbc, $query);
                        if (!$result) {
                            echo mysqli_error($dbc);
                        } 
                        else {
                            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            $rental_id = $row['RENTAL_ID'];
                            $customer_id = $_SESSION['customerID'];
                            try{
                                $dbc->autocommit(FALSE); // i.e., start transaction
                                $query = "INSERT INTO PAYMENT(PAYMENT_ID, CUSTOMER_ID, STAFF_ID, RENTAL_ID, AMOUNT, PAYMENT_DATE, LAST_UPDATE)
                                            VALUES('0', '{$customer_id}', '{$_SESSION['user']}', '{$rental_id}', '{$amount}', '{$date}', '{$date}')";
                                $result = $dbc->query($query);//$result = mysqli_query($dbc, $query);
                                if (!$result) {
                                    echo mysqli_error($dbc);
                                    $result->free();
                                    throw new Exception($dbc->error);
                                } 
                                else{
                                    $message .= "<p> Penalty payment for Rental #{$rental_id} acknowledged!</p>";
                                    $paymentReady = 1;
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
                    }
                    
                }
                if(isset($message)){
                    $message .= "<input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">";
                    
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
                            
                                    while($row=mysqli_fetch_array($rscust,MYSQLI_ASSOC)){
                                        echo "
                                            <option value=\"{$row['CUSTOMER_ID']}\">ID #{$row['CUSTOMER_ID']} - {$row['LAST_NAME']}, {$row['FIRST_NAME']}</option>
                                        ";
                                    }
                                
                            ?>
                        </select> <br>
                <input type = 'submit' class="w3-button w3-teal w3-round" name='submit' style="margin: 50px 0 0 0" value = 'Submit'><br>
            </form>
            </div>
            <?php
                if(isset($message)){
                    $message = "<form method=\"post\" action=\"return-film.php\">".$message."</form>";
                    echo '<div class="w3-grey w3-padding-16" style="margin: 20px 90px; padding:20px; float:left; width:30%; border-radius: 10px;">';
                    echo '<p><b>'.$message. '</b></p>';
                    echo '</div>';
                }
            ?>
		</div>
        
<?php endblock() ?>