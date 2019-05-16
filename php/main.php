<script type="text/javascript" src="ajaxfuns.js"></script>


<?php 

function paginationElements($conn)
{
	$limit = 5;
	$aktualPage = isset($_GET['page'])?$_GET['page'] : 1;
	$jump = ($aktualPage-1)*$limit;

	$q = "SELECT idNews FROM news";
	$results = $conn->query($q);
	$number_of_columns = $results->num_rows;
	$numberPages = ceil($number_of_columns/$limit);

	$results->close();

	if($aktualPage>1)
		echo "<a href='".$_SERVER['PHP_SELF']."?page=".($aktualPage-1)."'>&lt;&lt;</a>&nbsp;&nbsp;";

	for($i=0; $i<$numberPages; $i++)
		echo "<a href='".$_SERVER['PHP_SELF']."?page=".($i+1)."'>[".($i+1)."]</a>";

	if($aktualPage<$numberPages)
		echo "&nbsp;&nbsp;<a href='".$_SERVER['PHP_SELF']."?page=".($aktualPage+1)."'>&gt;&gt;</a>";
}



function timetable($con)
{
	echo '<div>';
		

		$sql = "Select season from actualseason order by id desc limit 1";
		$res = $con->query($sql);

		if($res->num_rows>0)
		{
			$val = $res->fetch_assoc();

			echo "<div class='timetable_header'>";
			echo 	"<header>";
			echo 		"<h2>Terminarz rozgrywek seniorów <br/> sezon ".$val['season']."/".((int)$val['season']+1)."</h2>";
			echo 	"</header>";
			echo "</div>";

			echo "<section>";
			echo 	"<div class='games_table'>";

			$res->close();
			$sql = "Select * from timetable".$val['season']."";

			$result = $con->query($sql);

			if($result->num_rows>0)
			{
				$count=1;
				$date="";
				while ($row=$result->fetch_assoc()) 
				{
					if($date=='' || $date!=$row['date'])
					{
						if($date!='')
							echo '</table></div>';
						$date=$row['date'];
						echo '<div class="games_title">';
						echo 	'<header>'; 
						echo 		'<h3>Kolejka '.$count.' &nbsp; &nbsp; '.$date.'</h3>';
						echo 	'</header>'; 
						echo '</div>';
						echo 	'<table>';
						$count++;
					}
					
					echo '<tr>';
					echo '<td class="team_timetable1">'.$row['team1'].'</td>';
					echo '<td class="dash">-</td>';
					echo '<td class="team_timetable2">'.$row['team2'].'</td>';
					echo '<td class="score1">'.$row['score1'].'</td>';
					echo '<td class="dots">:</td>';
					echo '<td class="score2">'.$row['score1'].'</td>';
					echo '<td class="info">'.$row['hour'].'</td></tr>';		
					echo '<tr><td colspan="7" class="info2">'.$row['comment'].'</td></tr>';	

				}
				echo '</table>';
			}
		}	
		echo '</section></div>
</div>';
}

