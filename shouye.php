<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .container {
            padding-top: 40px;
        }
  </style>
</head>
<body>
  
<div class="container d-flex justify-content-between align-items-center py-3">
      <h2 class="mb-0">📚| 图书下载</h2>  
      <a href="upload.html" class="btn btn-primary">👆上传图书</a>
</div>

<div class="container">
  <div class="row">
    <?php
    header("Content-Type: text/html; charset=utf-8");
    session_start();
    include('inc/db.php');

    $result = $con->query("SELECT * FROM ebooks ORDER BY id DESC");

    while ($book = $result->fetch_assoc()) {
    ?>
      <div class="col-md-2 mb-4">
        <div class="card h-100 shadow-sm">
          <img src="ziyuan/images/<?php echo $book['pic']; ?>" class="card-img-top">
          <div class="card-body d-flex flex-column">
            <h6 class="card-title"><?php echo $book['title']; ?></h6>
            <form action="php/download.php" method="get" class="mt-auto">
              <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
              <button class="btn btn-primary w-100">下载</button>
            </form>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

</body>
</html>

