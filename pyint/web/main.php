<html>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script src="dom.jsPlumb-1.7.5-min.js"></script>
	
	<style>
		.component{
			background-color: grey;
			width: 90px;
			height: 90px;
			position:absolute;
			text-align: center;
			
			font-family: 'Roboto', sans-serif; 
		}
		

		._jsPlumb_drag_select * {
 		   -webkit-touch-callout: none;
 		   -webkit-user-select: none;
 		   -khtml-user-select: none;
 		   -moz-user-select: none;
 		   -ms-user-select: none;
 		   user-select: none;    
		}
		.editinput{
			width: 100%;
		}
	</style>
	</head>



	<body style="width:1080px; height:1000px">


<nav class="navbar navbar-default navbar-fixed-top">
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><button type="button"  class="btn btn-small btn-info navbar-btn"id="addThreeway">Threeway</button>&nbsp;&nbsp;</li>
            <li><button type="button"  class="btn btn-small btn-info navbar-btn"id="addAnd">And</button>&nbsp;&nbsp;</li>
            <li><button type="button"  class="btn btn-small btn-info navbar-btn"id="addOr">Or</button>&nbsp;&nbsp;</li>
            <li><button type="button"  class="btn btn-small btn-info navbar-btn"id="addTemp">Temperature</button>&nbsp;&nbsp;</li>
            <li><button type="button"  class="btn btn-small btn-info navbar-btn"id="addWebButton">WebButton</button>&nbsp;&nbsp;</li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>


<!-- Need to set up a parser for the basic spec here. Need to set up specs, random IDs, plus coords-->
<?php
require 'Predis/Autoloader.php';

Predis\Autoloader::register();

$client = new Predis\Client();


$des=$client->get("descriptor");


?>
	<div id="hello" spec="i:35-1" style="<?php echo $client->get("i:35-1:pos")?>" class="component source"><p class="txt"><?php echo $client->get("i:35-1:name");?></p><br><button  type="button" class="edit btn btn-info btn-sm">edit</button></div>
	<div id="hello3" spec="i:36-1" style="<?php echo $client->get("i:36-1:pos")?>" class="component source"><p class="txt"><?php echo $client->get("i:36-1:name");?></p><br><button  type="button" class="edit btn btn-info btn-sm">edit</button></div>

	<div id="hello2"  spec="i:34-0" style="<?php echo $client->get("i:34-0:pos")?>" class="component output"><p class="txt"><?php echo $client->get("i:34-0:name");?></p><br><button  type="button" class="edit btn btn-info btn-sm">edit</button></div>
	<div id="finalout"  spec="i:35-0" style="<?php echo $client->get("i:36-0:pos")?>" class="component output"><p class="txt"><?php echo $client->get("i:36-0:name");?></p><br><button  type="button" class="edit btn btn-info btn-sm">edit</button></div>
<?php

