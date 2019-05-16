(function ($){

	$.fn.niceMenu = function()
	{
		const $liNumber = $("#menu-items ul li");
		const $box		= $("#content-menu-box #box-open-menu");
		const $closeTooltip = $("#close-tooltip");
		const heightOfElements  = 41;
		let posCounter = 0;

		let settings = {
			delay: 0,
			positions: [5,15,25],
			jumpLi: count($liNumber.length),
			counter: 0,
			topLi: ($liNumber.length-1)*heightOfElements
		}
		
		let setLiElemShow = {
			padding: "20px",
			width: "180px",
			height: "40px",
			fontSize: "16px",
			zIndex: "200",
			top: settings.topLi
		}

		let setLiElemHide = {
			padding: "0",
			width: "40px",
			height: "5px",
			fontSize: "0px",
			zIndex: "0"
		}

		function count(value)
		{				
			return Math.round(value/3);
		}

		function checkPosition(i,tmp,set)
		{
			tmpNum = tmp;
			if(i>=tmpNum)
			{
				tmpNum+=set.jumpLi;
				set.counter++;
			}
			
			return set.counter
		}

		let tmpNum = settings.jumpLi;

		$liNumber.each(function(i){
				
			posCounter = checkPosition(i,tmpNum,settings);

			$(this).css({
				top: settings.positions[posCounter]
			});
		});
		

		$box.on("click", function(evt){
			if (!$liNumber.is(":animated"))
			{
				$($liNumber.get().reverse()).each(function(i, liElem){
				 	
				 	$(liElem).delay(settings.delay*100).animate({
				 		top: setLiElemShow.top,
				 		fontSize: setLiElemShow.fontSize,
				 		width: setLiElemShow.width,
				 		height: setLiElemShow.height,
				 		zIndex: setLiElemShow.zIndex
				 	},400, function(){
				 		if(i===0)
				 		{
				 			$box.hide();
				 			settings.counter=0;
							tmpNum = settings.jumpLi;
							settings.delay=0;
							setLiElemShow.top = settings.topLi;
				 		}
				 	});
				 	
				 	setLiElemShow.top-=heightOfElements;
				 	settings.delay++;
				 });
			}
		});

		$liNumber.first().on("mousemove", function(evt){
			
			$closeTooltip.css({
				display: "block",
				top: evt.pageY,
				left: evt.pageX+20
			});
		})
		.on("mouseout", function(evt){
			
			$closeTooltip.css({
				display: "none"
			});
		})
		.on("click", function(evt){
		
			evt.stopPropagation();
			evt.preventDefault();
			
			if (!$liNumber.is(":animated"))
			{
				$liNumber.each(function(i){

					posCounter = checkPosition(i,tmpNum,settings);

					$(this).delay(i*100).animate({
						top: settings.positions[posCounter],
						width: setLiElemHide.width,
						height: setLiElemHide.height,
						padding: setLiElemHide.padding,
						fontSize: setLiElemHide. fontSize, 
						zIndex: setLiElemHide.zIndex
					},400, function(){
						
						if(i===$liNumber.length-1)
						{
							$box.show();
							settings.counter=0;
							tmpNum = settings.jumpLi;
						}
					});
				});
			}
		});			

		return true
	}

}(jQuery));


