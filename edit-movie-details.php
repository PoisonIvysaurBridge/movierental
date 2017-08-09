<?php include 'base.php' ?>

<?php startblock('content') ?>
    <?php
        //session_start();
        require_once('mysql_connect.php');
        $query="SELECT LANGUAGE_ID, NAME FROM LANGUAGE";
        $languages=mysqli_query($dbc,$query);
        $orig=mysqli_query($dbc,$query);

        if(isset($_POST['film']))
            $_SESSION['film'] = $_POST['film'];
        $query = "SELECT FILM_ID, TITLE, DESCRIPTION, RELEASE_YEAR, LANGUAGE_ID, ORIGINAL_LANGUAGE_ID, RENTAL_DURATION, 
                         RENTAL_RATE, LENGTH, REPLACEMENT_COST, RATING, SPECIAL_FEATURES
                  FROM FILM 
                  WHERE FILM_ID = '".$_SESSION['film']."'";
        $result = mysqli_query($dbc, $query);
        if(!$result){
            echo mysqli_error($dbc);
        }
        else{
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
    ?>
    <div class="w3-container"  style="margin: 0 30px;" >
		<h1 style="text-align: center;">Edit Movie: Film #<?php echo $row['FILM_ID']." - "; echo $row['TITLE']; ?></h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="w3-half">
            Film Title: <input type = 'text' name = 'title' required value="<?php echo $row['TITLE']; ?>"> <br>
            Film Description: <br> <textarea rows='5' cols='40' name ='desc' value="<?php echo $row['DESCRIPTION']; ?>"> </textarea> <br>
            Release Year: <input type = 'number' name = 'releaseyear' min="1990" value="<?php echo $row['RELEASE_YEAR']; ?>"> <br>
            Current Language: <select name = 'lang'> 
                                    <?php
                                        if (!$languages) {
                                            echo mysqli_error($dbc);
                                        }
                                        else {
                                            while($curLang=mysqli_fetch_array($languages,MYSQLI_ASSOC)){
                                                echo "
                                                    <option value=\"{$curLang['LANGUAGE_ID']}\">{$curLang['NAME']}</option>
                                                ";
                                            }
                                        }
                                    ?>
                              </select> <br>
            Original Language: <select name = 'origLang'> 
                                    <option selected value="0" > -- select an option -- </option>
                                    <?php
                                        if (!$orig) {
                                            echo mysqli_error($dbc);
                                        }
                                        else {
                                            while($origLang=mysqli_fetch_array($orig,MYSQLI_ASSOC)){
                                                echo "
                                                    <option value=\"{$origLang['LANGUAGE_ID']}\">{$origLang['NAME']}</option>
                                                ";
                                            }
                                        }
                                    ?>
                               </select> <br>
            Rental Duration (in days): <input type = 'number' name = 'duration' min="3" required value="<?php echo $row['RENTAL_DURATION']; ?>"> <br>
            Rental Rates (in USD): <input type='number' step='any' name = 'rate' min="4.99" required value="<?php echo $row['RENTAL_RATE']; ?>"><br>
            Movie Length (in minutes): <input type = 'number' name = 'length' min="1" value="<?php echo $row['LENGTH']; ?>"> <br>
        </div>    <!-- FIRST HALF -->
        
        <div class="w3-half">
            Replacement Cost (if lost or damaged): <input type = 'number' step = 'any' name = 'replace' min="19.99" required value="<?php echo $row['REPLACEMENT_COST']; ?>"> <br>
            Movie Maturity Rating: <select name = 'rating'> 
                                        <option value = 'G' selected> General Audience </option> 
                                        <option value = 'PG'> Parental Guidance </option> 
                                        <option value = 'PG13'> PG-13 </option> 
                                        <option value = 'R'> Restricted </option> 
                                        <option value = 'NC17'> Adults Only </option> 
                                    </select> <br><br>
            Special Features:<br>
            <input type='checkbox' name='features[]' value='Trailers'>Trailers<br>
            <input type='checkbox' name='features[]' value='Commentaries'>Commentaries<br>
            <input type='checkbox' name='features[]' value='Deleted Scenes'>Deleted Scenes<br>
            <input type='checkbox' name='features[]' value='Behind the Scenes'>Behind the Scenes<br>
            
            <input type="hidden" name="filmID" value="<?php echo $row['FILM_ID']; ?>">
            <input type = 'submit' class="w3-button w3-teal w3-round" name='edit' style="margin: 50px 0 0 0" value = 'Edit Movie'><br>
        </div><!-- SECOND HALF -->
        </form>
        <?php
            if (isset($_POST['edit'])){      
                $message=NULL;
                $date = date("Y-m-d H:i:s");

                $filmID = $_SESSION['film'];
                // REQUIRED / NOT NULL fields
                $title = $_POST['title'];           // title
                $lang = $_POST['lang'];             // language
                $duration = $_POST['duration'];     // duration
                $rate = $_POST['rate'];             // rate
                $replacement = $_POST['replace'];   // replacement cost
                $update = date("Y-m-d H:i:s");      // last updated

                if (!empty($_POST['desc'])){        // description
                    $desc = $_POST['desc'];
                }
                else{
                    $desc = NULL;
                }

                if (!empty($_POST['releaseyear']) && !$_POST['releaseyear'] == ""){ // release year
                    $year = (int) $_POST['releaseyear'];
                }
                else{
                    $year = -1; 
                }
                
                if (($_POST['origLang']) != 0){    // original language
                    $orig = $_POST['origLang'];
                }
                else{
                    $orig = -1;
                }

                if (!empty($_POST['length'])){    // length
                    $length = $_POST['length'];
                }
                else{
                    $length = -1;
                }

                $rating = $_POST['rating'];     // rating

                $features = '';
                if(isset($_POST['features'])){
                    foreach($_POST['features'] as $feature){
                        if($feature == end($_POST['features']))// if last of the array
                            $features .= $feature;
                        else
                            $features .= $feature . ",";
                    }
                    
                }
                else{
                    $features = NULL;
                }
            
                if(!isset($message)){   //\"('{$features}')\"
                /*
                    $query = "INSERT INTO FILM (FILM_ID, TITLE, DESCRIPTION, RELEASE_YEAR, LANGUAGE_ID, ORIGINAL_LANGUAGE_ID, RENTAL_DURATION, RENTAL_RATE, LENGTH, REPLACEMENT_COST, RATING, SPECIAL_FEATURES, LAST_UPDATE)
                                VALUES('0', '{$title}', '{$desc}', '{$year}', '{$lang}', '$orig', '{$duration}', '{$rate}', '{$length}', '{$replacement}', '{$rating}', '{$features}', '$update')";
                    $result=mysqli_query($dbc,$query);*/
                    // prepare and bind
                    try{
                        $dbc->autocommit(FALSE); // i.e., start transaction
                        $query="UPDATE FILM SET
                                TITLE = '".$title."',
                                DESCRIPTION = '".$desc."',
                                RELEASE_YEAR = '".$year."',
                                LANGUAGE_ID = '".$lang."',
                                ORIGINAL_LANGUAGE_ID = '".$orig."',
                                RENTAL_DURATION = '".$duration."',
                                RENTAL_RATE = '".$rate."',
                                LENGTH = '".$length."',
                                REPLACEMENT_COST = '".$replacement."',
                                RATING = '".$rating."',
                                SPECIAL_FEATURES = '".$features."',
                                LAST_UPDATE = '".$date."'
                                WHERE FILM_ID = '".$filmID."'";
                        
                        $result = $dbc->query($query);//$result=mysqli_query($dbc,$query);

                        if (!$result) {
                            echo mysqli_error($dbc); 
                        }
                        else{
                            $message="<b><p>New movie {$title} {$rate} updated! </b>"; 
                                        //<p> Let's now go and add the categories and actors!";
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
                    $message .= "<form method=\"post\" action=\"edit-movie.php\">
                                            <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">
                                </form>";
                    echo '<div class="w3-grey w3-padding-16" style="margin: 20px 0 0 0; padding:20px; float:left; width:40%; border-radius: 10px;">';
                    echo '<p><b>'.$message. '</b></p>';
                    echo '</div>';
                }
            }
        ?>
	</div>
<?php endblock() ?>