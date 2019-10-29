<h3 class="text-center">Items populares</h3>

<?php 

$transQ = $conn->query("SELECT * FROM cart WHERE paid = 1 ORDER BY id DESC LIMIT 5"); /* VIDEO 35 */
$results = array();

while($row = mysqli_fetch_assoc($transQ)) {
    $results[] = $row;
}; 
$row_count = $transQ->num_rows;

$used_ids = array();
for ($i=0; $i <$row_count ; $i++) { 
    $json_items = $results[$i]['items'];
    $items = json_decode($json_items,true);
    foreach($items as $item) {
        if (!in_array($item['id'], $used_ids)) {
           $used_ids[] = $item['id'];
        }
    }
}
?>

<div id="recent-widget">
    <table class="table table-condensed">
        <?php foreach ($used_ids as $id): 
            $productQ = $conn-query("SELECT id, title FROM products WHERE id = '{$id}'");
            $product = mysqli_fetch_assoc($productQ);
            ?>
            
            <tr>
                <td>
                    <?php echo subsrt($product['title'],0,15);?>
                </td>
                <td>
                    <a href="" class="text-primary" onclick="detailsModal(<?php echo $id;?>)">Ver</a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
</div>