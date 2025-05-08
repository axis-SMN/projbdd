<!DOCTYPE html>
<?php 
require_once "fonctions_db.php";
$html="";
try{

			        $ma_db=connexion();
			        
			        $html=affichetempsmoyen($ma_db);
			        

			    }catch (Exception $ex) {

			    die("ERREUR FATALE : ". $ex->getMessage().'<form><input type="button" value="Retour" onclick="history.go(-1)"></form>');  // termine le script
			    }
 ?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="style/display.css">
	<title>Temps moyens des arrêts</title>
	
	<?php echo $html; ?>
</head>
<body>



</body>
</html>