$desc=json_decode($des,TRUE);
$added=array();
foreach ($desc as $item) {
	if($item["function"]!="direct"){
		?>
		<div id="<?php echo $item["id"]?>"  style="<?php echo $client->get($item["id"].":pos")?>" function="<?php echo $item["function"] ?>" class="component module"><?php echo $item["function"]?></div>
	<?php
	}
		foreach ($item["inputs"] as $key) {
			if(!in_array($key, $added)){
				if($key[0]=="w"){?>
					
					<div id="wb-<?php echo $key?>" style="<?php echo $client->get($key.":pos")?>"  spec="<?php echo $key ?>" class="component source"><p class="txt"><?php echo $client->get($key.":name");?></p><br><button  type="button" class="edit btn btn-info btn-sm">edit</button><button type="button" class="wbButton btn btn-info btn-sm">del</button></div>
					
				<?php }else if ($key[0]=="t"){?>
					
					<div id="temp-<?php echo $key?>"  style="<?php echo $client->get($key.":pos")?>" spec="<?php echo  $key?>" class="component source "><p>Temperature</p><p class="txt"><?php echo $client->get($key.":name")?></p><button type="button" class="edit btn btn-info btn-sm">edit</button></div>
					
				<?php }
				array_push($added, $key);
			}
		}

}
?>

	<script>
	window.eps={}
	$(document).ready(function(){
		$("#addThreeway").click(function(){
			id="threeway-"+Math.floor((Math.random() * 40000) + 0)
			$("body").append('<div id="'+id+'"function="threeway" class="component module">THREE</div>');
			x=$("#"+id);
			x.css("left",Math.floor((Math.random() * 400) + 40));
			x.css("top",Math.floor((Math.random() * 400) + 40));
			y=x.attr("id");
			initThreeway(y);
			var els = document.querySelectorAll(".component");
			firstInstance.draggable(els);
			setClickEdits();
		});
		$("#addOr").click(function(){
			id="or-"+Math.floor((Math.random() * 40000) + 0)
			$("body").append('<div id="'+id+'"function="or" class="component module">OR</div>');
			x=$("#"+id);
			x.css("left",Math.floor((Math.random() * 400) + 40));
			x.css("top",Math.floor((Math.random() * 400) + 40));
			y=x.attr("id");
			initThreeway(y);
			var els = document.querySelectorAll(".component");
			firstInstance.draggable(els);
			setClickEdits();
		});
		$("#addAnd").click(function(){
			id="and-"+Math.floor((Math.random() * 40000) + 0)
			$("body").append('<div id="'+id+'"function="and" class="component module">AND</div>');
			x=$("#"+id);
			x.css("left",Math.floor((Math.random() * 400) + 40));
			x.css("top",Math.floor((Math.random() * 400) + 40));
			y=x.attr("id");
			initThreeway(y);
			var els = document.querySelectorAll(".component");
			firstInstance.draggable(els);
			setClickEdits();
		});
		$("#addTemp").click(function(){
			id="temp-"+Math.floor((Math.random() * 40000) + 0)
			$("body").append('<div id="'+id+'" spec="t:'+id+'" class="component source "><p>Temperature</p><p class="txt"> < 25</p><button type="button" class="edit btn btn-info btn-sm">edit</button>');
			x=$("#"+id);
			x.css("left",Math.floor((Math.random() * 400) + 40));
			x.css("top",Math.floor((Math.random() * 400) + 40));
			y=x.attr("id");
			initSource(y);
			var els = document.querySelectorAll(".component");
			firstInstance.draggable(els);
			setClickEdits();	
		});
		$("#addWebButton").click(function(){
			id="wb-"+Math.floor((Math.random() * 40000) + 0)
			$("body").append('<div id="'+id+'" spec="w:'+id+'" class="component source"><p class="txt">New Button</p><button type="button" class="edit btn btn-info btn-sm">edit</button><button type="button" class="wbButton btn btn-info btn-sm">del</button></div></div>');
			x=$("#"+id);
			x.css("left",Math.floor((Math.random() * 400) + 40));
			x.css("top",Math.floor((Math.random() * 400) + 40));
			y=x.attr("id");
			initSource(y);
			var els = document.querySelectorAll(".component");
			firstInstance.draggable(els);
			setClickEdits();	
		});
		setClickEdits();
	});

	jsPlumb.ready(function() {
		firstInstance = jsPlumb.getInstance();
		firstInstance.importDefaults({
		  Connector : [ "Bezier", { curviness: 150 } ],
		  Anchors : [ "RightCenter" ],
		  DragOptions: {
                    stop: function (event) {
                    	window.testEvent=event;
                    	lft=$(event.el).css("left");
                    	tp=$(event.el).css("top");
                    	if($(event.el).hasClass("module")){
                    		nme=$(event.el).attr("id");
                    	}else{
                    		nme=$(event.el).attr("spec");
                    	}
                    	$.post("/switch/update_pos.php",{"id":nme,"data":"left:"+lft+"; top:"+tp+";"});
                        
                    	}
                    }

		});



$(".source").each(function(){initSource($(this).attr("id"))});
$(".output").each(function(){initOutput($(this).attr("id"))});
$(".module").each(function(){initThreeway($(this).attr("id"))});

//And dump out JSON here for the script to take over where...
window.conns='<?php echo $des;?>';

window.conns=JSON.parse(conns);
for (var i = 0; i < window.conns.length; i++) {
	ccon=window.conns[i];
	if(ccon["function"]=="direct"){
		inid=$("[spec='"+ccon["inputs"][0]+"']").attr("id");
		outid=$("[spec='"+ccon["outputs"][0]+"']").attr("id");
		console.log("direct: "+inid+" "+outid);
		firstInstance.connect({source:window.eps[inid]["outs"][0], target:window.eps[outid]["ins"][0]});
	}else{
		in1=$("[spec='"+ccon["inputs"][0]+"']").attr("id");
		in2=$("[spec='"+ccon["inputs"][1]+"']").attr("id");
		device=ccon["id"];
		out1=$("[spec='"+ccon["outputs"][0]+"']").attr("id");
		firstInstance.connect({source:window.eps[in1]["outs"][0], target:window.eps[device]["ins"][0]});
		firstInstance.connect({source:window.eps[in2]["outs"][0], target:window.eps[device]["ins"][1]});
		firstInstance.connect({source:window.eps[device]["outs"][0], target:window.eps[out1]["ins"][0]});
	}
};
//JS to parse that JSON and programmatically add connections.
		firstInstance.bind("connection",function(e,q){gatherList()});
		firstInstance.bind("connectionDetached",function(e,q){gatherList()});
		firstInstance.bind("connectionMoved",function(e,q){gatherList()});
		var els = document.querySelectorAll(".component");
		firstInstance.draggable(els);
	});

