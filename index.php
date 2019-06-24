<?php
	session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <title>In shops sales report</title>
        <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/myCSS.css">
		<link rel="stylesheet" href="css/switchSlider.css">
  </head>
  <body>
    <?php
        include("class/classProduct.php");
        include("class/classDb.php");
        include("class/classXML.php");
    ?>
    <div class="container">
      <div class="row">
        <div class='col-xs-12 col-12 text-center'>
          <h2>Sales Raport</h2>
        </div>
        </div>
      <div class="row">
        <div class='col-xs-2 col-2'>
<!--          <div class="radio">
            <label><input type="radio" name = "isDiscontinued" aria-label="..." value="0" checked="checked"/>Regular stock &nbsp;&nbsp;</label>
            <label><input type="radio" name = "isDiscontinued" aria-label="..." value="1"/>Discontinued stock &nbsp;&nbsp;</label>
          </div>-->

					<!-- Rounded switch -->
					<div id='divIsDiscontinued'>
						Regular stock
					</div>
					<label class="switch">
						<input type="checkbox" id='chbDiscontinuedId' checked=checked>
						<span class="slider round"></span>
					</label>
					
        </div>
				<div class='col-xs-2 col-2'>
					<!-- Rectangular switch -->
					<!--<label class="switch">
						<input type="checkbox">
						<span class="slider"></span>
					</label>-->
					
					<!-- Rounded switch -->
					<div id='divDateRangeId'>
						Last 90 days
					</div>
					<label class="switch">
						<input type="checkbox" id='chbDateRangeId' checked=checked>
						<span class="slider round"></span>
					</label> 
				</div>
				<div class='col-xs-8 col-8'>
					<div id='dateDiv'>
						<div class= 'row'>
							<div class='col-xs-4 col-4 dateInputs'>
								Date from: <input type="text" class="form-control form-control-sm" name="dateFrom" id="dateFrom" value="" />
							</div>
							
							<div class='col-xs-4 col-4 dateInputs'>
								Date to: <input type="text" class="form-control form-control-sm" name="dateTo" id="dateTo" value="" />
							</div>
							<div class='col-xs-4 col-4'>
							</div>
						</div>
					</div>
				</div>
      </div>


      
      <div class="row">
        <div class='col-xs-10 col-10'>
          <?php
		  
          $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
          $db = new dbConnection($xml->getConnectionArray());
            $details = new product($db->getDbConnection(2));
            
            $select = "<select id='supplierName' class='selectpicker form-control'>";
            $select .= "<option>Choose Supplier</option>";
              $select .= $details->supplierList();
            $select .= "</select>";
            
            echo $select.'<br>';
          ?>  
        </div>
        <div class='col-xs-1 col-1'>
            <button class = "btn btn-secondary" id = "search"><i class="fa fa-toggle-right fa-lg" aria-hidden="true"></i></button>
        </div>
        <div id = 'exportDivButton' class='col-xs-1 col-1'>
            
        </div>   
      </div>
      
      <div class="row">
        <div class='col-xs-12 col-12'>
          <div id="result" style="width: 100%;"></div>
        </div>
      </div>
  
      <div class="row">
        <div class='col-xs-12 col-12'>
          <div class="alert alert-secondary" role="alert">
            <div id="foot" style="width: 100%;">ver: <?php include('version.php');?></div>
          </div>
        </div>
      </div>
    </div>  
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
  
	
					<!-- Include Date Range Picker -->
		<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>		
				
		<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />


		  <script>
				function dates() {
					$('input[name="dateFrom"]').daterangepicker({
								startDate: moment().subtract(90, 'days'),
								singleDatePicker: true,
								showDropdowns: true,
								locale: {
										format: 'YYYY-MM-DD'
								}
					});
					
					$('input[name="dateTo"]').daterangepicker({
								startDate: moment(),
								singleDatePicker: true,
								showDropdowns: true,
								locale: {
										format: 'YYYY-MM-DD'
								}
					 });
				}
			</script>
	
  <script>
        
    $( document ).ready(function() {
        console.log( "ready!" );
				$('#dateDiv').hide();
        $('#search').tooltip({title: "Generate stats.", trigger: "hover"});
        $('#exportToExcel').tooltip({title: "Create <b>Excel</b> file.",  html: true, trigger: "hover"}); 
        $("#exportToExcel").hide();
        $('[data-toggle="tooltip"]').tooltip();
        
          $.get( "https://www.robertkocjan.com/petRepublic/ip/ipGetArray.php", function(i) {
						//console.log(i);
						var configArray = i;
          $.get( "getIpFromServer.php", { ipArray: configArray }, function(data) {
              //console.log(data);
          });
        });
		  console.log($('#dateFrom').val());
    });
		dates();
		
		$('#chbDateRangeId').click(function() {
			if($('#chbDateRangeId').is(':checked')){
				dates();
				$('#divDateRangeId').html("Last 90 days");
				$('#dateDiv').hide();
			}else{
				$('#divDateRangeId').html("Date range");
				$('#dateDiv').show();
			}
		});
		
		
		$('#chbDiscontinuedId').click(function() {
			if($('#chbDiscontinuedId').is(':checked')){
				
				$('#divIsDiscontinued').html("Regular stock");
			}else{
				$('#divIsDiscontinued').html("Discontinued Lines");
			}
		});
	
  </script>
         <!-- Optional JavaScript -->    
<!--          <script>
        $( "#supplierName" )
        .change(function () {

          var supplierName = $("#supplierName option:selected").text();
            if (supplierName != 'Choose Supplier'){
                var spinner = '<Div class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></DIV>';
                $('#result').html(spinner);
                
              $.post( "sql/sqlProductsPerSupplier.php", { supplierName: supplierName })
                  .done(function( data ) {
                      $('#result').html(data);
                  });            
            }
        })
        .change();
    </script>-->

    <script>
        $( "#search" )
        .click(function () {
		  var isDiscontinued = '';
		  
		  if($('#chbDiscontinuedId').is(':checked') === true){
			isDiscontinued = 0;
		  }else{
			isDiscontinued = 1;
		  }
		  
          var supplierName = $("#supplierName option:selected").text();
          supplierName = supplierName.replace(" ","[space]");
         
          
		  var dateFrom = $('#dateFrom').val();
		  var dateTo = $('#dateTo').val();
            if (supplierName != 'Choose Supplier'){
                var spinner = '<Div class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></DIV>';
                $('#result').html(spinner);
                
              $.post( "sql/sqlProductsPerSupplier.php", { supplierName: supplierName, isDiscontinued: isDiscontinued, dateFrom: dateFrom, dateTo: dateTo })
                  .done(function( data ) {
                      $('#result').html(data);
                  });            
            }
        })
        .change();
    </script>
  </body>
</html>