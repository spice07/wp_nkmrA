<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>うちのちょうみりょう</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="phone">

  <div class="topbar">
    <?php echo date('Y年n月j日'); ?>
  </div>

  <div class="content" style="text-align:center;">
    <h1>○○（ユーザー名）<br>のちょうみりょう</h1>

    <a href="add.php" class="btn pink">調味料を登録</a>
    <a href="#" class="btn blue">料理する</a>
  </div>

  <a href="manage.php"
     style="position:absolute;right:16px;bottom:16px;
            border:1px solid #999;border-radius:10px;
            padding:10px 14px;text-decoration:none;color:#111;">
    管理ページ
  </a>

</div>

</body>
</html>
