<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Tareas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .task-list ul {
            list-style: none;
            padding: 0;
        }
        .task-list li {
            background: #f4f4f4;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .completed {
            text-decoration: line-through;
            color: gray;
        }
        .task-form input, .task-form textarea {
            width: 100%;
            padding: 5px;
            margin: 5px 0 10px;
        }
        .task-form button {
            padding: 8px 12px;
            cursor: pointer;
        }
        .reset-button {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background: red;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .reset-button:hover {
            background: darkred;
        }
    </style>
</head>
<body>
