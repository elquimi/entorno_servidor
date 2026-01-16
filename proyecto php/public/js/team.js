// ==================== Team Management ====================

let currentTeam = null;
let allMoves = [];
let currentPokemonType = 'existing'; // existing o custom
let selectedBasePokemon = null;

/**
 * Carga los movimientos disponibles
 */
function loadAvailableMoves() {
    fetch(`${BASE_PATH}/api/team/moves`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data) {
                allMoves = data.data;
            }
        })
        .catch(err => console.error('Error loading moves:', err));
}

/**
 * Abre el modal para crear un Pokémon
 */
function openCreatePokemonModal() {
    const modal = document.getElementById('createPokemonModal');
    if (modal) {
        modal.classList.add('active');
        currentPokemonType = 'existing';
        switchPokemonType('existing');
        resetForms();
        loadAvailableMoves();
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Cierra el modal para crear Pokémon
 */
function closeCreatePokemonModal() {
    const modal = document.getElementById('createPokemonModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
        resetForms();
    }
}

/**
 * Cambia entre tipo de Pokémon (existente o personalizado)
 */
function switchPokemonType(type, event) {
    if (event) {
        event.preventDefault();
    }
    
    currentPokemonType = type;
    
    // Actualizar botones
    document.querySelectorAll('.type-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event?.target?.classList.add('active');
    document.querySelector(`[data-type="${type}"]`)?.classList.add('active');
    
    // Mostrar/ocultar formularios
    document.getElementById('existingPokemonForm')?.classList.toggle('active', type === 'existing');
    document.getElementById('customPokemonForm')?.classList.toggle('active', type === 'custom');
    
    if (type === 'existing') {
        resetExistingForm();
    }
}

/**
 * Reinicia los formularios
 */
function resetForms() {
    resetExistingForm();
    resetCustomForm();
}

/**
 * Reinicia formulario existente
 */
function resetExistingForm() {
    document.getElementById('existingPokeName').value = '';
    document.getElementById('existingPokemonInfo').innerHTML = '';
    document.getElementById('existingAbilitiesSection').style.display = 'none';
    document.getElementById('existingMovesSection').style.display = 'none';
    selectedBasePokemon = null;
}

/**
 * Reinicia formulario personalizado
 */
function resetCustomForm() {
    document.getElementById('customNickname').value = '';
    document.getElementById('customPokeType').value = '';
    document.getElementById('customHP').value = '100';
    document.getElementById('customAtk').value = '100';
    document.getElementById('customDef').value = '100';
    document.getElementById('customSpAtk').value = '100';
    document.getElementById('customSpDef').value = '100';
    document.getElementById('customSpeed').value = '100';
    document.getElementById('customAbility').value = '';
    document.getElementById('customMoves').value = '';
}

/**
 * Obtiene sugerencias de Pokémon existentes para agregar al equipo
 */
function getSuggestionsForTeam(query) {
    if (!query || query.length < 1) return;
    
    fetch(`${BASE_PATH}/api/pokemon/search-partial?q=${encodeURIComponent(query)}`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data) {
                displayTeamSuggestions(data.data);
            }
        })
        .catch(err => console.error(err));
}

/**
 * Muestra sugerencias de Pokémon
 */
function displayTeamSuggestions(suggestions) {
    const list = document.getElementById('existingSuggestions');
    list.innerHTML = '';
    
    suggestions.forEach(pokemon => {
        const li = document.createElement('li');
        li.className = 'suggestion-item';
        li.setAttribute('data-pokemon-name', pokemon.name);
        li.innerHTML = `
            ${pokemon.image ? `<img src="${pokemon.image}" alt="${pokemon.name}" onerror="this.style.display='none'">` : ''}
            <div class="pokemon-info">
                <div class="pokemon-name">${pokemon.name}</div>
                <div class="pokemon-id">#${pokemon.id || ''}</div>
            </div>
        `;
        list.appendChild(li);
    });
    
    list.classList.add('active');
}

/**
 * Selecciona un Pokémon existente para el equipo
 */
function selectExistingPokemon(pokemonName) {
    document.getElementById('existingPokeName').value = pokemonName;
    document.getElementById('existingSuggestions').classList.remove('active');
    
    // Cargar datos del Pokémon
    fetch(`${BASE_PATH}/api/pokemon/search?name=${encodeURIComponent(pokemonName)}`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data) {
                selectedBasePokemon = data.data;
                displayExistingPokemonInfo(data.data);
                loadExistingAbilities(pokemonName);
                loadExistingMoves(pokemonName);
            }
        })
        .catch(err => console.error(err));
}

/**
 * Muestra información del Pokémon existente
 */
