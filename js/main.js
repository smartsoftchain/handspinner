

$(function(){
	console.log("cases=>"+cases);
  var maxWidth = 625
  var f1 = "<?php echo $color1; ?>";
  var f2 = "<?php echo $files; ?>";
  var type= "<?php echo $color1; ?>";
  var cases = "<?php echo $cases1; ?>";
  var path = "case/"+cases+"/"+type+"/";
  
  var $text = $('#text');
  var $strokeColor=  $('#stroke-color');
  var $fillColor=  $('#fill-color');
  var lgtmText = null;
  var $fontFamilies = $('#font-families');
  //var $fontFamily = $('#font-family');
  var $bold = $('#bold');
  var $italic = $('#italic');
  var $x1 = parseInt($('#x').val());
  var $y1 = parseInt($('#y').val());
  var $h1 = parseInt($('#h').val());
  var $w1 = parseInt($('#w').val());
  var $xt = parseInt($('#xt').val());
  var $yt = parseInt($('#yt').val());
  console.log("path="+path);
  var canvas = new fabric.Canvas('myCanvas');
  
		canvas.on('mouse:down', function(e) {
          //console.log('Mouse down');
        });
        canvas.on('mouse:up', function(e) {
          //console.log('Mouse up');
        });
        canvas.on('mouse:move', function(e) {
          console.log('Mouse move');
        });
        


	canvas.setBackgroundImage(path+f1+".png", canvas.renderAll.bind(canvas), {
            originX: 'left',
            originY: 'top',
            left: 0,
            top: 0,
            width:625,
            height:625
        });
	canvas.setOverlayImage(path+f1+"_w.png", canvas.renderAll.bind(canvas), {
            originX: 'left',
            originY: 'top',
            left: 0,
            top: 0,
            width:625,
            height:625,
            selectable:false
        });


	fabric.Image.fromURL("./upload/"+f2, function(img) {
  console.log("x="+$x1);
		canvas.add(img.set({ left: $x1, top: $y1,width: $h1,height: $w1 }));
		
		console.log("xt="+xt+" yt="+yt);
		canvas.add(new fabric.Text($text.val(), {
	      left: $xt, top: $yt,
	      fill: $fillColor.val(),
	      stroke: $strokeColor.val(),
	      strokeWidth: 2,
	      fontFamily: $fontFamilies.val(),
	      fontSize: 50,
          fontWeight: ($bold.is(':checked') ? 'bold' : null),
          fontStyle: ($italic.is(':checked') ? 'italic' : null)
	    }));
      

	});


	fabric.util.addListener(document.getElementById('casecolr'), 'change', function () {
	    canvas.backgroundImage.remove();
	    var colors = $(this).val();
	    
		canvas.setBackgroundImage(path+colors+".png", canvas.renderAll.bind(canvas), {
	            originX: 'left',
	            originY: 'top',
	            left: 0,
	            top: 0,
	            width:625,
	            height:625
	        });
	        	
	    canvas.renderAll();
	});

    canvas.renderAll();
    
	console.log(canvas);

	canvas.on('touch:gesture',function(event){
	    isGestureEvent = true;      
	    var lPinchScale = event.self.scale;  
	    var scaleDiff = (lPinchScale -1)/10 + 1;  // Slow down zoom speed    
	    canvas.setZoom(canvas.viewport.zoom*scaleDiff);   

	});


	$(".strs").on("change",function(){
        var cases = $("#casesample").val();
        var color = $("#casecolr").val();
        var type = $("#casetype").val();
		var h = $("#h").val();
		var w = $("#w").val();
		var files = $("#files").val();
		draw(color,files,cases,type,w,h);
	});
});