<?php include ("connect.php")?>
<?php
error_reporting(0);
session_start();
include ("item.php");

if (!empty($_GET['id']) && !empty($_GET['quantity']) && empty($_GET['index']))
{
	$result= mysql_query("SELECT * FROM menu WHERE id=".$_GET['id']);
	$menu = mysql_fetch_array($result);

	$item = new Item();
	$item->id = $menu['id'];
	$item->name = $menu['name'];
	$item->price = $menu['price'];
	$item->quantity = $_GET['quantity'];
	$item->duration = $menu['duration'];
	
	$index= -1;
	$cart= unserialize(serialize($_SESSION['cart']));
	for ($i=0; $i<count($cart); $i++)
		if($cart[$i]->id == $_GET['id'])
		{
			$index = $i;
			break;
		}
	if($index==-1)
		$_SESSION['cart'][]= $item;
	else
	{
		$_SESSION['cart']=$cart;
		echo "<script>alert('Item has been added before')</script>";
	}
}
if (empty($_GET['id']) && empty($_GET['quantity']) && !empty($_GET['index']) || $_GET['index']==0)
{
	$cart= unserialize(serialize($_SESSION['cart']));
	unset($cart[$_GET['index']]);
	$cart = array_values($cart);
	$_SESSION['cart']=$cart;
}
if ($_GET['action']=="delete")
{
	unset($_SESSION['cart']); 
	echo"<script>alert('Order list is empty')</script>";
	exit();
	
}
if ($_GET['order']=="taken")
{
	$counter= mysql_query("SELECT COUNT(*) AS total FROM order_details");
	$count= mysql_fetch_array($counter);
	$inNumber= str_pad($count['total'] + 1, 4, 0, STR_PAD_LEFT);
	$tableno=$_GET['table'];
	
	
	$max=count($_SESSION['cart']);
	for($i=0;$i<$max;$i++)
	{
		$name=$_SESSION['cart'][$i]->name;
		$price=$_SESSION['cart'][$i]->price;
		$qty=$_SESSION['cart'][$i]->quantity;
		$duration=$_SESSION['cart'][$i]->duration;
		$order=mysql_query("INSERT INTO order_details (ticket_id, menu_name, price ,quantity, status, table_number, delivery_time) 
		VALUES ('$inNumber','$name','$price','$qty','0','$tableno','$duration')");	
	}
	echo "<div align='center'><a href='index.html'><button class='btn btn-warning'>Go Back To Menu</button></a></div>";
	echo"<script>alert('Order has be made. Delivery time 10mins.')</script>";
	unset($_SESSION['cart']); 
	session_destroy();
	exit();
}

?>
<table class="table table-striped">
	<tr class="warning">
		<th>Delete</th>
		<th>Name</th>
		<th>Price</th>
		<th>Qty</th>
		<th>₦</th>
	</tr>
	<?php
	$cart= unserialize(serialize($_SESSION['cart']));
	$s =0;
	$index = 0;
	for ($i=0; $i<count($cart); $i++)
	{
		$s +=$cart[$i]->price * $cart[$i]->quantity;
	?>
	<tr>
		<td>
		<span class="hidden"><?php echo $cart[$i]->id ?> <?php echo $cart[$i]->duration ?></span>
		<a href="cart.html?index=<?php echo $index ?>" onClick="return confirm('Are you sure?')">
		<button class="btn btn-xs btn-danger">
			<i class="glyphicon glyphicon-remove"></i>
		</button>
		</a>
		</td>
		<td><?php echo $cart[$i]->name ?></td>
		<td><?php echo $cart[$i]->price ?></td>
		<td><?php echo $cart[$i]->quantity ?></td>
		<td><?php echo $cart[$i]->price * $cart[$i]->quantity ?></td>
	</tr>
	<?php
	$index++;
	}
	?>
	<tr class="success">
		<td  colspan="4" align="right"><b>Total</b></td>
		<td><b><?php echo $s ?></b></td>
	</tr>
	
</table>

<div class="row">
	<div class="col-md-12">
		<select name="table" class="table form-control">
		<option value="">Choose Table</option>
		<?php
			for ($i=1; $i<=10; $i++)
			{
		?>
				<option value="table-<?php echo $i;?>">Table <?php echo $i;?></option>
		<?php
			}
		?>
		</select>
	</div>
	<div class="col-md-6 col-sm-6 col-xs-6">
		<button class="btn btn-warning btn-group-justified order"><i class="glyphicon glyphicon-ok"></i> Confirm Order</button>
	</div>
	<div class="col-md-6 col-sm-6 col-xs-6">
		<a href="cart.html?action=delete" onClick="return confirm('Are you sure you want to cancel order?')">
		<button class="btn btn-danger btn-group-justified"><i class="glyphicon glyphicon-remove"></i> Cancel Order</button>
		</a>
	</div>
</div>

<script>
$(".order").click(function(){
	var table =  $(this).parent().siblings().children(".table").val();
	if(table=="")
	{
		alert("Please choose a table");
	}
	else
	{
		window.location="cart.html?order=taken&table="+table;	
	}
	
});

$(".cart-direct").click(function(){
	var id= this.id;
	var quantity =  $(this).parent().siblings().children(".quantity").val();
	window.location="cart.html?menu_id="+id+"&quantity="+quantity;
});
</script>