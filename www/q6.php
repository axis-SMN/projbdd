<?php
	require_once "fonctions_db.php";
	$action="";
	$message = "";
	$saisie="Gare : <input type='text'  name='nom' value='' >
        nombre d'arrets :<input type='number' name='nb' min='0' max='500' value=''>";
	$boutonTest = "<BR/> <BR/><input type='submit' name='action' value='Tester'>";
	
	if(isset($_POST["action"])){ 
	 // si on a affiché la page via le formulaire
		$action = $_POST["action"];
		$nb = $_POST["nb"];
		$nom = $_POST["nom"];
		if($action == "Tester"){ //si on a appuyé sur tester
			if ($nb=="" and $nom==""){
				$message="Entrez des valeurs dans les saisies si vous voulez voir les données";
			}
			else{
				try{
					$ma_db=connexion();
					$message=select_gare($ma_db,$nom,$nb);
					
				}
				catch (Exception $ex) {
	    			die("ERREUR FATALE : ". $ex->getMessage().'<form><input type="button" value="Retour" onclick="history.go(-1)"></form>');
				}
			}
		}//fin du if tester
 }// fin test formulaire

?>
	
	
<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="style/display.css">
	<title>chercher une gare </title>
</head>
<body>
	<h1>Recherche</h1>
	<form action="q6.php" method="post">
		
		
		<?php
			echo $saisie;
			echo $boutonTest;
				
		?>
	</form>
	<?php
	echo "<P>Résultat :<BR>".$message."</P>";
	?>
</body>
</html>
