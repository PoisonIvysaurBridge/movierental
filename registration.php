<?php include 'base.php' ?>

<?php startblock('content') ?>
    <?php
        require_once('mysql_connect.php');
        if (isset($_POST['register'])){
            $message = NULL;

            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];

            if (!empty($_POST['email'])){        // description
                $email = $_POST['email'];
            }
            else{
                $email = NULL;
            }

            $address1 = $_POST['address1'];

            if (!empty($_POST['address2'])){        // description
                $address2 = $_POST['address2'];
            }
            else{
                $address2 = NULL;
            }

            $district = $_POST['district'];
            $city = $_POST['city'];

            if (!empty($_POST['postal'])){        // description
                $postal = $_POST['postal'];
            }
            else{
                $postal = NULL;
            }

            $phone = $_POST['phone'];

            if(!isset($message)){   

            }

            if(isset($message)){   
                $message .= "<form method=\"post\" action=\"registration.php\">
                                    <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">
                            </form>";
                echo '<div class="w3-grey w3-padding-16" style="margin: 0 0 20px 0; padding:20px; float:left; width:30%; border-radius: 10px;">';
                echo '<p><b>'.$message. '</b></p>';
                echo '</div>';
            }
        }
    ?>

    <div class="w3-container"  style="margin: 0 30px;" >
        <h1 style="text-align: center;">New Customer Registration</h1>

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
							echo "<option value ="."{$row['ADDRESS_ID']}".">"."{$row['ADDRESS']}"."</option>";
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
							echo "<option value ="."{$row['ADDRESS_ID']}".">"."{$row['ADDRESS']}"."</option>";
						}
					}
					
				?>
            </datalist><br>
            Home District: <input type = 'text' name = 'district' required> <br>
            City: <select name="city" required>
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
            Postal Code: <input type = 'number' name = 'postal' min="1"> <br>
            Phone Number: <input type = 'number' name ='phone' min="1" required> <br>
            <input type = 'submit' class="w3-button w3-teal w3-round" name="register" value = 'Register'><br>

        </form>
    </div?
<?php endblock() ?>