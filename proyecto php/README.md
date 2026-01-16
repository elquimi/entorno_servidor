# 🔴 Pokémon Calculator

Una aplicación web interactiva para buscar, comparar y analizar estadísticas de Pokémon.

## Características

✨ **Búsqueda de Pokémon**: Busca cualquier Pokémon por nombre y obtén toda su información
- Nombre y ID
- Tipo/Tipos
- Estadísticas completas (HP, Ataque, Defensa, Ataque Esp., Defensa Esp., Velocidad)
- Imagen oficial del Pokémon
- Total de estadísticas

🥊 **Comparación de Pokémon**: Compara las estadísticas de dos Pokémon
- Visualización lado a lado
- Tabla comparativa detallada
- Identificación del ganador en cada estadística
- Comparación de totales

📊 **Estadísticas Personalizadas**: Crea y analiza conjuntos de estadísticas personalizadas
- Ingresa valores de estadísticas
- Calcula automáticamente: total, promedio, máximo y mínimo
- Visualización clara de resultados

## Estructura del Proyecto

```
proyecto php/
├── public/                  # Archivos públicos (HTML, CSS, JS, imágenes)
│   ├── index.html          # Página principal
│   ├── 404.html            # Página de error
│   ├── css/
│   │   └── styles.css      # Estilos principales
│   ├── js/
│   │   └── script.js       # Lógica frontend
│   └── images/             # Imágenes del sitio
│
├── src/                     # Código PHP backend
│   ├── controllers/        # Controladores de la aplicación
│   │   ├── PokemonController.php
│   │   └── StatsController.php
│   │
│   ├── models/            # Modelos de datos
│   │   └── Pokemon.php
│   │
│   └── services/          # Servicios de negocio
│       ├── PokemonService.php
│       └── StatsService.php
│
├── database/              # Archivos de base de datos (si se usa)
│
└── index.php              # Punto de entrada de la aplicación
```

## Requisitos

- PHP 7.2 o superior
- Servidor web (Apache, Nginx, etc.)
- Acceso a internet (para la API de PokéAPI)

## Instalación

1. **Descargar/Clonar el proyecto**
   ```bash
   cd c:\wamp64\www\temp\proyecto php
   ```

2. **Configurar el servidor web**
   - Asegúrate de que tu servidor web esté configurado para servir archivos desde esta carpeta
   - La URL base debe ser: `http://localhost/proyecto php/` (o similar según tu configuración)

3. **No se requieren dependencias externas**
   - El proyecto usa la API pública de PokéAPI (https://pokeapi.co/)
   - No requiere base de datos local

## Cómo Usar

### Buscar Pokémon
1. Ve a la pestaña "Buscar Pokémon"
2. Escribe el nombre del Pokémon (ej: "pikachu", "charizard")
3. Haz clic en "Buscar" o presiona Enter
4. Verás toda la información del Pokémon

### Comparar Pokémon
1. Ve a la pestaña "Comparar Pokémon"
2. Ingresa el nombre del primer Pokémon
3. Ingresa el nombre del segundo Pokémon
4. Haz clic en "Comparar"
5. Verás una comparación detallada de sus estadísticas

### Estadísticas Personalizadas
1. Ve a la pestaña "Estadísticas Personalizadas"
2. Ingresa los valores para cada estadística (0-255)
3. Haz clic en "Calcular Estadísticas"
4. Verás el análisis de tus estadísticas

## API Endpoints

### Búsqueda de Pokémon
```
GET /api/pokemon/search?name=<nombre>
```
Retorna información completa del Pokémon

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": {
        "id": 25,
        "name": "Pikachu",
        "type": "electric",
        "hp": 35,
        "attack": 55,
        "defense": 40,
        "spAtk": 50,
        "spDef": 50,
        "speed": 90,
        "totalStats": 320,
        "image": "..."
    }
}
```

### Comparación de Pokémon
```
GET /api/pokemon/search?name=<nombre1>
GET /api/pokemon/search?name=<nombre2>
```
Realiza dos búsquedas y compara los resultados

## Tecnologías Utilizadas

**Backend:**
- PHP 7.2+
- Arquitectura MVC (Model-View-Controller)

**Frontend:**
- HTML5
- CSS3 (con Flexbox y Grid)
- JavaScript (Fetch API)

**Datos:**
- PokéAPI (https://pokeapi.co/api/v2/)

## Características Futuras

- 🔐 Sistema de autenticación de usuarios
- 💾 Guardar Pokémon favoritos
- 📱 Aplicación móvil
- 🎮 Simulador de batallas
- 🗄️ Base de datos local para caché
- 🌐 Soporte para múltiples idiomas
- 📊 Gráficas de comparación avanzadas

## Solución de Problemas

### "Pokémon no encontrado"
- Verifica que el nombre esté escrito correctamente
- Los nombres deben estar en inglés
- Usa minúsculas (la búsqueda no es sensible a mayúsculas)

### Los errores de conexión
- Asegúrate de tener conexión a internet
- PokéAPI puede tener límites de velocidad
- Intenta de nuevo en unos momentos

## Licencia

Este proyecto es de código abierto y está disponible para uso educativo.

## Autor

Proyecto creado con ❤️ para amantes de Pokémon

---

¡Disfruta comparando tus Pokémon favoritos! 🔴⚪
