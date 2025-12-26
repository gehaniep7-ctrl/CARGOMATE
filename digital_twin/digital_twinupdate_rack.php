<?php
$file = __DIR__ . "/racks.json";
$racks = json_decode(file_get_contents($file), true);
$msg = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){
  $rackId = $_POST["rackId"] ?? "";
  $newCurrent = (int)($_POST["current"] ?? 0);

  for($i=0; $i<count($racks); $i++){
    if($racks[$i]["rackId"] === $rackId){
      // keep within 0..capacity
      if($newCurrent < 0) $newCurrent = 0;
      if($newCurrent > $racks[$i]["capacity"]) $newCurrent = $racks[$i]["capacity"];

      $racks[$i]["current"] = $newCurrent;
      $racks[$i]["lastUpdated"] = date("Y-m-d H:i:s");
      break;
    }
  }

  file_put_contents($file, json_encode($racks, JSON_PRETTY_PRINT));
  $msg = "Rack updated and saved ✅";
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Update Rack</title>
  <style>
    body{font-family:Arial;background:#f4f6f9;padding:20px;}
    .box{background:#fff;padding:15px;border-radius:10px;max-width:420px;}
    select,input,button{width:100%;padding:10px;margin-top:10px;}
    button{background:#2c7be5;color:#fff;border:none;border-radius:8px;cursor:pointer;}
    a{display:inline-block;margin-top:15px;}
    .msg{margin-top:10px;color:green;}
  </style>
</head>
<body>
  <h2>Update Rack (Simulator / Admin)</h2>
  <div class="box">
    <form method="POST">
      <label>Select Rack</label>
      <select name="rackId" required>
        <?php foreach($racks as $r): ?>
          <option value="<?= htmlspecialchars($r["rackId"]) ?>">
            <?= htmlspecialchars($r["rackId"]) ?> (Zone <?= htmlspecialchars($r["zone"]) ?>)
          </option>
        <?php endforeach; ?>
      </select>

      <label>New Current Stock</label>
      <input type="number" name="current" placeholder="Enter stock number" required />

      <button type="submit">Save Update</button>
    </form>

    <?php if($msg): ?>
      <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <a href="racks.php">Back to Digital Twin View</a>
  </div>
</body>
</html>
