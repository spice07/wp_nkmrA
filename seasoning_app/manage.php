<?php
$db = new PDO('sqlite:seasoning.sqlite');
$items = $db->query("SELECT * FROM seasonings")->fetchAll(PDO::FETCH_ASSOC);

$byDate = [];
foreach ($items as $item) {
  $byDate[$item['expiry']][] = $item;
}

$year  = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('n');
$tab   = $_GET['tab'] ?? 'calendar';
$firstDay = date('w', strtotime("$year-$month-01"));
$daysInMonth = date('t', strtotime("$year-$month-01"));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>管理ページ</title>
<link rel="stylesheet" href="style.css">
<style>
.calendar .week, .calendar .days {
  display:grid; grid-template-columns:repeat(7,1fr); text-align:center;
}
.day { border:1px solid #ddd; height:44px; line-height:44px; }
.expiry { background:#ffd6d6; font-weight:bold; }
.card {
  display:flex; gap:10px; border:1px solid #ccc;
  border-radius:12px; padding:10px; margin-top:10px;
}
.card img { width:80px; height:80px; object-fit:cover; border-radius:8px; }
.low  { accent-color:red; }
.mid  { accent-color:orange; }
.high { accent-color:green; }
</style>
</head>
<body>

<div class="phone">

<div class="topbar">
  <a href="wp.php" class="back">＜</a>
  <div class="title">管理ページ</div>
</div>

<div class="tabs">
  <a href="?tab=calendar" class="tab <?= $tab==='calendar'?'active':'' ?>">カレンダー</a>
  <a href="?tab=amount" class="tab <?= $tab==='amount'?'active':'' ?>">残量確認</a>
</div>

<div class="content">

<?php if ($tab === 'calendar'): ?>

<form method="get" style="display:flex;gap:10px;">
<input type="hidden" name="tab" value="calendar">
<select name="year" onchange="this.form.submit()">
<?php for($y=2025;$y<=2050;$y++): ?>
<option value="<?= $y ?>" <?= $y==$year?'selected':'' ?>><?= $y ?>年</option>
<?php endfor; ?>
</select>

<select name="month" onchange="this.form.submit()">
<?php for($m=1;$m<=12;$m++): ?>
<option value="<?= $m ?>" <?= $m==$month?'selected':'' ?>><?= $m ?>月</option>
<?php endfor; ?>
</select>
</form>

<div class="calendar">
<div class="week"><div>日</div><div>月</div><div>火</div><div>水</div><div>木</div><div>金</div><div>土</div></div>
<div class="days">
<?php
for($i=0;$i<$firstDay;$i++) echo '<div></div>';
for($d=1;$d<=$daysInMonth;$d++){
  $date = sprintf('%04d-%02d-%02d',$year,$month,$d);
  if(isset($byDate[$date])){
    echo "<a class='day expiry' href='?tab=calendar&year=$year&month=$month&date=$date'>$d</a>";
  } else {
    echo "<div class='day'>$d</div>";
  }
}
?>
</div>
</div>

<?php
if (!empty($_GET['date']) && isset($byDate[$_GET['date']])){
  foreach ($byDate[$_GET['date']] as $item){
    echo "<div class='card'><img src='{$item['photo']}'><div>{$item['name']}</div></div>";
  }
}
?>

<?php else: ?>

<?php foreach ($items as $item):
$level = $item['amount'] < 30 ? 'low' : ($item['amount'] < 60 ? 'mid' : 'high');
?>
<div class="card">
<img src="<?= $item['photo'] ?>">
<div>
<b><?= $item['name'] ?></b><br>
<?= $item['expiry'] ?><br>
<input type="range" min="0" max="100" value="<?= $item['amount'] ?>" class="<?= $level ?>" disabled>
</div>
</div>
<?php endforeach; ?>

<?php endif; ?>

</div>
</div>
</body>
</html>
