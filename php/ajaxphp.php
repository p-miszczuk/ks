<?php  
	
	session_start();
	require_once("connect.php");

	if(isset($_SESSION['admin']))
	{
		if(isset($_GET['editAktSeason']) && isset($_GET['newValue']))
		{
			$akt1 = $_GET['editAktSeason'];
			$akt2 = $_GET['newValue'];

			$sql = "Update actualseason set season='$akt2' where id='$akt1'";

			if(!$conn->query($sql))
			$_SESSION['error_connect'] = 1;
		}
		
		else if(isset($_GET['newActSeason']))
		{
			$sql = "Insert into actualseason (id,season) VALUES ('','".$_GET['newActSeason']."')";
			if (!$conn->query($sql))
			$_SESSION['error_connect'] = 1;
		}
		
		else if(isset($_GET['delAktElem']))
		{
			$delAkt = $_GET['delAktElem'];
			$sql = "Delete from actualseason where id='$delAkt'";
			if (!$conn->query($sql))
				$_SESSION['error_connect'] = 1;
		}
		
		else if (isset($_POST['length']))
		{
			$tabLength = $_POST['length'];
			$tmpTab = [];

			for ($i=0; $i<$tabLength; $i++)
			{
				$tmpTab[$i] = $_POST['team'.$i];
			}

			$sql = "Select season from actualseason order by id desc limit 1";
			$res = $conn->query($sql);

			if ($res->num_rows > 0)
			{
				$rowSesaon = "table".$res->fetch_assoc()['season'];

				$check = "SHOW TABLES LIKE '".$rowSesaon."' ";
				$num = $conn->query($check);
				
				if ($num->num_rows > 0)
				{
					echo "Tabela istnieje.";
				}
				else
				{
					$sql = "Create table IF NOT EXISTS `".$rowSesaon."` (id int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, pos int(3), team VARCHAR(50), games int(3), pkt int(3), win int(3), draw int(3), lost int(3), gplus int(3), gminus int(3), winH int(3), drawH int(3), lostH int(3), gplusH int(3), gminusH int(3), winL int(3), drawL int(3), lostL int(3), gplusL int(3), gminusL int(3))";

					if($conn->query($sql))
					{
						for($i=0; $i<$tabLength; $i++)
						{
							$sql = "Insert into `".$rowSesaon."` (id,pos,team,games,pkt,win,draw,lost,gplus,gminus,winH,drawH,lostH,gplusH,gminusH,winL,drawL,lostL,gplusL,gminusL) VALUES ('',0,'".$tmpTab[$i]."',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
							
							if(!$conn->query($sql))
							{
								break;
								echo "Błąd tworzenia bazy danych";
							}
						}
						
						echo "Tabela została utworzona";
					}
					else
						echo "Wystąpił błąd podczas tworzenia tabeli";
				}				
			}
			return true;		
		}
	}

	if(isset($_GET['showTable']))
	{
		echo "<div class='main'>";

		$sql = "Select season from actualseason order by id desc limit 1";
		
		$result = $conn->query($sql);

		if ($result->num_rows > 0)
		{
			$anw = $result->fetch_assoc();

			$sql2 = "Select id,pos,team,games,pkt,win,draw,lost,gplus,gminus,winH,drawH,lostH,gplusH,gminusH,winL,drawL,lostL,gplusL,gminusL from table".$anw['season']."  ORDER BY pkt DESC, pos ASC, win DESC, draw DESC, lost ASC, gplus DESC, gminus ASC";

			if ($res = $conn->query($sql2))
			{
				if ($res->num_rows > 0)
				{	 
					echo "<div id='table-content'>";
					echo "<h2 class=\"header-table\">Tabela \"B\" klasy sezon ".$anw['season']."/".((int)$anw['season']+1)."</h2>";
					echo '<div class="full-table"><table>';
					echo '<tbody><tr>';
					echo '<td class="full-table-games">Poz</td>';
					echo '<td class="full-table-teams">Drużyna</td>';
					echo '<td class="full-table-games">Pkt</td>';		
					echo '<td class="full-table-games">M</td>';
					echo '<td class="full-table-games">W</td>';
					echo '<td class="full-table-games">R</td>';
					echo '<td class="full-table-games">P</td>';
					echo '<td class="full-table-games">B+</td>';
					echo '<td class="full-table-games">B-</td>';
					echo '<td class="full-table-games">Wd</td>';
					echo '<td class="full-table-games">Rd</td>';
					echo '<td class="full-table-games">Pd</td>';
					echo '<td class="full-table-games">Bd+</td>';
					echo '<td class="full-table-games">Bd-</td>';		
					echo '<td class="full-table-games">Ww</td>';	
					echo '<td class="full-table-games">Rw</td>';	
					echo '<td class="full-table-games">Pw</td>';	
					echo '<td class="full-table-games">Bw+</td>';		
					echo '<td class="full-table-games">Bw-</td>';		
					echo '</tr>';
					$count = 1;
					$pkt_ = ''; $games_ = ''; $win_ = ''; $draw_ = ''; $lost_ = ''; $gplus_ = ''; $gminus_ = '';

					while ($row = $res->fetch_assoc())
					{
						if ($row['id'] == 1)
							$opStyle = 'style="color: #ff0000; font-weight: 500;"';  
						else
							$opStyle = "";
						 
						echo '<tr '.$opStyle.' class="row-change-main" >'; 
						if ($pkt_ == '' || (int)$pkt_ != (int)$row['pkt'] || (int)$games_ != (int)$row['games'] || (int)$win_ != (int)$row['win'] || (int)$draw_ != (int)$row['draw'] || (int)$lost_ != (int)$row['lost'] || (int)$gplus_ != (int)$row['gplus'] || (int)$gminus_ != (int)$row['gminus'])
							echo '<td class="full-table-games">'.$count.'</td>';
						else
							echo '<td class="full-table-games"></td>';

						echo '<td class="full-table-teams">'.$row['team'].'</td>';
						echo '<td class="full-table-games">'.$pkt_=$row['pkt'].'</td>';
						echo '<td class="full-table-games">'.$games_=$row['games'].'</td>';
						echo '<td class="full-table-games">'.$win_=$row['win'].'</td>';
						echo '<td class="full-table-games">'.$draw_=$row['draw'].'</td>';
						echo '<td class="full-table-games">'.$lost_=$row['lost'].'</td>';
						echo '<td class="full-table-games">'.$gplus_=$row['gplus'].'</td>';
						echo '<td class="full-table-games">'.$gminus_=$row['gminus'].'</td>';
						echo '<td class="full-table-games">'.$row['winH'].'</td>';
						echo '<td class="full-table-games">'.$row['drawH'].'</td>';
						echo '<td class="full-table-games">'.$row['lostH'].'</td>';
						echo '<td class="full-table-games">'.$row['gplusH'].'</td>';
						echo '<td class="full-table-games">'.$row['gminusH'].'</td>';
						echo '<td class="full-table-games">'.$row['winL'].'</td>';
						echo '<td class="full-table-games">'.$row['drawL'].'</td>';
						echo '<td class="full-table-games">'.$row['lostL'].'</td>';
						echo '<td class="full-table-games">'.$row['gplusL'].'</td>';
						echo '<td class="full-table-games">'.$row['gminusL'].'</td>';
						echo '</tr>';
						
						$count++;
					}
					$res->close();
					echo "</tbody></table></div>";

					echo "<section>";
					echo "<h2 class='hide-element'>Legenda - tabela</h2>";
					echo "<fieldset>";
					echo "<legend>Legenda</legend>";
						echo "<div id='table-legend'>";
							echo "<div class='row'><div class='cell'>Poz</div><div class='cell'>- pozycja w tabeli</div>";	
							echo "<div class='cell'>Pkt</div><div class='cell'>- zdobyte punkty</div></div>";	
							echo "<div class='row'><div class='cell'>M</div><div class='cell'>- rozegrane mecze</div>";	
							echo "<div class='cell'>W</div><div class='cell'>- zwycięstwa</div></div>";
							echo "<div class='row'><div class='cell'>R</div><div class='cell'>- remisy</div>";	
							echo "<div class='cell'>P</div><div class='cell'>- porażki</div></div>";
							echo "<div class='row'><div class='cell'>B+</div><div class='cell'>- bramki strzelone</div>";	
							echo "<div class='cell'>B-</div><div class='cell'>- bramki stracone</div></div>";
							echo "<div class='row'><div class='cell'>Wd</div><div class='cell'>- zwycięstwa u siebie</div>";	
							echo "<div class='cell'>Rd</div><div class='cell'>- remisy u siebie</div></div>";
							echo "<div class='row'><div class='cell'>Pd</div><div class='cell'>- porażki u siebie</div>";	
							echo "<div class='cell'>Bd+</div><div class='cell'>- bramki strzelone u siebie</div></div>";
							echo "<div class='row'><div class='cell'>Bd-</div><div class='cell'>- bramki stracone u siebie</div>";	
							echo "<div class='cell'>Ww</div><div class='cell'>- zwycięstwa wyjazdowe</div></div>";
							echo "<div class='row'><div class='cell'>Rw</div><div class='cell'>- remisy wyjazdowe</div>";	
							echo "<div class='cell'>Pw</div><div class='cell'>- porażki wyjazdowe</div></div>";	
							echo "<div class='row'><div class='cell'>Bw+</div><div class='cell'>- brmki strzelone na wyjeździe</div>";	
							echo "<div class='cell'>Bd-</div><div class='cell'>- bramki stracone na wyjeździe</div></div>";	
						echo "</div>";
					echo "</fieldset>";
					echo "</section>";
					echo "</div>";
				}
				else
					echo "Wystapił nieoczekiwany błąd, spróbuj ponownie później.";
			}
			else
				echo "Brak tabeli aktualnego sezonu. Spróbuj ponownie później.";
		}
		else
			echo "Wystapił błąd, spróbuj ponownie później";
		$result->close();

		echo "</div>";
	}
	
	else if(isset($_GET['content']))
	{
		echo "<section class='main'>";
			
			echo "<div id='desc-akt'>";
				echo "<h2>Aktualności</h2>";
			echo "</div>";
			
			echo "<section id='games'>";
				echo "<h3 class='hide-element'>Następny, poprzedni mecz</h3>"; 
				echo "<div class='check-game'>";
					echo "<div role='button' tabindex='0' aria-pressed='false' class='next-game current-values'>Następny mecz</div>";
					echo "<div role='button' tabindex='0' aria-pressed='true' class='previous-game last-values'>Poprzedni mecz</div>";
				echo "</div>";

				$valuesArr = actValues($conn);
				echo "<div class='content-games'>";
					echo "<div id='next-game-values'>";
						echo "<div id='info-next-game'>";
							echo "<table><tbody>";
								
								echo "<tr><td>Data:</td><td>".$valuesArr[12]."</td></tr>";	
								echo "<tr><td>Przeciwnik:</td><td>".$valuesArr[9]."</td></tr>";
								echo "<tr><td>Miejsce:</td><td>".$valuesArr[11]."</td></tr>";
						 		echo "<tr><td>Godzina:</td><td>".$valuesArr[13]."</td></tr>";
							
								if ($valuesArr[15] != 0)
									echo "<tr><td>Mecz sparingowy</td><td></td></tr>";
							
							echo "</tbody></table>";
						echo "</div>";

						echo "<div class='info-image'><img src='".$valuesArr[10]."' style='width: 100%' height='100%'/> </div>";

					echo "</div>";

					echo "<div id='previous-game-values'>";
						echo "<div id='info-previous-game'>";
							
							if ($valuesArr[5] == 0)
							{
								echo "<p>".$valuesArr[0]."</p>";
								echo "<p>".$valuesArr[7].":".$valuesArr[8]."</p>";
								echo "<p>KS Przedmieście</p>";
							}
							else
							{
								echo "<p>KS Przedmieście</p>";
								echo "<p>".$valuesArr[7].":".$valuesArr[8]."</p>";
								echo "<p>".$valuesArr[0]."</p>";
							}

							echo "<p>".$valuesArr[3]." godz: ".$valuesArr[4]."</p>";


							if ($valuesArr[6] != 0)
								echo "<p>Mecz sparingowy</p>"; 
						
						echo "</div>";

						echo "<div class='info-image'><img src='".$valuesArr[1]."' style='width: 100%' height='100%'/> </div>";

					echo "</div>";
				echo "</div>";
			echo "</section>";

			echo "<section class='news'>";
					echo "<h3 class='hide-element'>Artykuły na stronie</h3>";
					$limit =5;
					$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
					$jump = ($cur_page-1)*$limit;

					$q = "SELECT idNews FROM news";
					$results = $conn->query($q);
					$number_of_columns = $results->num_rows;
					$number_of_pages = ceil($number_of_columns/$limit);

					$results->close();
				
					$sql = "Select * from news order by idNews DESC LIMIT $jump,$limit";
					$result = $conn->query($sql);

					if ($result->num_rows > 0)
					{
						while ($row = $result->fetch_assoc()) 
						{

							echo "<article class='new1'>";
							echo 		"<figure>";
							echo 			"<img class='image-news' src='".strtolower((string)$row['logo'])."' alt='logo' />";
							echo 		"</figure>";
							echo 		"<div class='text' data-idNews='".$row['idNews']."' data-idSite='".$cur_page."'>";
							echo 			"<header>";
							echo 				"<h4><div class='title-event'>".$row['title']."</div></h4>";
							echo 			"</header>";
							echo 			"<div class='article-content'>".cutString($row['description'],200)."</div>";
							echo 			"<footer>";	
		 					echo 				"<div class='date-event'>";
		 					echo 					"<time datetime='".$row['date']."'>".setDate($row['date'])."</time>";
		 					echo				"</div>";
		 					echo 		    "</footer>";
							echo 		"</div>";
							echo "</article>";

						}
					}

					$result->close();				
			echo "</section>";
						
			echo "<asides>";
			echo "<h3 class='hide-element'>Następna, poprzednia strona z artykułami</h3>";
			echo "<nav id='pagin'>";	
				paginationElements($cur_page,$number_of_pages); 
			echo "</nav>";
			echo "</asides>";
			

		echo "</section>";
	}
	
	else if (isset($_GET['aside']))
	{
		aside($conn);
	}
	
	else if (isset($_GET['article'])) 
	{
		$idNews = $_GET['article'];
		$idSite = $_GET['site'];
		$sql = "SELECT * FROM news where idNews='$idNews'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
					
				echo "<div id='mainContent'><article>";
				echo "<div class='new2'>";
				echo 	"<figure>";
				echo 		"<img class='image-news' src='".strtolower($row['logo'])."' alt='' />";
				echo 	"</figure>";

				echo 	"<div class='allText'>";
				echo 		"<header>";
				echo      		"<h2><div class='title-event'>".$row['title']."</div></h2>";
				echo 			"<p class='date-event'><data value='".$row['date']."' >".setDate($row['date'])."</data></p>";
				echo 		"</header>";
				echo 		"<div class='article-content'>".$row['description']."</div>";
							if ($row['photo'] != "")
							{		
				echo			"<div class='article-photo'><figure>";
				echo 				"<img src='".strtolower($row['photo'])."' alt='".$row['title']."' title='Zdjęcie do artykułu ".$row['title']."' >";
				echo			"</figure></div>";
							}
				echo 	 "</div>";
				echo "</div>";
				

				$sqlCom = "Select * from comments where idNews='".$idNews."' order by idCom DESC";
		 		$results = $conn->query($sqlCom);

			 	if ($results->num_rows > 0)
			 	{
			 		echo "<hr />";
			 		echo "<div id='allCountedComment' > Komentarze (".$results->num_rows.") </div>";
			 		echo "<div class='all_comments'>";
			 		echo "<section>";
			 		while($rows=$results->fetch_assoc())
			 		{
						echo "<article>";
						echo "<div class='com'>";
						echo "<span class='commentDate'>".$rows['dateTime']."</span>";
						echo "<p>".$rows['comment']."<span class='nick'> ~".$rows['nick']."</span></p>";
						echo "</div>";
						echo "</article>";
			 		}
			 		echo "</section>";
			 		echo "</div>";
			 	}

				echo "<div class='add_comment'>";
				echo "<section>";
				echo 	"<form name='comment' action='javascript:void(0)' id='addingComent'>";
				echo 		"<input type='hidden' name='id' value='".$idNews."'/>";
				echo 		"<input type='text' name='nick' class='nickAdd' minlength='3' placeholder='nick' onfocus=\"this.placeholder=''\" onblur=\"this.placeholder='nick'\" maxlength='20' required='true'/>";
				echo 		"<textarea name='area' placeholder='komentarz' minlength='10' onfocus=\"this.placeholder=''\" onblur=\"	this.placeholder='komentarz'\" maxlength='400' required='true'></textarea>";	
				echo 		"<div class='check'><input type='checkbox' name='reg' required='true' /> Zapoznałem się z <i class='reg-click'> regulaminem </i></div>";
				echo 		"<input id='button-comment' type='submit' name='submit' value='DODAJ'/>";
				echo 		"<div id='imageLoader'></div>";							
				echo 	"</form>";	
				echo "</section>";
				echo "</div>";
				echo "<aside>";
				echo 	"<button id='back-button' role='link' data-backId='".$idSite."'> WRÓĆ </button>";
				echo "</aside>";
				
			echo "</article>";
			echo "</div>";

			$result->close();
		}
	}
	
	else if (isset($_GET['timetable']) && $_GET['timetable'] == 'true')
	{		
		$sql = "Select season from actualseason order by id desc limit 1";
		$res = $conn->query($sql);

		if ($res->num_rows > 0)
		{
			$val = $res->fetch_assoc();
			
			echo 	"<section class='main'>";
			echo 	"<header class='timetable-header'>";
			echo 		"<h2>Terminarz rozgrywek seniorów <br/> sezon ".$val['season']."/".((int)$val['season']+1)."</h2>";
			echo 	"</header>";
			
			echo 	"<div class='games-table'>";

			$res->close();
			$sql = "Select * from timetable".$val['season']."";

			if ($result = $conn->query($sql))
			{	
				if ($result->num_rows>0)
				{
					$count = 1;
					$date = "";
					
					while ($row = $result->fetch_assoc()) 
					{
						if ($date == "" || $date != $row['date'])
						{
							if ($date != "") 
								echo '</table>';
							
							$date=$row['date'];
					
							echo 	'<table>';
							echo 		'<thead style="margin-bottom: 10px" >';
							echo 			'<tr>'; 
							echo 				'<th colspan="6"><h3>Kolejka '.$count.' &nbsp; &nbsp; '.$date.'</h3></th>';
							echo 			'</tr>';
							echo 		'</thead>'; 	
						
							$count++;
						}

						if ($row['idTeam1'] == 1 || $row['idTeam2'] == 1)
						{
							$str_beg = "<strong>";
							$str_end = "</strong>";
						}
						else
						{
							$str_beg = "";
							$str_end = "";
						}
						
						echo '<tr>';
						echo '<td class="team_timetable1">'.$str_beg.$row['team1'].$str_end.'</td>';
						echo '<td class="score1">'.$str_beg.$row['score1'].$str_end.'</td>';
						echo '<td class="dots">'.$str_beg.':'.$str_end.'</td>';
						echo '<td class="score2">'.$str_beg.$row['score2'].$str_end.'</td>';
						echo '<td class="team_timetable2">'.$str_beg.$row['team2'].$str_end.'</td>';
						echo '<td class="info">'.$str_beg.$row['hour'].$str_end.'</td></tr>';		
						echo '<tr><td colspan="6" class="info2">'.$str_beg.$row['comment'].$str_end.'</td></tr>';	

					}
					echo '</table>';
				}
			}
			else
				echo "<div>Brak terminarza aktualnego sezonu. Spróbuj ponowanie później.</div>";
			echo '</section></div>';
		}		
	}
	
	else if (isset($_GET['players']) && $_GET['players'] == 'true')
	{
		$sql = "Select name, position, lastclub, photo from players";

		if (($result = $conn->query($sql))->num_rows > 0)
		{
			echo "<section class='main'>";
			echo "<h2 id='players-header'>Aktualny skład drużyny seniorów</h2>";
			echo "<div id='players-container' >";
			while ($row = $result->fetch_assoc())
			{
				$image = $row['photo'];
				if ($image == "") 
					$image = "images/no_image.png";
				
				echo 		"<div class='player'>";
				echo 			"<div class='player-photo'><img src='".strtolower($image)."' alt='".$row['name']."' /></div>";
				echo 			"<div class='player-data'>";
				echo 				"<div class='names-of-data'>imię i nazwisko</div>";
				echo 				"<div class='data-names'>".$row['name']."</div>";
				echo 				"<div class='names-of-data'>pozycja na boisku</div>";
				echo 				"<div class='data-names'>".$row['position']."</div>";
				echo 				"<div class='names-of-data'>poprzedni klub</div>";
				echo				"<div class='data-names'>".$row['lastclub']."</div>";
				echo 			"</div>";
				echo 		"</div>";
				
			}
			echo "</div>";
			echo "</div>";
			$result->close();
		}
	}

	else if (isset($_GET['gallery']) && $_GET['gallery'] == 'true')
	{

		$sql = "Select * from gallery";
		$result = $conn->query($sql);
		$gallery = "";

		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$gallery .= $row['name'].$row['src']."&";
			}
		}
			
		echo $gallery;
		// echo $gallery = 'KS Przedmieście![{"alt":"Zdjęcie 1", "src": "1.jpg"},
		// 								  {"alt":"Zdjęcie 2", "src": "2.jpg"}, 
		// 								  {"alt":"Zdjęcie 3", "src": "3.jpg"}]&
		// 				 KS Przedmieście![{"alt":"Zdjęcie 4", "src": "4.jpg"},
		// 								  {"alt":"Zdjęcie 5", "src": "5.jpg"},
		// 								  {"alt":"Zdjęcie 6", "src": "6.jpg"}]&';
	}

	else
	{
		header("Location: index.php");
		$conn->close();
	}

