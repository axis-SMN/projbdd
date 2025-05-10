<!DOCTYPE html>
<html>
<head>
    <title>Temps d'arrêt moyen par arrêt et par trajet</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
    </style>
</head>
<body>

<h2>Temps d'arrêt moyen par trajet et par itinéraire</h2>

<?php
function format_seconds_to_time($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $secs = $seconds % 60;
    return sprintf("%02d:%02d:%02d", $hours, $minutes, $secs);
}

try {
    $bdd = new PDO('mysql:host=db;dbname=groupXX;charset=utf8', 'groupXX', 'secret');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des noms d'itinéraires
    $itineraireNames = [];
    $itineraireQuery = $bdd->query("SELECT ID, NOM FROM ITINERAIRE");
    while ($row = $itineraireQuery->fetch(PDO::FETCH_ASSOC)) {
        $itineraireNames[$row['ID']] = $row['NOM'];
    }

    // Requête principale
    $sql = "
        SELECT 
            ITINERAIRE_ID,
            TRAJET_ID,
            TIME_TO_SEC(ABS(TIMEDIFF(HEURE_DEPART, HEURE_ARRIVEE))) AS STOP_DURATION
        FROM HORRAIRE
        WHERE 
            HEURE_ARRIVEE NOT IN ('00:00:00', '0') 
            AND HEURE_DEPART NOT IN ('00:00:00', '0')
            AND HEURE_ARRIVEE IS NOT NULL
            AND HEURE_DEPART IS NOT NULL
    ";

    $result = $bdd->query($sql);
    if (!$result) {
        die("Erreur SQL : " . $bdd->errorInfo()[2]);
    }

    $data = [];

    // Regrouper par itinéraire et trajet
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $itineraireId = $row['ITINERAIRE_ID'];
        $trajetId = $row['TRAJET_ID'];
        $duree = intval($row['STOP_DURATION']);  // en secondes

        $data[$itineraireId][$trajetId][] = $duree;
    }

    echo "<table><tr><th>ITINÉRAIRE</th><th>TRAJET</th><th>TEMPS MOYEN PAR ARRÊT</th></tr>";

    $moyennesParItineraire = [];

    foreach ($data as $itineraireId => $trajets) {
        $itineraireNom = isset($itineraireNames[$itineraireId]) ? $itineraireNames[$itineraireId] : "ID {$itineraireId}";

        foreach ($trajets as $trajetId => $durees) {
            $somme = array_sum($durees);
            $count = count($durees);
            $moyenneSec = $count > 0 ? round($somme / $count) : 0;
            $moyenneFormatted = format_seconds_to_time($moyenneSec);

            echo "<tr><td>{$itineraireNom}</td><td>{$trajetId}</td><td>{$moyenneFormatted}</td></tr>";

            $moyennesParItineraire[$itineraireNom][] = $moyenneSec;
        }

        $moyenneItineraireSec = round(array_sum($moyennesParItineraire[$itineraireNom]) / count($moyennesParItineraire[$itineraireNom]));
        $moyenneItineraireFormatted = format_seconds_to_time($moyenneItineraireSec);

        echo "<tr><td><strong>{$itineraireNom}</strong></td><td></td><td><strong>{$moyenneItineraireFormatted} (moyenne itinéraire)</strong></td></tr>";
    }

    echo "</table>";

} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

</body>
</html>
