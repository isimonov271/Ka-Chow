<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$id  = '1xfDemOCc2rR7Caze4yZGNKqYpnhOVyrhwoPUVaBet4g';
$gid = 0;
$src = "https://docs.google.com/spreadsheets/d/$id/export?format=csv&gid=$gid";

$csv = @file_get_contents($src);
if ($csv === false) {                    // fallback на cURL, если allow_url_fopen=Off
    $ch = curl_init($src);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10,
    ]);
    $csv = curl_exec($ch);
    curl_close($ch);
    if ($csv === false) { die('Не удалось скачать CSV.');}
}

$rows  = preg_split("/\r\n|\n|\r/", trim($csv)); // кроссплатформенная разбивка строк
$data  = array_map('str_getcsv', $rows);
$body  = array_slice($data, 1); // пропустить заголовок из файла (если он есть)
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <title>Таблица</title>
</head>
<body>
<section>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-12">
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th>Тип</th><th>Описание</th><th>Стоимость</th><th>Кло-во</th>
            <th>В работе</th><th>Занято по иным причинам</th>
            <th>Свободно</th><th>Статус</th><th>Комментарий</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($body as $row): ?>
            <tr>
              <?php foreach ($row as $cell): ?>
                <td><?= htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') ?></td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</section>
</body>
</html>
