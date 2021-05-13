<?php
    include("../class/classProduct.php");
    include("../class/classDb.php");
    include("../class/classXML.php");
    
    set_time_limit(0);
    ini_set('max_execution_time', 3000);
    ini_set('max_input_vars', 9000);
    
    $sup_name = $_POST['supplierName'];  //getting supplier name from select
    
    $supplierName = str_replace("[space]"," ",$sup_name);
    echo "<input type='hidden' id='supName' value='".$sup_name."'>";
    
    $isDiscontinued = $_POST['isDiscontinued'];
    echo 'From: '.$_POST['dateFrom'].'  -  '.'To: '.$_POST['dateTo'];
    
    $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    $db = new dbConnection($xml->getConnectionArray());
    
    $product = new product($db->getDbConnection(2));
    
    //echo $product->getDate().'<br/>';
   $product -> setDateRange($_POST['dateFrom'],$_POST['dateTo']);
    
    $allProductsFromSupp = $product->allProdFromSupplier($supplierName,$isDiscontinued);
    
    
    
    
    $productsArray = array();
    
    /**
     *
     *
     *  Get all shops and create array with details
     *
     *
     **/
    for($i=0; $i < $db->getMaxIndex();$i++){
        
        $product->openConnection($db->getDbConnection($i));
        $productsArray[$db->getShopName()] = ($product->saleDetails($supplierName));
        $qtyArray[$db->getShopName()] = ($product->qtyDetails($supplierName));
    }

   //print_r($allProductsFromSupp);
    
    
    $saleDetailArray = array();
    $allProdArray = array();
    $productName = "";

    
    for ($i=0; $i < count($allProductsFromSupp);$i++){
        $found = 0;
        
        $productName = $allProductsFromSupp[$i]["name"];
        $productSalePrice = $allProductsFromSupp[$i]["salePrice"];
        $manufacturer = $allProductsFromSupp[$i]["manufacturer"];
        

        foreach($productsArray as $key=>$value){
            //$prod .= "<td>".$qtyArray[$key][$productName]["sellPrice"]."</td>";

            for($j=0; $j < count($productsArray[$key]);$j++){
                
                if($productName == $productsArray[$key][$j]["name"]){
                    $saleDetailArray[$key] = array('sale' => $productsArray[$key][$j]["sold"], 'qty' => $productsArray[$key][$j]["currentQty"]);
                    $found =1;
                    break;
                }
            }
            
            if($found == 0){
                $saleDetailArray[$key] = array('sale' => '0', 'qty' => $qtyArray[$key][$productName]["qty"]);
            }
            $found = 0;
          
        }

    $allProdArray[] = array('name' => $productName, 'manufacturer' => $manufacturer, 'supCode' => $allProductsFromSupp[$i]["supCode"], 'intCode' => $allProductsFromSupp[$i]["intCode"],'salePrice' => $productSalePrice,'shop' => $saleDetailArray);
    }
   
    $table = "<table>";
    $prod = "";
    $head = "";
    
    
    $head .="<tr>";
    $head .="<th>Name</th>";
    $head .="<th>Manufacturer</th>";

      //Getting shop name and number from array
     
     
    for($i=0; $i < $db->getMaxIndex(); $i++){
        $head .= '<th>'.ucfirst($db->getShopsName()[$i][0]).'<br/>('.$db->getShopsName()[$i][1].')</th>';
     }
     $head .= '<th>Total Sold<br/>Value</th>';
     $head .= "</tr>";
    
    
  for($i =0; $i < count($allProdArray); $i++){
    $prod .= "<tr>";
        $prod .= "<td>";
            $prod .= $allProdArray[$i]["name"];
        $prod .= "</td>";
        
        $prod .= "<td>";
            $prod .= $allProdArray[$i]["manufacturer"];
        $prod .= "</td>";
        
        $totalSold = 0;
        
        for($j=0; $j < $db->getMaxIndex(); $j++){
            
        
            $prod .= "<td class='centerTable'>";
                $prod .= $allProdArray[$i]["shop"][$db->getShopsName()[$j][0]]["sale"]." (".$allProdArray[$i]["shop"][$db->getShopsName()[$j][0]]["qty"].")";
            $prod .= "</td>";
            
            $totalSold += $allProdArray[$i]["shop"][$db->getShopsName()[$j][0]]["sale"];
        }

        $prod .= "<td class='centerTable'>".$totalSold*$allProdArray[$i]["salePrice"]."</td>";
    $prod .= "</tr>";        
  }

 //$button = "<br/><button id='excelExport'><Excel><img src='exportExcel.jpg'/><br/>Export to Excel</button>";  

echo $table.$head.$prod."</table>";

//echo '<script>$("#exportToExcel").show();</script>';

?>



<script>
    var supplier = $('#supName').val();
    var products = <?php echo json_encode($allProdArray); ?>;
    
    
    $('#exportDivButton').html("<button class = 'btn btn-success' id = 'exportToExcel'><i class='fa fa-file-excel-o fa-lg' aria-hidden='true'></i></button>");
    $( "#exportToExcel" ).click(function(){
       $.post( "pages/exportToExcel.php", {
            
            supplierName: supplier,
            products: products
        }).done(function( data ) {
                $('#result').html(data);
                //$("#exportToExcel").hide();
                $('#exportDivButton').html("");
        });
    });
    
</script>
