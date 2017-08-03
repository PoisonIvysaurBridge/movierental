<?php include 'base.php' ?>

<?php startblock('content') ?>
    <div class="w3-container"  style="margin: 0 30px;" >
        <form action="" method="post">
        Film Name: <select name = 'mTitle'> <option value = 'NULL' selected> </option> 
									<option value = 'Film 1'> Film 1 </option>
									<option value = 'Film 2'> Film 2 </option>
                    </select> <br>
        Film Actors (placeholder): <select name = 'actor'> <option value = 'NULL' selected> </option> 
                                            <option value = 'AC' selected > Actor Current </option>
                                            <option value = 'AB'> Actor B </option>
                    </select> <br>
        Film Category: <select name = 'fCategory'> <option value = 'NULL' selected> </option> 
                                            <option value = 'CC' selected> Category Current </option>
                                            <option value = 'CB'> Category B </option>
                    </select> <br>
        Film Maturity Rating: <select name = 'fRating'> <option value = 'NULL' selected> </option> 
                                            <option value = 'RC' selected> Rating Current </option>
                                            <option value = 'RB'> Rating B </option>
                    </select> <br>
        Film Current Language: <select name = 'fcLanguage'> <option value = 'NULL' selected> </option> 
                                            <option value = 'CLC' selected> Language Current </option>
                                            <option value = 'CLB'> Language B </option>
                    </select> <br>
        Film Original Language: <select name = 'foLanguage'> <option value = 'NULL' selected> </option> 
                                            <option value = 'OLC' selected> Language Current </option>
                                            <option value = 'OLB'> Language B </option>
                    </select> <br>
        Film Description: <br> <textarea rows='5' cols='40' name ='nDescription'>Current Description</textarea> <br>
        Release Year: <input type = 'year' name = 'rYear' value = '2017'> <br>

        <input type = 'submit' value = 'Submit Changes!'>
        </form>
    </div>
<?php endblock() ?>