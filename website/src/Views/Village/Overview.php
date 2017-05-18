<h1><?= $village["name"]?></h1>
<div>
	<h2>Ressourcen:</h2>
	Holz : <?= intval($village["wood"])?><br>
	Stein : <?= intval($village["stone"])?><br>
	Gold : <?= intval($village["gold"])?>
</div>
<ul>
	<li><a href="/Village/Main?villageId=<?= $village["villageId"]?>">Hauptgebäude</a></li>
</ul>
<a href="/Village">Alle Dörfer</a>