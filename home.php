<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
include 'DBController.php';
$db_handle = new DBController();
$typeResult = $db_handle->runQuery("SELECT DISTINCT type FROM product ORDER BY type ASC");

if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
        case "add":

            if (!empty($_POST["quantity"])) {
                $productByCode = $db_handle->runQuery("SELECT * FROM product WHERE code='" . $_GET["code"] . "'");
                $itemArray = array($productByCode[0]["code"] => array('name' => $productByCode[0]["name"], 'code' => $productByCode[0]["code"], 'quantity' => $_POST["quantity"], 'price' => $productByCode[0]["price"], 'image' => $productByCode[0]["image"]));
                if (!empty($_SESSION["cart_item"])) {

                    if (in_array($productByCode[0]["code"], array_keys($_SESSION["cart_item"]))) {
                        echo "<script>";
                        echo "alert('You already added to the cart, it will be added again!');";
                        echo "</script>";
                        foreach ($_SESSION["cart_item"] as $k => $v) {
                            if ($productByCode[0]["code"] == $k) {
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
                    if ($_GET["code"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                    if (empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            break;
    }
}
?>
<html>

<head>
    <link href="style.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="css/table.css" type="text/css" rel="stylesheet" />

    <title>Home - RS2 </title>
</head>

<body style="margin: 30px;">

    <img src="images/RStwo.png" width="40" height="70" alt="RS2" style="display: block;
  margin-left: auto;
  margin-right: auto;
  width: 15%;">
    <div class="page-heade">
        <a style="float: right;" href="logout.php" class="btn btn-danger">Logout</a>
        <h1 style="float: left;">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
    </div>

    <br>
    <br>
    <br>
    <hr>
    <div id="shopping-cart">
        <div class="txt-heading">Shopping Cart</div>

        <?php
        if (isset($_SESSION["cart_item"])) {
            $total_quantity = 0;
            $total_price = 0;
        ?>
            <table>
                <tbody>
                    <tr>
                        <th style="text-align:left;">Name</th>
                        <th style="text-align:left;">Code</th>
                        <th style="text-align:right;" width="5%">Quantity</th>
                        <th style="text-align:right;" width="10%">Unit Price</th>
                        <th style="text-align:right;" width="10%">Price</th>
                        <th style="text-align:center;" width="5%">Remove</th>
                    </tr>
                    <?php
                    foreach ($_SESSION["cart_item"] as $item) {
                        $item_price = $item["quantity"] * $item["price"];
                    ?>
                        <tr>
                            <td><img style="width: 55px; height: 55px" src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
                            <td><?php echo $item["code"]; ?></td>
                            <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                            <td style="text-align:right;"><?php echo "$ " . $item["price"]; ?></td>
                            <td style="text-align:right;"><?php echo "$ " . number_format($item_price, 2); ?></td>
                            <td style="text-align:center;"><a href="home.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="images/delete_icon.png" alt="Remove Item" style="width: 20px; height:20px" /></a></td>
                        </tr>
                    <?php
                        $total_quantity += $item["quantity"];
                        $total_price += ($item["price"] * $item["quantity"]);
                    }
                    ?>

                    <tr>
                        <td colspan="2" align="right">Total:</td>
                        <td align="right"><?php echo $total_quantity; ?></td>
                        <td align="right" colspan="2"><strong><?php echo "$ " . number_format($total_price, 2); ?></strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <a id="btnEmpty" href="home.php?action=empty">Empty Cart</a>


        <?php
        } else {
        ?>
            <div class="no-records">Your Cart is Empty</div>
        <?php
        }
        ?>

    </div>
    <h2>select product type for search</h2>
    <form method="POST" name="search" action="home.php">
        <div id="demo-grid">
            <div class="search-box">
                <div class="row">
                    <input type="text" placeholder="Enter Product Name" class="form-control" name="search" style="width: 200px;">
                    <select id="Place" name="type[]" class="form-control" searchable="Search here.." style="width: 200px;">
                        <option value="0" selected="selected">Select type</option>
                        <?php
                        if (!empty($typeResult)) {
                            foreach ($typeResult as $key => $value) {
                                echo '<option value="' . $typeResult[$key]['type'] . '">' . $typeResult[$key]['type'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <br>
                <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
            </div>

            <hr>

            <h3>Product Details:</h3>

            <?php
            if (preg_match("/[^A-Za-z]/", $_POST['search'])) {
                echo "Invalid Characters!";
            }
            if (!empty($_POST['type'])) {
            ?>
                <table cellpadding="10" cellspacing="1">
                    <tbody>
                        <?php
                        $query = "SELECT * from product";
                        $i = 0;
                        $selectedOptionCount = count($_POST['type']);
                        $selectedOption = "";
                        while ($i < $selectedOptionCount) {
                            $selectedOption = $selectedOption . "'" . $_POST['type'][$i] . "'";
                            if ($i < $selectedOptionCount - 1) {
                                $selectedOption = $selectedOption . ", ";
                            }

                            $i++;
                        }
                        $query = $query . " WHERE type in (" . $selectedOption . ") and name like '%" . $_POST['search'] . "%'";

                        $result = $db_handle->runQuery($query);
                    }
                    if (!empty($result)) {
                        foreach ($result as $key => $value) {
                        ?>
    </form>
    <div class="product-item">
        <form method="post" action="home.php?action=add&code=<?php echo $result[$key]["code"]; ?>">
            <div class="product-image"><img height="120" width="160" src="<?php echo $result[$key]["image"]; ?>"></div>
            <div class="product-tile-footer">
                <div class="product-title"><?php echo $result[$key]["name"]; ?></div>
                <div class="product-price"><?php echo $result[$key]["type"]; ?></div> <br>
                <div class="product-price"><?php echo "$" . $result[$key]["price"]; ?></div>
                <div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" />
                    <input type="submit" value="Add to Cart" class="btnAddAction" />
                </div>
            </div>
        </form>
    </div>
<?php
                        }
?>

</tbody>
</table>
<?php
                    }
?>
</div>

</body>

</html>