<?php
function getTariff($days, $tariff)
{
    $keys = array_keys($tariff);
    sort($keys);
    foreach ($keys as $index => $value) {
        if ($value <= $days && (!isset($keys[$index + 1]) || $keys[$index + 1] > $days)) {
            return $tariff[$value];
        }
    }
    return null;
}

function getTotalCost()
{
    require_once '../backend/sdbh.php';
    $dbh = new sdbh();
    $data = $_POST;
    $product = $dbh->mselect_rows('a25_products', ['ID' => $data['product']], 0, 1, 'id')[0];
    $days = intVal($data['days']);
    if (empty($days)) {
        echo 'ошибка';
        return;
    }
    if (!empty($product['TARIFF'])) {
        $tariffs = unserialize($product['TARIFF']);
        $tariff = getTariff($days, $tariffs);
        $productCost = $tariff * $days;
    } else {
        $productCost = $product['PRICE'];
    }
    $additionalCost = 0;
    if (!empty($data['additional'])) {
        $additionalCost = array_sum($data['additional']) * $days;
    }
    $totalCost = $productCost + $additionalCost;
    echo $totalCost;
}

getTotalCost();
