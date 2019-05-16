<?php  

	session_start();
	require_once("connect.php");

	if(isset($_SESSION['admin']))
	{
		if (isset($_POST['galleryName']) && isset($_POST['addPhotoName']) && isset($_FILES["addPhotoFile"]))
		{
			$tmpGalleryNames = $_POST['addPhotoName'];
			$tmpFile = $_FILES["addPhotoFile"];

			$createJSON = "![";

			for ($i=0; $i<count($tmpGalleryNames); $i++) {
				$createJSON .= '{"alt": "'.$tmpGalleryNames[$i].'", "src": "'.$tmpFile['name'][$i].'"},'; 
			}

			$src = substr($createJSON, 0, -1)."]";
			
			$sql = "Insert into gallery (id,name,src) VALUES ('','".$_POST['galleryName']."','$src')";

			if ($conn->query($sql))
			{
				for ($i=0; $i<count($tmpGalleryNames); $i++)
				{
					if(isset($_FILES["addPhotoFile"]['tmp_name'][$i]))
					{
						$file_tmp = $tmpFile['tmp_name'][$i];
						$file_name = $tmpFile['name'][$i];
						$link = "images/".$file_name;

						if(is_uploaded_file($file_tmp))
						{ 
							if(!file_exists($link))
							{
								move_uploaded_file($file_tmp,$link);
						 	}
						}
						else
							echo "Wystąpił błąd. Spróbuj ponownie.";
					}
					else
						echo "Wystąpił błąd. Spróbuj ponownie.";						
				}
			}	
			else
				echo "Galeria nie została dodana, wystąpił nieoczekiwany błąd.";	
		}

		else if (isset($_POST['idGallery']) && isset($_POST['nameGallery']))
		{
			$namePhoto = $_POST['namePhotos'];
			$src = $_POST['srcName'];

			$createJSON = "![";
			$counter = 0;

			for ($i=0; $i<count($src); $i++)
			{
				if (!$_FILES['galleryFile']['name'][$i])
					$createJSON .= '{"alt": "'.$namePhoto[$i].'", "src": "'.$src[$i].'"},';
				else
					$createJSON .= '{"alt": "'.$namePhoto[$i].'", "src": "'.$_FILES['galleryFile']['name'][$i].'"},';

				$counter++;
			}


			for ($i=$counter; $i<count($namePhoto); $i++)
				$createJSON .= '{"alt": "'.$namePhoto[$i].'", "src": "'.$_FILES['galleryFile']['name'][$i].'"},';
			
			$src = substr($createJSON, 0, -1)."]";

			$sql = "Update gallery set name='".$_POST['nameGallery']."', src='".$src."' where id='".$_POST['idGallery']."'";

			if ($conn->query($sql))
			{
				for ($i=0; $i<count($_FILES['galleryFile']['name']); $i++)
				{
					if ($_FILES['galleryFile']['name'][$i])
					{
						$file_tmp = $_FILES['galleryFile']['tmp_name'][$i];
						$file_name = $_FILES['galleryFile']['name'][$i];
						$link = "images/".$file_name;

						if(is_uploaded_file($file_tmp))
						{ 
							if(!file_exists($link))
							{
								move_uploaded_file($file_tmp,$link);
						 	}
						}
					}
				}

				echo "Galeria została dodana";	
			}
			else
				echo "Wystąpił błąd. Spróbuj ponownie.";
		}

		else if (isset($_POST['namePlayer']) && isset($_POST['position']) && isset($_POST['lastClub']))
		{
			$photo = (isset($_FILES['photoPlayer'])) ? "images/".$_FILES['photoPlayer']['name'] : "";
			$sql = "Insert into players (id,name,position,lastclub,photo) VALUES ('','".$_POST['namePlayer']."','".$_POST['position']."','".$_POST['lastClub']."', '".$photo."')";

			if ($conn->query($sql))
			{
				if($_FILES["photoPlayer"]['name'])
				{
					$file_tmp = $_FILES['photoPlayer']['tmp_name'];
					$file_name = $_FILES['photoPlayer']['name'];
					$link = "images/".$file_name;

					if(is_uploaded_file($file_tmp))
					{ 
						if(!file_exists($link))
							move_uploaded_file($file_tmp,$link);
					}
					else
						echo "Wystąpił błąd. Spróbuj ponownie.";
				}

				echo "Dodano zawodnika.";
			}
		}

		else if (isset($_GET['deletePlayer']))
		{
			$sql = "Delete from players where id='".$_GET['deletePlayer']."'";

			if ($conn->query($sql))
				echo "Zawodnik został usuniety.";
			else
				echo "Wystapił błąd. Spróbuj ponownie później.";
		}

		else if (isset($_POST['editName']) && isset($_POST['editPosition']) && isset($_POST['editClub']))
		{
			$photo = (isset($_FILES['filePlayer'])) ? "images/".$_FILES['filePlayer']['name'] : " ";

			if ($photo == " ")
			{
				$sql = "Update players set name='".$_POST['editName']."', position='".$_POST['editPosition']."', lastclub='".$_POST['editClub']."' where id='".$_POST['idValue']."' ";

				if ($conn->query($sql))
					echo "Dane zostały zmienione.";
				else
					echo "Wystapił błąd. Spróbuj ponownie później.";
			}
			else
			{
				$sql = "Update players set name='".$_POST['editName']."', position='".$_POST['editPosition']."', lastclub='".$_POST['editClub']."', photo='".$photo."' where id='".$_POST['idValue']."' ";
				
				if ($conn->query($sql))
				{
					$file_tmp = $_FILES['filePlayer']['tmp_name'];
					$file_name = $_FILES['filePlayer']['name'];
					$link = "images/".$file_name;

					if(is_uploaded_file($file_tmp))
					{ 
						if(!file_exists($link))
							move_uploaded_file($file_tmp,$link);
					}
					else
						echo "Wystąpił błąd. Spróbuj ponownie później.";
				}
					echo "Dane zostały zmienione.";
			}
		}

		else
			echo "Błąd. Podane wartości nie wystepują.";
	}
	else
		header("index.php");

	$conn->close();
?>