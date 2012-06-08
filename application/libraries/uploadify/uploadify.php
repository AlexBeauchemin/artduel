<?php

include_once('../../accesscontrol.php');
include_once('../WideImage/WideImage.php');

function processAvatar($targetFile,$fileName){
    global $APPLICATION_INFO;
	$user_directory = $APPLICATION_INFO['Root'].'/images/avatars/'.$_SESSION['user']['ID'];

    //Delete the old avatar
	/*if ($_SESSION['user']["picture"] != ""){
		if (file_exists($user_directory.'/'.$_SESSION['user']["picture"])){
			unlink($user_directory.'/'.$_SESSION['user']["picture"]);
		}
	}*/
    if(is_dir($user_directory))
        rmdir($user_directory);
	//Create the user directory if it doesn't exists
	#if(!file_exists($user_directory)) {
		mkdir($user_directory);
	#}
		
	$time=time();
	$image_name='avatar-'.$_SESSION['user']['ID'].'-'.$time.'.jpg';
    $image_name_big='avatar-'.$_SESSION['user']['ID'].'-'.$time.'_big.jpg';
	
	//Crop the image, rename it and move it
	$image = WideImage::load($targetFile);
	$image->resize(50, 50 , 'outside','down')->crop('center', 'center' , 50,50)->saveToFile($user_directory.'/'.$image_name,60);
	unset($image);

    //Crop the image, rename it and move it (big)
	$image = WideImage::load($targetFile);
	$image->resize(120, 120 , 'outside','down')->crop('center', 'center' , 120,120)->saveToFile($user_directory.'/'.$image_name_big,90);
	unset($image);
	
	//Delete the uploaded file
	unlink($targetFile);
	unset($targetFile);
	
	//Associate the new avatar with the user in the database
	mysql_query("UPDATE profiles SET picture='".$image_name."', useFbPicture=0 WHERE IDUser=".$_SESSION['user']['ID']);	
	
	//Update the session of the user with the picture
	$_SESSION['user']['picture']=$image_name;
	$_SESSION['user']['useFbPicture']=0;
	
	return $image_name;
}

function processSubmission($targetFile,$fileName){
    global $APPLICATION_INFO;
	$user_directory = $APPLICATION_INFO['Root'].'/images/submissions/'.$_SESSION['user']['ID'];
	$tmp_directory = $APPLICATION_INFO['Root'].'/images/submissions/tmp';

	//Create the user directory if it doesn't exists
	if(!file_exists($user_directory)) {
		mkdir($user_directory,0775,true); 
	} 
	//Create the tmp directory if it doesn't exists
	if(!file_exists($tmp_directory)) {
		mkdir($tmp_directory,0775,true); 
	} 
	
	$time=time();
	$image_name=$_SESSION['user']['ID'].'_'.$time;
	
	//Resize the image, rename it and move it
	//The big one
	$image = WideImage::load($targetFile);
	$image->resize(1080, null , 'inside','down')->saveToFile($tmp_directory.'/'.$image_name.'_big.jpg',90);
	unset($image);
	
	//Medium
	$image = WideImage::load($targetFile);
	$image->resize(470, 500, 'inside','down')->saveToFile($tmp_directory.'/'.$image_name.'_medium.jpg',80);
	unset($image);
	
	//Small
	$image = WideImage::load($targetFile);
	$image->resize(100, 100, 'outside','down')->crop('center','center',100, 100)->saveToFile($tmp_directory.'/'.$image_name.'_small.jpg',80);
	unset($image);
	
	//Delete the uploaded file
	unlink($targetFile);
	unset($targetFile);
	
	//Associate the new avatar with the user in the database
	$query="INSERT INTO submissions SET win=0 , lose=0 , active=0 , IDUser=".protect($_SESSION['user']['ID']).", image='".$image_name."' , dateAdded='".date('Y-m-d H:i:s')."'";
	mysql_query($query);
	return ($tmp_directory.'/'.$image_name.'_medium.jpg');
}

function error($message){
    global $APPLICATION_INFO;
    $myFile = $APPLICATION_INFO['Root'] . '/artduel/errors/uploadify.txt';
    $fh = fopen($myFile, 'a');
    $string = date('Y-m-d H:i:s') . chr(13) . chr(10);
    $string .= $_SESSION['user']['ID'] . chr(13) . chr(10);
    $string .= $message . chr(13) . chr(10) . chr(13) . chr(10);
    fwrite($fh,$string);

    fclose($fh);
}

if ($_SESSION['user']['ID'] && $_SESSION['user']['ID']!=0){
	if (!empty($_FILES)) {
			if (isset($_REQUEST['page'])){
				if ($_REQUEST['page']=="submission"){
					$page='submission';
				}
				else{
					$page='avatar';
				}
			}
			else {
				$redirection=true;
				if (!isset($page)){
					$page='avatar';
				}
			}
			
			$tempFile = $_FILES['Filedata']['tmp_name'];
			
			if ($page == 'submission') 	{
				$targetPath = $APPLICATION_INFO['Root'] . "/images/submissions/tmp/";
			}
			else {
				$targetPath = $APPLICATION_INFO['Root'] . "/images/avatars/tmp/";
			}
				
			$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
			
			if (filesize($tempFile) > 2048000){
				die('The file is too big.');	
			}
			
			$listExt='*.jpg;*.jpeg;*.gif;*.png';
			 $fileTypes  = str_replace('*.','',$listExt);
			 $fileTypes  = str_replace(';','|',$fileTypes);
			 $typesArray = explode('|',$fileTypes);

			 $fileParts  = pathinfo($_FILES['Filedata']['name']);

			 if (in_array(strtolower($fileParts['extension']),$typesArray)) {
				
				move_uploaded_file($tempFile,$targetFile);
				if ($page=='avatar') {
					try{
						$file_name=processAvatar($targetFile,$_FILES['Filedata']['name']);
					}
					catch (Exception $e) {
						die("Error processing the avatar : ".$e->getMessage());
					}
				}
				else {
					try{
						$file_name=processSubmission($targetFile,$_FILES['Filedata']['name']);
					}
					catch (Exception $e) {
						die("Error processing your submission : ".$e->getMessage());
					}
				}
					
				if (isset($redirection)){
					if ($page=="avatar") { 
						header("Location: profil.php");
					}
					else  {
						header("Location: submission.php?save=1");
					}
				}
				else {
					if ($page=='avatar') {
						echo($file_name);
					}
					else{
						echo 'done';	
					}
				}
		
			 } else {
			 	die('Invalid file type.');
			 }
	}
}
else {
	die("Unable to retrieve user data.");	
}

?>