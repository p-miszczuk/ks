<?php 
	ob_start();
	session_start();
	include_once("phpmailer/phpmailer.php");
	require_once("connect.php");
	

	if(isset($_SESSION['admin']))
		{
		
		if(isset($_POST['editCom']))
		{
			$nick = $_POST['nick'];
			$comment = $_POST['comment'];
			$idCom = $_POST['idCom'];

			$sql = "Update comments set nick='$nick', comment='$comment' where idCom='$idCom'";

			if($conn->query($sql))
			{
				$conn->close();
				header('Location: navigation.php');
				exit();
			}

		}
		elseif(isset($_GET['delete']))
		{
			$id = $_GET['delete'];

			$sql = "Delete from comments where idCom='$id'";

			if($conn->query($sql) === true)
			{
				$conn->close();
				header('Location: navigation.php');
				exit();
			}
		}
	}

	if($_POST['nick']!="" && $_POST['desc'])
	{	
		$nick = $_POST['nick'];
		$comment = $_POST['desc'];
		$idNews = $_POST['id'];

		if(!$nick==null || !$nick=="")
		{
			$sql = "Insert into comments (idCom,idNews,nick,comment,dateTime) values ('','$idNews','$nick','$comment',now())";

			if($conn->query($sql) === true)
			{
				$title = msg($conn,$idNews);
				mails($nick,date('l jS F Y h:i:s A'),$comment,$title);
			}
		}
	}

	function msg($con,$id)
	{	
		$sql = "Select title from news where idNews='$id'";
		$result = $con->query($sql);
		if($result->num_rows>0)
		{
			$row=$result->fetch_assoc();
			return $row['title'];
		}
		else
		{
			return "error";
		}
	}
?>