<?php

session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();
if (!empty($_GET["action"])) {
	switch ($_GET["action"]) {
		case "add":
			if (!empty($_POST["quantity"])) {
				$productById = $db_handle->runQuery("SELECT * FROM shoes WHERE id='" . $_GET["id"] . "'");
				$itemArray = array($productById[0]["id"] => array('name' => $productById[0]["name"], 'id' => $productById[0]["id"], 'quantity' => $_POST["quantity"], 'price' => $productById[0]["price"], 'image' => $productById[0]["image"]));

				if (!empty($_SESSION["cart_item"])) {
					if (in_array($productById[0]["id"], array_keys($_SESSION["cart_item"]))) {
						foreach ($_SESSION["cart_item"] as $k => $v) {
							if ($productById[0]["id"] == $k) {
								if (empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
						}
					} else {
						$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
					}
				} else {
					$_SESSION["cart_item"] = $itemArray;
				}
			}
			break;
		case "remove":
			if (!empty($_SESSION["cart_item"])) {
				foreach ($_SESSION["cart_item"] as $k => $v) {
					if ($_GET["id"] == $k)
						unset($_SESSION["cart_item"][$k]);
					if (empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
				}
			}
			break;
	}
}
?>
<HTML>

<HEAD>
	<TITLE>Golden Sneaker</TITLE>
	<link href="style.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

</HEAD>

<BODY>
	<div class="container-fluid">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">
					<div>
						<h1>Our Products</h1>
						<img src="assets/nike.png" alt="" style='height: 70px;>

						<?php
						$product_array = $db_handle->runQuery("SELECT * FROM shoes ORDER BY id ASC");
						if (!empty($product_array)) {
							foreach ($product_array as $key => $value) {
						?>
								<!-- <div class="product-item"> -->
								<form method="post" action="index.php?action=add&id=<?php echo $product_array[$key]["id"]; ?>">
									<!-- <div class="product-image"> -->
									<img src="<?php echo $product_array[$key]["image"]; ?>" style=' height: 380px;'>
						<!-- </div> -->
						<div class="product-tile-footer">
							<div class="product-title"><b><?php echo $product_array[$key]["name"]; ?></b></div>
							<div class="product-description"><?php echo $product_array[$key]["description"]; ?></div>
							<div class="product-price"><b><?php echo "$" . $product_array[$key]["price"]; ?></b></div>
							<input type="hidden" name="image" value="<?php echo $product_array[$key]["image"]; ?>">
							<input type="hidden" name="name" value="<?php echo $product_array[$key]["name"]; ?>">
							<input type="hidden" name="price" value="<?php echo $product_array[$key]["price"]; ?>">
							<div class="cart-action"><input type="hidden" class="product-quantity" name="quantity" value="1" size="2" />
								<input type="submit" name="add_to_cart" value="Add to Cart" class="btnAddAction" />
							</div>
						</div>
						</form>
						<!-- </div> -->
				<?php
							}
						}
				?>
					</div>
				</div>
				<div class="col-md-6">
					<h1>Your cart</h1>
					<img src="assets/nike.png" alt="" style='height: 70px;'>

					<?php
					if (isset($_SESSION["cart_item"])) {
						$total_quantity = 0;
						$total_price = 0;
					?>
						<table class="tbl-cart" cellpadding="10" cellspacing="10">
							<tbody>
								<?php
								foreach ($_SESSION["cart_item"] as $item) {
								?>
									<tr>
										<td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" />
										
										<div class="product-title"><b><?php echo $item["name"]; ?></b></div>
										</td>
									</tr>
									<tr>
									<td >$<?php echo $item["price"]; ?></td>
										
									</tr>
									<tr>
										<td style="text-align:right;"><button name="button" value="OK" type="button" onclick="minus()"><img src="assets/minus.png" style='height: 10px;'></button>
											<script>
												function minus() {
													//
												}
											</script>
										</td>
										<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
										<td><button name="button" value="OK" type="button" onclick="plus()"><img src="assets/plus.png" style='height: 10px;'></button>
											<script>
												function plus() {
													//
												}
											</script>
										</td>
										<td style="text-align:center;"><a href="index.php?action=remove&id=<?php echo $item["id"]; ?>" class="btnRemoveAction"><img src="assets/trash.png" alt="Remove Item" style='height: 20px;'/></a></td>
									</tr>
								<?php
									$total_price += ($item["price"] * $item["quantity"]);
								}
								?>

								<tr>
									<td align="right" colspan="2"><strong><?php echo "$ " . number_format($total_price, 2); ?></strong></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					<?php
					} else {
					?>
						<div class="no-records">Your Cart is Empty</div>
					<?php
					}

					?>


				</div>

			</div>
		</div>
	</div>
	</div>
</BODY>

</HTML>