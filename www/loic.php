<!DOCTYPE html>
<?php 
require_once "fonctions_db.php";
$html="";
if(isset($_POST["action"])){ // si on arrive via le formulaire
		$action = $_POST["action"];
		
		if($action == "Tester"){ //si on a appuyé sur tester
			$dateform=$_POST['date_form'];
			try{

			        $ma_db=connexion();
			        
			        $html=recherche_service($ma_db,$dateform);
			        

			    }catch (Exception $ex) {

			    die("ERREUR FATALE : ". $ex->getMessage().'<form><input type="button" value="Retour" onclick="history.go(-1)"></form>');  // termine le script
			    }
		}	    
}
 ?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<form action="loic.php" method="post">
		<input type="date" name="date_form">
		<input type="submit" name='action' value = "Tester">
	</form>
	<?php echo $html; ?>
</head>
<body>



</body>
</html>