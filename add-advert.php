<?php require 'pages/header.php'; ?>
<?php 
if(empty($_SESSION['cLogin'])){
	?>
	<script type="text/javascript">
		window.location.href='login.php';
	</script>	
	<?php
	exit;
}

require 'classes/adverts.class.php';
$a = new Adverts();
if (
	(isset($_POST['title']) && !empty($_POST['title'])) ||
	(isset($_POST['category']) && !empty($_POST['category'])) ||
	(isset($_POST['value']) && !empty($_POST['value'])) ||
	(isset($_POST['description']) && !empty($_POST['description'])) ||
	(isset($_POST['status']) && !empty($_POST['status']))
) {
	$title = addslashes($_POST['title']);
	$category = addslashes($_POST['category']);
	$value = addslashes($_POST['value']);
	$description = addslashes($_POST['description']);
	$status = addslashes($_POST['status']);

	$a->addAdvert($title, $category, $value, $description, $status);
	?>
	<div class="alert alert-success">Advert added successfully!</div>
	<?php
}
?>

		<div class="container">
			<h1>Add new advert</h1>

			<form method="POST" enctype="multipart/form-data">

				<div class="form-group">
					<label for="category">Category</label>
					<select name="category" id="category" class="form-control">
						<option></option>
						<?php
						require 'classes/categories.class.php';
						$c = new Categories();
						$categories = $c->getList();
						foreach ($categories as $category) : ?>
							<option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
						<?php endforeach; 
						?>
					</select>
				</div>

				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" name="title" id="title" class="form-control">
				</div>

				<div class="form-group">
					<label for="value">Value</label>
					<input type="text" name="value" id="value" class="form-control">
				</div>

				<div class="form-group">
					<label for="description">Description</label>
					<textarea class="form-control" name="description"></textarea>
				</div>

				<div class="form-group">
					<label for="status">Status</label>
					<select name="status" id="status" class="form-control">
						<option value="0"></option>
						<option value="1">Bad</option>
						<option value="2">Good</option>
						<option value="3">Excelent</option>
					</select>	
				</div>

				<input type="submit" value="add" class="btn btn-default">

			</form>

		</div>

<?php require 'pages/footer.php'; ?>