//functions
function actValues($conn)
{
	$sql = "Select teamname, logo, place, date, clock, homegames, sparing, score1, score2 from nextgame order by id DESC";
	$tmpTab = array();
	$logo = '';

	if (($res = $conn->query($sql))->num_rows > 0)
	{ 
		while ($rows = $res->fetch_assoc())
		{ 
			if ($rows['logo'] == "")
			{
				$sql = "Select season from actualseason order by id DESC limit 1";

				if (($results = $conn->query($sql))->num_rows > 0)
				{
					$season = $results->fetch_assoc()['season'];
					$sql = "Select logo from season".$season." where team='".$rows['teamname']."' ";
					
					if (($result = $conn->query($sql))->num_rows > 0)
						$logo = $result->fetch_assoc()['logo'];
						
						if ($logo == "")					
							$logo = "images/logo/logo.gif";
					
				}
				$results->close();
				foreach ($rows as $key => $value) 
				{						
					if ($key == 'logo')
						$value = strtolower($logo);
					
					array_push($tmpTab,$value);
				}		
			}
			else
			{
				foreach ($rows as $key => $value) 
				{	
					array_push($tmpTab,$value);
				}
			}
		}
		$res->close();
		return $tmpTab;
	}
	else
		$tmpName = ["błąd","błąd","błąd","błąd","błąd","błąd","błąd","błąd","błąd","błąd","błąd","błąd","błąd","błąd","błąd","błąd","błąd","błąd"];

	return $tmpTab;
}

