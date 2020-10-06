<?php
$array = explode(',', $_GET['array']);

// 修正はここから
for ($i = 0; $i < count($array); $i++) {
    for ($j = 0; $j < count($array) - 1 - $i; $j++) { //比較する残りの値の数くりかえし
        if ($array[$j] > $array[$j + 1]) { //右隣の値より大きければ
            $order = $array[$j + 1];       //インデックスを+1した値を変数に代入しておく
            $array[$j + 1] = $array[$j];   //比較された値のインデックスを変更
            $array[$j] = $order;           //比較した値のインデックスを変更
        }
    }
}
// 修正はここまで

echo "<pre>";
print_r($array);
echo "</pre>";
