// Variables globales
let currentPokemon1 = null;
let currentPokemon2 = null;
let allMoves = []; // Lista de todos los movimientos

// Definir ruta base relativa (con espacio codificado como %20)
const BASE_PATH = '/temp/proyecto%20php';

// ==================== Tabla de Efectividad de Tipos ====================
/**
 * Matriz de efectividad de tipos
 * Para cada tipo de DEFENSA, especifica qué tipos de ATAQUE son débiles/resistencias/inmunidades
 */
const TYPE_EFFECTIVENESS = {
    "Normal": {
        "weak_to": ["Lucha"],
        "resists": [],
        "immune_to": ["Fantasma"]
    },
    "Fuego": {
        "weak_to": ["Agua", "Tierra", "Roca"],
        "resists": ["Fuego", "Planta", "Hielo", "Bicho", "Acero", "Hada"],
        "immune_to": []
    },
    "Agua": {
        "weak_to": ["Eléctrico", "Planta"],
        "resists": ["Fuego", "Agua", "Hielo", "Acero"],
        "immune_to": []
    },
    "Eléctrico": {
        "weak_to": ["Tierra"],
        "resists": ["Eléctrico", "Volador", "Acero"],
        "immune_to": []
    },
    "Planta": {
        "weak_to": ["Fuego", "Hielo", "Veneno", "Volador", "Bicho"],
        "resists": ["Tierra", "Agua", "Planta", "Eléctrico"],
        "immune_to": []
    },
    "Hielo": {
        "weak_to": ["Fuego", "Lucha", "Roca", "Acero"],
        "resists": ["Hielo"],
        "immune_to": []
    },
    "Lucha": {
        "weak_to": ["Volador", "Psíquico", "Hada"],
        "resists": ["Roca", "Bicho", "Siniestro"],
        "immune_to": []
    },
    "Veneno": {
        "weak_to": ["Tierra", "Psíquico"],
        "resists": ["Lucha", "Veneno", "Bicho", "Hada"],
        "immune_to": []
    },
    "Tierra": {
        "weak_to": ["Agua", "Planta", "Hielo"],
        "resists": ["Veneno", "Roca"],
        "immune_to": ["Eléctrico"]
    },
    "Volador": {
        "weak_to": ["Eléctrico", "Roca", "Hielo"],
        "resists": ["Lucha", "Bicho", "Planta"],
        "immune_to": []
    },
    "Psíquico": {
        "weak_to": ["Bicho", "Fantasma", "Siniestro"],
        "resists": ["Lucha", "Psíquico"],
        "immune_to": []
    },
    "Bicho": {
        "weak_to": ["Fuego", "Volador", "Roca"],
        "resists": ["Lucha", "Tierra", "Planta"],
        "immune_to": []
    },
    "Roca": {
        "weak_to": ["Agua", "Planta", "Lucha", "Tierra", "Acero"],
        "resists": ["Normal", "Volador", "Veneno", "Fuego"],
        "immune_to": []
    },
    "Fantasma": {
        "weak_to": ["Fantasma", "Siniestro"],
        "resists": ["Veneno", "Bicho"],
        "immune_to": ["Normal", "Lucha"]
    },
    "Dragón": {
        "weak_to": ["Hielo", "Dragón", "Hada"],
        "resists": ["Fuego", "Agua", "Planta", "Eléctrico"],
        "immune_to": []
    },
    "Siniestro": {
        "weak_to": ["Lucha", "Bicho", "Hada"],
        "resists": ["Fantasma", "Siniestro"],
        "immune_to": ["Psíquico"]
    },
    "Acero": {
        "weak_to": ["Fuego", "Agua", "Tierra"],
        "resists": ["Normal", "Volador", "Roca", "Bicho", "Planta", "Psíquico", "Hielo", "Dragón", "Hada", "Acero"],
        "immune_to": ["Veneno"]
    },
    "Hada": {
        "weak_to": ["Veneno", "Acero"],
        "resists": ["Lucha", "Bicho", "Siniestro"],
        "immune_to": []
    }
};

/**
 * Calcula el multiplicador de daño basado en la efectividad de tipos
 * Si el defensor tiene múltiples tipos, los multiplicadores se acumulan
 */
function getTypeMultiplier(moveType, defenderTypes) {
    if (!moveType || !defenderTypes) return 1;
    
    // Normalizar tipos del defensor (pueden venir como "Fuego, Tierra" o array)
    let types = defenderTypes;
    if (typeof defenderTypes === 'string') {
        types = defenderTypes.split(',').map(t => t.trim()).filter(Boolean);
    } else if (!Array.isArray(defenderTypes)) {
        types = [defenderTypes];
    }
    
    let multiplier = 1;
    
    // Para cada tipo del defensor
    for (let defType of types) {
        const effectiveness = TYPE_EFFECTIVENESS[defType];
        
        if (!effectiveness) continue;
        
        // Verificar inmunidad
        if (effectiveness.immune_to && effectiveness.immune_to.includes(moveType)) {
            return 0; // Si es inmune, el daño es 0
        }
        
        // Verificar debilidad
        if (effectiveness.weak_to && effectiveness.weak_to.includes(moveType)) {
            multiplier *= 2; // x2 por cada tipo débil
        }
        // Verificar resistencia
        else if (effectiveness.resists && effectiveness.resists.includes(moveType)) {
            multiplier *= 0.5; // x0.5 por cada tipo que resiste
        }
    }
    
    return multiplier;
}

// Event listeners para tabs
document.querySelectorAll('.nav-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        switchTab(this.dataset.tab);
    });
});

/**
 * Cambia entre tabs
 */
function switchTab(tabName) {
    // Ocultar todos los tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    // Desactivar todos los botones
    document.querySelectorAll('.nav-tab').forEach(btn => {
        btn.classList.remove('active');
    });

    // Mostrar tab seleccionado
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}

