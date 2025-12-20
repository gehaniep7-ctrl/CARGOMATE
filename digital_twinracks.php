<?php
$file = __DIR__ . "/racks.json";
$racks = json_decode(file_get_contents($file), true);

function statusFromPercent($p){
  if($p >= 95) return "CRITICAL";
  if($p >= 80) return "WARNING";
  return "OK";
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Rack Digital Twin</title>
  <style>
    body{font-family:Arial;background:#f4f6f9;padding:20px;}
    .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:15px;}
    .card{background:#fff;padding:15px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.1);}
    .status{padding:5px 10px;border-radius:20px;font-size:12px;color:#fff;display:inline-block;}
    .OK{background:green;}
    .WARNING{background:orange;}
    .CRITICAL{background:red;}
    .bar{height:12px;background:#ddd;border-radius:10px;overflow:hidden;margin-top:8px;}
    .fill{height:100%;background:#2c7be5;}
    a{display:inline-block;margin-bottom:12px;}
  </style>
</head>
<body>
  <h1>Rack Digital Twin</h1>
  <a href="update_rack.php">Go to Update Rack Page</a>

  <div class="grid">
    <?php foreach($racks as $r): 
      $percent = round(($r["current"] / $r["capacity"]) * 100);
      $status = statusFromPercent($percent);
    ?>
      <div class="card">
        <h3><?= htmlspecialchars($r["rackId"]) ?> (Zone <?= htmlspecialchars($r["zone"]) ?>)</h3>
        <div class="status <?= $status ?>"><?= $status ?></div>
        <p><b>Item:</b> <?= htmlspecialchars($r["item"]) ?></p>
        <p><b>Stock:</b> <?= (int)$r["current"] ?> / <?= (int)$r["capacity"] ?> (<?= $percent ?>%)</p>
        <div class="bar"><div class="fill" style="width:<?= $percent ?>%"></div></div>
        <small>Last Updated: <?= htmlspecialchars($r["lastUpdated"]) ?></small>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
