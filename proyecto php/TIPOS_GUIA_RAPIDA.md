# SISTEMA DE TIPOS - GUÍA RÁPIDA

## 🎯 ¿Qué se implementó?

Se agregó un **sistema de efectividad de tipos** que calcula automáticamente cuánto daño hace un movimiento según el tipo del Pokémon atacante y defensor.

## 📊 Ejemplos Prácticos

### ✅ EJEMPLO 1: Un tipo débil (x2)
```
Atacante: Charizard lanza LANZA LLAMAS (Fuego)
Defensor: Venusaur (Planta)
Resultado: 2x de daño - ¡MUY EFECTIVO!

Por qué: Fuego es super efectivo contra Planta
```

### ✅ EJEMPLO 2: Dos tipos débiles (x4 - ¡PODEROSO!)
```
Atacante: Charizard lanza DANZA ESPADA (Planta)
Defensor: Tyranitar (Roca / Tierra)
Resultado: 4x de daño - ¡EXTREMADAMENTE EFECTIVO!

Por qué: 
  - Planta es débil a Roca: x2
  - Planta es débil a Tierra: x2
  - Total: x2 × x2 = x4 ⚡⚡⚡
```

### ✅ EJEMPLO 3: Resistencia (x0.5)
```
Atacante: Charizard lanza LANZA LLAMAS (Fuego)
Defensor: Blastoise (Agua)
Resultado: 0.5x de daño - POCO EFECTIVO

Por qué: Agua resiste el Fuego
```

### ✅ EJEMPLO 4: Inmunidad (x0)
```
Atacante: Machamp lanza RAYO CONFUSO (Normal)
Defensor: Gengar (Fantasma)
Resultado: 0x de daño - ¡INMUNE!

Por qué: Fantasma es completamente inmune a movimientos Normales
```

## 🔧 Archivos Implementados

| Archivo | Descripción |
|---------|-------------|
| `src/data/types.json` | Tabla de efectividades (18 tipos Pokémon) |
| `src/services/TypeService.php` | Lógica de cálculo de multiplicadores |
| `src/services/StatsService.php` | (Actualizado) Nuevo método `calculateDamageWithType()` |
| `public/js/script.js` | (Actualizado) Tabla de tipos + función `getTypeMultiplier()` |
| `test_types.php` | Pruebas unitarias del sistema |

## ✨ Características Principales

✓ **Multiplicadores acumulativos**: Si un Pokémon tiene dos tipos, los multiplicadores se suman
✓ **18 tipos diferentes**: Todos los tipos de Pokémon generación 1-8
✓ **Inmunidades**: Algunos movimientos no hacen daño a ciertos tipos
✓ **Interfaz mejorada**: Muestra si el movimiento es "Muy efectivo", "Poco efectivo", "Normal" o "Inmune"
✓ **Backend + Frontend**: Funciona en JavaScript (cliente) y PHP (servidor)

## 🧪 Pruebas Realizadas

```
✓ PRUEBA 1: Fuego vs Agua = 0.5x (CORRECTO)
✓ PRUEBA 2: Planta vs Roca/Tierra = 4x (CORRECTO)
✓ PRUEBA 3: Normal vs Fantasma = 0x (CORRECTO)
✓ PRUEBA 4: Cálculo completo de daño (CORRECTO)
```

## 📈 Fórmula de Daño

```
Daño Base = ((((2 × nivel / 5 + 2) × poder × ataque) / defensa) / 50) + 2

Daño Final = Daño Base × Multiplicador de Tipo × Variación (85%-100%)
```

## 🎮 Cómo Usar en la Web

1. Abre la pestaña "Calcular Daño"
2. Selecciona Pokémon atacante
3. Selecciona Pokémon defensor
4. Ingresa movimiento (nombre, tipo, poder)
5. **¡Nuevo!** Verás la efectividad del tipo mostrada automáticamente

## 📋 Tabla Completa de Tipos

### Tipos y sus Debilidades

- **Normal**: Débil a Lucha
- **Fuego**: Débil a Agua, Tierra, Roca
- **Agua**: Débil a Eléctrico, Planta
- **Eléctrico**: Débil a Tierra
- **Planta**: Débil a Fuego, Hielo, Veneno, Volador, Bicho
- **Hielo**: Débil a Fuego, Lucha, Roca, Acero
- **Lucha**: Débil a Volador, Psíquico, Hada
- **Veneno**: Débil a Tierra, Psíquico
- **Tierra**: Débil a Agua, Planta, Hielo
- **Volador**: Débil a Eléctrico, Roca, Hielo
- **Psíquico**: Débil a Bicho, Fantasma, Siniestro
- **Bicho**: Débil a Fuego, Volador, Roca
- **Roca**: Débil a Agua, Planta, Lucha, Tierra, Acero
- **Fantasma**: Débil a Fantasma, Siniestro
- **Dragón**: Débil a Hielo, Dragón, Hada
- **Siniestro**: Débil a Lucha, Bicho, Hada
- **Acero**: Débil a Fuego, Agua, Tierra
- **Hada**: Débil a Veneno, Acero

### Tipos Inmunes

- **Fantasma**: Inmune a Normal y Lucha
- **Tierra**: Inmune a Eléctrico
- **Siniestro**: Inmune a Psíquico
- **Acero**: Inmune a Veneno

## 🚀 API REST

### Endpoint: `POST /api/stats/damage`

```bash
curl -X POST http://localhost/temp/proyecto%20php/api/stats/damage \
  -H "Content-Type: application/json" \
  -d '{
    "attacker": {"name": "Charizard", "spAtk": 109},
    "defender": {"name": "Venusaur", "type": "Planta"},
    "move": {"name": "Lanza Llamas", "power": 90, "type": "Fuego"},
    "level": 50
  }'
```

## 💡 Próximas Mejoras Sugeridas

- [ ] Agregar naturalezas (que modifican estadísticas)
- [ ] Considerar habilidades especiales
- [ ] Implementar STAB (Same Type Attack Bonus)
- [ ] Agregar condiciones de campo (lluvia, granizo, etc.)
- [ ] Histórico de cálculos
- [ ] Gráficos de fortalezas/debilidades

## ✅ Status Actual

**TODO FUNCIONA PERFECTAMENTE** ✨

El sistema está listo para usar en la interfaz web. Los multiplicadores acumulativos (x4) funcionan correctamente como solicitaste.