// ==================== Lista y filtro ====================
let fullPokemonList = [];
let sortMode = 'asc';

function loadPokemonList() {
    fetch(`${BASE_PATH}/api/pokemon/list`)
        .then(r => r.json())
        .then(({success, data}) => {
            if (!success) return;
            fullPokemonList = data || [];
            renderPokemonList(fullPokemonList);
        })
        .catch(() => {});
}

function renderPokemonList(list) {
    const ul = document.getElementById('pokemonList');
    ul.innerHTML = '';
    const sorted = [...list].sort((a,b) => {
        const ai = a.id ?? 0, bi = b.id ?? 0;
        return sortMode === 'desc' ? (bi - ai) : (ai - bi);
    });
    sorted.forEach(item => {
        const li = document.createElement('li');
        li.className = 'pokemon-list-item';
        li.innerHTML = `
            <img class="pokemon-thumb" src="${item.image || ''}" alt="${item.name}" onerror="this.style.display='none'">
            <span class="pokemon-id">#${item.id ?? ''}</span>
            <span class="pokemon-name">${item.name}</span>
        `;
        li.addEventListener('click', () => {
            // Buscar el Pokémon y abrirlo en modal
            fetch(`${BASE_PATH}/api/pokemon/search?name=${encodeURIComponent(item.name)}`)
                .then(r => r.text().then(t => {
                    try {
                        return JSON.parse(t);
                    } catch (e) {
                        throw new Error('Respuesta inválida');
                    }
                }))
                .then(data => {
                    if (data.success) {
                        displayPokemonCard(data.data, 'modalBody');
                        openPokemonModal();
                    }
                })
                .catch(() => {});
        });
        ul.appendChild(li);
    });
}

function filterPokemonList(query) {
    const q = (query || '').trim().toLowerCase();
    if (!q) {
        renderPokemonList(fullPokemonList);
        return;
    }
    // Solo mostrar Pokémon que empiezan con el query
    const filtered = fullPokemonList.filter(p => (p.name || '').toLowerCase().startsWith(q));
    renderPokemonList(filtered);
}

// Inicialización para lista y controles
document.addEventListener('DOMContentLoaded', () => {
    loadPokemonList();
    const input = document.getElementById('searchInput');
    if (input) {
        input.addEventListener('input', () => filterPokemonList(input.value));
    }
    const sortSel = document.getElementById('sortMode');
    if (sortSel) {
        sortSel.addEventListener('change', (e) => {
            sortMode = e.target.value || 'asc';
            filterPokemonList(input ? input.value : '');
        });
    }
});

/**
 * Busca un Pokémon
 */
function searchPokemon() {
    const searchInput = document.getElementById('searchInput');
    const pokemonName = searchInput.value.trim();

    if (!pokemonName) {
        showError('Por favor ingresa un nombre de Pokémon', 'searchResults');
        return;
    }

    showLoading('searchResults');

    // Llamar a la API (usar ruta relativa)
    fetch(`${BASE_PATH}/api/pokemon/search?name=${encodeURIComponent(pokemonName)}`)
        .then(response => response.text())
        .then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Respuesta del servidor:', text);
                throw new Error('El servidor devolvió una respuesta inválida');
            }
        })
        .then(data => {
            if (data.success) {
                displayPokemonCard(data.data, 'modalBody');
                openPokemonModal();
            } else {
                showError(data.error || 'Pokémon no encontrado', 'modalBody');
                openPokemonModal();
            }
        })
        .catch(error => {
            showError('Error al buscar: ' + error.message, 'searchResults');
        });
}

/**
 * Compara dos Pokémon
 */
function comparePokemon() {
    const pokemon1Name = document.getElementById('comparePokemon1').value.trim();
    const pokemon2Name = document.getElementById('comparePokemon2').value.trim();

    if (!pokemon1Name || !pokemon2Name) {
        showError('Por favor ingresa nombres de dos Pokémon', 'compareResults');
        return;
    }

    showLoading('compareResults');

    // Primero obtener los datos de ambos Pokémon
    Promise.all([
        fetch(`${BASE_PATH}/api/pokemon/search?name=${encodeURIComponent(pokemon1Name)}`).then(r => r.json()),
        fetch(`${BASE_PATH}/api/pokemon/search?name=${encodeURIComponent(pokemon2Name)}`).then(r => r.json())
    ])
    .then(([data1, data2]) => {
        if (data1.success && data2.success) {
            displayComparison(data1.data, data2.data);
        } else {
            showError('No se encontraron uno o ambos Pokémon', 'compareResults');
        }
    })
    .catch(error => {
        showError('Error al comparar: ' + error.message, 'compareResults');
    });
}

/**
 * Calcula estadísticas personalizadas
 */
function calculateCustomStats() {
    const stats = {
        hp: parseInt(document.getElementById('hp').value) || 0,
        attack: parseInt(document.getElementById('attack').value) || 0,
        defense: parseInt(document.getElementById('defense').value) || 0,
        spAtk: parseInt(document.getElementById('spAtk').value) || 0,
        spDef: parseInt(document.getElementById('spDef').value) || 0,
        speed: parseInt(document.getElementById('speed').value) || 0
    };

    showLoading('customStatsResults');

    // Realizar cálculo local
    const total = Object.values(stats).reduce((a, b) => a + b, 0);
    const average = (total / Object.keys(stats).length).toFixed(2);

    const result = {
        stats: stats,
        total: total,
        average: parseFloat(average),
        max: Math.max(...Object.values(stats)),
        min: Math.min(...Object.values(stats))
    };

    displayCustomStats(result);
}

/**
 * Muestra una tarjeta de Pokémon
 */
