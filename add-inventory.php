<?php include 'base.php' ?>

<?php startblock('content') ?>
	<?php
        //session_start();
        require_once('mysql_connect.php');
        $query="SELECT FILM_ID, TITLE FROM FILM ORDER BY LAST_UPDATE DESC";
        $films=mysqli_query($dbc,$query);
		$query="SELECT STORE_ID, ADDRESS FROM STORE AS S JOIN ADDRESS AS A ON A.ADDRESS_ID = S.ADDRESS_ID";
		$stores=mysqli_query($dbc,$query);
    ?>
	<div class="w3-container"  style="margin: 0 30px;" >
		<h1 style="text-align: center;">Add Film Copy to Inventory</h1>

		<div class="w3-containter" style="float:left; width: 60%;">
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="usrform">
                Movie: <select name="film">
							<?php
								if (!$films) {
									echo mysqli_error($dbc);
								}
								else {
									while($row=mysqli_fetch_array($films,MYSQLI_ASSOC)){
										echo "
											<option value=\"{$row['FILM_ID']}\">Film #{$row['FILM_ID']} - {$row['TITLE']}</option>
                                        ";
									}
								}
							?>
							</select><br>
				
                  <!--Add New MOVIE Button -->
                  <input class="w3-button w3-teal w3-round" type="submit" name="submit" value="Add Film">
		    </form>
		</div>
		
		<?php

            $flag=0;
            if (isset($_POST['submit'])){
                $message=NULL;

                if(!isset($message)){
                    $flag=1;
                    $date = date("Y-m-d H:i:s");
                    try{
                        $dbc->autocommit(FALSE); // i.e., start transaction
                        $query="INSERT INTO INVENTORY (INVENTORY_ID,FILM_ID,STORE_ID, LAST_UPDATE) 
                                VALUES ('0','{$_POST['film']}','{$_SESSION['storeID']}','$date')";
                        
                        $result = $dbc->query($query);//$result=mysqli_query($dbc,$query);
                        if (!$result) {
                                echo 'Query error: ';
                                echo mysqli_error($dbc);
                                $result->free();
                                throw new Exception($dbc->error);
                        }
                        else{
                            $filmID = $_POST['film'];
                            $query = "SELECT TITLE from FILM WHERE FILM_ID='{$filmID}'";
                            $result=mysqli_query($dbc,$query);
                            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                            $title = $row['TITLE'];
                            $message="<b><p>Movie {$title}<br>added! </b> 
                                <form method=\"post\" action=\"add-movie.php\">
                                <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">
                                </form>";
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
                if (isset($message)){
                    echo '<div class="w3-grey w3-padding-16" style="padding:20px; float:left; width:30%; border-radius: 10px;">';
                    echo '<p><b>'.$message. '</b></p>';
                    echo '</div>';
                }
            }/*End of main Submit conditional*/


        ?>

	</div>
<?php endblock() ?>