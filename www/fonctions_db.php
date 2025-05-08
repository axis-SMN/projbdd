<?php 
function connexion()
	{//	Numéro de port 08 mysql et 06 mariadb
    /*
		$ma_db= new PDO("mysql:dbname=group06;host=localhost;port=3306" , "group06" , "secret" , 
					   //  type de DB  nom de la DB nom de l'adresse (ou IP)  nom utilisateur  mdp

				
			array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
     */
    $ma_db = new PDO('mysql:host=db;dbname=group06;charset=utf8', 'group06', 'secret');
	return $ma_db;
}
function affichetempsmoyen($ma_db){
	
	$sql ="select iti_nom, trajet_id, temps_arret_moyen from resultat_temps_moyen;";
  $instru= $ma_db->prepare($sql);
  
 	$instru->execute();
 	$instru-> setfetchmode(PDO::FETCH_ASSOC);
 	$tab=$instru->fetchall();
 	$return="\n<TABLE>";
 	$return.="<THEAD>";
 	$return.="<TD>itinéraires</TD><TD>trajets</TD><TD>temps moyens des arrêts</TD>";
 	$return.="</THEAD>";
 	foreach ($tab as $ligne) {
 		$return.="\n<TR>";
 		$return.="<TD>".$ligne['iti_nom']."</TD>";
 		$return.="<TD>".$ligne['trajet_id']."</TD>";
 		$return.="<TD>".$ligne['temps_arret_moyen']."</TD>";
 		$return.="</TR>";
 	}
 	$return.="\n</TABLE>";
 	return $return;
}
function recherche_service($ma_db, $dateform){
	$sql ="select * from services_exception
	          where  date_service =:daterecherche;";
  $instru= $ma_db->prepare($sql);
  $instru->bindvalue('daterecherche',$dateform,PDO::PARAM_STR);
 	$instru->execute();
 	$instru-> setfetchmode(PDO::FETCH_ASSOC);
 	$tab=$instru->fetchall();
 	$return="\n<TABLE>";
 	$return.="<THEAD>";
 	$return.="<TD>Service</TD>";
 	$return.="</THEAD>";
 	foreach ($tab as $ligne) {
 		$return.="\n<TR>";
 		$return.="<TD>".$ligne['nom']."</TD>";
 		$return.="</TR>";
 	}
 	$return.="\n</TABLE>";
 	return $return;
 	
}



 ?>