function displayPokemonCard(pokemon, containerId) {
    const container = document.getElementById(containerId);
    const maxStat = 255;
    const percent = v => Math.max(0, Math.min(100, Math.round((v / maxStat) * 100)));
    const types = (pokemon.type || '').split(',').map(t => t.trim()).filter(Boolean);
    const typeColors = {
        'Normal': '#A8A77A', 'Fuego': '#EE8130', 'Agua': '#6390F0', 'Eléctrico': '#F7D02C',
        'Planta': '#7AC74C', 'Hielo': '#96D9D6', 'Lucha': '#C22E28', 'Veneno': '#A33EA1', 'Tierra': '#E2BF65',
        'Volador': '#A98FF3', 'Psíquico': '#F95587', 'Bicho': '#A6B91A', 'Roca': '#B6A136', 'Fantasma': '#735797',
        'Dragón': '#6F35FC', 'Siniestro': '#705746', 'Acero': '#B7B7CE', 'Hada': '#D685AD'
    };
    const badge = t => `<span class="badge" style="background:${typeColors[t]||'#888'}">${t}</span>`;

    const html = `
        <div class="infobox">
            <div class="infobox-header">
                <div>
                    <div class="infobox-title">${pokemon.name}</div>
                    <div class="infobox-subtitle">#${pokemon.id ?? ''}</div>
                </div>
            </div>
            ${pokemon.image ? `
            <div class="infobox-image">
                <img src="${pokemon.image}" alt="${pokemon.name}">
            </div>` : ''}
            <div class="badges">${types.map(badge).join('')}</div>
            <div class="stats">
                ${[
                    ['HP', pokemon.hp],
                    ['Ataque', pokemon.attack],
                    ['Defensa', pokemon.defense],
                    ['At. esp.', pokemon.spAtk],
                    ['Def. esp.', pokemon.spDef],
                    ['Velocidad', pokemon.speed]
                ].map(([label, val]) => `
                <div class="stat-row">
                    <div>${label}</div>
                    <div class="bar-bg"><div class="bar-fill" style="width:${percent(val)}%"></div></div>
                    <div><strong>${val}</strong></div>
                </div>
                `).join('')}
                <div class="stat-row stat-total">
                    <div><strong>Total</strong></div>
                    <div></div>
                    <div><strong>${(pokemon.hp || 0) + (pokemon.attack || 0) + (pokemon.defense || 0) + (pokemon.spAtk || 0) + (pokemon.spDef || 0) + (pokemon.speed || 0)}</strong></div>
                </div>
            </div>
        </div>
    `;

    container.innerHTML = html;
}

/**
 * Muestra la comparación de dos Pokémon
 */
function displayComparison(pokemon1, pokemon2) {
    const container = document.getElementById('compareResults');
    
    const stats = ['hp', 'attack', 'defense', 'spAtk', 'spDef', 'speed'];
    const total1 = pokemon1.totalStats;
    const total2 = pokemon2.totalStats;
    
    let statsTableHtml = `
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Estadística</th>
                    <th>${pokemon1.name}</th>
                    <th>${pokemon2.name}</th>
                    <th>Ganador</th>
                </tr>
            </thead>
            <tbody>
    `;

    const statLabels = {
        'hp': 'HP',
        'attack': 'Ataque',
        'defense': 'Defensa',
        'spAtk': 'Ataque Especial',
        'spDef': 'Defensa Especial',
        'speed': 'Velocidad'
    };

    stats.forEach(stat => {
        const val1 = pokemon1[stat];
        const val2 = pokemon2[stat];
        let winner = '🤝 Empate';
        let class1 = '';
        let class2 = '';

        if (val1 > val2) {
            winner = `✓ ${pokemon1.name}`;
            class1 = 'winner';
            class2 = 'loser';
        } else if (val2 > val1) {
            winner = `✓ ${pokemon2.name}`;
            class1 = 'loser';
            class2 = 'winner';
        }

        statsTableHtml += `
            <tr>
                <td><strong>${statLabels[stat]}</strong></td>
                <td class="${class1}">${val1}</td>
                <td class="${class2}">${val2}</td>
                <td>${winner}</td>
            </tr>
        `;
    });

    const totalWinner = total1 > total2 ? `✓ ${pokemon1.name}` : (total2 > total1 ? `✓ ${pokemon2.name}` : '🤝 Empate');
    const totalClass1 = total1 > total2 ? 'winner' : (total2 > total1 ? 'loser' : '');
    const totalClass2 = total2 > total1 ? 'winner' : (total1 > total2 ? 'loser' : '');

    statsTableHtml += `
            <tr style="background: #f0f0f0; font-weight: bold;">
                <td>Total</td>
                <td class="${totalClass1}">${total1}</td>
                <td class="${totalClass2}">${total2}</td>
                <td>${totalWinner}</td>
            </tr>
        </tbody>
        </table>
    `;

    const html = `
        <div class="comparison-container">
            <div class="pokemon-card">
                <h3>${pokemon1.name} (#${pokemon1.id})</h3>
                ${pokemon1.image ? `
                    <div class="pokemon-image">
                        <img src="${pokemon1.image}" alt="${pokemon1.name}" onerror="this.style.display='none'">
                    </div>
                ` : ''}
                <div class="pokemon-info">
                    <div class="info-group">
                        <label>Tipo:</label>
                        <value>${pokemon1.type}</value>
                    </div>
                </div>
            </div>
            <div class="pokemon-card">
                <h3>${pokemon2.name} (#${pokemon2.id})</h3>
                ${pokemon2.image ? `
                    <div class="pokemon-image">
                        <img src="${pokemon2.image}" alt="${pokemon2.name}" onerror="this.style.display='none'">
                    </div>
                ` : ''}
                <div class="pokemon-info">
                    <div class="info-group">
                        <label>Tipo:</label>
                        <value>${pokemon2.type}</value>
                    </div>
                </div>
            </div>
        </div>
        ${statsTableHtml}
    `;

    container.innerHTML = html;
}

/**
 * Muestra estadísticas personalizadas
 */
