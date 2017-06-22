<h1 id="villageTitle"><?= htmlentities($village["name"]) ?></h1>
<form id="renameVillage" action="/Village/ChangeVillageName" method="POST">
	<?= $html->renderCSRF() ?>
	<input type="hidden" name="villageId" value="<?= $village["villageId"] ?>"/>
	<input style="display:none" id="villagename" type="text" name="villagename" value="<?= htmlentities($village["name"]) ?>"/>
</form>
<div>
	<h2>Ressources:</h2>
	Wood : <?= intval($village["wood"])?>(<?=intval($village["woodproduction"])?> / Hour)<br>
	Stone : <?= intval($village["stone"])?>(<?=intval($village["stoneproduction"])?> / Hour)<br>
	Gold : <?= intval($village["gold"])?>
</div>
<div id="buildings">
	
</div>
<ul>
	<li><a href="/Main?villageId=<?= $village["villageId"]?>">Main</a></li>
</ul>
<a href="/Village">All villages</a>
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