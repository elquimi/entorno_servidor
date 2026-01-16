# Pokémon Calculator - Guía de Inicio Rápido

## 🚀 Inicio Rápido

### Configuración del servidor local (WAMP)

1. **Asegúrate de que WAMP esté ejecutándose**
   - Abre WAMP y verifica que todos los servicios estén verdes

2. **Accede a la aplicación**
   - Abre tu navegador y ve a: `http://localhost/proyecto php/`

3. **¡Listo!** Comienza a buscar y comparar Pokémon

### ⚠️ ¿Te dice "Página no encontrada"?

Si ves un error 404, lee el archivo **SOLUCION_404.md** en la carpeta del proyecto.

**Soluciones rápidas:**
- ✅ Verifica que WAMP esté corriendo (todos en verde)
- ✅ Usa la URL exacta: `http://localhost/proyecto php/` (con espacio)
- ✅ Presiona Ctrl+Shift+R para limpiar caché
- ✅ Si tienes dudas, abre: `http://localhost/proyecto php/diagnostico.php`

---

## 🔍 Ejemplos de Uso

### Ejemplo 1: Buscar un Pokémon

1. Ve a la pestaña "Buscar Pokémon"
2. Escribe "pikachu" en el campo de búsqueda
3. Haz clic en "Buscar"
4. Verás:
   - Nombre e ID
   - Imagen oficial
   - Tipo
   - Todas sus estadísticas
   - Total de estadísticas

**Pokémon disponibles para buscar:**
- pikachu, charizard, blastoise, venusaur
- dragonite, alakazam, machamp, gengar
- ¡Y miles más!

### Ejemplo 2: Comparar dos Pokémon

1. Ve a la pestaña "Comparar Pokémon"
2. Primer Pokémon: "charizard"
3. Segundo Pokémon: "dragonite"
4. Haz clic en "Comparar"
5. Verás:
   - Tarjetas de ambos Pokémon lado a lado
   - Tabla comparativa de cada estadística
   - Ganador en cada categoría
   - Ganador general

### Ejemplo 3: Estadísticas Personalizadas

1. Ve a la pestaña "Estadísticas Personalizadas"
2. Ingresa valores de ejemplo:
   - HP: 150
   - Ataque: 120
   - Defensa: 100
   - Ataque Esp.: 140
   - Defensa Esp.: 90
   - Velocidad: 110
3. Haz clic en "Calcular Estadísticas"
4. Verás:
   - Estadísticas ingresadas
   - Total: 810
   - Promedio: 135
   - Máximo: 150
   - Mínimo: 90

## 📚 Estructura de Carpetas Explicada

```
proyecto php/
│
├── public/                    # Archivos que ve el usuario
│   ├── index.html            # Página HTML principal
│   ├── 404.html              # Página de error
│   ├── css/
│   │   └── styles.css        # Estilos (colores, fuentes, diseño)
│   ├── js/
│   │   └── script.js         # Lógica interactiva del navegador
│   └── images/               # Imágenes
│
├── src/                       # Código PHP backend (servidor)
│   ├── controllers/          # Controlan la lógica de la app
│   │   ├── PokemonController.php
│   │   └── StatsController.php
│   ├── models/              # Definen cómo son los datos
│   │   └── Pokemon.php
│   └── services/            # Hacen el trabajo pesado
│       ├── PokemonService.php
│       └── StatsService.php
│
├── database/                 # Para base de datos (futuro)
├── index.php                 # Punto de entrada (controla rutas)
├── config.php               # Configuración de la app
└── README.md                # Documentación completa
```

## 🔧 Cómo Funciona

### Flujo de una búsqueda:

1. **Usuario escribe "pikachu" en HTML** (public/index.html)
2. **JavaScript (public/js/script.js) envía solicitud**
   ```
   GET /api/pokemon/search?name=pikachu
   ```
3. **PHP (index.php) recibe la solicitud**
4. **PokemonController** procesa la solicitud
5. **PokemonService** busca en la API de PokéAPI
6. **Pokemon (modelo) estructura los datos**
7. **Respuesta JSON vuelve al navegador**
8. **JavaScript muestra los resultados en HTML**

### Componentes principales:

**Controllers** (controladores)
- Reciben solicitudes del navegador
- Deciden qué hacer
- Retornan respuestas JSON

**Services** (servicios)
- Hacen la lógica del negocio
- Buscan en APIs externas
- Hacen cálculos complejos

**Models** (modelos)
- Definen la estructura de datos
- En este caso: datos de Pokémon

## 🌐 API de PokéAPI

La aplicación usa PokéAPI, una API pública gratuita.

**Endpoint usado:**
```
https://pokeapi.co/api/v2/pokemon/{nombre}
```

**Ejemplo de respuesta:**
```json
{
  "id": 25,
  "name": "pikachu",
  "stats": [
    {"base_stat": 35, "stat": {"name": "hp"}},
    {"base_stat": 55, "stat": {"name": "attack"}},
    ...
  ],
  "sprites": {"front_default": "url_imagen"},
  "types": [{"type": {"name": "electric"}}]
}
```

## 💡 Consejos

- **Búsqueda rápida:** Presiona Enter después de escribir el nombre
- **Comparación rápida:** Presiona Enter en cualquiera de los campos
- **Nombres válidos:** Deben estar en inglés y en minúsculas
- **Sin conexión:** Si la API no responde, intenta de nuevo

## 🐛 Si algo no funciona

1. **Verifica que WAMP esté corriendo**
   - Todos los servicios deben estar verdes
   - Apache y PHP funcionando

2. **Verifica la URL**
   - Debe ser: `http://localhost/proyecto php/`
   - No: `http://localhost/proyecto%20php/`

3. **Abre la consola del navegador** (F12)
   - Ve a la pestaña "Console"
   - Mira si hay errores rojo

4. **Intenta recargar** (Ctrl + Shift + R)
   - Limpia la caché del navegador

## 🚀 Próximas mejoras

- Base de datos local para caché
- Sistema de favoritos
- Gráficas comparativas
- Modo oscuro
- Aplicación móvil
- Más idiomas

---

¡Diviértete con la Pokémon Calculator! 🔴⚪✨