function displayExistingPokemonInfo(pokemon) {
    const info = document.getElementById('existingPokemonInfo');
    info.innerHTML = `
        <div class="pokemon-info-section">
            ${pokemon.image ? `<img src="${pokemon.image}" alt="${pokemon.name}">` : ''}
            <div style="margin-top: 10px;">
                <strong>${pokemon.name}</strong> (#${pokemon.id})
                <div class="stat-row" style="margin-top: 10px;">
                    <span>HP: <strong>${pokemon.hp}</strong></span>
                    <span>Ataque: <strong>${pokemon.attack}</strong></span>
                    <span>Defensa: <strong>${pokemon.defense}</strong></span>
                </div>
                <div class="stat-row">
                    <span>At.Esp: <strong>${pokemon.spAtk}</strong></span>
                    <span>Def.Esp: <strong>${pokemon.spDef}</strong></span>
                    <span>Vel: <strong>${pokemon.speed}</strong></span>
                </div>
            </div>
        </div>
    `;
}

/**
 * Carga habilidades del Pokémon existente
 */
function loadExistingAbilities(pokemonName) {
    const section = document.getElementById('existingAbilitiesSection');
    const select = document.getElementById('existingAbility');
    
    // Por ahora, mostrar una habilidad por defecto
    section.style.display = 'block';
    select.innerHTML = `
        <option value="Habilidad estándar">Habilidad estándar</option>
    `;
}

/**
 * Carga movimientos del Pokémon existente
 */
function loadExistingMoves(pokemonName) {
    const section = document.getElementById('existingMovesSection');
    const movesList = document.getElementById('existingMovesList');
    
    fetch(`${BASE_PATH}/api/team/${currentTeam?.id || 'default'}/pokemon/moves/${encodeURIComponent(pokemonName)}`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data) {
                section.style.display = 'block';
                displayMoveCheckboxes(data.data, movesList, true);
            }
        })
        .catch(err => {
            section.style.display = 'block';
            movesList.innerHTML = '<p style="color: #999;">No se pudieron cargar los movimientos</p>';
        });
}

/**
 * Muestra checkboxes de movimientos
 */
function displayMoveCheckboxes(moves, container, isExisting = false) {
    container.innerHTML = '';
    
    moves.slice(0, 10).forEach((move, idx) => {
        const label = document.createElement('label');
        label.style.display = 'flex';
        label.style.alignItems = 'center';
        label.style.gap = '8px';
        label.style.padding = '8px';
        label.style.cursor = 'pointer';
        label.style.borderRadius = '6px';
        label.style.marginBottom = '5px';
        label.style.transition = 'background 0.2s';
        
        label.onmouseover = () => label.style.background = '#f0f0f0';
        label.onmouseout = () => label.style.background = 'transparent';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = 'move';
        checkbox.value = typeof move === 'string' ? move : (move.name || move.original);
        checkbox.maxchecked = 4; // Máximo 4 movimientos
        
        const moveName = typeof move === 'string' ? move : (move.name || move.original);
        
        label.appendChild(checkbox);
        label.appendChild(document.createTextNode(moveName));
        container.appendChild(label);
    });
}

/**
 * Guarda un Pokémon al equipo
 */
function savePokemonToTeam() {
    if (!currentTeam) {
        showError('No hay equipo seleccionado');
        return;
    }
    
    let pokemonData = {};
    
    if (currentPokemonType === 'existing') {
        if (!selectedBasePokemon) {
            showError('Selecciona un Pokémon');
            return;
        }
        
        const selectedMoves = Array.from(document.querySelectorAll('#existingMovesList input[name="move"]:checked'))
            .map(cb => cb.value)
            .slice(0, 4);
        
        pokemonData = {
            isCustom: false,
            nickname: selectedBasePokemon.name,
            basePokemonName: selectedBasePokemon.name,
            basePokemonId: selectedBasePokemon.id,
            hp: selectedBasePokemon.hp,
            attack: selectedBasePokemon.attack,
            defense: selectedBasePokemon.defense,
            spAtk: selectedBasePokemon.spAtk,
            spDef: selectedBasePokemon.spDef,
            speed: selectedBasePokemon.speed,
            ability: document.getElementById('existingAbility').value,
            moves: selectedMoves,
            type: selectedBasePokemon.type,
            image: selectedBasePokemon.image
        };
    } else {
        const nickname = document.getElementById('customNickname').value.trim();
        if (!nickname) {
            showError('El mote es obligatorio para Pokémon personalizados');
            return;
        }
        
        const movesInput = document.getElementById('customMoves').value;
        const moves = movesInput
            .split(',')
            .map(m => m.trim())
            .filter(m => m)
            .slice(0, 4);
        
        pokemonData = {
            isCustom: true,
            nickname: nickname,
            basePokemonName: '',
            basePokemonId: null,
            hp: parseInt(document.getElementById('customHP').value),
            attack: parseInt(document.getElementById('customAtk').value),
            defense: parseInt(document.getElementById('customDef').value),
            spAtk: parseInt(document.getElementById('customSpAtk').value),
            spDef: parseInt(document.getElementById('customSpDef').value),
            speed: parseInt(document.getElementById('customSpeed').value),
            ability: document.getElementById('customAbility').value,
            moves: moves,
            type: document.getElementById('customPokeType').value,
            image: 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/default.png'
        };
    }
    
    // Agregar al equipo en el backend
    const teamId = currentTeam.id || 'default';
    fetch(`${BASE_PATH}/api/team/${teamId}/pokemon/add`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(pokemonData)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            currentTeam = data.data;
            renderTeam();
            closeCreatePokemonModal();
            showSuccess('¡Pokémon agregado al equipo!');
        } else {
            showError(data.error || 'Error al agregar Pokémon');
        }
    })
    .catch(err => showError('Error: ' + err.message));
}

