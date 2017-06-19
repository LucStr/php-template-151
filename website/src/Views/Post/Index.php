<h1>Posts</h1>
<?php 
if(isset($_SESSION["userId"])){
?>
<a href="/Post/CreateIndex">Neuer Post</a></br>
<?php 
}
foreach ($posts as $post){
?>
<div>
	<a href="/Post/Detail?postId=<?= $post["postId"]?>"><?= $post["title"]?>
		<img width="100" height="auto" src="/Post/GetPostImage?postId=<?= $post["postId"] ?>" />
	</a>
	Likes: <lable id="likeCount<?= $post["postId"]?>"><?= $post["likes"] ?><lable>
	<a href="javascript:like(<?= $post["postId"]?>)">Like</a>
</div>
<?php 
}
?>
<script>
	function like(postId){
		$.ajax({
			url: "/Post/Like",
			data: {
				postId: postId
				},
			success: function(e){
					$("#likeCount" + postId).html(e);
				}
		});		
	}
</script>