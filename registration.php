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
        <form action="address.php" method="post">
            First Name: <input type = 'text' name = 'firstname' required> <br>
            Last Name: <input type = 'text' name = 'lastname' required> <br>
            Email Address: <input type ='text' name = 'email'> <br>
            <!--
            Primary Home Address: 
            <input list="address1" name="address1"  required>
            <datalist id="address1">
                <?php
                    /*
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
					*/
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
            -->
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
            <!--
            <div id="change">
            Home District: 
            <select name="district" class="form-control" required>
            </select><br>
        
            Postal Code: 
            <select name="postal" class="form-control" required>
            </select><br>
            </div>

            Phone Number: <input type = 'number' name ='phone' min="1" required> <br>
            -->


            <input type = 'submit' class="w3-button w3-teal w3-round" name="address" value = 'Next'><br>
            
        </form>
        </div>
        
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