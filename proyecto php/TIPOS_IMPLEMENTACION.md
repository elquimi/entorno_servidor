# Sistema de Tipos de Pokémon - Documentación

## Descripción General

Se ha implementado un sistema completo de efectividad de tipos para la calculadora de daño de Pokémon. Ahora el sistema calcula correctamente:

- **Debilidades**: Cuando un movimiento es de un tipo débil contra el defensor (x2 de daño)
- **Resistencias**: Cuando un movimiento es resistido por el tipo del defensor (x0.5 de daño)
- **Inmunidades**: Cuando un movimiento no afecta al defensor (x0 de daño)
- **Multiplicadores acumulativos**: Si un Pokémon tiene dos tipos, los multiplicadores se aplican de forma acumulativa

## Ejemplos de Funcionamiento

### Ejemplo 1: Debilidad Simple
- **Movimiento**: Lanza Llamas (Fuego, poder 90)
- **Defensor**: Venusaur (tipo Planta)
- **Resultado**: 2x de daño (MUY EFECTIVO)
- **Cálculo**: Planta es débil a Fuego → x2 multiplicador

### Ejemplo 2: Multiplicador Acumulativo (x4)
- **Movimiento**: Danza Espada (Planta)
- **Defensor**: Tyranitar (tipo Roca/Tierra)
- **Resultado**: 4x de daño (¡EXTREMADAMENTE EFECTIVO!)
- **Cálculo**: 
  - Planta es débil a Roca → x2
  - Planta es débil a Tierra → x2
  - Total: 2 × 2 = x4

### Ejemplo 3: Resistencia
- **Movimiento**: Lanza Llamas (Fuego, poder 90)
- **Defensor**: Blastoise (tipo Agua)
- **Resultado**: 0.5x de daño (POCO EFECTIVO)
- **Cálculo**: Agua resiste Fuego → x0.5 multiplicador

### Ejemplo 4: Inmunidad
- **Movimiento**: Rayo Confuso (Normal)
- **Defensor**: Gengar (tipo Fantasma)
- **Resultado**: 0x de daño (¡INMUNE!)
- **Cálculo**: Fantasma es inmune a Normal → x0

## Archivos Implementados

### 1. `src/data/types.json`
Matriz de efectividad de tipos que define para cada tipo:
- `weak_to`: Tipos de movimientos que hacen x2 de daño
- `resists`: Tipos de movimientos que hacen x0.5 de daño
- `immune_to`: Tipos de movimientos que hacen x0 de daño

### 2. `src/services/TypeService.php`
Servicio que maneja la lógica de efectividad de tipos:
- `getDamageMultiplier($moveType, $defenderTypes)`: Calcula el multiplicador de daño
- `getTypeEffectiveness($type)`: Obtiene información de efectividad de un tipo
- Soporta Pokémon con múltiples tipos

### 3. Actualización de `src/services/StatsService.php`
Se agregó el método `calculateDamageWithType()` que:
- Calcula daño usando la fórmula oficial de Pokémon
- Aplica multiplicadores de tipo automáticamente
- Retorna información detallada del daño (min, max, porcentaje HP, KOs)

### 4. Actualización de `src/controllers/StatsController.php`
Se agregó el método `calculateDamageWithTypes()` que expone la API:
- Endpoint: `POST /api/stats/damage`
- Acepta JSON con datos de atacante, defensor y movimiento

### 5. `public/js/script.js`
Se agregaron:
- `TYPE_EFFECTIVENESS`: Matriz de tipos en JavaScript
- `getTypeMultiplier()`: Función para calcular multiplicadores
- Actualización de `calculateDamage()`: Ahora muestra efectividad de tipos

## Uso en la Interfaz Web

1. Abre la pestaña "Calcular Daño"
2. Selecciona un Pokémon atacante
3. Selecciona un Pokémon defensor
4. Ingresa el nombre, tipo y poder del movimiento
5. Haz clic en "Calcular"

El resultado mostrará:
- Daño mínimo y máximo con tipos considerados
- Porcentaje de HP que recibe el defensor
- Número de "KOs" (ataques necesarios para derrotar)
- **NUEVO**: Efectividad del tipo (Muy efectivo, Poco efectivo, Normal, Inmune)

