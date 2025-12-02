<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Nómina</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 1rem;
    }

    form {
      background-color: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 400px;
    }

    h1 {
      text-align: center;
      margin-bottom: 1.5rem;
      font-size: 1.5rem;
      color: #333;
    }

    label {
      display: block;
      margin-top: 1rem;
      margin-bottom: 0.3rem;
      font-weight: 600;
      color: #444;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 0.6rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
      box-sizing: border-box;
      transition: border-color 0.2s;
    }

    input:focus {
      outline: none;
      border-color: #2575fc;
      box-shadow: 0 0 5px rgba(37, 117, 252, 0.4);
    }

    button {
      margin-top: 1.5rem;
      width: 100%;
      padding: 0.75rem;
      background-color: #2575fc;
      color: white;
      font-size: 1rem;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #1a5fd0;
    }
  </style>
</head>
<body>

  <form action="procesar.php" method="post">
    <h1>Formulario de Nómina</h1>

    <label for="nombre">Introduce tu nombre</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="fechaNacimiento">Introduce tu fecha de nacimiento</label>
    <input type="text" id="fechaNacimiento" name="fechaNacimiento" required placeholder="DD/MM/AAAA">

    <label for="horasRealizadas">Horas de la semana (formato 8|8|8)</label>
    <input type="text" id="horasRealizadas" name="horasRealizadas" required>

    <label for="salarioHora">Introduce lo que cobras por hora</label>
    <input type="number" id="salarioHora" name="salarioHora" required step="0.01" min="0">

    <label for="retencion">Introduce tu retención (%)</label>
    <input type="number" id="retencion" name="retencion" required step="0.01" min="0">

    <button type="submit">Enviar</button>
  </form>

</body>
</html>
