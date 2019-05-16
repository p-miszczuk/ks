var XHR = null;

function select()
{
	$("body > header").load("header.html", function(resp,status){
		scroll();
		if (status == "error")
			alert("wystapił nieoczekiwany błąd");
	});

	const href = window.location.href;

	if (href.includes("terminarz")){
		
		getValues("php/ajaxphp.php?timetable=true");

	}

	else if (href.includes("tabela")){
		
		getValues("php/ajaxphp.php?showTable="+encodeURIComponent(true));

	}

	else if (href.includes("zawodnicy")){

		getValues("php/ajaxphp.php?players="+encodeURIComponent(true));

	}

	else if (href.includes("statystyki")){

		$("#container").load("stats.html", function(resp,status){
			
			asideHome();

			if (status == "error")
				alert("Wystąpił nieoczekiwany bład. Spróbuj ponownie później.");
		});
	}
	
	else if (href.includes("historia")){
		
		$("#container").load("history.html", function(resp,status){
			
			asideHome();

			if (status == "error")
				alert("Wystąpił nieoczekiwany bład. Spróbuj ponownie później.");
		});
	}
	
	else if (href.includes("galeria")){
		
		getData('php/ajaxphp.php?gallery='+encodeURIComponent("true"));	

	}

	else if (href.includes("kontakt")){

		$("#container").load("contact.html", function(resp, status){

			asideHome();

			if (status == "error")
				alert("Wystąpił nieoczekiwany bład. Spróbuj ponownie później.");
		});
	}

	else
	{
		homeSite("");
	}

	$("body > footer").load("footer.html", function(resp, status){
		setDate();
		if (status == "error")
				alert("Wystąpił nieoczekiwany bład. Spróbuj ponownie później.");
	});

	$(document).on("click", ".reg-click", fnOkno);

	$(document).on("click", "#menu-svg", function(){

			$(".main-menu").slideToggle(300);
			$(this).toggleClass("anim");	
	});

	$(document).on("click",".main-menu > ol > li", function(){
		
		if ($(window).width() <= 768)
		{
			if ($(this).siblings().find("ul").css("display") == 'block') {

				$(this).siblings().find("ul").slideToggle(300);			
			 	$(this).siblings().find("span").toggleClass("rotate-icon-down");
			 	$(this).find("span").toggleClass("rotate-icon-down");
				$(this).find("ul").slideToggle(300);	
			}
			else {
				$(this).find("span").toggleClass("rotate-icon-down");
				$(this).find("ul").slideToggle(300);
			}		
			
		}
	});
}

function setDate()
{
	document.getElementById("dateYear").innerHTML = new Date().getFullYear();
}


function getHeightOfElements()
{
	return $('.logo').outerHeight(true) || $(document).height();
}

function scroll()
{
	const $bar = $(".nav");
	const $main = $("#container");
	const hieghtOfStickElement = $bar.outerHeight(true); 

	$(window).scroll(function()
	{
		let posOfHeader = getHeightOfElements();
	
		if ($(this).scrollTop() >= posOfHeader)
		{	
			$bar.addClass("nav-sticky");
			$main.css({"margin-top":hieghtOfStickElement});
		}
		else
		{
			$bar.removeClass("nav-sticky");
			$main.css({"margin-top":"0"});	
		}
	});
}

function ajaxInit()
{
	try
	{
		XHR = new XMLHttpRequest(); //tworzenie nowego obiektu
		return XHR;
	}
	catch(e)
	{
		try
		{
			XHR = new ActiveXObject("MSXML2.XMLHTTP.3.0");
			return XHR;
		}
		catch(e)
		{ 
			alert("niestety Twoja przeglądrka nie obsługuje AJAX");
		}
	}
	return 0;
}

