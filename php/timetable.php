<?php
	
	session_start();
	require_once("connect.php");

	function move($con,$add)
	{
		header("Location: navigation.php".$add."");
		$con->close();			
	}

	if(isset($_SESSION['admin']))
	{
		if(isset($_POST['addTeams']))
		{
			$season = $_POST['season'];
			$teams = $_POST['team'];
			$files = $_FILES['file'];
			$end = false;

			$sql = "Create table if not exists season".$season." (id int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, team VARCHAR(50), logo VARCHAR(50))";

			if ($conn->query($sql) === true)
			{
				for ($i=0; $i<count($teams); $i++)
				{
					$file_tmp = $files['tmp_name'][$i];
					$file_name = $files['name'][$i];
					$link_tmp = strtolower("images/logo/".$file_name);

					if (is_uploaded_file($file_tmp))
					{
						if (file_exists($link_tmp))
							move_uploaded_file($file_tmp,$link_tmp);
					}

					$sql2 = "Insert into season".$season." (id, team, logo) values ('','$teams[$i]','$link_tmp')";

					if ($conn->query($sql2) === false)
					{
						$_SESSION['error_connect'] = 1;
						move($conn,"");
					}
				}

				$_SESSION['ok'] = 1;
			}
			else
			{
				//$_SESSION['error_connect'] = 1;
				//move($conn,"");
				echo "blad";
			}
		} // end of add season

		else if (isset($_POST['newTimetable']))
		{
			$numberOfGames = $_POST['numberOfGames']."<br>";
			$numberOfRepeat = $_POST['numberOfRepeat'];
			$season = $_POST['season'];
			$dateGames = $_POST['dateGames'];
			$team1 = $_POST['team1'];
			$team2 = $_POST['team2'];
			$hour = $_POST['hour'];
			$comment = $_POST['description'];

			$sql = "Create table if not exists timetable".$season." (id int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, idTeam1 int(3), team1 VARCHAR(50), idTeam2 int(3), team2 VARCHAR(50), score1 int(3), score2 int(3), date VARCHAR(20), hour VARCHAR(50), comment VARCHAR(250))";

			if ($conn->query($sql))
			{
				$count = 0;
				for ($j=0; $j<$numberOfRepeat; $j++)
				{
					for ($i=0; $i<$numberOfGames; $i++)
					{
						$idTeam_1 = getTeam($conn,$team1[$count],$season);
						$idTeam_2 = getTeam($conn,$team2[$count],$season);
						
						$sql_ = "Insert into timetable".$season." (id,idTeam1,team1,idTeam2,team2,date,hour,comment) VALUES ('',$idTeam_1,'$team1[$count]',$idTeam_2,'$team2[$count]','$dateGames[$j]','$hour[$count]', '$comment[$count]')";
			
						if ($conn->query($sql_) === false)
						{
							$_SESSION['error_connect'] = 1;
							move($conn,'');
						}
						else	
							$count++;
					}
				}

				$_SESSION['ok'] = 1;
			}
			else
			{
				$_SESSION['error_connect'] = 1;
			}
		}// end of create new timetable

		else if (isset($_POST['editSeason']))
		{
			$season = $_POST['season'];
			$add = false;

			$team = $_POST['team'];
			$link = $_FILES['file'];

			for ($i=0; $i<count($team); $i++)
			{
				if ($link['name'][$i] || $link['name'][$i] !== '')
				{
					$file_tmp = $link['tmp_name'][$i];
					$file_name = $link['name'][$i];

					$link_tmp = strtolower("images/logo/".$file_name);
					
					if(is_uploaded_file($file_tmp))
					{
						if(!file_exists($link_tmp))
							move_uploaded_file($file_tmp,$link_tmp);
					}

					$sql = "Update ".$season." set team='".$team[$i]."', logo='".$link_tmp."' where id='".($i+1)."'";
					$conn->query($sql);
				}
				else
				{
					$sql = "Update ".$season." set team='".$team[$i]."' where id='".($i+1)."'";
			 		$conn->query($sql);
				}
			}
		} //endo of edit Season

		else if (isset($_GET['delete']))
		{
			$table = $_GET['delete'];
			$sql = "Drop table if exists $table";
			
			if ($conn->query($sql))
			{
				$_SESSION['ok'] = 1;
				move($conn,"");
			}
		} //delete of table
		
		else if (isset($_POST['save']))
		{ 
			$number = $_POST['number'];
			$timeTable = $_POST['timeTable'];
			
			for ($i = 1; $i < (int)($number+1); $i++)
			{
				$id = $_POST['id'.$i];
				$team1 = trim($_POST['team1'.$i]);
				$team2 = trim($_POST['team2'.$i]);
				$idTeam1 = $_POST['idTeam1'.$i];
				$idTeam2 = $_POST['idTeam2'.$i];
				$score1 = $_POST['score1'.$i];
				$score2 = $_POST['score2'.$i];
				$date = trim($_POST['date']);
				$hour = trim($_POST['hour'.$i]);
				$desc = trim($_POST['description'.$i]);

				if ($score1 != '' || $score2 != '')
				{			
					$sql = "Update timetable".$timeTable." set team1='$team1', team2='$team2', score1=$score1, score2=$score2, date='$date', hour='$hour', comment='$desc' where id=$id";
					if($conn->query($sql) === false)
					{
						$_SESSION['error_connect'] = 1;
						break;
					}
					
					if ($score1 > $score2)
					{
						$win =  getValueOfElement($conn,$timeTable,$idTeam1);
						$lost =  getValueOfElement($conn,$timeTable,$idTeam2);

						$obj1 = new addValues((int)$win['games'],(int)$win['pkt'],(int)$win['win'],(int)$win['gplus'],(int)$win['gminus'],(int)$win['winH'],(int)$win['gplusH'],(int)$win['gminusH']);
						$sql1 =  "Update table".$timeTable." set games=".($obj1->a+1).",pkt=".($obj1->b+3).", win=".($obj1->c+1).", gplus=".($obj1->d+(int)$score1).", gminus=".($obj1->e+(int)$score2).",winH=".($obj1->f+1).", gplusH=".($obj1->g+(int)$score1).", gminusH=".($obj1->h+(int)$score2)." where id=$idTeam1";

						$obj2 = new addValues((int)$lost['games'],(int)$lost['pkt'],(int)$lost['lost'],(int)$lost['gplus'],(int)$lost['gminus'],(int)$lost['lostL'],(int)$lost['gplusL'],(int)$lost['gminusL']);
						$sql2 =  "Update table".$timeTable." set games=".($obj2->a+1).", lost=".($obj2->c+1).", gplus=".($obj2->d+(int)$score2).", gminus=".($obj2->e+(int)$score1).",lostL=".($obj2->f+1).", gplusL=".($obj2->g+(int)$score2).", gminusL=".($obj2->h+(int)$score1)." where id=$idTeam2";

						if (!$conn->query($sql1) || !$conn->query($sql2))
						{
							$_SESSION['error_connect'] = 1;
							break;
							move($conn,"?url=editTeamTable");
						}


					}
					else if ($score1 < $score2)
					{
						$lost =  getValueOfElement($conn,$timeTable,$idTeam1);
						$win =  getValueOfElement($conn,$timeTable,$idTeam2);

						$obj1 = new addValues((int)$win['games'],(int)$win['pkt'],(int)$win['win'],(int)$win['gplus'],(int)$win['gminus'],(int)$win['winL'],(int)$win['gplusL'],(int)$win['gminusL']);
						$sql1 =  "Update table".$timeTable." set games=".($obj1->a+1).",pkt=".($obj1->b+3).", win=".($obj1->c+1).", gplus=".($obj1->d+(int)$score2).", gminus=".($obj1->e+(int)$score1).",winL=".($obj1->f+1).", gplusL=".($obj1->g+(int)$score2).", gminusL=".($obj1->h+(int)$score1)." where id=$idTeam2";

						$obj2 = new addValues((int)$lost['games'],(int)$lost['pkt'],(int)$lost['lost'],(int)$lost['gplus'],(int)$lost['gminus'],(int)$lost['lostH'],(int)$lost['gplusH'],(int)$lost['gminusH']);
						$sql2 =  "Update table".$timeTable." set games=".($obj2->a+1).", lost=".($obj2->c+1).", gplus=".($obj2->d+(int)$score1).", gminus=".($obj2->e+(int)$score2).",lostH=".($obj2->f+1).", gplusH=".($obj2->g+(int)$score1).", gminusH=".($obj2->h+(int)$score2)." where id=$idTeam1";


						if (!$conn->query($sql1) || !$conn->query($sql2))
						{
							$_SESSION['error_connect']=1;
							break;
							move($conn,"?url=editTeamTable");
						}
					}
					else 
					{
						$draw1 =  getValueOfElement($conn,$timeTable,$idTeam1);
						$draw2 =  getValueOfElement($conn,$timeTable,$idTeam2);

						$obj1 = new addValues((int)$draw1['games'],(int)$draw1['pkt'],(int)$draw1['draw'],(int)$draw1['gplus'],(int)$draw1['gminus'],(int)$draw1['drawH'],(int)$draw1['gplusH'],(int)$draw1['gminusH']);
						$sql1 =  "Update table".$timeTable." set games=".($obj1->a+1).",pkt=".($obj1->b+1).", draw=".($obj1->c+1).", gplus=".($obj1->d+(int)$score1).", gminus=".($obj1->e+(int)$score2).",drawH=".($obj1->f+1).", gplusH=".($obj1->g+(int)$score1).", gminusH=".($obj1->h+(int)$score2)." where id=$idTeam1";

						$obj2 = new addValues((int)$draw2['games'],(int)$draw2['pkt'],(int)$draw2['draw'],(int)$draw2['gplus'],(int)$draw2['gminus'],(int)$draw2['drawL'],(int)$draw2['gplusL'],(int)$draw2['gminusL']);
						$sql2 =  "Update table".$timeTable." set games=".($obj2->a+1).",pkt=".($obj2->b+1).", draw=".($obj2->c+1).", gplus=".($obj2->d+(int)$score2).", gminus=".($obj2->e+(int)$score1).",drawL=".($obj2->f+1).", gplusL=".($obj2->g+(int)$score2).", gminusL=".($obj2->h+(int)$score1)." where id=$idTeam2";

						if (!$conn->query($sql1) || !$conn->query($sql2))
						{
							$_SESSION['error_connect'] = 1;
							break;
							move($conn,"?url=editTeamTable");
						}
					}
				}
				else
				{
					$sql = "Update timetable".$timeTable." set team1='$team1', team2='$team2', score1=null, score2=null, date='$date', hour='$hour', comment='$desc' where id=$id";
					if($conn->query($sql) === false)
					{
						$_SESSION['error_connect'] = 1;
						break;
						move($conn,"?url=editTeamTable");
					}
				}			
			}
		} //end of edit timeTable
		
		else if(isset($_GET['number']))
		{
			$id = $_GET['number'];
			//id,pos,team,pkt,win,draw,lost,gplus,gminus,winH,drawH,lostH,gplusH,gminusH,winL,drawL,lostL,gplusL,gminusL
			$tabElem = ['pos','team','games','pkt','win','draw','lost','gplus','gminus','winH','drawH','lostH','gplusH','gminusH','winL','drawL','lostL','gplusL','gminusL'];

			$seas = $_POST['season'];

			for ($i=0; $i<count($tabElem); $i++)
			{
				if ($i != 1)
					$sql = "Update table".$seas." set ".$tabElem[$i]."=".$_POST['poz'.($i+1)]." where id=".$id."";
				else
					$sql = "Update table".$seas." set ".$tabElem[$i]."='".$_POST['poz'.($i+1)]."' where id=".$id."";

				if (!$conn->query($sql))
				{
					$_SESSION['error_connect'] = 1;
					break;
					move($conn,"?url=editTable");
				}	
			}

			$_SESSION['ok'] = 1;
			move($conn,"?url=editTable");
		}
		// last and next game adding data to database
		else if (isset($_POST['nextGame']))
		{
			$home = (isset($_POST['home'])) ? 1 : 0;
			$sparing = (isset($_POST['sparing'])) ? 1 : 0;
			$team = $_POST['team'];
			$place = $_POST['where'];
			$date = $_POST['date'];
			$clock = $_POST['clock'];
			$score1 = $_POST['score1'];
			$score2 = $_POST['score2'];
			$isTeam = (isset($_POST['isteam'])) ? 1 : 0;
			$logo = "";
			$que = "Select * from nextgame where id=1";
			$res = $conn->query($que);
			$row = $res->fetch_assoc();

			if ($isTeam == 1)
			{
				$logo = "";
			}
			else
			{
				$file_tmp = $_FILES['file']['tmp_name']; //nazwa pliku tymczasowego
				$file_name = $_FILES['file']['name']; //docelowa nazwa pliku
				$logo = strtolower("images/logo/".$file_name); // link do pliku w celu sprawdzenia czy dany plik już istnieje

				if (is_uploaded_file($file_tmp)) // zwraca true jesli plik zostal przesłany na serwer
				{
					if (!file_exists($logo))
						move_uploaded_file($file_tmp,$logo);
				}
				else
				{
					$sql = "Select season from actualseason order by id desc limit 1";
					$result = $conn->query($sql);

					if ($result->num_rows > 0)
					{
						$rows = $result->fetch_assoc();

						$query = "Select logo from season".$rows['season']." where id=1";

						$results = $conn->query($query);

						if ($results->num_rows > 0)
							$logo = $results->fetch_assoc()['logo'];

						$results->close();
					}

					$result->close();
				}	
			}

			$sql = "Update nextgame set teamname='$team', logo='$logo', place='$place', date='$date', clock='$clock', homegames='$home', sparing='$sparing', score1=0, score2=0 where id=1";

			$sql2 = "Update nextgame set teamname='".$row['teamname']."', logo='".$row['logo']."', place='".$row['place']."', date='".$row['date']."', clock='".$row['clock']."', homegames=".$row['homegames'].", sparing=".$row['sparing'].", score1=$score1, score2=$score2 where id=2";

			if ($conn->query($sql) && $conn->query($sql2))
				$_SESSION['ok'] = 1;
			else
				$_SESSION['error_connect'] = 1;
		}
	}

	move($conn,""); //header to navigation.php

	
	function getTeam($con,$team,$season)
	{
		$sql = "Select id from season".$season." where team='".$team."'";
		$res = $con->query($sql);
		
		if ($res->num_rows>0)
		{
			$row=$res->fetch_assoc();			
		}
		else
			return 0;

		return $row['id'];
	}

	function getValueOfElement($con,$season,$idTeam)
	{
		$sql ="Select * from table".$season." where id=$idTeam";
		$res = $con->query($sql);
		
		if ($res->num_rows>0)
			$row=$res->fetch_assoc();
		
		return $row;
	}

	class addValues
	{
		public $a; //games
		public $b; //pkt
		public $c; //wins or draws
		public $d; //golas+
		public $e; //goals-
		public $f; //wins or losts (home or leave)
		public $g; //goals+ (home or leave)
		public $h; //goals- (home or leave)

		function __construct($a_,$b_,$c_,$d_,$e_,$f_,$g_,$h_)
		{
			$this->a=$a_;
			$this->b=$b_;
			$this->c=$c_;
			$this->d=$d_;
			$this->e=$e_;
			$this->f=$f_;
			$this->g=$g_;
			$this->h=$h_;
		}
	}
?>
						