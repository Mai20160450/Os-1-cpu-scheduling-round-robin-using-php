<?php


?>


<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <title>Round Robbin</title>
        <link rel="stylesheet" href="Css/bootstrap.css">
        <link rel="stylesheet" href="Css/main.css">
    </head>
    
 <body>
     

     <h1>Round Robbin</h1>
<div class="Form">
    <form action="ProccessRunTimeCreation.php" method="POST">
      <div class="form-group">
        <label for="exampleInputEmail1">Please enter the Number Of Proccess :</label>
        <input type="number" class="form-control" id="PNo" name="PNo" placeholder="No Of Process" required>

      </div>

      <button type="submit" name="submit" value="Create" class="btn btn-primary">Create</button>
    </form> 
</div>

    <script src = "Js/jquery-3.2.0.min.js"></script>
    <script src = "Js/bootstrap.min.js"></script>
 
</body>
