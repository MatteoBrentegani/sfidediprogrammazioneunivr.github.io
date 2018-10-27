<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<title>Conferma registrazione</title>
</head>
<body>
	<div class="container">
		<header>
			<br>
			<h1 style="text-align: center">Iscrizione a Sfide di Programmazione</h1>
		</header>
		<br>
		<div>
<?php
	include "/var/www/private/db_sfide.php";

	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

	if ($mysqli->connect_errno) {
		die("Errore di connessione al database: " . $mysqli->connect_error);
	}

	$stmt = $mysqli->prepare("INSERT INTO studenti(nome, cognome, email, matricola, telefono, corso, anno) VALUES (?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("ssssssi", $_POST["first-name"], $_POST["last-name"], $_POST["email"], $_POST["id"], $_POST["phone"], $_POST["course"], $_POST["year"]);
	$success = $stmt->execute();
	$id = $mysqli->insert_id;

	if ($success) {
		$stmt = $mysqli->prepare("INSERT INTO conoscenze(studente, sa_programmare, concetti_di_programmazione, tecniche_algoritmiche, algoritmi, competenze, ha_partecipato_a_gare, gare_partecipate, conosce_git) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$sa_programmare = $_POST["knows-programming"] == "true";
		$concetti_di_programmazione = implode(";", $_POST["programming-concepts"]) ?? null;
		$tecniche_algoritmiche = implode(";", $_POST["known-algorithmic-concepts"]) ?? null;
		$algoritmi = implode(";", $_POST["known-algorithms"]) ?? null;
		$competenze = implode(";", $_POST["competences"]) ?? null;
		$ha_partecipato_a_gare = $_POST["partecipated-at-contests"] == "true";
		$gare_partecipate = implode(";", $_POST["participated-contests"]) ?? null;
		$conosce_git = $_POST["knows-git"] == "true";
		$stmt->bind_param("iissssisi", $id, $sa_programmare, $concetti_di_programmazione, $tecniche_algoritmiche, 
			$algoritmi, $competenze, $ha_partecipato_a_gare, $gare_partecipate, $conosce_git);
		$stmt->execute();

		if ($_POST["knows-git"] == "true" and $_POST["git-page"]) {
			$stmt = $mysqli->prepare("INSERT INTO pagine_git(studente, url) VALUES (?, ?)");
			$stmt->bind_param("is", $id, $_POST["git-page"]);
			$stmt->execute();
		}

		if (array_key_exists("languages", $_POST)) {
			$languages = $_POST["languages"];
			$stmt = $mysqli->prepare("INSERT INTO linguaggi(studente, nome, esperienza) VALUES (?, ?, ?)"); 
			foreach ($languages as $name => $experience) {
				$stmt->bind_param("iss", $id, $name, $experience);
				$stmt->execute();
			}
		}
	}
	
	$student_name = $_POST["first-name"];

	if ($success) {
		echo "<h2>Successo!</h2>";
		echo "<p>" . $student_name . ", la tua registrazione a 'Sfide di Programmazione' è stata registrata con successo</p>";
	} else {
		echo "<h2>Errore!</h2>";
		echo "<p>La registrazione è fallita. Questo potrebbe essere causato dal fatto che la matricola è già presente nel sistema, oppure alcuni dei dati immessi sono errati</p>";
		echo "<p>Prova a ricomplilare il modulo, se nuovamente la registrazione fallisce faccelo presente via email</p>";
	}
?>
		</div>
	</div>
</body>
</html>

