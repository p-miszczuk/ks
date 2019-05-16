<?php 
require_once("connect.php");

if(isset($_GET['showTable']))
{

	$sql = "Select season from actualseason order by id desc limit 1";
	$result = $conn->query($sql);

	if($result->num_rows>0)
	{
		$anw = $result->fetch_assoc();

		$sql2 = "Select pos,team,games,pkt,win,draw,lost,gplus,gminus,winH,drawH,lostH,gplusH,gminusH,winL,drawL,lostL,gplusL,gminusL from table".$anw['season']."  ORDER BY pkt DESC, games ASC, win DESC, draw DESC, lost ASC, gplus DESC, gminus ASC, pos ASC";

		$res = $conn->query($sql2);

		if($res->num_rows>0)
		{	 
			echo "<div class=\"headerTable\"><h3>TABELA SEZON ".$anw['season']."/".((int)$anw['season']+1)."</h3></div>";
			echo '<div class="full_table"><table>
			<tr>
			<td class="games_">Poz.</td>
			<td class="team_">Drużyna</td>
			<td class="games_">Pkt</td>
			<td class="games_">Mecze</td>
			<td class="games_">W</td>
			<td class="games_">R</td>
			<td class="games_">P</td>
			<td class="games_">B+</td>
			<td class="games_">B-</td>
			<td class="games_">Wd</td>
			<td class="games_">Rd</td>
			<td class="games_">Pd</td>
			<td class="games_">Bd+</td>
			<td class="games_">Bd-</td>
			<td class="games_">Ww</td>
			<td class="games_">Rw</td>
			<td class="games_">Pw</td>
			<td class="games_">Bw+</td>
			<td class="games_">Bw-</td>
			</tr>';
			$count=1;
			$pkt_ =''; $games_=''; $win_=''; $draw_=''; $lost_=''; $gplus_=''; $gminus_='';

			while($row=$res->fetch_assoc())
			{
				//echo "pkt ".$pkt_." row ".$row['pkt']."<br/>";
				echo '<tr>';
				if($pkt_=='' || (int)$pkt_!=(int)$row['pkt'] || (int)$games_!=(int)$row['games'] || (int)$win_!=(int)$row['win'] || (int)$draw_!=(int)$row['draw'] || (int)$lost_!=(int)$row['lost'] || (int)$gplus_!=(int)$row['gplus'] || (int)$gminus_!=(int)$row['gminus'])
					echo '<td class="games_">'.$count.'</td>';
				else
					echo '<td class="games_"></td>';

				echo '<td class="team_">'.$row['team'].'</td><td class="games_">'.$pkt_=$row['pkt'].'</td><td class="games_">'.$games_=$row['games'].'</td><td class="games_">'.$win_=$row['win'].'</td><td class="games_">'.$draw_=$row['draw'].'</td><td class="games_">'.$lost_=$row['lost'].'</td><td class="games_">'.$gplus_=$row['gplus'].'</td><td class="games_">'.$gminus_=$row['gminus'].'</td><td class="games_">'.$row['winH'].'</td><td class="games_">'.$row['drawH'].'</td><td class="games_">'.$row['lostH'].'</td><td class="games_">'.$row['gplusH'].'</td><td class="games_">'.$row['gminusH'].'</td><td class="games_">'.$row['winL'].'</td><td class="games_">'.$row['drawL'].'</td><td class="games_">'.$row['lostL'].'</td><td class="games_">'.$row['gplusL'].'</td><td class="games_">'.$row['gminusL'].'</td>';
				echo '</tr>';
				
				$count++;
			}
			$res->close();
			echo "</table></div>";
		}
		else
			echo "Wystapił błąd, spróbuj ponownie później";

	}
	else
		echo "Wystapił błąd, spróbuj ponownie później";
	$result->close();
}


?>