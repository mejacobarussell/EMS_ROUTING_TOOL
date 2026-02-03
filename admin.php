<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
$pass = "EMS"; 
if (!isset($_SESSION['auth'])) {
    if (isset($_POST['p']) && $_POST['p'] === $pass) { $_SESSION['auth'] = true; }
    else { die('<form method="POST" style="text-align:center;padding:50px;font-family:sans-serif;"><h2>Admin Login</h2><input type="password" name="p"><button>Go</button></form>'); }
}

$file = 'hospitals.json';
$msg = "";
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_new']) && !empty($_POST['new_n'])) {
        $newEntry = [
            "n"   => $_POST['new_n'],
            "c"   => $_POST['new_c'],
            "lat" => (float)$_POST['new_lat'],
            "lng" => (float)$_POST['new_lng'],
            "tags" => array_values(array_filter(array_map('strtoupper', array_map('trim', explode(',', $_POST['new_tags']))))),
            "lvl" => strtoupper($_POST['new_lvl']),
            "p"   => $_POST['new_p'],
            "dc"  => $_POST['new_dc'],
            "bc"  => $_POST['new_bc'] 
        ];
        array_unshift($data, $newEntry);
    } 
    elseif (isset($_POST['save_all'])) {
        foreach ($data as $i => $h) {
            $data[$i]['n']   = $_POST['n'][$i];
            $data[$i]['lvl'] = strtoupper($_POST['lvl'][$i]); // NOW EDITABLE
            $data[$i]['p']   = $_POST['p'][$i];
            $data[$i]['dc']  = $_POST['dc'][$i];
            $data[$i]['bc']  = $_POST['bc'][$i];
            $data[$i]['lat'] = (float)$_POST['lat'][$i];
            $data[$i]['lng'] = (float)$_POST['lng'][$i];
            $tagArr = explode(',', $_POST['tags'][$i]);
            $data[$i]['tags'] = array_values(array_filter(array_map('strtoupper', array_map('trim', $tagArr))));
        }
    }

    if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT))) {
        $msg = "<div style='background:#d4edda;color:#155724;padding:10px;border-radius:5px;margin-bottom:10px;'>✅ All changes (including Trauma Levels) saved!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>DFW EMS Admin</title>
    <style>
        body { font-family: -apple-system, sans-serif; background: #f4f7f6; padding: 20px; font-size: 12px; }
        .container { max-width: 1400px; margin: auto; }
        .card { background: white; padding: 12px; margin-bottom: 8px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-left: 5px solid #007bff; }
        .add-card { border-left: 5px solid #28a745; background: #f0fff4; margin-bottom: 30px; }
        
        /* Optimized 8-column grid */
        .grid { display: grid; grid-template-columns: 2fr 0.5fr 0.7fr 0.7fr 1fr 0.8fr 0.8fr 1.5fr; gap: 8px; }
        
        input { width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        label { font-size: 9px; font-weight: bold; color: #666; display: block; margin-bottom: 2px; text-transform: uppercase; }
        .save-bar { position: sticky; top: 0; background: #f4f7f6; padding: 10px 0; z-index: 100; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; margin-bottom: 20px; }
        button { padding: 10px 20px; cursor: pointer; border-radius: 4px; border: none; font-weight: bold; }
        .btn-add { background: #28a745; color: white; width: 100%; }
        .btn-save { background: #007bff; color: white; font-size: 16px; padding: 12px 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .ems-code { color: #d9534f; font-weight: bold; border: 1px solid #d9534f; }
        .ems-room { color: #2e7d32; font-weight: bold; border: 1px solid #2e7d32; }
        .lvl-input { text-align: center; font-weight: bold; background: #fffdf0; }
    </style>
</head>
<body>
<div class="container">
    <form method="POST">
    <div class="save-bar">
        <h1>🏥 EMS Hospital Database Admin</h1>
        <button type="submit" name="save_all" class="btn-save">💾 SAVE ALL CHANGES</button>
    </div>
    
    <?php echo $msg; ?>

    <div class="card add-card">
        <h3 style="margin:0 0 10px 0; color:#28a745;">+ Add New Hospital</h3>
        <div class="grid">
            <div><label>Hospital Name</label><input type="text" name="new_n"></div>
            <div><label>Level</label><input type="text" name="new_lvl" placeholder="I-IV" class="lvl-input"></div>
            <div><label>ER Door Code</label><input type="text" name="new_dc" class="ems-code"></div>
            <div><label>EMS Room</label><input type="text" name="new_bc" class="ems-room"></div>
            <div><label>ER Phone</label><input type="text" name="new_p"></div>
            <div><label>Lat</label><input type="text" name="new_lat"></div>
            <div><label>Long</label><input type="text" name="new_lng"></div>
            <div><label>Specialty Tags</label><input type="text" name="new_tags" placeholder="PSC, STEMI"></div>
        </div>
        <div style="margin-top:10px; display:flex; justify-content: space-between; align-items: flex-end;">
            <p style="margin:0; color:#666;">Note: Use uppercase for Levels (I, II, III, IV)</p>
            <div style="width:200px;"><button type="submit" name="add_new" class="btn-add">ADD HOSPITAL</button></div>
        </div>
    </div>

    <?php foreach ($data as $i => $h): ?>
        <div class="card">
            <div class="grid">
                <div>
                    <label>Hospital Name</label>
                    <input type="text" name="n[<?php echo $i; ?>]" value="<?php echo htmlspecialchars($h['n']); ?>">
                </div>
                <div>
                    <label>Level</label>
                    <input type="text" name="lvl[<?php echo $i; ?>]" value="<?php echo htmlspecialchars($h['lvl'] ?? ''); ?>" class="lvl-input">
                </div>
                <div>
                    <label>ER Door Code</label>
                    <input type="text" name="dc[<?php echo $i; ?>]" value="<?php echo htmlspecialchars($h['dc']); ?>" class="ems-code">
                </div>
                <div>
                    <label>EMS Room</label>
                    <input type="text" name="bc[<?php echo $i; ?>]" value="<?php echo htmlspecialchars($h['bc'] ?? ''); ?>" class="ems-room">
                </div>
                <div>
                    <label>ER Phone</label>
                    <input type="text" name="p[<?php echo $i; ?>]" value="<?php echo htmlspecialchars($h['p']); ?>">
                </div>
                <div>
                    <label>Latitude</label>
                    <input type="text" name="lat[<?php echo $i; ?>]" value="<?php echo $h['lat']; ?>">
                </div>
                <div>
                    <label>Longitude</label>
                    <input type="text" name="lng[<?php echo $i; ?>]" value="<?php echo $h['lng']; ?>">
                </div>
                <div>
                    <label>Specialty Tags</label>
                    <input type="text" name="tags[<?php echo $i; ?>]" value="<?php echo htmlspecialchars(implode(', ', $h['tags'])); ?>">
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </form>
</div>
</body>
</html>
