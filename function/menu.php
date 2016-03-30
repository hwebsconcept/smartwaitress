<?php include ("connect.php");?>

<?php
session_start();

if (!empty($_POST['category_id']))
{
	$category_id = 	$_POST['category_id'];
	$categories= mysql_query("SELECT * FROM categories WHERE id= '$category_id'");
	$category=mysql_fetch_array($categories);
	$category_name= $category['name'];
	
	$arr = array('name' => $category_name);
}

$menu_query = mysql_query ("SELECT * FROM menu WHERE category_id='$category_id' ORDER BY id DESC");
while($menu= mysql_fetch_array($menu_query))
{
?>
<div class="col-md-1 col-sm-1 col-xs-2">
	<img src="admin/<?php echo $menu['img'] ?>" alt=""/>
</div>
<div class="col-md-7 col-sm-7 col-xs-5">
	<?php echo $menu['name'] ?><br />
	₦<?php echo $menu['price'] ?>
</div>
<div class="col-md-2 col-sm-2 col-xs-3">
	<select name="qty" class="quantity form-control">
	<?php
        for ($i=1; $i<=100; $i++)
        {
            ?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
            <?php
        }
    ?>
    </select>
</div>
<div class="col-md-2 col-sm-2 col-xs-2">
	<button class="btn btn-warning btn-sm pull-right cart-direct" id="<?php echo $menu['id'] ?>">
     <i class="glyphicon glyphicon-plus">
     </i></button>
</div>
<div class="clearfix"></div>
<hr/>
<?php
}
?>
<script>
$(".cart-direct").click(function(){
	var id= this.id;
	var quantity =  $(this).parent().siblings().children(".quantity").val();
	window.location="cart.html?menu_id="+id+"&quantity="+quantity;
});
</script>