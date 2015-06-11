<html>
	<head>
		<script src="dom.jsPlumb-1.7.5-min.js"></script>
		<style>
		.component{
			background-color: grey;
			width: 90px;
			height: 90px;
			position:absolute;
			text-align: center;
			line-height: 90px;
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
		</style>
		<script>
			jsPlumb.ready(function() {
				firstInstance = jsPlumb.getInstance();
				var els = document.querySelectorAll(".component");
				firstInstance.draggable(els)
			});
		</script>
	</head>

	<body>
	<div class="component">
	hello
	</div>	
	</body>
</html>