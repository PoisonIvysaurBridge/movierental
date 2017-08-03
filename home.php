
<?php include 'base.php' ?>


<?php startblock('content') ?>
    <!-- Header -->
        <header class="w3-container" style="padding-top:22px">
            <h4><b><i class="fa fa-dashboard"></i>Dashboard</b></h4>
        </header>

        <div class="w3-row-padding w3-margin-bottom">
            <div class="w3-quarter">
            <a href="rent-movie.php" style="text-decoration:none;">
            <div class="w3-container w3-blue w3-padding-16">
                <div class="w3-left"><i class="fa fa-cart-plus w3-xxxlarge"></i></div>
                <div class="w3-right">
                </div>
                <div class="w3-clear"></div>
                <h4>Rent Movie Copy</h4>
            </div>
            </a>
            </div>
            
            <div class="w3-quarter">
            <a href="return-movie.php" style="text-decoration:none;">
            <div class="w3-container w3-red w3-padding-16">
                <div class="w3-left"><i class="fa fa-cart-arrow-down w3-xxxlarge"></i></div>
                <div class="w3-right">
                </div>
                <div class="w3-clear"></div>
                <h4>Return Movie Copy</h4>
            </div>
            </a>
            </div>
            
            <div class="w3-quarter">
            <a href="add-inventory.php" style="text-decoration:none;">
            <div class="w3-container w3-orange w3-text-white w3-padding-16">
                <div class="w3-left"><i class="fa fa-plus w3-xxxlarge"></i></div>
                <div class="w3-right">
                </div>
                <div class="w3-clear"></div>
                <h4>Add Movie Copy</h4>
            </div>
            </a>
            </div>
            <!--
            <div class="w3-quarter">
            <a href="delete-movie.php" style="text-decoration:none;">
            <div class="w3-container w3-teal w3-padding-16">
                <div class="w3-left"><i class="fa fa-close w3-xxxlarge"></i></div>
                <div class="w3-right">
                </div>
                <div class="w3-clear"></div>
                <h4>Delete Movie Copy</h4>
            </div>
            </a>
            </div>-->
			<div class="w3-container" > <!-- POWER BI EMBEDDED CODE -->
			<h2>Dashboard A</h2>
			<iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiM2M2YjE2OTctMzYwNS00MWRmLWE4OTctNzk4Mjc1ZGIxNjRlIiwidCI6ImYzNGEzNWJkLWE2NWQtNDYwNS1iMGZhLWQyNTcxZjgzMWY1ZSIsImMiOjEwfQ%3D%3D" frameborder="0" allowFullScreen="true"></iframe>
			<h2>Dashboard B</h2>
			<iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiZTViYWM2MDUtZGUzNy00OTU1LWExNzUtNTAyZTY1M2I5YzFlIiwidCI6ImYzNGEzNWJkLWE2NWQtNDYwNS1iMGZhLWQyNTcxZjgzMWY1ZSIsImMiOjEwfQ%3D%3D" frameborder="0" allowFullScreen="true"></iframe>
			</div>
            
        </div>

<?php endblock() ?>