function file(url)
{
	"use_strict";

	XHR = ajaxInit();

	if (XHR != 0)
		{
				XHR.onreadystatechange = function()
			{
				if (XHR.readyState == 1 || XHR.readyState == 2 || XHR.readyState == 3)
				{
					document.getElementById("show").innerHTML = "<img src='images/ajax-loader.gif' alt='wczytywanie danych' />";
				}
				else if (XHR.readyState == 4 && XHR.status == 200)
				{
					setTimeout(function(){
						document.getElementById("show").innerHTML = XHR.responseText;
					},2000);
					
				}
			}
			XHR.open("GET", encodeURIComponent(url), true);
			XHR.send(null);
		}
}

function changeElement(val1)
{		
		let XHR = ajaxInit();
		
		let val2 = document.getElementById("inp"+val1).value;

		if(XHR!=null)
		{		
			XHR.onreadystatechange = function()
			{
				if(XHR.readyState == 4)
				{
					if(XHR.status == 200)
					{
						window.location.href = "navigation.php?url=aktSeason";
					}
					else
					{
						alert("wystapił błąd "+XHR.status);
					}
				}
				else
					document.getElementById("aktSeason").innerHTML = "<img src='images/ajax-loader.gif' alt='wczytywanie danych' />";	
			}
		
			XHR.open("GET", "php/ajaxphp.php?editAktSeason="+encodeURIComponent(val1)+"&newValue="+encodeURIComponent(val2), true);
			//XHR.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			XHR.send(null);		
		}
		return false;
}

function getValues(urlAdress)
{
	$.ajax({
		url: urlAdress,
		beforeSend: function() {
		    $('#container').css('background', 'url(images/ajax-loader.gif) no-repeat');
		},
		success: function(response){
			$("#container").html(response);
			$(".main").css({width: "95%", float: "none"});
		},
		error: function(e){
			alert("Wystąpił nieoczekiwany błąd. Spróbuj ponownie poźniej.");
		},
		complete: function(e){
			$('#container').css({background: 'transparent'});
		}
	});
}

function homeSite(link)
{
	const setLink = (link == "") ? "" : "&"+link;

	$.ajax({
		url: 'php/ajaxphp.php?content=true'+setLink, 
		success: function(response){
			$("#container").html(response);
			asideHome();
		},
		error: function(e){
			alert("Błąd po stronie przeglądarki. Twoja przyglądarka jest przestarzała.");
		},
		complete: function(e){
			loadCode(link.slice(link.lastIndexOf("=") + 1));
		}
	});
}

function asideHome()
{
	$.ajax({
		url: 'php/ajaxphp.php?aside='+encodeURIComponent('true'), 
		success: function(response){
			let aside = $("<aside>").html(response);
			
			$("<aside>").html(response).insertAfter($(".main"));
		},
		error: function(e){
			alert("Błąd po stronie przeglądarki. Twoja przyglądarka jest przestarzała.");
		},
		complete: function(e){
		}
	});
}

function openArticle(idNew,idSite)
{
	$main = $("#container");
	$.ajax({
		url : 'php/ajaxphp.php?article='+encodeURIComponent(idNew)+'&site='+encodeURIComponent(idSite),
		beforeSend: function() {
		    $main.css('background', 'url(images/ajax-loader.gif) no-repeat');
		},
		success: function(response){
			$(".main").html(response);
			$(window).scrollTop(0);
		},
		error: function(e){
			alert("Wystąpił nieoczekiwany błąd.");
		},
		complete: function(e){
			$main.css("background", "transparent");
		}
	})
	.done(function(e){
		setElements(); 
	});
}

function setElements()
{	
	const form = document.getElementById("addingComent");
	const submit = form.elements.namedItem("submit");
	
	form.addEventListener("submit", function(e){
		
		let id = 		form.elements[0].value;
		let nick = 		form.elements[1].value;
		let checked = 	form.elements[3].checked;
		let desc = 		form.elements[2].value;

		if (nick != "" && desc != "" && checked == true && nick.length > 3 && desc.length >= 10)
		{
			const $elem = document.getElementById("imageLoader");
			window.scrollTo(0,100);
			submit.type = "hidden";
			$elem.innerHTML="<img src='images/ajax-loader.gif' alt='wczytywanie danych' />";
			addComment(form,id,nick,desc,submit,$elem);
		}
	});

	document.getElementById("back-button").addEventListener("click", function(){

		let mainClass = document.querySelector(".main");

		homeSite("page="+this.getAttribute("data-backId"));
	});

	$("#allCountedComment").on("click", function(){
		$(this).next().toggle(600);
	});
}

