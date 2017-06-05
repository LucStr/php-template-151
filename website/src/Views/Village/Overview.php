<h1 id="villageTitle"><?= htmlentities($village["name"]) ?></h1>
<form id="renameVillage" action="/Village/ChangeVillageName" method="POST">
	<?= $html->renderCSRF() ?>
	<input type="hidden" name="villageId" value="<?= $village["villageId"] ?>"/>
	<input style="display:none" id="villagename" type="text" name="villagename" value="<?= htmlentities($village["name"]) ?>"/>
</form>
<div>
	<h2>Ressourcen:</h2>
	Holz : <?= intval($village["wood"])?>(<?=intval($village["woodproduction"])?> / Stunde)<br>
	Stein : <?= intval($village["stone"])?>(<?=intval($village["stoneproduction"])?> / Stunde)<br>
	Gold : <?= intval($village["gold"])?>
</div>
<div id="buildings">
	
</div>
<ul>
	<li><a href="/Main?villageId=<?= $village["villageId"]?>">Hauptgebäude</a></li>
</ul>
<a href="/Village">Alle Dörfer</a>
<script>
	$("#villageTitle").on("click", function(){
		$("#villageTitle").hide();
		$("#villagename").show();
		$("#villagename").select();
	});
	$("#villagename").on("focusout", function(){
		$("#villageTitle").show();
		$("#villagename").hide();
		$("#renameVillage").submit();
	});
</script>