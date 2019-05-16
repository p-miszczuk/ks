var XHR = null;

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

			if(match.length > 0)
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

	$("#new-gallery-btn").on('click', function(){
		alert("adad");//addGalleryElements($(this));
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

		if(XHR != null)
		{		
			XHR.onreadystatechange = function()
			{
				if(XHR.readyState == 4)
				{
					if(XHR.status == 200)
					{
						//document.getElementById("dd").innerHTML = XHR.responseText;
						window.location.href = "http://localhost/example/ks/nawigation.php?url=aktSeason";
					}
					else
					{
						alert("wystapił błąd "+XHR.status);
					}
				}
				else
					document.getElementById("show").innerHTML = "<img src='images/ajax-loader.gif' alt='wczytywanie danych' />";
			}

			XHR.open("GET", "php/ajaxphp.php?newActSeason="+encodeURIComponent(val), true);
			//XHR.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			XHR.send(null);		
		}
}

function delActElement(val)
{		
		let XHR = ajaxInit();
		
		if (XHR != null)
		{		
			XHR.onreadystatechange = function()
			{
				if(XHR.readyState == 4)
				{
					if(XHR.status == 200)
					{
						//document.getElementById("dd").innerHTML = XHR.responseText;
						window.location.href = "nawigation.php?url=aktSeason";
					}
					else
					{
						alert("wystapił błąd "+XHR.status);
					}
				}
				else
					document.getElementById("show").innerHTML = "<img src='images/ajax-loader.gif' alt='wczytywanie danych' />";
			}

			XHR.open("GET", "php/ajaxphp.php?delAktElem="+encodeURIComponent(val), true);
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
		
		if (XHR != null)
		{		
			XHR.onreadystatechange = function()
			{
				if(XHR.readyState == 1 || XHR.readyState == 2 || XHR.readyState == 3)
				{
					document.getElementById("show").innerHTML = "<img src='images/ajax-loader.gif' alt='wczytywanie danych' />";					
				}
				else
				{
					if(XHR.status == 200)
					{
						document.getElementById("showMessage").innerHTML = XHR.responseText;
						document.getElementById("show").innerHTML = "";						
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

function addGalleryElements(this)
{
	this.prop("disabled", true);
}