<?php require 'pages/header.php'; ?>
<?php 
require 'classes/adverts.class.php';
require 'classes/users.class.php'; 
require 'classes/categories.class.php';
$a = new Adverts();
$u = new Users();
$c = new Categories();

$filters = array(
	'category' => '',
	'value' => '',
	'status' => ''
);
if(isset($_GET['filters'])) {
	$filters = $_GET['filters'];
}

$total_adverts = $a->getTotalAdverts($filters);
$total_users = $u->getTotalUsers();

$p = 1;
if(isset($_GET['p']) && !empty($_GET['p'])) {
	$p = addslashes($_GET['p']);
}

$perPage = 2;
$total_pages = ceil($total_adverts/$perPage);

$adverts = $a->getLastAdverts($p, $perPage, $filters);
$categories = $c->getList();
?>

		<div class="container-fluid">
			<div class="jumbotron">
				<h2>Total adverts: <?php echo $total_adverts; ?></h2>
				<p>And we have more than <?php echo $total_users; ?> users registered.</p>
			</div>
			<div class="row">
				<div class="col-sm-3">
					<h4>Advanced Search</h4>
					<form method="GET">

						<div class="form-group">
							<label for="category">Category:</label>
							<select id="category" name="filters[category]" class="form-control">
								<option></option>
								<?php foreach($categories as $category) : ?>
									<option value="<?php echo $category['id'] ?>" <?php echo($category['id']==$filters['category'])?'selected="selected"':'' ?> ><?php echo $category['name'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="form-group">
							<label for="value">Value:</label>
							<select id="value" name="filters[value]" class="form-control">
								<option></option>
								<option value="0-50" <?php echo($filters['value']=='0-50')?'selected="selected"':'' ?> >R$ 0 ~ 50,00</option>
								<option value="51-100" <?php echo($filters['value']=='51-100')?'selected="selected"':'' ?> >R$ 51,00 ~ 100,00</option>
								<option value="101-200" <?php echo($filters['value']=='101-200')?'selected="selected"':'' ?> >R$ 101,00 ~ 200,00</option>
								<option value="201-500" <?php echo($filters['value']=='201-500')?'selected="selected"':'' ?> >R$ 201,00 ~ 500,00</option>
							</select>
						</div>

						<div class="form-group">
							<label for="status">Status:</label>
							<select id="status" name="filters[status]" class="form-control" <?php echo($category['id']==$filters['category'])?'selected="selected"':'' ?>>
								<option value="0"></option>
								<option value="1" <?php echo($filters['status']=='1')?'selected="selected"':'' ?> >Bad</option>
								<option value="2" <?php echo($filters['status']=='2')?'selected="selected"':'' ?> >Good</option>
								<option value="3" <?php echo($filters['status']=='3')?'selected="selected"':'' ?> >Excelent</option>
							</select>
						</div>

						<div class="form-group">
							<input type="submit" value="search" class="btn btn-info">
						</div>

					</form>
				</div>
				<div class="col-sm-9">
					<h4>Last adverts</h4>
					<table class="table table-striped">
						<tbody>
							<?php foreach($adverts as $advert) : ?>
								<tr>
									<td>
										<?php if(!empty($advert['url'])) : ?>
										<img src="assets/img/adverts/<?php echo $advert['url']; ?>" border="0" height='50'>
										<?php else : ?>
											<img src="assets/img/default.png" height='50' border="0">
										<?php endif; ?>
									</td>
									<td>
										<a href="product.php?id=<?php echo $advert['id']; ?>"><?php echo $advert['title']; ?></a><br>
										<?php echo $advert['category']; ?>
									</td>
									<td>
										$ <?php echo number_format($advert['value'], 2); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<ul class="pagination">
						<?php for($i=1;$i<=$total_pages;$i++) : ?>
							<li class="<?php echo ($p==$i)?'active':'' ?>"><a href="index.php?<?php
							$w = $_GET;
							$w['p'] = $i;
							echo http_build_query($w);
							?>"><?php echo $i; ?></a></li>
						<?php endfor; ?>
					</ul>
				</div>
			</div>
		</div>

<?php require 'pages/footer.php'; ?>