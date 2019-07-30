<?php
//include_once("classDB.php");

class product extends PDOException{
    
    private $pdo=null;
    private $petcoPDO = null;
    private $isDiscontinued = " Discontinued = '0' ";
    private $dates = array();
    
    
    private function showDate(){
        return date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-90,   date("Y")));
    }
    
    public function setDateRange($dateFrom,$dateTo){
        $this->dates = array('dateFrom' => $dateFrom.' 00:00:01',
                             'dateTo' => $dateTo.' 23:59:01');
       
    }
    
    //creating connection string to petco to getallheaders product list
    function __construct($petcoString){
        $petcoConnectionString = $petcoString;
        
        
        try{
            //$this->petcoPDO  = new PDO("sqlsrv:Server=86.47.51.83,1317;Database=petshoptest","sa","SMITH09ALPHA"); // charlestown db test
            $this->petcoPDO = new PDO($petcoConnectionString["server"],$petcoConnectionString["user"],$petcoConnectionString["password"]);
        }catch(Exception $e){
            //$this->petcoPDO  = new PDO("sqlsrv:Server=Server=192.168.1.2\SQLEXPRESS;Database=petshoptest","sa","SMITH09ALPHA");
            $this->petcoPDO = new PDO($petcoConnectionString["localServer"],$petcoConnectionString["user"],$petcoConnectionString["password"]);
        }
    }
    
    function openConnection($dbConnectionArray){
            try{
                
                $this->pdo = new PDO($dbConnectionArray["server"],$dbConnectionArray["user"],$dbConnectionArray["password"]); 
            }
            catch (PDOException $e){
               // var_dump($e);
                $this->pdo = new PDO($dbConnectionArray["localServer"],$dbConnectionArray["user"],$dbConnectionArray["password"]);
            }
    }
    
   
    /*
     *
     *Gettind list of all supplier name's and add it to select list
     *
     */
    function supplierList(){
       // $this->getMasterDbString();
        //$petcoPDO = this->getMasterDbString();
        $option = "<option>All</option>";
            $sql = "SELECT [Supplier] FROM [Suppliers] ORDER BY [Supplier] ASC";
            $query = $this->petcoPDO->prepare($sql);
            $query->execute();
                
            while($row = $query->fetch()){
              $option .= "<option>".$row['Supplier']."</option>";
            }
            
            //$petcoPDO = null;
        return $option;
    }
    
    
    /*
     **
     **
     ** get all products from choosen supplier
     **
     **
     **/
    
    function allProdFromSupplier($supplierName,$isDiscontinued){
        $this->setDiscontinued($isDiscontinued);
        $where = "";
            if($supplierName!='All'){
                $where = "[SupplierName] = '".$supplierName."' AND";    
            }
        
        
            $sql = "SELECT [Name of Item], [Selling Price], [InternalRefCode], [CodeSup], [Manufacturer] FROM [Stock] WHERE ".$where.$this->isDiscontinued." ORDER BY [Name of Item] ASC";
            //echo $sql;
            $query = $this->petcoPDO->prepare($sql);
            $query->execute();
            
            $prodFromSupArray = array();    
            while($row = $query->fetch()){
              $prodFromSupArray[] = array('name' => $row['Name of Item'],'salePrice' => round($row['Selling Price'],2),'supCode' => $row['CodeSup'],'intCode' => $row['InternalRefCode'], 'manufacturer' => $row['Manufacturer']);
            }
            
    return $prodFromSupArray;
    }
    
    
    
    function saleDetails($supplierName){
        
        $where = "";
            if($supplierName!='All'){
                $where = "SupplierName = '".$supplierName."' AND";    
            }
            
        $sql = "SELECT [Name of Item],SUM([QuantityBought]) as total, [Selling Price], (SUM([QuantityBought]) * [Selling Price]) as [value], Quantity
                FROM Stock
                	inner join [Orders] on [Name of Item] = [NameOfItem]
                	inner join [Days] on [Order Number] = OrderNo
				WHERE ([Date] > '".$this->dates['dateFrom']."' AND [Date] < '".$this->dates['dateTo']."')
                    AND ".$where.$this->isDiscontinued.
                    "group by [Selling Price],Quantity,[Name of Item] order by Stock.[Name of Item] ASC;";
    
    
        $query = $this->pdo->prepare($sql);
        $query->execute();
        
        $productArray = array();
        
        while($row = $query->fetch()){
            $productArray[] = array('name' => $row['Name of Item'], 'sold' => round($row['total'],2), 'currentQty' => round($row['Quantity'],2));
        }
        return $productArray;
    }
    
    function qtyDetails($supplierName){
        
        $where = "";
            if($supplierName!='All'){
                $where = "SupplierName = '".$supplierName."' AND";    
            }
        
        $sql = "SELECT [Name of Item], Quantity,[Selling Price]
                FROM Stock
                WHERE ".$where.$this->isDiscontinued.
                "ORDER BY [Name of Item] ASC;";
                
        $query = $this->pdo->prepare($sql);
        $query->execute();
        
        $qtyArray = array();

        while($row = $query->fetch()){
            $qtyArray[$row['Name of Item']] = array('qty' => round($row['Quantity'],2), 'sellPrice' => round($row['Selling Price'],2));
        }
        
        return $qtyArray;
    }
    
    function setDiscontinued($isDiscontinued){
        $this->isDiscontinued = " Discontinued = '".$isDiscontinued."' ";
    }
    
    function getDate(){
        return $this->showDate();
    }
    
    function close(){
        $this->pdo = null;
    }
}
?>