function paginationElements($page, $number)
{
	$aktualPage = $page;
	$numberPages = $number;
	
	if ($aktualPage > 1)
		echo "<a href='".$_SERVER['PHP_SELF']."?page=".($aktualPage-1)."'><span class='fa icon-right'></span></a>&nbsp;";

	for ($i=0; $i<$numberPages; $i++)
		echo "<a class='page-number' href='".$_SERVER['PHP_SELF']."?page=".($i+1)."' data-number='".($i+1)."' >".($i+1)."</a>";
	
	if ($aktualPage < $numberPages)
		echo "&nbsp;<a href='".$_SERVER['PHP_SELF']."?page=".($aktualPage+1)."'><span class='icon-right'></span></a>";

}

function aside($conn)
{
		echo "<h2 class='hide-element'>Elementy dodatkowe: tabela, mapa</h2>";
		echo "<div id='table'>";

			$sql = "Select season from actualseason order by id desc limit 1";
		$res = $conn->query($sql);

		if ($res->num_rows > 0)
		{
			$anw = $res->fetch_assoc();

				$sql2 = "Select id,pos,team,games,pkt,win,draw,lost,gplus,gminus from table".$anw['season']."  ORDER BY pkt DESC, pos ASC, win DESC, draw DESC, lost ASC, gplus DESC, gminus ASC";
				$result = $conn->query($sql2);

				$tmp_values = ['','','','','','',''];

				if ($result->num_rows > 0)
				{
					echo "<fieldset><legend><h4><em>Tabela</em></h4></legend>";
					echo "<table>";
					echo "<tr><td class='posName'><strong>Poz.</strong></td><td class='teamName'><strong>Drużyna</strong></td><td class='gamesName'><strong>M.</strong></td><td class='pointsName'><strong>Pkt.</strong></td></tr>";
					
					$count = 1;

					while ($row = $result->fetch_assoc())
					{
						if ($row['id'] == 1)
						{
							$startStrong = "<strong>";
							$closeStrong = "</strong>";
						}
						else
						{
							$startStrong = "";
							$closeStrong = "";
						}

						echo "<tr class='row-change'>";

						if($tmp_values[0] == '' || (int)$tmp_values[0] != (int)$row['pkt']  || (int)$tmp_values[1] != (int)$row['games'] || (int)$tmp_values[2] != (int)$row['win'] || (int)$tmp_values[3] != (int)$row['draw'] || (int)$tmp_values[4] != (int)$row['lost']  || (int)$tmp_values[5] != (int)$row['gplus'] || (int)$tmp_values[6] != (int)$row['gminus'])
						{
							echo "<td class='posName'>".$startStrong.($count++).$closeStrong.".</td>";
						}
						else
							echo '<td class="posName"></td>';


						echo "<td class='teamName'>".$startStrong.$row['team'].$closeStrong."</td><td class='gamesName'>".$startStrong.$row['games'].$closeStrong."</td><td class='pointsName'>".$startStrong.$row['pkt'].$closeStrong."</td></tr>";

							$tmp_values[0] = $row['pkt'];
							$tmp_values[1] = $row['games'];
							$tmp_values[2] = $row['win'];
							$tmp_values[3] = $row['draw'];
							$tmp_values[4] = $row['lost'];
							$tmp_values[5] = $row['gplus'];
							$tmp_values[6] = $row['gminus'];

					}

					echo "</table></fieldset>";
				}
				$result->close();
		}
		$res->close();

		echo "</div>";
			 
		echo "<div id='map'>";
			echo "<fieldset>";
				echo "<legend><h4><em>Jak dojechać<em></h4></legend>";
				echo "<a href='https://www.google.pl/maps/place/Stadion+KS+%22Przedmie%C5%9Bcie%22+Jaros%C5%82aw/@50.0401575,22.6701838,15z/data=!4m5!3m4!1s0x0:0x50c43ec84f4e7d5f!8m2!3d50.0404745!4d22.6751405' title='Położenie stadionu KS Przedmieście' target='_blank'>";
					echo "<img src='images/mapa.jpg' width='100%' alt='Położenie stadionu KS Przedmieście'>";
				echo "</a>";
			echo "</fieldset>";
		echo "</div>";
	$conn->close();
}

function cutString($str,$value)
{
	if (strlen($str) > $value)
	{
		$str = substr($str,0,$value);

		for ($i=strlen($str)-1; $i>0; $i--)
			if ($str[$i] == " ")
			{
				$str = substr($str, 0, $i)." [...]";
				break;
			}
	}

	return $str;
} 

function setDate($value)
{
	if ($value != "")
	{
		$months = ["Styczeń","Luty","Marzec","Kwiecień","Maj","Czerwiec","Lipiec","Sierpień","Wrzesień","Październik","Listopad","Grudzień"];

  		$tab = explode("-", $value);

  		$nr = (int)$tab[1]-1;

 		return $tab[2]." ".$months[$nr]." ".$tab[0];
	}
	else
		return $value;
 
}


?>