function setClickEdits(){
	$(".edit").off("click");
	$(".edit").click(function(){
			window.t=this;
			x=$(this).parent().children(".txt").text();
			if (x != ""){
				$(this).parent().children(".txt").remove();
				$(this).parent().append("<input class='editinput' type='text' value='"+x+"'/>");
				$(this).text("Done");
			}else{
				x=$(this).parent().children(".editinput").val();
				$(this).parent().children(".editinput").remove();
				$(this).parent().append("<p class='txt'>"+x+"</p>");
				console.log({"id":$(this).parent().attr("spec"),"data":x});
				$.post("/switch/update_data.php",{"id":$(this).parent().attr("spec"),"data":x})
				$(this).text("Edit");
			}
		});
	$(".wbButton").off("click");
	$(".wbButton").click(function(){
		firstInstance.remove($(this).parent().attr("id"));
		gatherList();
	});
}

function initThreeway(item){
x1=firstInstance.addEndpoint(item,{anchor:'BottomLeft'},{ isSource:false, isTarget:true, maxConnections:1});
x2=firstInstance.addEndpoint(item,{anchor:'TopLeft'},{ isSource:false, isTarget:true, maxConnections:1});
x3=firstInstance.addEndpoint(item,{anchor:'Right'},{ isSource:true, isTarget:false, maxConnections:40});
window.eps[item]={};
window.eps[item]["ins"]=[x1,x2];
window.eps[item]["outs"]=[x3];

}

function initSource(item){

x1=firstInstance.addEndpoint(item,{anchor:'Right'},{ isSource:true, isTarget:false, maxConnections:40});
window.eps[item]={};
window.eps[item]["outs"]=[x1];

}

function initOutput(item){
x1=firstInstance.addEndpoint(item,{anchor:'Left'},{ isSource:false, isTarget:true, maxConnections:1});
window.eps[item]={};
window.eps[item]["ins"]=[x1];

}


function gatherList(){
	//Iterate over all of our manipulators ( hello1), and find their inputs and outputs. Connecting manupulators to manipulators doesn't work.

	window.descriptor=[];
	$(".module").each(function(){

	inputs = firstInstance.getConnections({ target:$(this).attr("id") });
	var inputs_spec=[];
	for (var i = inputs.length - 1; i >= 0; i--) {
		inputs_spec.push($(inputs[i].source).attr("spec"));
	};
	outputs= firstInstance.getConnections({ source:$(this).attr("id") });
	var outputs_spec=[];
	for (var i = outputs.length - 1; i >= 0; i--) {
		outputs_spec.push($(outputs[i].target).attr("spec"));
	};
	if(inputs_spec.length < 1 && outputs_spec < 1){
		console.log("Empty Widget!");
	}else{
		window.descriptor.push({'function':$(this).attr("function"),'id':$(this).attr("id"),'inputs':inputs_spec,'outputs':outputs_spec});
	}
	});
//Check for outputs connected directly to inputs. (Implemeted as another function though)
	$(".output").each(function(){
		ins=firstInstance.getConnections({target:$(this).attr("id")});
		for (var i = ins.length - 1; i >= 0; i--) {
			if($(ins[i].source).hasClass("source")){
				window.descriptor.push({'function':'direct','inputs':[$(ins[i].source).attr("spec")],'outputs':[$(this).attr("spec")]});
			}

		};
	});
	$.post("/switch/update_descript.php",{"descrip":JSON.stringify(window.descriptor)});
	return window.descriptor;
}
	</script>
	</body>
</html>