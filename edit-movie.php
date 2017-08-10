<?php include 'base.php' ?>

<?php startblock('content') ?>
    <?php
        //session_start();
        require_once('mysql_connect.php');
        $query="SELECT FILM_ID, TITLE FROM FILM ORDER BY LAST_UPDATE";
        $films=mysqli_query($dbc,$query);

		$query="SELECT STORE_ID, ADDRESS FROM STORE AS S JOIN ADDRESS AS A ON A.ADDRESS_ID = S.ADDRESS_ID";
		$stores=mysqli_query($dbc,$query);

        if(isset($_SESSION['film'])){
            unset($_SESSION['film']);
        }
    ?>
    <div class="w3-container"  style="margin: 0 30px;" >
		<h1 style="text-align: center;">Edit Movie</h1>

		<div class="w3-containter" style="float:left; width: 60%;">
			<form method="post" action="edit-movie-details.php" id="usrform">
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
                  <input class="w3-button w3-teal w3-round" type="submit" name="submit" value="Select Movie">
		    </form>
		</div>
    
<?php endblock() ?>