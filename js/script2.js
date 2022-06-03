$(function() {
			$("#slider").slider({
				orientation: "horizontal",
				range: "min",
				max: 100,
				value: 50,
				slide: refreshSwatch,
				change: refreshSwatch
			});
			/*
			$("#slider2").slider({
				orientation: "horizontal",
				range: "min",
				max: 100,
				value: 50,
				slide: refreshSwatch2,
				change: refreshSwatch2
			});
			*/
			$("#slider").slider("value", 50);
			//$("#slider2").slider("value", 50);
});
	    function refreshSwatch(e) {
			var slider = $("#slider").slider("value");
			var val = parseInt(slider) - 50;
			//console.log("scale"+val);
        	var cases = $j("#casesample").val();
        	var color = $j("#casecolr").val();
        	var files = $j("#files").val();
        	var type = $j("#casetype").val();
        	var wi = parseInt($("#h").val());
        	var w = 500+(val*10);
        	var h = 500+(val*10);
        	console.log(h);
        		$("#h").val(h)
        	draw(color,files,cases,type,w,h);
		}
		/*
		 function refreshSwatch2(e) {
			var slider = $("#slider2").slider("value");
			var val = parseInt(slider) - 50;
        	var cases = $j("#casesample").val();
        	var color = $j("#casecolr").val();
        	var files = $j("#files").val();
        	var type = $j("#casetype").val();
        	var r = val*10;
        	var w = 300;
        	var h = 300;
        	draw(color,files,cases,type,w,h,r);
		}
		*/
		
function draw(f1,f2,cases,type,w,h){
	var w2 = w;
	var h2 = h;

    var canvas  = document.getElementById('myCanvas');
    var context = canvas.getContext('2d');
    var isDragging = false;
    var dragTarget = null;
    var path = "case/"+cases+"/"+type+"/";
//console.log(path+f1+".png");
    var srcs = [
        path+f1+".png",
        "./upload/"+f2,
        path+f1+"_w.png"
    ];
    
    
    
    var images = [];
    for (var i in srcs) {
        images[i] = new Image();
        images[i].src = srcs[i];
    }

    var loadedCount = 0;
    for (var i in images) {
    
        images[i].addEventListener('load', function() {
            if (++loadedCount == images.length) {
                var x = 0;
                var y = 0;
                //var w = 300;
                //var h = 300;
                
                for (var j in images) {
                	if(j == 0){
                		w = 625;
                		h = 625;
                	}else if(j == 2){
                		w = 625;
                		h = 625;
                		x = 0;
                		y = 0;
                		//context.globalCompositeOperation = "lighter";
                	}else{
                		//console.log(images[i].drawOffsetX+"=>"+images[i].drawOffsetY);
                		x = $("#x").val();
                		y = $("#y").val();
                		w = w2;
                		h = h2;
                		
                		
                	}
                    images[j].drawOffsetX = x;
                    images[j].drawOffsetY = y;
                    images[j].drawWidth   = w;
                    images[j].drawHeight  = h;

                    context.drawImage(images[j], x, y, w, h);
                    //x += 50;
                    //y += 70;
                }
            }
        }, false);

        
    }

    var mouseDown = function(e) {
    	//console.log(e);
        var posX = parseInt(e.clientX - canvas.offsetLeft);
        var posY = parseInt(e.clientY - canvas.offsetTop);

        for (var i = images.length - 1; i >= 0; i--) {
        	//console.log("d=>"+images[i].src);
	        if(i == 1){
	            if (posX >= images[i].drawOffsetX &&
	                posX <= (images[i].drawOffsetX + images[i].drawWidth) &&
	                posY >= images[i].drawOffsetY &&
	                posY <= (images[i].drawOffsetY + images[i].drawHeight)
	            ) {
	            	
	              dragTarget = i;
	              isDragging = true;
	              break;
	            }
            }
        }
	}

    var mouseUp = function(e) {
    	$(document).off('.noScroll');
        isDragging = false;
    }

    var mouseOut = function(e) {
    	
         mouseUp(e);
	}

    var mouseMove = function(e) {
        var posX = parseInt(e.clientX - canvas.offsetLeft-150);
        var posY = parseInt(e.clientY - canvas.offsetTop);

        if (isDragging) {
            context.clearRect(0, 0, canvas.width, canvas.height);

            var x = 0;
            var y = 0;
            var w = 150;
            var h = 100;
            for (var i in images) {
            	
	                if (i == dragTarget) {
	                    x = posX - images[i].drawWidth / 2;
	                    y = posY - images[i].drawHeight / 2;
	                    
	                    images[i].drawOffsetX = x;
	                    images[i].drawOffsetY = y;
						$("#x").val(x);
						$("#y").val(y);
						$("#h").val(images[i].drawWidth);
	                } else {
	                    x = images[i].drawOffsetX;
	                    y = images[i].drawOffsetY;
	                }
	                w = images[i].drawWidth;
	                h = images[i].drawHeight;
	                
	                context.drawImage(images[i], x, y, w, h);
            	
            }
        }
    }
    
    var mouseMove2 = function(e) {
    	$(document).on('touchmove.noScroll', function(e) {e.preventDefault();});
        var posX = parseInt(e.touches[0].pageX - canvas.offsetLeft+150);
        var posY = parseInt(e.touches[0].pageY - canvas.offsetTop-150);
//pageX
console.log(e);
console.log("client "+e.touches[0].pageX+"=>"+e.touches[0].pageY);
console.log("offset "+canvas.offsetLeft+"=>"+canvas.offsetTop);
            context.clearRect(0, 0, canvas.width, canvas.height);

            var x = 0;
            var y = 0;
            var w = 150;
            var h = 100;
            for (var i in images) {
	                if (i == 1) {
	                    x = posX - images[i].drawWidth / 2;
	                    y = posY - images[i].drawHeight / 2;
	                    
	                    images[i].drawOffsetX = x;
	                    images[i].drawOffsetY = y;
						$("#x").val(x);
						$("#y").val(y);
						$("#h").val(images[i].drawWidth);
	                } else {
	                    x = images[i].drawOffsetX;
	                    y = images[i].drawOffsetY;
	                }
	                w = images[i].drawWidth;
	                h = images[i].drawHeight;
	                console.log("i=>"+i+" x=>"+x+" y=>"+y+" h=>"+h);
	                
	                context.drawImage(images[i], x, y, w, h);
            	
            }
        
    }
    
    
    canvas.addEventListener("touchstart", function(e){mouseDown(e);}, false);
	canvas.addEventListener("touchmove", function(e){mouseMove2(e);}, false);
	canvas.addEventListener("touchend", function(e){mouseOut(e);}, false);
    canvas.addEventListener('mousedown', function(e){mouseDown(e);}, false);
    canvas.addEventListener('mousemove', function(e){mouseMove(e);}, false);
    canvas.addEventListener('mouseup',   function(e){mouseUp(e);},   false);
    canvas.addEventListener('mouseout',  function(e){mouseOut(e);},  false);
}