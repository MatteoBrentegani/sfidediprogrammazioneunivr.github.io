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
	$send_email = false; // <-- change to true to send a confirmation email 	
	$success = true;

	$dbhost = ""; // <-- insert IP address of db server
	$dbname = "SfideDiProgrammazione";
	$dbuser = ""; // <-- insert username for access to db
	$dbpass = ""; // <-- insert password for db user

	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

	if ($mysqli->connect_errno) {
		die("Errore di connessione al database: " . $mysqli->connect_error);
	}

	$stmt = $mysqli->prepare("INSERT INTO studenti(nome, cognome, email, matricola, telefono, corso, anno) VALUES (?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("ssssssi", $_POST["first-name"], $_POST["last-name"], $_POST["email"], $_POST["id"], $_POST["phone"], $_POST["course"], $_POST["year"]);
	$success = $stmt->execute();

	if ($success) {
		$stmt = $mysqli->prepare("INSERT INTO conoscenze(id, conoscenze) VALUES ((SELECT id FROM studenti WHERE matricola = ?), ?)");
		$stmt->bind_param("ss", $_POST["id"], json_encode($_POST));
		$success = $stmt->execute();
	}
	
	$student_id = $_POST["id"];	
	$student_name = $_POST["first-name"];
	$student_email = $_POST["email"];

	if ($success and $send_email) {
	
		$mail_subject = "Iscrizione Sfide di Programmazione";
		$mail_body = "Benvenuto " . $student_name . "!\r\n\r\n" . 
			"La tua iscrizone a Sfide di Programmazione è stata registrata con successo\r\n";
		$mail_from = "test@example.com"; // <-- insert email address from wich sending the email
		$mail_headers = "From: " . $mail_from . "\r\n" . "Reply-To: " . $mail_from;

		if (!mail($student_email, $mail_subject, $mail_body, $mail_headers)) {
			echo "<h2>Errore!</h2>";
			echo "<p>Non siamo riusciti a spedire l'email di conferma all'indirizzo " . $student_email . "!</p>";
			echo "<p>L'indirizzo immesso è corretto?</p>";
		}
	}

	if ($success) {
		echo "<h2>Successo!</h2>";
		echo "<p>" . $student_name . ", la tua registrazione a 'Sfide di Programmazione' è stata registrata con successo</p>";
		if ($send_email) {
			echo "<p>Un email di conferma è stata inviata con successo all'indirizzo " . $student_email . "</p>";
		}
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

