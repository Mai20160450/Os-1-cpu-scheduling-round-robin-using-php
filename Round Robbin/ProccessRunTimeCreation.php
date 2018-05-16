<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <title>Round Robbin</title>
        <link rel="stylesheet" href="Css/bootstrap.css">
        
        <link rel="stylesheet" href="Css/main.css">
    </head>
    
 <body>

<?php

if($_POST){
    if(isset($_POST['submit']) && ($_POST['submit'] == 'Create')){
        
        $PNo = $_POST['PNo']; 
        $formErrors = array();
                
        if(empty($PNo)){
                    
            $formErrors[] = "Number of Processe cant be <strong> Empty</strong>";
                    
        }
        if($PNo < 1 ){
                    
            $formErrors[] = "Number of Processe Must be <strong> Positive number</strong>";
                    
        }
                
        foreach($formErrors as $error){
            echo "<div class='Form'>"; 
            echo "<div class='alert alert-danger' style ='text-align: center;margin-top: 15px;'>". $error ."</div>" . "<br/>";
            echo "</div>";
        }    
        if(empty($formErrors)){
            session_start();
            $_SESSION['PNo'] = $PNo;
        
    
        ?>
<h1>Round Robbin</h1>     
<div class="Form">    
    
     
    <form action="GandChart.php" method="POST">
        <label for="exampleInputEmail1">Please enter the Time Slince :</label>
     <input class="form-control" type="number" name="Qt" placeholder ="Time Slice">
    <table class="table">
     <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col" style="text-align: center;">PNo</th>
          <th scope="col" style="text-align: center;">AT</th>
          <th scope="col" style="text-align: center;">BT</th>
        </tr>
      </thead>
        <tbody>

    <?php
            for ($i = 0 ; $i < $PNo ; $i++){
                echo '<tr>';
                $z = $i+1;
                echo "<td>$z</td>";
                echo '<td> <input class="form-control"  type="text" name="PNos[]" placeholder = "Proccess Name"></input></td>';
                echo '<td> <input class="form-control"  type="number" name="ATime[]" placeholder ="Process Arrival Time"></input></td>';
                echo '<td> <input class="form-control"  type="number" name="BTime[]" placeholder ="Process BTime" ></input></td>';
                echo '</tr>';
            }
         
            ?>
      </tbody>
    </table>
        <button type="submit" name="submit" value="Ok" class="btn btn-primary">Ok</button>
    </form>
</div>
<?php
    }
    }
}else {
           
    echo "<div class='Form'>";            
    echo "<div class= 'alert alert-danger' style ='text-align: center;margin-top: 15px;'>Sorry you Cant Browse This Page Directly</div>";
    echo "</div>";
}
?>
     
         <script src = "Js/jquery-3.2.0.min.js"></script>
    <script src = "Js/bootstrap.min.js"></script>
    
</body>