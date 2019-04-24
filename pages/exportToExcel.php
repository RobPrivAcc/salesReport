<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');
ini_set('max_input_vars', 9000);

include('../class/classDb.php');
include("../class/classXML.php");
	
//$fileName = $_POST['supplierName'];
$fileName = str_replace("[space]"," ",$_POST['supplierName']);
$productName = $_POST['products'];


require_once dirname(__FILE__) . '/../class/Excel/PHPExcel.php';

$objPHPExcel = new PHPExcel();

//$cellArray = array("D","E","F","G","H","I","J","K","L","M");
$cellArray = array("D","E","G","H","J","K","M","N","P","Q","S","T");


$objPHPExcel->getProperties()->setCreator("Robert Kocjan")
							 ->setLastModifiedBy("Robert Kocjan")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");

          $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
          $shop = new dbConnection($xml->getConnectionArray());

$shops = $shop->getShopsName();
                             
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'Product Name')
			->setCellValue('B2', 'Supplier Code')
			->setCellValue('C2', 'Internal Code')
			->mergeCells('D1:E1')->setCellValue('D1', $shops[0][0]."\n(".$shops[0][1].")")->setCellValue('D2', 'Sold')->setCellValue('E2', 'Stock')
			->mergeCells('G1:H1')->setCellValue('G1', $shops[1][0]."\n(".$shops[1][1].")")->setCellValue('G2', 'Sold')->setCellValue('H2', 'Stock')
			->mergeCells('J1:K1')->setCellValue('J1', $shops[2][0]."\n(".$shops[2][1].")")->setCellValue('J2', 'Sold')->setCellValue('K2', 'Stock')
			->mergeCells('M1:N1')->setCellValue('M1', $shops[3][0]."\n(".$shops[3][1].")")->setCellValue('M2', 'Sold')->setCellValue('N2', 'Stock')
			->mergeCells('P1:Q1')->setCellValue('P1', $shops[4][0]."\n(".$shops[4][1].")")->setCellValue('P2', 'Sold')->setCellValue('Q2', 'Stock')
            ->mergeCells('S1:T1')->setCellValue('S1', $shops[5][0]."\n(".$shops[5][1].")")->setCellValue('S2', 'Sold')->setCellValue('T2', 'Stock')
			->setCellValue('W2', "Order total\nqty")
            ->setCellValue('X2', "Total quantity\nin stores")
			->setCellValue('Y2', "Total sold\nquantity")
			->setCellValue('Z2', "Total sold\nvalue");
    
    $objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getFont()->setBold(true);
    
    $columnWidth = 12;
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth($columnWidth);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth($columnWidth+5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth($columnWidth-5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth($columnWidth-5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth($columnWidth-8);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth($columnWidth-5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth($columnWidth-5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth($columnWidth-8);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth($columnWidth-5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth($columnWidth-5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth($columnWidth-8);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth($columnWidth-5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth($columnWidth-5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth($columnWidth-8);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth($columnWidth-5); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth($columnWidth-5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth($columnWidth-5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth($columnWidth-5); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth($columnWidth-5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth($columnWidth-5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth($columnWidth);
    $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth($columnWidth);
    $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth($columnWidth);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth($columnWidth);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth($columnWidth);
	
    $objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getAlignment()->setWrapText(TRUE);
	$objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getAlignment()->setWrapText(TRUE);

$cellNo = 3;
for ($i = 0; $i < count($productName); $i++){
    
    $index = 0;
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$cellNo, $productName[$i]["name"]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$cellNo, $productName[$i]["supCode"]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$cellNo, $productName[$i]["intCode"]);
    
    $totalSold = 0;
	$totalSoldQty = 0;
     
	//echo count($productStatArray[0]).'<BR/>cc';
	 
    for($j=0; $j < count($shops);$j++){
		
		$currentShop = $shops[$j][0];
		
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellArray[$index].$cellNo, $productName[$i]["shop"][$currentShop]["sale"]);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellArray[++$index].$cellNo, $productName[$i]["shop"][$currentShop]["qty"]);
			

        //echo $index."<br/>";
		$totalSold += $productName[$i]["shop"][$currentShop]["sale"];
		$totalSoldQty += $productName[$i]["shop"][$currentShop]["qty"];
		
		$index++;
    }
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$cellNo, '=F'.$cellNo.'+I'.$cellNo.'+L'.$cellNo.'+O'.$cellNo.'+R'.$cellNo.'+U'.$cellNo);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$cellNo, $totalSoldQty);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$cellNo, $totalSold);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$cellNo, $productName[$i]["salePrice"]*$totalSold);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$cellNo.':T'.$cellNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $cellNo++;
}

//
$columnLen = $cellNo-1;

$objPHPExcel->getActiveSheet()
    ->getStyle('D1:D'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');

$objPHPExcel->getActiveSheet()
    ->getStyle('E1:E'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');
	
	$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$columnLen)->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );


$objPHPExcel->getActiveSheet()
    ->getStyle('G1:G'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');
	
$objPHPExcel->getActiveSheet()
    ->getStyle('H1:H'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');

	$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$columnLen)->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );

	
$objPHPExcel->getActiveSheet()
    ->getStyle('J1:J'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');

	$objPHPExcel->getActiveSheet()
    ->getStyle('K1:K'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');

	$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$columnLen)->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );

	
$objPHPExcel->getActiveSheet()
    ->getStyle('M1:M'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');
//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

	$objPHPExcel->getActiveSheet()
    ->getStyle('N1:N'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');

	$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$columnLen)->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );

	
	$objPHPExcel->getActiveSheet()
    ->getStyle('P1:P'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');
//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

	$objPHPExcel->getActiveSheet()
    ->getStyle('Q1:Q'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');

$objPHPExcel->getActiveSheet()->getStyle('Q1:Q'.$columnLen)->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );


	$objPHPExcel->getActiveSheet()
    ->getStyle('S1:S'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');
//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

	$objPHPExcel->getActiveSheet()
    ->getStyle('T1:T'.$columnLen)
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFd6d6d6');

	$objPHPExcel->getActiveSheet()->getStyle('T1:T'.$columnLen)->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );

	// TOTAL QUANTITY
	
	$objPHPExcel->getActiveSheet()->getStyle('X1:X'.$columnLen)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('X1:X'.$columnLen)->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
	
	
	
$objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D2:Z2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
$fileName_to_save = str_replace(" ","_",$fileName).'.xlsx';




$objWriter->save('../files/'.$fileName_to_save);


$pathToFile = dirname(pathinfo(__FILE__)['dirname']).'\\files\\'.$fileName_to_save;

if (file_exists($pathToFile) && isset($productName)){
    //echo "Click to download <a href = '/raport_v2/files/".$fileName."'>".$fileName."</a>";
		$show = "<br/><div class='row'>";
        $show .= "<div class='col-xs-12 col-12'>";
			$show .= "<a href = '/raport_v2/files/".$fileName_to_save."' class='btn btn-primary'><i class='fa fa-download' aria-hidden='true'></i>  Download <b>".$fileName_to_save."</b></a>";
		$show .= "</div>";
	$show .= "</div><br/>";
	echo $show;
}else{
    echo "Ups.. something went wrong and file wasn't created. Contact Robert.";    
}


?>