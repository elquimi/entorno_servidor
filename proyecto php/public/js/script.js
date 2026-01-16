// Variables globales
let currentPokemon1 = null;
let currentPokemon2 = null;

// Definir ruta base relativa (con espacio codificado como %20)
const BASE_PATH = '/temp/proyecto%20php';

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
            document.getElementById('searchInput').value = item.name;
            searchPokemon();
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
    const filtered = fullPokemonList.filter(p => (p.name || '').toLowerCase().includes(q));
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
                displayPokemonCard(data.data, 'searchResults');
            } else {
                showError(data.error || 'Pokémon no encontrado', 'searchResults');
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
        normal: '#A8A77A', fire: '#EE8130', water: '#6390F0', electric: '#F7D02C',
        grass: '#7AC74C', ice: '#96D9D6', fighting: '#C22E28', poison: '#A33EA1', ground: '#E2BF65',
        flying: '#A98FF3', psychic: '#F95587', bug: '#A6B91A', rock: '#B6A136', ghost: '#735797',
        dragon: '#6F35FC', dark: '#705746', steel: '#B7B7CE', fairy: '#D685AD'
    };
    const badge = t => `<span class="badge" style="background:${typeColors[t.toLowerCase()]||'#888'}">${t}</span>`;

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
});