function displayCustomStats(result) {
    const container = document.getElementById('customStatsResults');
    
    const html = `
        <div class="pokemon-card">
            <h3>Resumen de Estadísticas</h3>
            <div class="pokemon-info">
                <div class="info-group">
                    <label>Total de Estadísticas:</label>
                    <value>${result.total}</value>
                </div>
                <div class="info-group">
                    <label>Promedio:</label>
                    <value>${result.average}</value>
                </div>
                <div class="info-group">
                    <label>Máximo:</label>
                    <value>${result.max}</value>
                </div>
                <div class="info-group">
                    <label>Mínimo:</label>
                    <value>${result.min}</value>
                </div>
            </div>
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Estadística</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>HP</td>
                        <td><strong>${result.stats.hp}</strong></td>
                    </tr>
                    <tr>
                        <td>Ataque</td>
                        <td><strong>${result.stats.attack}</strong></td>
                    </tr>
                    <tr>
                        <td>Defensa</td>
                        <td><strong>${result.stats.defense}</strong></td>
                    </tr>
                    <tr>
                        <td>Ataque Especial</td>
                        <td><strong>${result.stats.spAtk}</strong></td>
                    </tr>
                    <tr>
                        <td>Defensa Especial</td>
                        <td><strong>${result.stats.spDef}</strong></td>
                    </tr>
                    <tr>
                        <td>Velocidad</td>
                        <td><strong>${result.stats.speed}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;

    container.innerHTML = html;
}

/**
 * Muestra un mensaje de carga
 */
function showLoading(containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = '<div class="loading"><div class="spinner"></div><p>Cargando...</p></div>';
}

/**
 * Muestra un mensaje de error
 */
function showError(message, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = `<div class="error-message">❌ ${message}</div>`;
}

/**
 * Muestra un mensaje de éxito
 */
function showSuccess(message, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = `<div class="success-message">✓ ${message}</div>`;
}

// Permitir búsqueda con Enter
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchPokemon();
        }
    });

    document.getElementById('comparePokemon1').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            comparePokemon();
        }
    });

    document.getElementById('comparePokemon2').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            comparePokemon();
        }
    });

    // Cerrar modal al hacer clic en el overlay
    const modal = document.getElementById('pokemonModal');
    if (modal) {
        const overlay = modal.querySelector('.modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', closePokemonModal);
        }
    }
});

/**
 * Abre el modal del Pokémon
 */
function openPokemonModal() {
    const modal = document.getElementById('pokemonModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Cierra el modal del Pokémon
 */
function closePokemonModal() {
    const modal = document.getElementById('pokemonModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}
// ==================== Autocompletado ====================
let debounceTimers = {};

/**
 * Obtiene sugerencias de Pokémon
 */
function getSuggestions(query, inputId) {
    const suggestionsList = document.getElementById(`suggestions${inputId === 'comparePokemon1' ? '1' : '2'}`);
    
    if (!query || query.length < 1) {
        suggestionsList.classList.remove('active');
        return;
    }

    const q = query.toLowerCase().trim();
    
    // Cancelar petición anterior si existe
    if (debounceTimers[inputId]) {
        clearTimeout(debounceTimers[inputId]);
    }

    // Debounce: esperar 300ms antes de filtrar
    debounceTimers[inputId] = setTimeout(() => {
        // Filtrar localmente de la lista completa
        const filtered = fullPokemonList.filter(p => 
            (p.name || '').toLowerCase().startsWith(q)
        );
        
        if (filtered.length > 0) {
            displaySuggestions(filtered, inputId);
        } else {
            suggestionsList.classList.remove('active');
        }
    }, 300);
}

/**
 * Muestra las sugerencias en la lista
 */
function displaySuggestions(suggestions, inputId) {
    const suggestionsList = document.getElementById(`suggestions${inputId === 'comparePokemon1' ? '1' : '2'}`);
    suggestionsList.innerHTML = '';

    // Ordenar alfabéticamente
    suggestions.sort((a, b) => (a.name || '').localeCompare((b.name || ''), 'es'));

    suggestions.forEach((pokemon, index) => {
        const li = document.createElement('li');
        li.className = 'suggestion-item';
        li.setAttribute('data-pokemon-name', pokemon.name);
        li.setAttribute('data-input-id', inputId);
        li.innerHTML = `
            ${pokemon.image ? `<img src="${pokemon.image}" alt="${pokemon.name}" onerror="this.style.display='none'">` : ''}
            <div class="pokemon-info">
                <div class="pokemon-name">${pokemon.name}</div>
                <div class="pokemon-id">#${pokemon.id || ''}</div>
            </div>
        `;
        suggestionsList.appendChild(li);
    });

    suggestionsList.classList.add('active');
}

/**
 * Selecciona un Pokémon de las sugerencias
 */
function selectPokemonSuggestion(pokemonName, inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        input.value = pokemonName;
        input.focus();
    }
    
    const suggestionsList = document.getElementById(`suggestions${inputId === 'comparePokemon1' ? '1' : '2'}`);
    if (suggestionsList) {
        suggestionsList.classList.remove('active');
    }
}

/**
 * Inicializa los event listeners del autocompletado
 */
function initAutocompletado() {
    const input1 = document.getElementById('comparePokemon1');
    const input2 = document.getElementById('comparePokemon2');
    const suggestions1 = document.getElementById('suggestions1');
    const suggestions2 = document.getElementById('suggestions2');

    if (input1) {
        input1.addEventListener('input', (e) => getSuggestions(e.target.value, 'comparePokemon1'));
        input1.addEventListener('blur', () => {
            setTimeout(() => {
                if (suggestions1) suggestions1.classList.remove('active');
            }, 200);
        });
    }

    if (input2) {
        input2.addEventListener('input', (e) => getSuggestions(e.target.value, 'comparePokemon2'));
        input2.addEventListener('blur', () => {
            setTimeout(() => {
                if (suggestions2) suggestions2.classList.remove('active');
            }, 200);
        });
    }

    // Usar mousedown en lugar de click para evitar conflictos con blur
    document.addEventListener('mousedown', (e) => {
        const suggestionItem = e.target.closest('.suggestion-item');
        if (suggestionItem) {
            e.preventDefault();
            const pokemonName = suggestionItem.getAttribute('data-pokemon-name');
            const inputId = suggestionItem.getAttribute('data-input-id');
            selectPokemonSuggestion(pokemonName, inputId);
            return;
        }
    });

    // Cerrar sugerencias al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.autocomplete-wrapper')) {
            if (suggestions1) suggestions1.classList.remove('active');
            if (suggestions2) suggestions2.classList.remove('active');
        }
    });
    
    // Cerrar selector modal al hacer clic en overlay
    const selectorModal = document.getElementById('selectorModal');
    if (selectorModal) {
        const overlay = selectorModal.querySelector('.modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', closeSelectorModal);
        }
    }
    
    // Inicializar autocompletado de movimientos
    loadMoves();
    initMoveAutocomplete();
}

// ==================== Autocompletado de Movimientos ====================
/**
 * Carga la lista de movimientos desde el archivo JSON
 */
function loadMoves() {
    fetch(`${BASE_PATH}/src/data/moves.json`)
        .then(r => r.json())
        .then(data => {
            allMoves = data || [];
            console.log('Movimientos cargados:', allMoves.length);
        })
        .catch(err => {
            console.error('Error cargando movimientos:', err);
            allMoves = [];
        });
}

/**
 * Inicializa el autocompletado para el input de movimientos
 */
function initMoveAutocomplete() {
    const moveInput = document.getElementById('moveNameDmg');
    if (!moveInput) return;
    
    // Crear contenedor de sugerencias si no existe
    let moveSuggestions = document.getElementById('moveSuggestions');
    if (!moveSuggestions) {
        moveSuggestions = document.createElement('ul');
        moveSuggestions.id = 'moveSuggestions';
        moveSuggestions.className = 'autocomplete-list';
        moveInput.parentElement.style.position = 'relative';
        moveInput.parentElement.appendChild(moveSuggestions);
    }
    
    // Event listener para input
    moveInput.addEventListener('input', (e) => {
        const query = e.target.value.trim().toLowerCase();
        
        if (query.length < 2) {
            moveSuggestions.classList.remove('active');
            return;
        }
        
        // Filtrar movimientos
        const matches = allMoves.filter(move => 
            move.name.toLowerCase().includes(query)
        ).slice(0, 10); // Máximo 10 sugerencias
        
        if (matches.length === 0) {
            moveSuggestions.classList.remove('active');
            return;
        }
        
        // Renderizar sugerencias
        moveSuggestions.innerHTML = matches.map(move => `
            <li class="suggestion-item" data-move-name="${move.name}">
                <strong>${move.name}</strong>
                <span style="font-size: 0.85em; color: #666;">
                    ${move.type} | ${move.category === 'physical' ? 'Físico' : 'Especial'} | Poder: ${move.power}
                </span>
            </li>
        `).join('');
        
        moveSuggestions.classList.add('active');
    });
    
    // Cerrar al perder foco
    moveInput.addEventListener('blur', () => {
        setTimeout(() => {
            moveSuggestions.classList.remove('active');
        }, 200);
    });
    
    // Seleccionar movimiento al hacer clic
    document.addEventListener('mousedown', (e) => {
        const suggestionItem = e.target.closest('#moveSuggestions .suggestion-item');
        if (suggestionItem) {
            e.preventDefault();
            const moveName = suggestionItem.getAttribute('data-move-name');
            selectMove(moveName);
        }
    });
}

/**
 * Mapeo de tipos en inglés a español
 */
const typeTranslations = {
    'Normal': 'Normal',
    'Fire': 'Fuego',
    'Water': 'Agua',
    'Electric': 'Eléctrico',
    'Grass': 'Planta',
    'Ice': 'Hielo',
    'Fighting': 'Lucha',
    'Poison': 'Veneno',
    'Ground': 'Tierra',
    'Flying': 'Volador',
    'Psychic': 'Psíquico',
    'Bug': 'Bicho',
    'Rock': 'Roca',
    'Ghost': 'Fantasma',
    'Dragon': 'Dragón',
    'Dark': 'Siniestro',
    'Steel': 'Acero',
    'Fairy': 'Hada'
};

/**
 * Selecciona un movimiento y rellena los campos automáticamente
 */
function selectMove(moveName) {
    const move = allMoves.find(m => m.name === moveName);
    if (!move) return;
    
    // Rellenar campos
    document.getElementById('moveNameDmg').value = move.name;
    document.getElementById('movePowerDmg').value = move.power;
    
    // Traducir tipo del movimiento de inglés a español
    const typeInSpanish = typeTranslations[move.type] || move.type;
    document.getElementById('moveTypeDmg').value = typeInSpanish;
    
    document.getElementById('movePhysicalOrSpecial').value = move.category === 'physical' ? 'physical' : 'special';
    
    // Cerrar sugerencias
    const moveSuggestions = document.getElementById('moveSuggestions');
    if (moveSuggestions) {
        moveSuggestions.classList.remove('active');
    }
}

// ==================== Calculadora de Daño ====================
let selectedAttacker = null;
let selectedDefender = null;

/**
 * Abre el selector de Pokémon atacante
 */
function openAttackerSelector() {
    document.getElementById('selectorTitle').textContent = 'Seleccionar Pokémon Atacante';
    document.getElementById('selectorSearch').value = '';
    document.getElementById('selectorList').innerHTML = '';
    
    // Mostrar modal
    const modal = document.getElementById('selectorModal');
    modal.classList.add('active');
    
    // Cargar lista de Pokémon base
    switchSelectorSource('base');
}

/**
 * Abre el selector de Pokémon defensor
 */
function openDefenderSelector() {
    document.getElementById('selectorTitle').textContent = 'Seleccionar Pokémon Defensor';
    document.getElementById('selectorSearch').value = '';
    document.getElementById('selectorList').innerHTML = '';
    
    // Mostrar modal
    const modal = document.getElementById('selectorModal');
    modal.classList.add('active');
    
    // Cargar lista de Pokémon base
    switchSelectorSource('base');
}

/**
 * Cambia la fuente del selector (base o equipo)
 */
function switchSelectorSource(source, event) {
    if (event) event.preventDefault();
    
    const search = document.getElementById('selectorSearch');
    const list = document.getElementById('selectorList');
    
    // Actualizar tabs activos
    document.querySelectorAll('.selector-tab').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`[data-source="${source}"]`).classList.add('active');
    
    if (source === 'base') {
        // Cargar Pokémon base
        fetch(`${BASE_PATH}/api/pokemon/list`)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.data) {
                    renderSelectorList(data.data);
                }
            });
    } else if (source === 'team') {
        // Cargar equipo actual
        fetch(`${BASE_PATH}/api/team/all`)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.data && data.data[0]) {
                    const members = data.data[0].members || [];
                    renderSelectorList(members);
                }
            });
    }
    
    if (search) {
        search.value = '';
        search.oninput = (e) => filterSelectorList(e.target.value, source);
    }
}

/**
 * Renderiza la lista del selector
 */
function renderSelectorList(items) {
    const list = document.getElementById('selectorList');
    list.innerHTML = '';
    
    items.forEach(item => {
        const div = document.createElement('div');
        div.className = 'selector-item';
        div.innerHTML = `
            ${item.image ? `<img src="${item.image}" alt="${item.name || item.nickname}">` : ''}
            <div class="pokemon-info">
                <div class="pokemon-name">${item.name || item.nickname}</div>
                <div class="pokemon-id">#${item.id || ''}</div>
            </div>
        `;
        
        div.addEventListener('click', () => {
            selectPokemonForDamageCalc(item);
        });
        
        list.appendChild(div);
    });
}

/**
 * Filtra la lista del selector
 */
function filterSelectorList(query, source) {
    const q = (query || '').toLowerCase();
    const items = document.querySelectorAll('.selector-item');
    items.forEach(item => {
        const name = (item.querySelector('.pokemon-name').textContent || '').toLowerCase();
        item.style.display = name.includes(q) ? '' : 'none';
    });
}

/**
 * Cierra el selector modal
 */
function closeSelectorModal() {
    const modal = document.getElementById('selectorModal');
    modal.classList.remove('active');
}

/**
 * Selecciona un Pokémon para la calculadora
 */
function selectPokemonForDamageCalc(pokemon) {
    const title = document.getElementById('selectorTitle').textContent;
    
    // Si solo tenemos id, name e image (de la lista), cargar datos completos
    if (!pokemon.hp && !pokemon.attack) {
        // Cargar datos completos del Pokémon
        fetch(`${BASE_PATH}/api/pokemon/search?name=${encodeURIComponent(pokemon.name)}`)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.data) {
                    const fullPokemon = data.data;
                    if (title.includes('Atacante')) {
                        selectedAttacker = fullPokemon;
                        displayAttackerInfo(fullPokemon);
                    } else if (title.includes('Defensor')) {
                        selectedDefender = fullPokemon;
                        displayDefenderInfo(fullPokemon);
                    }
                    closeSelectorModal();
                }
            })
            .catch(err => {
                console.error('Error cargando Pokémon:', err);
                alert('Error al cargar los datos del Pokémon');
            });
    } else {
        // Ya tenemos todos los datos
        if (title.includes('Atacante')) {
            selectedAttacker = pokemon;
            displayAttackerInfo(pokemon);
        } else if (title.includes('Defensor')) {
            selectedDefender = pokemon;
            displayDefenderInfo(pokemon);
        }
        closeSelectorModal();
    }
}

/**
 * Muestra información del atacante
 */
function displayAttackerInfo(pokemon) {
    const box = document.getElementById('attackerInfo');
    const attack = pokemon.attack || 0;
    const spAtk = pokemon.spAtk || 0;
    box.innerHTML = `
        <div style="text-align: center; padding: 12px; background: #fff; border-radius: 8px; border: 1px solid #e5e7eb;">
            ${pokemon.image ? `<img src="${pokemon.image}" alt="${pokemon.name}" style="max-width: 100px; max-height: 100px; margin-bottom: 8px;">` : ''}
            <div><strong>${pokemon.name || pokemon.nickname}</strong></div>
            ${pokemon.type ? `<div style="font-size: 0.85em; color: #6b7280;">Tipo: ${pokemon.type}</div>` : ''}
            <div style="margin-top: 8px; font-size: 0.9em;">
                <div>⚔️ AT: <strong>${attack}</strong></div>
                <div>✨ AT.ESP: <strong>${spAtk}</strong></div>
            </div>
        </div>
    `;
}

/**
 * Muestra información del defensor
 */
function displayDefenderInfo(pokemon) {
    const box = document.getElementById('defenderInfo');
    const types = (pokemon.type || '').split(',').map(t => t.trim()).filter(Boolean);
    const defense = pokemon.defense || 0;
    const spDef = pokemon.spDef || 0;
    const hp = pokemon.hp || 0;
    box.innerHTML = `
        <div style="text-align: center; padding: 12px; background: #fff; border-radius: 8px; border: 1px solid #e5e7eb;">
            ${pokemon.image ? `<img src="${pokemon.image}" alt="${pokemon.name}" style="max-width: 100px; max-height: 100px; margin-bottom: 8px;">` : ''}
            <div><strong>${pokemon.name || pokemon.nickname}</strong></div>
            <div style="font-size: 0.85em; color: #6b7280;">Tipos: ${types.join(', ') || 'Desconocido'}</div>
            <div style="margin-top: 8px; font-size: 0.9em;">
                <div>❤️ HP: <strong style="color: #27ae60;">${hp}</strong></div>
                <div>🛡️ DEF: <strong>${defense}</strong></div>
                <div>✨ DEF.ESP: <strong>${spDef}</strong></div>
            </div>
        </div>
    `;
}

/**
 * Calcula el daño entre dos Pokémon usando la fórmula oficial + efectividad de tipos
 */
function calculateDamage() {
    if (!selectedAttacker || !selectedDefender) {
        alert('Selecciona un Pokémon atacante y defensor');
        return;
    }
    
    const moveName = document.getElementById('moveNameDmg').value || 'Movimiento';
    const movePower = parseInt(document.getElementById('movePowerDmg').value) || 0;
    const moveType = document.getElementById('moveTypeDmg').value || 'Normal';
    const moveCategory = document.getElementById('movePhysicalOrSpecial').value || '';
    
    if (movePower <= 0) {
        alert('Ingresa un poder de movimiento válido (mayor a 0)');
        return;
    }
    
    if (!moveCategory) {
        alert('Selecciona si el movimiento es Físico o Especial');
        return;
    }
    
    const level = 50; // Nivel estándar para cálculos
    const attackerHP = selectedAttacker.hp || 100;
    const attackerAtk = selectedAttacker.attack || 100;
    const attackerSpAtk = selectedAttacker.spAtk || 100;
    const defenderDef = selectedDefender.defense || 100;
    const defenderSpDef = selectedDefender.spDef || 100;
    const defenderHP = selectedDefender.hp || 100;
    
    // Usar la opción seleccionada para determinar si es físico o especial
    const isSpecialMove = moveCategory === 'special';
    
    const attack = isSpecialMove ? attackerSpAtk : attackerAtk;
    const defense = isSpecialMove ? defenderSpDef : defenderDef;
    
    // Fórmula oficial de Pokémon (Gen III en adelante):
    // damage = ((((2 * level / 5 + 2) * power * attack / defense) / 50) + 2) * modifiers
    
    let baseDamage = ((((2 * level / 5 + 2) * movePower * attack / defense) / 50) + 2);
    
    // ==================== CÁLCULO DE STAB (Same Type Attack Bonus) ====================
    // Si el tipo del movimiento coincide con alguno de los tipos del atacante, +50% de daño
    const attackerTypes = (selectedAttacker.type || '').split(',').map(t => t.trim()).filter(Boolean);
    const hasSTAB = attackerTypes.includes(moveType);
    
    if (hasSTAB) {
        baseDamage = baseDamage * 1.5;
    }
    
    // ==================== CÁLCULO DE EFECTIVIDAD DE TIPOS ====================
    const defenderTypes = selectedDefender.type || 'Normal';
    const typeMultiplier = getTypeMultiplier(moveType, defenderTypes);
    
    // Si la efectividad es 0, es inmune
    if (typeMultiplier === 0) {
        const resultsDiv = document.getElementById('damageResults');
        resultsDiv.innerHTML = `
            <div class="comparison-container" style="max-width: 100%; padding: 20px; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border-radius: 12px; margin-top: 20px; border: 2px solid #6b7280;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div style="text-align: center;">
                        <strong>${selectedAttacker.name || 'Atacante'}</strong>
                        <div style="font-size: 0.9em; color: #6b7280; margin-top: 4px;">
                            Tipo: ${selectedAttacker.type || 'Desconocido'}
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <strong>${selectedDefender.name || 'Defensor'}</strong>
                        <div style="font-size: 0.9em; color: #6b7280; margin-top: 4px;">
                            Tipo: ${defenderTypes}
                        </div>
                        <div style="font-size: 0.9em; color: #6b7280; margin-top: 4px;">
                            <strong>HP: ${defenderHP}</strong>
                        </div>
                    </div>
                </div>
                
                <h3 style="margin: 12px 0; text-align: center;">${moveName}</h3>
                <p style="text-align: center; margin: 8px 0; color: #666;">
                    Tipo: <strong>${moveType}</strong> | Poder: <strong>${movePower}</strong> | Tipo de movimiento: <strong>${isSpecialMove ? 'Especial' : 'Físico'}</strong>
                </p>
                <hr style="margin: 12px 0; border: none; border-top: 1px solid #e5e7eb;">
                
                <div style="background: #fee; border: 1px solid #f99; border-radius: 8px; padding: 12px; text-align: center;">
                    <p style="margin: 0; font-size: 1.2em; color: #c33;"><strong>¡${selectedDefender.name || 'El Pokémon defensor'} es INMUNE!</strong></p>
                    <p style="margin: 8px 0 0 0; font-size: 0.95em; color: #666;">
                        El tipo ${moveType} no hace daño al tipo ${defenderTypes}
                    </p>
                </div>
            </div>
        `;
        return;
    }
    
    // Aplicar multiplicador de tipo al daño base
    const baseDamageWithType = baseDamage * typeMultiplier;
    
    // Aplicar variación (85% - 100%)
    const minDamage = Math.floor(baseDamageWithType * 0.85);
    const maxDamage = Math.floor(baseDamageWithType * 1.0);
    
    // Calcular porcentaje de HP
    const percentMin = Math.round((minDamage / defenderHP) * 100);
    const percentMax = Math.round((maxDamage / defenderHP) * 100);
    
    // Calcular koces necesarios (ataques para derrotar)
    const koces = Math.ceil(defenderHP / maxDamage);
    
    // Generar descripción de efectividad con estilo mejorado
    let effectivenessHTML = '';
    let effectivenessColor = '#666';
    let effectivenessBackground = '#f9f9f9';
    let effectivenessIcon = '';
    
    if (typeMultiplier >= 4) {
        effectivenessHTML = `¡¡¡EXTREMADAMENTE EFECTIVO!!! (x${Math.round(typeMultiplier * 10) / 10})`;
        effectivenessColor = '#fff';
        effectivenessBackground = 'linear-gradient(135deg, #f39c12 0%, #e74c3c 100%)';
        effectivenessIcon = '⚡⚡⚡';
    } else if (typeMultiplier > 1) {
        const mult = Math.round(typeMultiplier * 10) / 10;
        effectivenessHTML = `¡MUY EFECTIVO! (x${mult})`;
        effectivenessColor = '#fff';
        effectivenessBackground = 'linear-gradient(135deg, #27ae60 0%, #229954 100%)';
        effectivenessIcon = '⚡ ';
    } else if (typeMultiplier < 1) {
        const mult = Math.round(typeMultiplier * 10) / 10;
        effectivenessHTML = `Poco efectivo (x${mult})`;
        effectivenessColor = '#fff';
        effectivenessBackground = 'linear-gradient(135deg, #e67e22 0%, #d35400 100%)';
        effectivenessIcon = '↓ ';
    } else {
        effectivenessHTML = `Efectividad normal (x1)`;
        effectivenessColor = '#333';
        effectivenessBackground = '#f9f9f9';
    }
    
    const resultsDiv = document.getElementById('damageResults');
    resultsDiv.innerHTML = `
        <div class="comparison-container" style="max-width: 100%; padding: 20px; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border-radius: 12px; margin-top: 20px; border: 2px solid #e5e7eb;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div style="text-align: center; padding: 12px; background: #fff; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <strong style="font-size: 1.05em;">${selectedAttacker.name || 'Atacante'}</strong>
                    <div style="font-size: 0.85em; color: #6b7280; margin-top: 4px;">
                        Tipo: <strong>${attackerTypes.join(', ') || 'Desconocido'}</strong>
                    </div>
                    <div style="font-size: 0.85em; color: #6b7280; margin-top: 8px;">
                        ${isSpecialMove ? 'Ataque Esp:' : 'Ataque:'} <strong style="font-size: 1.1em; color: #e74c3c;">${attack}</strong>
                    </div>
                </div>
                <div style="text-align: center; padding: 12px; background: #fff; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <strong style="font-size: 1.05em;">${selectedDefender.name || 'Defensor'}</strong>
                    <div style="font-size: 0.85em; color: #6b7280; margin-top: 4px;">
                        Tipo: <strong>${defenderTypes}</strong>
                    </div>
                    <div style="font-size: 0.85em; color: #6b7280; margin-top: 4px;">
                        ${isSpecialMove ? 'Defensa Esp:' : 'Defensa:'} <strong style="font-size: 1.1em; color: #3498db;">${defense}</strong>
                    </div>
                    <div style="font-size: 0.85em; color: #6b7280; margin-top: 4px;">
                        <strong style="font-size: 1.1em; color: #27ae60;">❤️ HP: ${defenderHP}</strong>
                    </div>
                </div>
            </div>
            
            <div style="background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #e5e7eb; margin-bottom: 12px;">
                <h3 style="margin: 0 0 8px 0; text-align: center; font-size: 1.1em;">${moveName}</h3>
                <p style="text-align: center; margin: 8px 0; color: #666; font-size: 0.9em;">
                    Tipo: <strong>${moveType}</strong> | Poder: <strong>${movePower}</strong> | Categoría: <strong>${isSpecialMove ? 'Especial' : 'Físico'}</strong>
                </p>
            </div>
            
            ${hasSTAB ? `
            <div style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); border: 2px solid #6c3483; border-radius: 8px; padding: 12px; text-align: center; margin-bottom: 12px;">
                <p style="margin: 0; font-size: 1.1em; color: #fff; font-weight: bold;">
                    ✨ STAB ACTIVO ✨ (x1.5 de daño)
                </p>
                <p style="margin: 4px 0 0 0; font-size: 0.85em; color: #f3e5f5;">
                    El tipo del movimiento coincide con el tipo del atacante
                </p>
            </div>
            ` : ''}
            
            <div style="background: ${effectivenessBackground}; border: 2px solid #333; border-radius: 8px; padding: 14px; text-align: center; margin-bottom: 12px;">
                <p style="margin: 0; font-size: 1.2em; color: ${effectivenessColor}; font-weight: bold;">
                    ${effectivenessIcon} ${effectivenessHTML}
                </p>
            </div>
            
            <div style="background: linear-gradient(135deg, #fee8e8 0%, #fef5f5 100%); border: 2px solid #e74c3c; border-radius: 8px; padding: 16px; text-align: center; margin-bottom: 12px;">
                <p style="margin: 0 0 12px 0; font-size: 0.9em; color: #666;"><strong>${selectedDefender.name || 'Defensor'}</strong> recibe:</p>
                <h2 style="color: #e74c3c; margin: 0 0 12px 0; font-size: 2.2em;">
                    ${minDamage} - ${maxDamage} de daño
                </h2>
                <p style="font-size: 1.15em; margin: 0;">
                    <strong style="color: #e74c3c;">${percentMin}% - ${percentMax}%</strong> <span style="color: #666;">de su HP total</span>
                </p>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #e5e7eb;">
                <div style="text-align: center; padding: 8px;">
                    <p style="margin: 0; font-size: 0.85em; color: #6b7280;">Ataques para K.O.</p>
                    <p style="margin: 4px 0 0 0; font-size: 1.3em; font-weight: bold; color: #3498db;">${koces}</p>
                </div>
                <div style="text-align: center; padding: 8px;">
                    <p style="margin: 0; font-size: 0.85em; color: #6b7280;">HP Defensor</p>
                    <p style="margin: 4px 0 0 0; font-size: 1.3em; font-weight: bold; color: #27ae60;">${defenderHP}</p>
                </div>
            </div>
        </div>
    `;
}

// Inicializar autocompletado cuando cargue el DOM
document.addEventListener('DOMContentLoaded', () => {
    initAutocompletado();
});