function home($c)
{
	echo "<main>
				<article>
					<div id='descAkt'>
						<h1>Aktualności</h1>
					</div>
				</article>

				<article>
					<section>
						<div id='games'>
							<div id='next-last-game'><span class='icon-exchange'></span></div>
							<div id='name-game'>
								<div id='flip-card'>
									<div id='front'>
										<div class='flip-card-conteiner'>
											<div class='flip-card-description'>Następny mecz</div>
											<div class='flip-card-image'><img src='images/logo/logo.gif' style='width: 100%' height='100%'/> </div>
											<div class='flip-card-info-front'>
												<table>
													<tr><td>Przeciwnik:</td><td>Tęcza Jankowice</td></tr>
													<tr><td>Miejsce:</td><td>Stadion KS Przedmieście</td></tr>
													<tr><td>Godzina:</td><td>17:00</td></tr>
													<tr><td>Data:</td><td>12.08.2017</td></tr>
												</table>
											</div>
										</div>
									</div>
									<div id='back'>
										<div class='flip-card-conteiner'>
											<div class='flip-card-description'>Poprzedni mecz</div>
											<div class='flip-card-image'><img src='images/logo/logo.gif' style='width: 100%' height='100%'/> </div>
											<div class='flip-card-info-back'>
												<p>Wiraż Chłopice</p>
												<p>0:2</p>
												<p>KS Przedmieście</p>
												<p>17.07.2017 godz: 17:00</p>
											</div>
										</div>
									</div>
								</div>
							</div> 
						</div>
					</section>
				</article>

				<article>
					<section>
						<div class='news'>";
								$conn = $c;
								$limit =5;
								$cur_page = isset($_GET['page'])?$_GET['page'] : 1;
								$jump = ($cur_page-1)*$limit;

								$q = "SELECT idNews FROM news";
								$results = $conn->query($q);
								$number_of_columns = $results->num_rows;
								$number_of_pages = ceil($number_of_columns/$limit);

								$results->close();
							

								$sql = "Select * from news order by idNews DESC LIMIT $jump,$limit";
								$result = $conn->query($sql);

								if($result->num_rows>0)
									{
										while ($row = $result->fetch_assoc()) 
										{
											echo "<div class='new1'>
													<img src='".$row['logo']."' width='10%' style='float: left;' />
												  	<div class='text'>
													<p class='dateEvent'>".$row['date']."</p>
													<p class='titleEvent'>".$row['title']."</p>
													<p>".$row['description']."</p>";


												 	$sqlCom = "Select * from comments where idNews='".$row['idNews']."' order by idCom DESC";
												 	$results = $conn->query($sqlCom);

												 	if($results->num_rows>0)
												 	{
												 		echo "<div class='all_comments'>";
												 		while($rows=$results->fetch_assoc())
												 		{
														echo "<div class='com'><span class='commentDate'>".$rows['dateTime']."</span></br>".$rows['comment']."<span class='nick'> ~".$rows['nick']."</span> </div>";
												 		}
												 		echo "</div>";
												 	}

											echo "<div class='add_comment'>
														<form  name='comment' method='POST' action='addComment.php'>
														<input type='hidden' name='id' value='".$row['idNews']."'/>
														<input type='text' name='nick' class='nick' minlength='3' placeholder='nick' onfocus=\"this.placeholder=''\" onblur=\"this.placeholder='nick'\" maxlength='20' required='true'/><br/>
														<textarea name='area' placeholder='komentarz' minlength='10' onfocus=\"this.placeholder=''\" onblur=\"this.placeholder='komentarz'\" maxlength='400' required='true'></textarea>	
														<br/>
														<input type='checkbox' name='reg' style='float: left;' required><span class='reg' onClick='fnOkno()'/>Zapoznałem się z regulaminem</span>
														<div style='clear: both;'></div>
														<br/>
														<input class='button' type='submit' name='add_com' value='Dodaj'/>							
														</form>
													</div>
												 </div>
													<div style='clear:both;'></div>
												  </div>	
													";

											
										}
									}

								$result->close();
								
								

							
							
						echo "</div>
						<div id='pagin'>";	
							paginationElements($cur_page,$number_of_pages); //main.php
							
							
						echo "</div>
					</section>
				</article>
			</main>
		<aside>
			<div id='table'>";
			
		
			shortTable($conn);
			  
			echo "</div>

			
			<div id='map'>
			<fieldset>
			<legend><h4><em>Jak dojechać<em></h4></legend>
				<a href='https://www.google.pl/maps/place/Stadion+KS+%22Przedmie%C5%9Bcie%22+Jaros%C5%82aw/@50.0401575,22.6701838,15z/data=!4m5!3m4!1s0x0:0x50c43ec84f4e7d5f!8m2!3d50.0404745!4d22.6751405' title='Położenie stadionu KS Przedmieście' target='_blank'>
					<img src='images/mapa.jpg' width='100%' alt='Położenie stadionu KS Przedmieście'>
				</a>
			</div>
			</fieldset>
			
		</aside>
		
		<div style='clear: both;'></div>";
		$conn->close();
}

function shortTable($conn)
{
			$sql = "Select season from actualseason order by id desc limit 1";
		$res = $conn->query($sql);

		if($res->num_rows>0)
		{
			$anw = $res->fetch_assoc();

				$sql2 = "Select team,games,pkt from table".$anw['season']."  ORDER BY pkt DESC, games ASC, win DESC, draw DESC, lost ASC, gplus DESC, gminus ASC, pos ASC";
				$result = $conn->query($sql2);

				if($result->num_rows>0)
				{
					echo "<fieldset><legend><h4><em>Tabela</em></h4></legend>";
					echo "<table>";
					echo "<tr><td class='posName'><strong>Poz.</strong></td><td class='teamName'><strong>Drużyna</strong></td><td class='gamesName'><strong>M.</strong></td><td class='pointsName'><strong>Pkt.</strong></td></tr>";
					$count=1;
					while($row=$result->fetch_assoc())
					{
							echo "<tr><td class='posName'>".($count++).".</td><td class='teamName'>".$row['team']."</td><td class='gamesName'>".$row['games']."</td><td class='pointsName'>".$row['pkt']."</td></tr>";	
					}
					echo "</table></fieldset>";
				}
				$result->close();
		}
		$res->close();
}
	
?>
