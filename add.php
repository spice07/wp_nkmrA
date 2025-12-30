<?php
$db = new PDO('sqlite:seasoning.sqlite');
$db->exec("
CREATE TABLE IF NOT EXISTS seasonings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT,
  expiry TEXT,
  photo TEXT,
  amount INTEGER
)");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $photoPath = null;

  if (!empty($_FILES['photo']['name'])) {
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $filename = date('YmdHis') . '_' . rand(1000,9999) . '.' . $ext;
    $photoPath = 'uploads/' . $filename;
    move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
  }

  $stmt = $db->prepare(
    "INSERT INTO seasonings (name, expiry, photo, amount)
     VALUES (:name, :expiry, :photo, :amount)"
  );
  $stmt->execute([
    ':name'   => $_POST['name'],
    ':expiry' => $_POST['expiry'],
    ':photo'  => $photoPath,
    ':amount' => $_POST['amount']
  ]);

  header('Location: manage.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>調味料を登録</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="phone">
  <div class="topbar">
    <a href="wp.php" class="back">＜</a>
    <div class="title">調味料を登録</div>
  </div>

  <div class="content">
    <form method="post" enctype="multipart/form-data">

      <label>調味料名</label>
      <input type="text" name="name" required>

      <label>内容量</label>
      <div class="row">
        <input type="number" name="amount" placeholder="例：500">
        <select name="unit">
          <option value="ml">ml</option>
          <option value="g">g</option>
        </select>
      </div>

      <label>賞味期限</label>
      <input type="date" name="expiry"
             min="2025-01-01" max="2050-12-31" required>

      <label>写真</label>
      <div class="upload">
        写真をアップロード
        <input type="file" name="photo" accept="image/*">
      </div>

      <button class="submit">登録</button>
    </form>
  </div>
</div>

</body>
</html>
