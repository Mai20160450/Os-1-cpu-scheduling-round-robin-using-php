

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
    if(isset($_POST['submit']) && ($_POST['submit'] == 'Ok')){
        session_start();
        $PNames = array();
        $PArrivalTime = array();
        $PBTime = array();
        for($i = 0 ; $i < $_SESSION['PNo'] ; $i++ ){
            $PNames[$i]         = $_POST['PNos'][$i];
            $PArrivalTime[$i]   = $_POST['ATime'][$i];
            $PBTime[$i]         = $_POST['BTime'][$i];
            
        }
        $Qt = $_POST['Qt'];
        $formErrors = array();
                
        if(empty($Qt)){
                    
            $formErrors[] = "QT cant be <strong> Empty</strong>";
                    
        }
        if($Qt < 0 ){
                    
            $formErrors[] = "Number of Processe Must be <strong> Positive number</strong>";           
        }
        for($i = 0 ; $i < $_SESSION['PNo'] ; $i++ ){
            if(empty($PNames[$i])){
                $formErrors[] = "Name of Processe $i cant be <strong> Empty</strong>";
            }
            if(strlen($PArrivalTime[$i]) < 0){
                $formErrors[] = "AT of Processe $i cant be <strong> Empty</strong>";
            }
            if($PArrivalTime[$i] < 0 ){
                    
                $formErrors[] = "AT of Processe $i Must be <strong> Positive number</strong>";           
            }
            if(empty($PBTime[$i])){
                $formErrors[] = "AT of Processe $i cant be <strong> Empty</strong>";
            }
            if($PBTime[$i] < 0 ){
                    
                $formErrors[] = "AT of Processe $i Must be <strong> Positive number</strong>";           
            }
        }  
        foreach($formErrors as $error){
            echo "<div class='Form'>"; 
            echo "<div class='alert alert-danger' style ='text-align: center;margin-top: 15px;'>". $error ."</div>" . "<br/>";
            echo "</div>";
        }
        if(empty($formErrors)){
        $ArrayOfProcess = array();
        
      include "Process.php";
        
        
        for($i = 0 ; $i < $_SESSION['PNo'] ; $i++){
            $proc = new Process();
            $proc->PNo  = $PNames[$i];
            $proc->AT = $PArrivalTime[$i];
            $proc->BT = $PBTime[$i];
            $proc->RT = $proc->BT;
            $ArrayOfProcess[$i] = $proc;
        }
        
        
        
        

        function my_sort_function($a, $b)
        {
            return $a->AT > $b->AT;
        }

        usort($ArrayOfProcess, 'my_sort_function');

        $GandChart = array();
        $ReadyQueue = array();
        $ArrayResultOfProcess=array();
        $QtResults = array();
        
        $SumOfTAT = 0.0;
        $SumOfWT = 0.0;
        $SumOfResponseTime = 0.0;
        $AvgTAT = 0.0;
        $AvgWT = 0.0;
        $AvgResponseTime=0.0;
        
     
        $FirstElementInTheArray = new Process();
        $FirstElementInTheArray = array_values($ArrayOfProcess)[0];
        array_shift($ArrayOfProcess);
        array_push($GandChart , $FirstElementInTheArray->PNo);
        
        
        
        
        $QtCounter = $FirstElementInTheArray->AT;
        array_push($QtResults , $QtCounter);
        if($FirstElementInTheArray->Res == -1){
            $FirstElementInTheArray->Res = $QtCounter;
            $FirstElementInTheArray->ResponseTime = $FirstElementInTheArray->Res - $FirstElementInTheArray->AT;
            $SumOfResponseTime+=$FirstElementInTheArray->ResponseTime;
        }
        
        if($FirstElementInTheArray->BT <= $Qt){
            $QtCounter+=$FirstElementInTheArray->BT;
            array_push($QtResults , $QtCounter);
            $FirstElementInTheArray->RT=0;
            $FirstElementInTheArray->CT=$QtCounter;
            $FirstElementInTheArray->TAT=$FirstElementInTheArray->CT - $FirstElementInTheArray->AT;
            $FirstElementInTheArray->WT = $FirstElementInTheArray->TAT - $FirstElementInTheArray->BT;
            $SumOfTAT+=$FirstElementInTheArray->TAT;
            $SumOfWT+=$FirstElementInTheArray->WT;
            array_push($ArrayResultOfProcess , $FirstElementInTheArray);
            
            while(! empty($ArrayOfProcess)){
                if($QtCounter >= array_values($ArrayOfProcess)[0]->AT){
                    $n = new Process();
                    $n = array_values($ArrayOfProcess)[0];
                    array_push($ReadyQueue , $n);
                    array_shift($ArrayOfProcess);
                }else{
                    break;
                }
            }
            
            
          
            
        }
        
        else{
            $FirstElementInTheArray->RT -=$Qt;
            $QtCounter +=$Qt;
            array_push($QtResults , $QtCounter);

                 while(! empty($ArrayOfProcess)){
                if($QtCounter >= array_values($ArrayOfProcess)[0]->AT){
                    $n = new Process();
                    $n = array_values($ArrayOfProcess)[0];
                    array_push($ReadyQueue , $n);
                    array_shift($ArrayOfProcess);
                }else{
                    break;
                }
            }
            array_push($ReadyQueue , $FirstElementInTheArray);
        }
        
       
        
        
        while(!empty($ReadyQueue)){
            $Object = new Process();
            $Object =array_values($ReadyQueue)[0];
            array_shift($ReadyQueue);
            if($Object->Res == -1){
                $Object->Res =$QtCounter;
                $Object->ResponseTime = $Object->Res - $Object->AT;
                $SumOfResponseTime+=$Object->ResponseTime;  
            }
            array_push($GandChart , $Object->PNo);
            if($Object->RT <= $Qt){
                $QtCounter += $Object->RT;
                array_push($QtResults , $QtCounter);
                $Object->RT = 0;
                $Object->CT = $QtCounter;
                $Object->TAT = $Object->CT - $Object->AT;
                $Object->WT = $Object->TAT - $Object->BT;
                $SumOfTAT += $Object->TAT;
                $SumOfWT += $Object->WT;
                array_push($ArrayResultOfProcess , $Object);
                   while(!empty($ArrayOfProcess)){
                if($QtCounter >= array_values($ArrayOfProcess)[0]->AT){
                    $n = new Process();
                    $n = array_values($ArrayOfProcess)[0];
                    array_push($ReadyQueue , $n);
                    array_shift($ArrayOfProcess);
                }else{
                    break;
                }
            }
            
        }
        
        else{
            $Object->RT -=$Qt;
            $QtCounter+=$Qt;
            array_push($QtResults , $QtCounter);

                 while(!empty($ArrayOfProcess)){
                if($QtCounter >= array_values($ArrayOfProcess)[0]->AT){
                    $n = new Process();
                    $n = array_values($ArrayOfProcess)[0];
                    array_push($ReadyQueue , $n);
                    array_shift($ArrayOfProcess);
                }else{
                    break;
                }
            }
            array_push($ReadyQueue , $Object);
        }
            }
    
        $AvgTAT = $SumOfTAT/ $_SESSION['PNo'];
        $AvgWT = $SumOfWT / $_SESSION['PNo'];
        $AvgResponseTime = $SumOfResponseTime / $_SESSION['PNo'];
        
    //$ArrayResultOfProcess $AvgTAT $AvgWT $AvgResponseTime $GandChart $QtResults
      ?>  
    <h1>Round Robbin</h1>    
   <div class="Form">  
     <table  class="table table-bordered">
 <thead>
    <tr style="color: #4b134f;font-size: 18px;">
      <th scope="col">PNo</th>
      <th scope="col">AT</th>
        <th scope="col">BT</th>
        <th scope="col">CT</th>
        <th scope="col">TAT</th>
        <th scope="col">WT</th>
        <th scope="col">ResponseTime</th>
    </tr>
  </thead>
    <tbody>
        
        <?php
        function cmp($a, $b){
    return strcmp($a->PNo, $b->PNo);
        }

        usort($ArrayResultOfProcess, "cmp");
        echo "<div>";
        for ($i = 0 ; $i < $_SESSION['PNo'] ; $i++){
            echo '<tr>';
            echo "<td>"; echo array_values($ArrayResultOfProcess)[$i]->PNo ;"</td>";
            echo '<td>'; echo array_values($ArrayResultOfProcess)[$i]->AT; echo'</td>';
            echo '<td>'; echo array_values($ArrayResultOfProcess)[$i]->BT; echo'</td>';
            echo '<td>'; echo array_values($ArrayResultOfProcess)[$i]->CT; echo'</td>';
            echo '<td>'; echo array_values($ArrayResultOfProcess)[$i]->TAT; echo'</td>';
            echo '<td>'; echo array_values($ArrayResultOfProcess)[$i]->WT; echo'</td>';
            echo '<td>'; echo array_values($ArrayResultOfProcess)[$i]->ResponseTime; echo'</td>';
       
            echo '</tr>';
        }
        //echo "</div>";
     ?>
        <?php
        
        ?>
       </tbody>
</table>
       
       
       
       
         
     <table  class="table table-bordered">
 <thead>
    <tr style="color: #4b134f;font-size: 18px;">
      <th scope="col"  style="text-align: center;">AvgTAT</th>
      <th scope="col"  style="text-align: center;">AvgWT</th>
        <th scope="col"  style="text-align: center;">AvgResponseTime</th>
    </tr>
  </thead>
    <tbody>
        
        <?php
            echo '<tr>';
            echo "<td>"; echo $AvgTAT  ;"</td>";
            echo '<td>'; echo $AvgWT; echo'</td>';
            echo '<td>'; echo $AvgResponseTime; echo'</td>';
       
            echo '</tr>';
        //echo "</div>";
     ?>
        <?php
        
        ?>
       </tbody>
</table>
       
       
       
  
    <div class="Divgand">
        <ul class="GandChart">
        <?php
               for($i=0 ; $i < count($GandChart) ; $i++){
            echo "<li class='GandChartElments'>";   echo array_values($GandChart)[$i];echo "</li>";
        }
            ?>
        </ul>
        </div>
     
     <ul class="QtResults">
     <?php
               for($i=0 ; $i < count($QtResults) ; $i++){
            echo "<li class='QtElments'>";   echo array_values($QtResults)[$i];echo "</li>";
        }
            ?>
     </ul>
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