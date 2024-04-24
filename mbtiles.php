<?php
//header('Access-Control-Allow-Origin: *');
$zoom = (int)$_GET['z'];
$column = (int)$_GET['x'];
//$row = (int)$_GET['y'];
$row = pow(2, $zoom) - 1 - (int)$_GET['y'];

foreach (["13.mbtiles", "273.mbtiles"] as $tileset) {
  try {
    $conn = new PDO("sqlite:$tileset");

    $sql = "SELECT * FROM tiles WHERE zoom_level = :zoom AND tile_column = :column AND tile_row = :row";
    $q = $conn->prepare($sql);

    $q->bindParam(":zoom", $zoom);
    $q->bindParam(":column", $column);
    $q->bindParam(":row", $row);

    $q->execute();

    $q->bindColumn(1, $zoom_level);
    $q->bindColumn(2, $tile_column);
    $q->bindColumn(3, $tile_row);
    $q->bindColumn(4, $tile_data, PDO::PARAM_LOB);

    $ok = false;
    while ($q->fetch()) {
      header('Cache-Control: no-transform'); // bypass CloudFlare brotli
      header("Content-Type: application/x-protobuf");
      header('Content-Encoding: gzip');
      $ok = true;

      echo base64_encode(fpassthru($tile_data));
    }
  } catch (PDOException $e) {
    print 'Exception : ' . $e->getMessage();
  }
}