function addComment(form,id,nick,comment,submit,$elem)
{
	$.ajax({
		url 	: "php/addComment.php",
		method 	: "POST",
		data 	: {
			id : id,
			nick : nick,
			desc : comment
		}
	})
	.done(function(e){
		form.reset();
		$elem.innerHTML = "Komentarz został dodany. Wkrótce pojawi się na stronie.";
	})
	.fail(function(){
		$elem.innerHTML = "Komentarz nie został dodany. Spróbuj ponownie później.";
	})
	.always(function(res){
		submit.type = "submit";
	});
}

function loadCode(paginNumber)
{	
	$(".next-game, .previous-game").on("click", function(){

		const $this = $(this);
		
		if ($this.css("cursor") == "pointer")
		{
			$this.toggleClass("last-values").addClass("current-values");
			$this.siblings().toggleClass("current-values").addClass("last-values");
			$this.parent().siblings().children().fadeToggle(400).css("display","flex");
		}
	});

	const moreButton = document.querySelectorAll('.text');
		
	moreButton.forEach(function(val){
		val.addEventListener('click',function(){
			openArticle(val.getAttribute("data-idNews"), val.getAttribute("data-idSite"));
		});
	});

	if (paginNumber === "")
		paginNumber = 1;

	$("#pagin .page-number").eq(paginNumber-1).addClass("pagin-border");
	$("#pagin a").addClass("active");

	$("#pagin a").on('click', function(e){
		let link = $(this).attr("href");
		link = link.slice(link.lastIndexOf("?")+1);
		homeSite(link);
		e.preventDefault();
	});
}

function getData(url)
{	
	// getting all gallerys from database
	$.ajax({
		url: url,
		success: function(resp){

			let container  = document.getElementById("container");
			let main = document.createElement("div");
			main.classList.add("main");
			main.innerHTML = "<h2 class='hide-element'>Galeria</h2>";
			let wrapper	  = document.createElement("div"); 
			wrapper.classList.add("gallery-wrapper");

			if (resp != "")
			{
				let galleryNames = new Array();
				let galleryImages = new Array();

		 		let readyResp = resp.substring(0,resp.lastIndexOf('&'));
		 		let shareResp = readyResp.split("&");

				for (let i = 0; i < shareResp.length; i++)
				{ 
					let moreShare = shareResp[i].split("!");
				 	for (let j = 0; j < moreShare.length; j++)
				 	{
				 		(j === 0) ? galleryNames.push(moreShare[j]) : galleryImages.push(moreShare[j]);	
				 				
				 	}
				 	showBoxGallery(i,container,main,wrapper,galleryImages[i],galleryNames[i]);
				}

				main.append(wrapper);
				container.append(main);
				
				asideHome();

				$(".image-box").on("click", function()
		 		{
		 			const $img = $(this).children(":first-child");
		 			$img.galleryStart(JSON.parse(galleryImages[$img.attr("data-id")]));
		 		});
			}
			else
			{
				wrapper.innerHTML = "Brak zdjęć w galerii";
				main.append(wrapper);
				container.append(main);
				asideHome();
			}	

	 	},
	 	error: function(e){
	 		alert("Wystąpił nieoczekiwany błąd "+e);
	 	} 
	});

	function showBoxGallery(i,container,main,wrapper,images,titles)
	{
		//create elements
		let box 	  = document.createElement("figure");
		let imageBox  = document.createElement("div");
		let titleName = document.createElement("figcaption");

		//create and share images, just one src is needed
		let image = new Image();
		let img = JSON.parse(images);
		
		//load and resize image
		image.addEventListener('load',function(){
			if(image.width > image.height)
			{
				image.width = '200';
				image.height = image.width*(image.height/image.width);
			}
			else
			{
				image.height = '200';
				image.width = image.height*(image.width/image.height);
			}

			image.classList.add("image-box-image");
		});

		//set image attribiutes
		image.src = "images/"+img[0].src;
		image.alt = img[0].alt;
		image.setAttribute("data-id", i);
				
		//set classes of created elements and values of title all gallery
		imageBox.classList.add("image-box");
		titleName.classList.add("gallery-name");
		box.classList.add("gallery-box");
		
		titleName.innerHTML = titles;

		//adding elements to container
		imageBox.append(image);
		box.append(imageBox);
		box.append(titleName);
		wrapper.append(box);
	}

}

