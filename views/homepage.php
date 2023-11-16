<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/menu.css">

    <div id="info"><?= sessiondata('login', 'email') ?> - <?= sessiondata('login', 'name') ?></div>
    <div id="logout"><a href="<?= base_url('auth/logout') ?>"  rel="noopener">Logout</a>  </div>
    <div id="container"></div>
    <div id="menu">
        <button id="table">TABLE</button>
        <button id="sphere">SPHERE</button>
        <button id="helix">HELIX</button>
        <button id="grid">GRID</button>
    </div>
    
    <script type="importmap">
        {
            "imports": {
            "three": "https://unpkg.com/three@0.158.0/build/three.module.js",
            "three/addons/": "https://unpkg.com/three@0.158.0/examples/jsm/"
            }
        }
    </script>
</head>

<body>

    <script>
        var data = <?= json_encode($data)?>;
    </script>
    <script type="module" src="./assets/js/main.js"></script>
</body>

</html>