<?php

//-----変数の初期設定-----
$bg_clr = ['black', 'red', 'white']; 
$dir_list = [0, 0, 0, 0]; // [上, 下, 左, 右]
$dir_can_list = [];
$wid = 31;
$hgt = 31;
$pos = ['wid' => rand(1, ($wid-1)/2) * 2 - 1, 'hgt' => rand(1, ($hgt-1)/2) * 2 - 1];
$err_msg = [];
$end_flg = 0;
$dir_err_cnt = 0;
$sum = 0;
$total = ($wid - 1) * ($hgt - 1) / 2 - 1;

//-----配列の値を全て0にする-----
for ($i = 0; $i < $hgt; $i++) {
  for ($j = 0; $j < $wid; $j++) {
    $sq[$i][$j] = 0;
  }
}

//-----開始位置の値を1にする-----
$sq[$pos['hgt']][$pos['wid']] = 1;

while ($end_flg == 0) {

  // 進める方向があるかどうか
  if (array_sum($dir_list) != 4) {
  
    //進行可能なものだけ配列に格納
    $dir_can_list = [];
    foreach ($dir_list as $key => $num) {
      if ($num == 0) {
        $dir_can_list[$key] = $key;
      }
    }

    // 進める方向内で進む方向をランダムで決める
    $dir_num = array_rand($dir_can_list);
    
    switch ($dir_num) {
      case 0: // 上方向
        if ( ($pos['hgt'] - 2) >= 0 && $sq[$pos['hgt'] - 2][$pos['wid']] == 0 ) {
          $pos['hgt'] -= 2;
          $sq[$pos['hgt']][$pos['wid']] = 1;
          $sq[$pos['hgt'] + 1][$pos['wid']] = 1;
          $dir_list = [0, 0, 0, 0];
        } else {
          $dir_list[$dir_num] = 1;
        }
      break;
      
      case 1: // 下方向
        if ( ($pos['hgt'] + 2) < $hgt && $sq[$pos['hgt'] + 2][$pos['wid']] == 0 ) {
          $pos['hgt'] += 2;
          $sq[$pos['hgt']][$pos['wid']] = 1;
          $sq[$pos['hgt'] - 1][$pos['wid']] = 1;
          $dir_list = [0, 0, 0, 0];
        } else {
          $dir_list[$dir_num] = 1;
        }
      break;
      
      case 2: // 左方向
        if ( ($pos['wid'] - 2) >= 0 && $sq[$pos['hgt']][$pos['wid'] - 2] == 0 ) {
          $pos['wid'] -= 2;
          $sq[$pos['hgt']][$pos['wid']] = 1;
          $sq[$pos['hgt']][$pos['wid'] + 1] = 1;
          $dir_list = [0, 0, 0, 0];
        } else {
          $dir_list[$dir_num] = 1;
        }
      break;
      
      case 3: // 右方向
        if ( ($pos['wid'] + 2) < $wid && $sq[$pos['hgt']][$pos['wid'] + 2] == 0 ) {
          $pos['wid'] += 2;
          $sq[$pos['hgt']][$pos['wid']] = 1;
          $sq[$pos['hgt']][$pos['wid'] - 1] = 1;
          $dir_list = [0, 0, 0, 0];
        } else {
          $dir_list[$dir_num] = 1;
        }
      break;

      default: // 進行可能方向ではない時
      $dir_err_cnt++;
        $err_msg[] = '進行方向が正しく算出されませんでした。('.$dir_err_cnt.'回目)';
        break;
    }
  } else {
    if ($sq[$pos['hgt'] - 1][$pos['wid']] == 1) { // 上方向
      $pos['hgt'] -= 2;
      $sq[$pos['hgt'] + 1][$pos['wid']] = 2;
      $sq[$pos['hgt'] + 2][$pos['wid']] = 2;
      $dir_list = [0, 0, 0, 0];
      
    } elseif ($sq[$pos['hgt'] + 1][$pos['wid']] == 1) { // 下方向
      $pos['hgt'] += 2;
      $sq[$pos['hgt'] - 1][$pos['wid']] = 2;
      $sq[$pos['hgt'] - 2][$pos['wid']] = 2;
      $dir_list = [0, 0, 0, 0];
    } elseif ($sq[$pos['hgt']][$pos['wid'] - 1] == 1 ) { // 左方向
      $pos['wid'] -= 2;
      $sq[$pos['hgt']][$pos['wid'] + 1] = 2;
      $sq[$pos['hgt']][$pos['wid'] + 2] = 2;
      $dir_list = [0, 0, 0, 0];
    } elseif ($sq[$pos['hgt']][$pos['wid'] + 1] == 1) { // 右方向
      $pos['wid'] += 2;
      $sq[$pos['hgt']][$pos['wid'] - 1] = 2;
      $sq[$pos['hgt']][$pos['wid'] - 2] = 2;
      $dir_list = [0, 0, 0, 0];
    } else { // 戻る方向がない時
      $end_flg = 1;
    }
  }
}

//-----終了位置の値を2にする-----
$sq[$pos['hgt']][$pos['wid']] = 2;

//-----2の値の数の確認-----
foreach ($sq as $row) {
  $sum += array_sum($row);
}
if (($sum / 2) != $total) {
  $err_msg[] = "処理の回数が正しくありません.";
}

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>自動生成迷路</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
<?php if (empty($err_msg)) : ?>
  <table>
<?php   foreach ($sq as $row) : ?>
    <tr>
<?php     foreach ($row as $num) : ?>
      <td class="<?php echo $bg_clr[$num]; ?>"></td>
<?php     endforeach; ?>
    </tr>
<?php   endforeach; ?>
  </table>
<?php else : ?>
  <ul class="err">
<?php   foreach ($err_msg as $val) : ?>
    <li><?php echo $val; ?></li>
<?php   endforeach; ?>
  </ul>
<?php endif; ?>
</body>
</html>