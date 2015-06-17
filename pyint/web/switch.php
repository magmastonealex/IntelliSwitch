<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link href="bootstrap-switch.min.css" rel="stylesheet">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="bootstrap-switch.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script>
	$(document).ready(function(){
		$("[name='switchr']").bootstrapSwitch();
		$(".panel-heading").click(function(){
			$(this).parent().children(".table").collapse("toggle");
		});
		$.support.transition = false;
		$("[name='switchr']").on('switchChange.bootstrapSwitch', function(event, state) {
			window.ex=event;
			console.log(((state) ? 1 : 0));
			$.post("/switch/update_switch.php",{"id":$(this).parent().parent().parent().attr("id"),"state":((state) ? 1 : 0)});
		});
	});
	</script>
	<style>
		.test{
			background-color: grey;
			border-bottom-left-radius: 10px;
			border-bottom-right-radius: 10px;
		}
		.cnt{
			text-align: center;
		}
		.rgt{
			text-align: right;
		}
		.collapsing {
    -webkit-transition: none;
    transition: none;
}
	</style>
</head>

<body>
	<div class="container">
		<div class="panel panel-info">
			
			<div class="panel-heading">Web Switches</div>
			<table class="table collapse in">
<?php
require 'Predis/Autoloader.php';

Predis\Autoloader::register();

$client = new Predis\Client();


$des=$client->get("descriptor");

$desc=json_decode($des,TRUE);
$added=array();
foreach ($desc as $item) {
		foreach ($item["inputs"] as $key) {
			if(!in_array($key, $added)){
				if($key[0]=="w"){?>
					<tr>
				<td class="rgt"><?php echo $client->get($key.":name");?></td><td class="cnt" id="<?php echo $key; ?>"><input type="checkbox" name="switchr" <?php

				if ($client->get($key)=="1"){
					echo 'checked';
				}
				?> ></td>
				</tr>
				<?php
					array_push($added, $key);
				}
			}
		}
}
?>
			</table>
		</div>
	</div>
</body>
</html>