## Tabla de Tipos Implementada

Se implementó la tabla de tipos estándar de Pokémon con 18 tipos:

| Tipo | Débil a | Resiste | Inmune a |
|------|---------|---------|----------|
| Normal | Lucha | - | Fantasma |
| Fuego | Agua, Tierra, Roca | Fuego, Planta, Hielo, Bicho, Acero, Hada | - |
| Agua | Eléctrico, Planta | Fuego, Agua, Hielo, Acero | - |
| Eléctrico | Tierra | Eléctrico, Volador, Acero | - |
| Planta | Fuego, Hielo, Veneno, Volador, Bicho | Tierra, Agua, Planta, Eléctrico | - |
| Hielo | Fuego, Lucha, Roca, Acero | Hielo | - |
| Lucha | Volador, Psíquico, Hada | Roca, Bicho, Siniestro | - |
| Veneno | Tierra, Psíquico | Lucha, Veneno, Bicho, Hada | - |
| Tierra | Agua, Planta, Hielo | Veneno, Roca | Eléctrico |
| Volador | Eléctrico, Roca, Hielo | Lucha, Bicho, Planta | - |
| Psíquico | Bicho, Fantasma, Siniestro | Lucha, Psíquico | - |
| Bicho | Fuego, Volador, Roca | Lucha, Tierra, Planta | - |
| Roca | Agua, Planta, Lucha, Tierra, Acero | Normal, Volador, Veneno, Fuego | - |
| Fantasma | Fantasma, Siniestro | Veneno, Bicho | Normal, Lucha |
| Dragón | Hielo, Dragón, Hada | Fuego, Agua, Planta, Eléctrico | - |
| Siniestro | Lucha, Bicho, Hada | Fantasma, Siniestro | Psíquico |
| Acero | Fuego, Agua, Tierra | Normal, Volador, Roca, Bicho, Planta, Psíquico, Hielo, Dragón, Hada, Acero | Veneno |
| Hada | Veneno, Acero | Lucha, Bicho, Siniestro | - |

## Pruebas

Se incluye un archivo `test_types.php` que valida:
1. Multiplicadores simples (0.5x, 1x, 2x)
2. Multiplicadores acumulativos (x4)
3. Inmunidades (x0)
4. Cálculo completo de daño

Ejecutar pruebas:
```bash
php test_types.php
```

## Próximas Mejoras (Sugerencias)

1. **Naturaleza de Pokémon**: Agregar sistema de naturalezas que modifican estadísticas
2. **Habilidades**: Considerar habilidades que modifiquen tipos o daño
3. **Objetos**: Implementar objetos que causen STAB (Same Type Attack Bonus)
4. **Condiciones de campo**: Lluvia, Granizo, etc., que afecten el daño
5. **Histórico de cálculos**: Guardar cálculos anteriores
6. **Gráfico de comparativa**: Mostrar visualmente las fortalezas/debilidades

## API REST

### Endpoint: POST /api/stats/damage

**Request:**
```json
{
  "attacker": {
    "name": "Charizard",
    "attack": 84,
    "spAtk": 109,
    "hp": 78,
    "defense": 78,
    "spDef": 85,
    "speed": 100
  },
  "defender": {
    "name": "Venusaur",
    "hp": 80,
    "defense": 83,
    "spDef": 100,
    "type": "Planta"
  },
  "move": {
    "name": "Lanza Llamas",
    "power": 90,
    "type": "Fuego"
  },
  "level": 50
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "moveName": "Lanza Llamas",
    "moveType": "Fuego",
    "movePower": 90,
    "isSpecialMove": true,
    "attackerName": "Charizard",
    "attackerStat": 109,
    "defenderName": "Venusaur",
    "defenderType": "Planta",
    "defenderStat": 100,
    "defenderHP": 80,
    "typeMultiplier": 2,
    "effectiveness": "Muy efectivo",
    "minDamage": 76,
    "maxDamage": 90,
    "percentMin": 95,
    "percentMax": 113,
    "kos": 1,
    "immune": false
  }
}
```

## Conclusión

El sistema de tipos está completamente funcional y listo para usar. Los multiplicadores acumulativos (como x2 × x2 = x4) funcionan correctamente, permitiendo cálculos realistas de daño en batallas de Pokémon.
