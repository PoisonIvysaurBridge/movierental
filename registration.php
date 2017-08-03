<?php include 'base.php' ?>

<?php startblock('content') ?>
<?php
        require_once('mysql_connect.php');/*
        $query="SELECT UNIT FROM MEASUREMENTS";
        $resultMeasurements=mysqli_query($dbc,$query);
        $query="SELECT INGCATEGORYID, NAME FROM INGREDIENTCATEGORY";
        $resultIngredientCategory=mysqli_query($dbc,$query);*/
        $former = 0;
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
                <option value="some values">
                <option value="some values">
                <option value="some values">
                <option value="some values">
                <option value="some values">
            </datalist><br>
            Secondary Home Address (leave blank if none): 
            <input list="address2" name="address2">
            <datalist id="address2">
                <option value="some values">
                <option value="some values">
                <option value="some values">
                <option value="some values">
                <option value="some values">
            </datalist><br>
            Home District: <input type = 'text' name = 'district' required> <br>
            City: <select name="city" required>
                    <option value="volvo">Volvo</option>
                    <option value="saab">Saab</option>
                    <option value="mercedes">Mercedes</option>
                    <option value="audi">Audi</option>
                </select><br>
            Postal Code: <input type = 'number' name = 'postal' min="1"> <br>
            Phone Number: <input type = 'number' name ='phone' min="1" required> <br>
            <input type = 'submit' class="w3-button w3-teal w3-round" value = 'Register'><br>

        </form>
    </div?
<?php endblock() ?>