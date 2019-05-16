<?php 
	
	session_start();
	require_once("connect.php");
	
	if ($_SESSION['admin'] == 1)
	{
		if (isset($_POST["addEvent"]))
		{
			$title = $_POST["event"];
			$description = $_POST["description"];
			$file_tmp = $_FILES['fileName']['tmp_name']; //nazwa pliku tymczasowego
			$file_name = $_FILES['fileName']['name']; //docelowa nazwa pliku
			//$file_size = $_FILES['fileName']['size']; //rozmiar pliku
			$link_logo = strtolower("images/logo/".$file_name); // link do pliku w celu sprawdzenia czy dany plik już istnieje

			$file_photo_tmp = $_FILES['photoEvent']['tmp_name'];
			$file_photo_name = $_FILES['photoEvent']['name'];

			$link_photo = strtolower("images/".$file_photo_name);

			if (is_uploaded_file($file_tmp)) // zwraca true jesli plik zostal przesłany na serwer
			{
				if (!file_exists($link_logo))
					move_uploaded_file($file_tmp,$link_logo);
				
			}
			else
				$link_logo = "images/logo/logo.GIF";
			

			if (is_uploaded_file($file_photo_tmp))
			{
				if (!file_exists($link_photo))
					move_uploaded_file($file_photo_tmp,$link_photo);
			}
			else
				$link_photo = "";

				
			$sql = "Insert into news (idNews,date,title,description,logo,photo) Values ('',now(),'$title','$description','$link_logo','$link_photo')";
			
			if($conn->query($sql) === true)
			{
				$conn->close(); 
				$_SESSION['ok'] = 1;
				header('Location: navigation.php');
			}
			else
			{
				$conn->close();
				$_SESSION['error_connect']=1;
				header('Location: navigation.php?url=add_Event');
			}				
		}
		
		else if (isset($_POST["editevent"]) && isset($_GET['id']))
		{
			$id = $_GET['id'];
			$title = $_POST["event"];
			$description = $_POST["description"];
			$linkFlag = false;
			$photoFlag = false;
		
			if ($_FILES['fileName']['name'])
			{
				$file_tmp = $_FILES['fileName']['tmp_name']; //nazwa pliku tymczasowego
				$file_name = $_FILES['fileName']['name']; //docelowa nazwa pliku
				//$file_size = $_FILES['fileName']['size']; //rozmiar pliku
				$link = strtolower("images/logo/".$file_name); // link do pliku w celu sprawdzenia czy dany plik już istnieje

				if (is_uploaded_file($file_tmp)) // zwraca true jesli plik zostal przesłany na serwer
				{
					if (!file_exists($link))
						move_uploaded_file($file_tmp,$link);
				}

				$linkFlag = true;
			}

			if ($_FILES['photoEvent']['name'])
			{
				$file_tmp = $_FILES['photoEvent']['tmp_name']; //nazwa pliku tymczasowego
				$file_name = $_FILES['photoEvent']['name']; //docelowa nazwa pliku
				//$file_size = $_FILES['fileName']['size']; //rozmiar pliku
				$linkPhoto = strtolower("images/logo/".$file_name); // link do pliku w celu sprawdzenia czy dany plik już istnieje

				if (is_uploaded_file($file_tmp)) // zwraca true jesli plik zostal przesłany na serwer
				{
					if (!file_exists($linkPhoto))
						move_uploaded_file($file_tmp,$linkPhoto);
				}
				
				$photoFlag = true;
			}

			if ($linkFlag == true && $photoFlag == true)				
				$sql = "Update news set title='$title', description='$description', logo='$link', photo='$linkPhoto' WHERE idNews='$id'";
			
			else if ($linkFlag == true)
				$sql = "Update news set title='$title', description='$description', logo='$link' WHERE idNews='$id'";
			
			else if ($photoFlag == true)
				$sql = "Update news set title='$title', description='$description', photo='$linkPhoto' WHERE idNews='$id'";
			
			else
				$sql = "Update news set title='$title', description='$description' WHERE idNews='$id'";
			
			if($conn->query($sql) === true)
			{
				$conn->close();
				$_SESSION['ok']=1;
				header('Location: navigation.php');
			}
			else
			{
				$conn->close();
				$_SESSION['error_connect']=1;
				header('Location: navigation.php?url=add_Event');
			}							
		}

		elseif (isset($_GET['delete'])) 
		{
			$id = $_GET['delete'];
			$sql = "Delete from news where idNews = '$id'";
			if($conn->query($sql) === true)
			{
				$conn->close();
				header('Location: navigation.php');
			}
			else
			{
				$_SESSION['error_connect'] = 1;
				$conn->close();
				header('Location: navigation.php?url=change_Event');
			}
		}
	}
	else
	{
		$conn->close();
		header('Location: navigation.php');
	}

?>