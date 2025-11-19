<?php
// paso4/app/views/header.php
?>
<!doctype html>
<html lang="es">
<head>
 <meta charset="utf-8">
 <title>Paso 4 — Router / Front Controller</title>
 <meta name="viewport" content="width=device-width,initial-scale=1">
 <style>
 body{font-family:Arial,Helvetica,sans-serif;max-width:1000px;margin:18px auto;padding:10px}
 header{background:#e8f6ff;padding:10px;border-radius:6px}
 h1{margin:0}
 .nav{margin-top:8px}
 .btn{display:inline-block;padding:6px 10px;margin-right:6px;border:1px solid #bbb;border-radius:4px;textdecoration:none;background:#fff}
 </style>
</head>
<body>
<header>
 <h1>Paso 4 — Router y Front Controller</h1>
 <p class="small">Entrada única en <code>public/index.php</code> que despacha peticiones a 
controladores en <code>app/controllers/</code>.</p>
</header>
<main>