function fnOkno()
{
	const win = document.createElement('div');
	const close = document.createElement('button');
	const text = document.createElement('h3');
	const txt = document.createTextNode("Regulamin dodawania komentarza");
	const ok = document.createTextNode("Zrozumiałem");
	const ol = document.createElement("ol");
	const hrtop = document.createElement("hr");
	const hrbottom = document.createElement("hr");

	close.style.cssText = 'padding:4px 10px;border:none;border-radius:3px; margin-top: 10px;float:right;';
	text.style.cssText = 'text-align:center;color:#f00;font-size:15px;margin: 5px 0;';
	win.style.cssText = 'position:fixed;top:50%;left:50%;padding:10px 3%;transform:translate(-50%,-50%);width:80%;z-index:50;background-color:#000;font-size:11px;font-style:Arial;color:#fff;border-radius:3px';
	ol.style.padding = '10px 15px';
	ol.innerHTML =  "<li>Administratorem Państwa danych osobowych jest KS Przedmieście Jarosław ul. Burmistrza Jerzego Matusza 11 37-500 Jarosław, e-mail: kontakt@ksprzedmiescie.pl (dalej jako „Administrator”)</li>"+
					"<li>Państwa dane będą przetwarzane w celu zamieszczenia komentarza na stronie internetowej Klubu Sportowego Przedmieście Jarosław.</li>"+
					"<li>Przysługuje Państwu prawo do żądania od Administratora dostępu do Państwa danych osobowych, ich sprostowania, usunięcia, ograniczenia przetwarzania tych danych lub ich przeniesienia.</li>"+
					"<li>Dane osobowe nie będą przekazywane innym odbiorcom.</li>"+
					"<li>Administrator nie przetwarza Państwa danych osobowych przy użyciu narzędzi do zautomatyzowanego podejmowania decyzji, w tym profilowania.</li>"+
					"<li>Wszystkie komentarze zawierające wulgarne słowa lub słowa, które wskazują sugestię wulagryzmu będą usuwane.</li>"+
					"<li>Wszystkie komentarze wymierzone w szeroko pojętą godność kogokolwiek będą usuwane.</li>" +
					"<li>Administrator zastrzega sobie mozliwość wyłączania komentarzy do artykułów.</li>" +
					"<li>Administrator zastrzega sobie mozliwość wyłączania, modyfikowania lub usuwania artykułów.</li>";

	close.appendChild(ok);
	text.appendChild(txt);
	win.appendChild(text);
	win.appendChild(hrtop);
	win.appendChild(ol);
	win.appendChild(hrbottom);
	win.appendChild(close);
	document.body.appendChild(win);

	close.onmouseover = function(){
		this.style.cursor = "pointer";
		this.style.backgroundColor = "#aaa";
	};

	close.onmouseout = function(){
		this.style.cursor = "none";
		this.style.backgroundColor = "#eee";
	};

	close.addEventListener('click', function(){
		document.body.removeChild(win);
	});

    //window.open("../regulamin.html", "Regulamin strony KS Przedmieście Jarosław", "toolbar=no, menubar=no, location=no, personalbar=no, status=no, resizable=yes, scrollbars=yes, copyhistory=yes, width=500, height=500, top=0, left=0");
}