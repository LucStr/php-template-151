<h1>Rangliste</h1>

<table>
	<tr>
		<th>Rang</th>
		<th>Name</th>
		<th>Punkte</th>
	</tr>
	<?php 
	foreach ($users as $key => $user){
	?>
		<tr>
			<td><?=$key + 1?></td>
			<td><?=htmlentities($user["username"])?></td>
			<td><?=$user["points"]?></td>
		</tr>
	<?php 
	}
	?>
</table>