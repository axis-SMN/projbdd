<?php
// Connexion à la base
$pdo = new PDO('mysql:host=db;dbname=groupXX;charset=utf8', 'groupXX', 'secret');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tables autorisées pour éviter injection
$tables = ['AGENCE', 'HORRAIRE', 'EXCEPTION'];

// Si une table est sélectionnée
$table = $_POST['table'] ?? null;
$filters = [];

if ($table && in_array($table, $tables)) {
    // Préparation des champs dynamiquement selon la table choisie
    $stmt = $pdo->query("DESCRIBE $table");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Construction de la requête avec WHERE si des filtres sont définis
    $sql = "SELECT * FROM $table";
    $where = [];
    $params = [];

    foreach ($columns as $col) {
        if (!empty($_POST[$col])) {
            if (preg_match('/_ID$|ID$|SEQUENCE|DIRECTION|CODE|TYPE/', $col)) {
                // Nombre ou ID : contrainte d'égalité
                $where[] = "$col = :$col";
                $params[":$col"] = $_POST[$col];
            } elseif (preg_match('/DATE/', $col)) {
                // Date : contrainte d'égalité
                $where[] = "$col = :$col";
                $params[":$col"] = $_POST[$col];
            } elseif (preg_match('/HEURE_DEPART|HEURE_ARRIVEE/', $col)) {
                // Heures de départ et d'arrivée : contrainte d'égalité
                $where[] = "$col = :$col";
                $params[":$col"] = $_POST[$col];
            }else {
                // Texte : contrainte de contenance
                $where[] = "$col LIKE :$col";
                $params[":$col"] = "%" . $_POST[$col] . "%";
            }
        }
    }

    if ($where) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $query = $pdo->prepare($sql);
    $query->execute($params);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recherche dans les tables</title>
</head>
<body>
    <h1>Recherche filtrée</h1>

    <form method="POST">
        <label for="table">Choisir une table :</label>
        <select name="table" id="table" onchange="this.form.submit()">
            <option value="">--Choisir--</option>
            <?php foreach ($tables as $t): ?>
                <option value="<?= $t ?>" <?= $table == $t ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if (isset($columns)): ?>
        <form method="POST">
            <input type="hidden" name="table" value="<?= $table ?>">
            <h2>Filtres pour la table <?= $table ?></h2>
            <?php foreach ($columns as $col): ?>
                <label><?= $col ?>:</label>
                <input type="text" name="<?= $col ?>" value="<?= $_POST[$col] ?? '' ?>"><br>
            <?php endforeach; ?>
            <input type="submit" value="Filtrer">
        </form>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <h2>Résultats</h2>
        <table border="1">
            <tr>
                <?php foreach (array_keys($results[0]) as $col): ?>
                    <th><?= $col ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($results as $row): ?>
                <tr>
                    <?php foreach ($row as $val): ?>
                        <td><?= htmlspecialchars($val) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($table && isset($results)): ?>
        <p>Aucun résultat trouvé.</p>
    <?php endif; ?>
</body>
</html>
