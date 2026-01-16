# Pokémon Calculator - API Documentation

## Endpoints

### 1. Búsqueda de Pokémon

**Endpoint:** `GET /api/pokemon/search`

**Parámetros:**
- `name` (string, requerido): Nombre del Pokémon a buscar

**Ejemplo:**
```
GET /api/pokemon/search?name=pikachu
```

**Respuesta (200 OK):**
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
        "image": "https://..."
    }
}
```

**Respuesta (404 Not Found):**
```json
{
    "error": "Pokémon no encontrado"
}
```

**Respuesta (500 Error):**
```json
{
    "error": "Error al buscar: mensaje de error"
}
```

---

### 2. Comparación de Pokémon

**Endpoint:** `GET /api/pokemon/search?name=pokemon1` + `GET /api/pokemon/search?name=pokemon2`

Se realizan dos búsquedas independientes y se comparan

**Ejemplo:**
```javascript
// Busca pikachu
fetch('/api/pokemon/search?name=pikachu')

// Busca charizard
fetch('/api/pokemon/search?name=charizard')

// El frontend compara los resultados
```

---

### 3. Cálculo de Estadísticas Personalizadas

**Endpoint:** `POST /api/stats/calculate`

**Body (JSON):**
```json
{
    "hp": 100,
    "attack": 120,
    "defense": 100,
    "spAtk": 140,
    "spDef": 90,
    "speed": 110
}
```

**Respuesta (200 OK):**
```json
{
    "success": true,
    "data": {
        "stats": {
            "hp": 100,
            "attack": 120,
            "defense": 100,
            "spAtk": 140,
            "spDef": 90,
            "speed": 110
        },
        "total": 760,
        "average": 126.67,
        "max": 140,
        "min": 90
    }
}
```

---

## Modelos de Datos

### Pokémon
```php
class Pokemon {
    public int $id;              // ID único del Pokémon
    public string $name;         // Nombre del Pokémon
    public string $type;         // Tipo/tipos separados por coma
    public int $hp;              // Puntos de vida (0-255)
    public int $attack;          // Ataque (0-255)
    public int $defense;         // Defensa (0-255)
    public int $spAtk;           // Ataque especial (0-255)
    public int $spDef;           // Defensa especial (0-255)
    public int $speed;           // Velocidad (0-255)
    public string $image;        // URL de la imagen
}
```

---

## Códigos de Estado HTTP

| Código | Significado | Descripción |
|--------|------------|-------------|
| 200 | OK | Solicitud exitosa |
| 400 | Bad Request | Parámetros inválidos |
| 404 | Not Found | Pokémon no encontrado |
| 500 | Server Error | Error interno del servidor |

---

## Límites de Tasa

- No hay límite de tasa implementado actualmente
- PokéAPI (usada internamente) tiene su propio límite
- Se recomienda no más de 10 solicitudes por segundo

---

## Ejemplos JavaScript

### Buscar Pokémon
```javascript
fetch('/api/pokemon/search?name=pikachu')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(data.data);
        } else {
            console.error(data.error);
        }
    });
```

### Comparar Pokémon
```javascript
Promise.all([
    fetch('/api/pokemon/search?name=pikachu').then(r => r.json()),
    fetch('/api/pokemon/search?name=charizard').then(r => r.json())
])
.then(([data1, data2]) => {
    if (data1.success && data2.success) {
        // Comparar data1.data y data2.data
    }
});
```

---

## Notas Importantes

1. **Seguridad:** Los nombres se validan antes de usarse
2. **Caché:** Se pueden cachear respuestas para mejorar rendimiento
3. **CORS:** El servidor no implementa CORS por defecto (uso local)
4. **Encoding:** Las respuestas están en UTF-8

---

## Fuentes de Datos

- **Pokémon:** PokéAPI (https://pokeapi.co/api/v2/)
- **Imágenes:** Oficiales de PokéAPI