/**
 * Renderiza el equipo
 */
function renderTeam() {
    const grid = document.getElementById('teamList');
    if (!currentTeam || !currentTeam.members) {
        grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">No hay Pokémon en el equipo</p>';
        return;
    }
    
    grid.innerHTML = currentTeam.members.map(pokemon => `
        <div class="team-pokemon-card">
            <img src="${pokemon.image}" alt="${pokemon.nickname}" onerror="this.style.display='none'">
            <div class="nickname">${pokemon.nickname}</div>
            <div class="pokemon-type">${pokemon.type || 'Sin tipo'}</div>
            <div class="stat-row" style="font-size: 0.85em; margin-bottom: 10px;">
                <span>HP: <strong>${pokemon.hp}</strong></span>
                <span>Atk: <strong>${pokemon.attack}</strong></span>
            </div>
            <div class="team-pokemon-actions">
                <button class="btn-small btn-edit" onclick="editPokemon('${pokemon.id}')">Editar</button>
                <button class="btn-small btn-delete" onclick="removePokemon('${pokemon.id}')">Eliminar</button>
            </div>
        </div>
    `).join('');
}

/**
 * Edita un Pokémon del equipo
 */
function editPokemon(pokemonId) {
    console.log('Editar Pokémon:', pokemonId);
    // Implementar edición
}

/**
 * Elimina un Pokémon del equipo
 */
function removePokemon(pokemonId) {
    if (!currentTeam) return;
    
    const teamId = currentTeam.id || 'default';
    fetch(`${BASE_PATH}/api/team/${teamId}/pokemon/${pokemonId}/remove`, {
        method: 'POST'
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            currentTeam = data.data;
            renderTeam();
            showSuccess('Pokémon eliminado del equipo');
        }
    })
    .catch(err => showError('Error: ' + err.message));
}

/**
 * Inicializa el equipo predeterminado
 */
function initializeTeam() {
    fetch(`${BASE_PATH}/api/team/all`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data && data.data.length > 0) {
                currentTeam = data.data[0];
            } else {
                // Crear nuevo equipo
                createNewTeam();
            }
            renderTeam();
        })
        .catch(err => {
            console.error(err);
            createNewTeam();
        });
}

/**
 * Crea un nuevo equipo
 */
function createNewTeam() {
    fetch(`${BASE_PATH}/api/team/create`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name: 'Mi Equipo', description: '' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            currentTeam = data.data;
            renderTeam();
        }
    })
    .catch(err => console.error(err));
}

/**
 * Inicializar cuando carga el DOM
 */
document.addEventListener('DOMContentLoaded', () => {
    initializeTeam();
    loadAvailableMoves();
    
    // Event listeners para autocompletado del equipo
    const existingInput = document.getElementById('existingPokeName');
    if (existingInput) {
        existingInput.addEventListener('input', (e) => getSuggestionsForTeam(e.target.value));
        existingInput.addEventListener('blur', () => {
            setTimeout(() => {
                document.getElementById('existingSuggestions')?.classList.remove('active');
            }, 200);
        });
    }
    
    // Event delegation para selecciones del equipo
    document.addEventListener('mousedown', (e) => {
        const suggestionItem = e.target.closest('#existingSuggestions .suggestion-item');
        if (suggestionItem) {
            e.preventDefault();
            const pokemonName = suggestionItem.getAttribute('data-pokemon-name');
            selectExistingPokemon(pokemonName);
        }
    });
});

// Funciones auxiliares
function showError(msg) {
    console.error(msg);
    alert(msg);
}

function showSuccess(msg) {
    console.log(msg);
    alert(msg);
}
