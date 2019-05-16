let XHR = null;

function init()
{
	$("#sendActSeas").on('click', function()
	{
		addElement($("#addActSeas").val());
	});

	const delBtn = document.querySelectorAll('.deleteAktSeason');
	if (delBtn.length > 0)
	{
		for (let i=0; i<delBtn.length; i++)
		{
			delBtn[i].onclick = function()
			{
				delActElement($(this).siblings('.valAktSeas').text());		
			}
		}
	}
	
	$("#createTable").on('click', function()
	{
		createNewTable(tableCreate);
	});

	$("#team-list").on("input", function(){
		let $input = $(this),
			val = $input.val(),
			list = $input.attr("list"),
			$disp = $(".last-next-logo"),
			$isTeam = $("#is-team-true"),
			match = $("#"+list+" option").filter(function(){
				return ($(this).val() === val);
			});

			if (match.length > 0)
			{
				$disp.css("display", "none");
				$isTeam.val(1);
			}
			else
			{
				$disp.css("display", "block");
				isTeam.val(0);
			}
	});

	$("#new-photo").on('click', function(){addGalleryElements();});

	$("#add-edit-photo").on("click", addGalleryEditElements);

	$(document).on("submit","#gallery-form", function(evt){
	 	
		evt.preventDefault();
		$inputFile = $(".div-input");

		if ($inputFile.length > 0)
		{
			let data = new FormData($(this)[0]);

		 	$.ajax({
		 		url: "php/nav.php",
		 		type: "POST",
		 		data: data,
		 		success: function(resp){
		 			alert(resp);
		 		},
		 		catche: false,
		 		contentType: false,
		 		processData: false
		 	});

		 	$inputFile.remove(); //remove all input elements
		 	$("#gallery-form")[0].reset(); //reset formular
		}
		else
			alert("Dodaj zdjecia");
	});

	$(document).on("click",".btn-delete", function(e){
		e.preventDefault();
		removeAddingPhoto($(this));
	});	

	$(document).on("click",".delete-photo", function(e){
		e.preventDefault();
		$(this).parents("div")[0].remove();		
	});

	$(document).on("submit", "#edit-gallery, #add-player, #form-edit-player", function(e){
		e.preventDefault();
		addPostElements($(this));
		$(this)[0].reset();
	});

	$(document).on("click", "#delete-player", function(e){
		e.preventDefault();
		deletePlayer($(this));
	});

	$(document).on("submit","#edit-season, #create-new-season", function(e){
		
		const imageShort = '.gif';
		let $file = $(this).find(".file-season");

		if ($file.length > 0)
		{
			for (let i=0; i<$file.length; i++)
			{
				if ($file[i].value != "")
				{
					let patt = new RegExp(imageShort);
					let res = patt.test($file[i].value);
					   	
					if (res !== true)
					{
						alert("Niewłaściwy format pliku.");
						return false;	
					}
				}
			}
		}
		else
		{
			alert("Wciśnij przycisk `Dodaj drużynę` aby dodać zespoły");
			return false;
		}
	});
}

function ajaxInit()
{
	try
	{
		XHR = new XMLHttpRequest(); //tworzenie nowego obiektu
	}
	catch(e)
	{
		try
		{
			XHR = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(e)
		{ //mozna utworzyc rowniez object ActiveXObject("Msxm12.XMLHTTP")
			alert("niestety Twoja przeglądrka nie obsługuje AJAX");
		}
	}
	return XHR;
}

function addElement(val)
{		
		let XHR = ajaxInit();
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
					document.getElementById("show").innerHTML = "<img src='images/ajax-loader.gif' alt='wczytywanie danych' />";
			}

			XHR.open("GET", "ajaxphp.php?newActSeason="+encodeURIComponent(val), true);
			//XHR.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			XHR.send(null);		
		}
}

function delActElement(val)
{		
		let XHR = ajaxInit();
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
					document.getElementById("show").innerHTML = "<img src='images/ajax-loader.gif' alt='wczytywanie danych' />";
			}

			XHR.open("GET", "ajaxphp.php?delAktElem="+encodeURIComponent(val), true);
			//XHR.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			XHR.send(null);		
		}
}

function createNewTable(table)
{		
		let link = "";
		for (let i=0; i<table.length; i++)
			link += "team"+i+"="+table[i]+"&";
		
		link +="length="+table.length;

		let XHR = ajaxInit();
		
		if(XHR != null)
		{		
			XHR.onreadystatechange = function()
			{
				let show = document.getElementById("show");
				if(XHR.readyState == 1 || XHR.readyState == 2 || XHR.readyState == 3)
				{
					show.innerHTML = "<img src='images/ajax-loader.gif' alt='wczytywanie danych' />";					
				}
				else
				{
					if(XHR.status == 200)
					{
						show.innerHTML = XHR.responseText; 				
					}
					else
					{
						alert("wystapił błąd "+XHR.status);
					}
				}	
			}

			XHR.open("POST", "php/ajaxphp.php", true);
			XHR.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			XHR.send(link);
		}
}

function addGalleryElements()
{
	const $sub = $("input[type='submit']");
	const $inputs = '<div class="div-input">Opis zdjęcia: '+
					'<input type="text" name="addPhotoName[]" class="add-photo-name" required>'+
					'Dodaj zdjęcie: <input type="file" name="addPhotoFile[]" accept="image/*" class="add-photo-file" required><br><span class="btn-delete">usuń okno</span></div>';

	$sub.before($inputs);
}

function addGalleryEditElements()
{
	const $sub = $("#edit-gallery input[type='submit']");
	const $inputs = '<div><li>Opis zdjęcia: '+
					'<input type="text" name="namePhotos[]" required></li>'+
					'<li>Dodaj zdjęcie: <input type="file" name="galleryFile[]" accept="image/*" required></li>'+
					'<li><button class="delete-photo">Usuń</button></li></div>';
	$sub.before($inputs);
}

function removeAddingPhoto(val)
{
	val.parent()[0].remove();
}

function addPostElements(val)
{
	let data = new FormData(val[0]);

	$.ajax({
		url: "php/nav.php",
		type: "POST",
		data: data,
		success: function(resp){
			alert(resp);
		},
		catche: false,
		contentType: false,
		processData: false
	});
}

function deletePlayer(val)
{
	$.ajax({
		url: "php/nav.php?deletePlayer="+val.attr('data-id'),
		data: "GET",
		success: function(resp){
			alert(resp);
			location.reload();
		}
	});
}