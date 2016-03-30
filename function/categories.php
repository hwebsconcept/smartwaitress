<?php include ("connect.php")?>
<?php
session_start();
$categories= mysql_query("SELECT * FROM categories ORDER BY id ASC");
while($category = mysql_fetch_array($categories))
{
?>
<div class="col-md-6 col-sm-6 col-xs-12" style="margin-bottom:15px;">
	<a href="menu.html?category_id=<?php echo $category['id'] ?>">
	<img src="admin/<?php echo $category['img'] ?>" alt="" class="img-responsive"/>
    <div class="name-alignment">
    	<h1 style="font-size:36px;"><b><?php echo $category['name'] ?></b></h1>
    </div>
    </a>
</div>
<?php
}
?>