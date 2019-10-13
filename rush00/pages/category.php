<?php

require_once 'pieces/header.php';

$url_category = url_get_category();
$category = get_one_by_key_val($DB, 'category', 'short', $url_category);

?>
<h1 class="page_title"><?=$category['name']?></h1>
<p><?=$category['description']?></p>
<div class="shop_items">
<?php

$items = get_items_by_category($DB, $category);
while ($item = mysqli_fetch_assoc($items)) {
  display_item($item);
}
?>
</div>
<?php

require_once 'pieces/footer.php';
