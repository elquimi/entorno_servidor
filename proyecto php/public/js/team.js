let currentTeam = null;
let allMoves = [];
let allPokemon = []; // Todos los Pokémon disponibles para búsqueda
let editingPokemonId = null; // ID del pokémon siendo editado
let currentPokemonType = 'existing'; // existing o custom
let selectedBasePokemon = null;
let selectedMovesForTeam = []; // Movimientos seleccionados para el Pokémon actual
let allAvailableMoves = []; // Todos los movimientos del Pokémon seleccionado
let allAbilitiesForCurrentPokemon = []; // Todas las habilidades del Pokémon seleccionado
let customBasePokemon = null; // Pokémon base para imagen en personalizados
let selectedCustomTypes = []; // Tipos seleccionados para Pokémon personalizado
const TYPE_LIST = ['Normal','Fuego','Agua','Eléctrico','Planta','Hielo','Lucha','Veneno','Tierra','Volador','Psíquico','Bicho','Roca','Fantasma','Dragón','Siniestro','Acero','Hada'];

/**
 * Carga todos los Pokémon disponibles
 */
function loadAllPokemon() {
    fetch(`${BASE_PATH}/api/pokemon/list`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data) {
                allPokemon = data.data;
                console.log('Pokémon cargados:', allPokemon.length);
            }
        })
        .catch(err => console.error('Error loading pokemon list:', err));
}

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
    
    // Mostrar/ocultar formularios (forzar display para evitar inline style conflicts)
    const existingForm = document.getElementById('existingPokemonForm');
    const customForm = document.getElementById('customPokemonForm');
    if (existingForm && customForm) {
        existingForm.classList.toggle('active', type === 'existing');
        customForm.classList.toggle('active', type === 'custom');
        existingForm.style.display = type === 'existing' ? 'block' : 'none';
        customForm.style.display = type === 'custom' ? 'block' : 'none';
    }
    
    // Solo resetear si NO estamos editando
    if (!editingPokemonId) {
        if (type === 'existing') {
            resetExistingForm();
        } else if (type === 'custom') {
            resetCustomForm();
        }
    }

    // Wire up custom base & type search inputs when visible
    if (type === 'custom') {
        const baseInput = document.getElementById('customBasePokeName');
        if (baseInput) {
            baseInput.oninput = (e) => getSuggestionsForCustomBase(e.target.value);
        }
        const typeInput = document.getElementById('customTypeSearch');
        if (typeInput) {
            typeInput.oninput = (e) => renderCustomTypeSuggestions(e.target.value);
        }
    }
}

/**
 * Reinicia los formularios
 */
function resetForms() {
    resetExistingForm();
    resetCustomForm();
    editingPokemonId = null;
    
    // Cambiar botón de vuelta a "Agregar"
    const saveBtn = document.querySelector('button[onclick="savePokemonToTeam()"]');
    if (saveBtn) saveBtn.textContent = 'Agregar al Equipo';
    customBasePokemon = null;
}

/**
 * Reinicia formulario existente
 */
function resetExistingForm() {
    console.log('🔴 resetExistingForm LLAMADO');
    console.trace(); // Mostrar stack trace para ver de dónde se llama
    document.getElementById('existingPokeName').value = '';
    document.getElementById('existingPokemonInfo').innerHTML = '';
    document.getElementById('existingAbilitiesSection').style.display = 'none';
    document.getElementById('existingMovesSection').style.display = 'none';
    document.getElementById('existingMoveSearch').value = '';
    selectedBasePokemon = null;
    selectedMovesForTeam = [];
}

/**
 * Sugerencias para Pokémon base en personalizados
 */
function getSuggestionsForCustomBase(query) {
    if (!query || query.length < 1) return;
    const q = query.toLowerCase().trim();
    const filtered = allPokemon.filter(pokemon => (pokemon.name || '').toLowerCase().startsWith(q));
    displayCustomBaseSuggestions(filtered);
}

function displayCustomBaseSuggestions(suggestions) {
    const list = document.getElementById('customBaseSuggestions');
    if (!list) return;
    list.innerHTML = '';
    suggestions.sort((a, b) => (a.name || '').localeCompare((b.name || ''), 'es'));
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

// Selección de Pokémon base para personalizados
document.addEventListener('mousedown', (e) => {
    const item = e.target.closest('#customBaseSuggestions .suggestion-item');
    if (item) {
        const name = item.getAttribute('data-pokemon-name');
        const base = allPokemon.find(p => p.name === name);
        if (base) {
            console.log('🔍 Pokémon base seleccionado:', base);
            
            customBasePokemon = base;
            const input = document.getElementById('customBasePokeName');
            if (input) input.value = base.name;
            const list = document.getElementById('customBaseSuggestions');
            if (list) list.classList.remove('active');
            
            // Buscar estadísticas completas del Pokémon desde la API
            console.log('📊 Buscando estadísticas completas para:', base.name);
            fetch(`${BASE_PATH}/api/pokemon/search?name=${encodeURIComponent(base.name)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.data) {
                        const pokemonData = data.data;
                        console.log('✅ Datos completos obtenidos:', pokemonData);
                        
                        // Cargar automáticamente las estadísticas del Pokémon
                        document.getElementById('customHP').value = pokemonData.hp || 100;
                        document.getElementById('customAtk').value = pokemonData.attack || 100;
                        document.getElementById('customDef').value = pokemonData.defense || 100;
                        document.getElementById('customSpAtk').value = pokemonData.spAtk || 100;
                        document.getElementById('customSpDef').value = pokemonData.spDef || 100;
                        document.getElementById('customSpeed').value = pokemonData.speed || 100;
                        
                        // Cargar tipos
                        const types = (pokemonData.type || '').split(', ').filter(t => t);
                        document.getElementById('customType1').value = types[0] || '';
                        document.getElementById('customType2').value = types[1] || '';
                        
                        console.log('✅ Tipos cargados:', types);
                        console.log('✅ Estadísticas y tipos cargados desde API');
                    }
                })
                .catch(err => console.error('❌ Error al cargar estadísticas:', err));
        }
    }
});

/**
 * Filtra tipos por prefijo y reconstruye el select
 */
function renderCustomTypeSuggestions(query) {
    const q = (query || '').trim().toLowerCase();
    const list = document.getElementById('customTypeSuggestions');
    if (!list) return;
    const filtered = q ? TYPE_LIST.filter(t => t.toLowerCase().startsWith(q)) : TYPE_LIST.slice();
    filtered.sort((a, b) => a.localeCompare(b, 'es'));
    list.innerHTML = '';
    filtered.forEach(type => {
        const li = document.createElement('li');
        li.className = 'suggestion-item' + (selectedCustomTypes.includes(type) ? ' selected' : '');
        li.setAttribute('data-type-name', type);
        li.innerHTML = `<div class="pokemon-info"><div class="pokemon-name">${type}</div></div>`;
        list.appendChild(li);
    });
    list.classList.add('active');
}

function renderSelectedCustomTypes() {
    const container = document.getElementById('customTypeSelected');
    if (!container) return;
    container.innerHTML = selectedCustomTypes.map(t => `<span class="badge">${t}</span>`).join('');
}

// Toggle type selection with max 2
document.addEventListener('mousedown', (e) => {
    const item = e.target.closest('#customTypeSuggestions .suggestion-item');
    if (item) {
        const typeName = item.getAttribute('data-type-name');
        const idx = selectedCustomTypes.indexOf(typeName);
        if (idx === -1) {
            if (selectedCustomTypes.length < 2) {
                selectedCustomTypes.push(typeName);
                item.classList.add('selected');
            } else {
                // If already 2, replace the oldest (optional) or ignore
                // Here we ignore to keep UX simple
            }
        } else {
            selectedCustomTypes.splice(idx, 1);
            item.classList.remove('selected');
        }
        renderSelectedCustomTypes();
    }
});

/**
 * Reinicia formulario personalizado
 */
function resetCustomForm() {
    document.getElementById('customNickname').value = '';
    document.getElementById('customType1').value = '';
    document.getElementById('customType2').value = '';
    document.getElementById('customHP').value = '100';
    document.getElementById('customAtk').value = '100';
    document.getElementById('customDef').value = '100';
    document.getElementById('customSpAtk').value = '100';
    document.getElementById('customSpDef').value = '100';
    document.getElementById('customSpeed').value = '100';
    document.getElementById('customAbility').value = '';
    document.getElementById('customMoves').value = '';
    document.getElementById('customBasePokeName').value = '';
    selectedCustomTypes = [];
}

/**
 * Obtiene sugerencias de Pokémon existentes para agregar al equipo
 */
function getSuggestionsForTeam(query) {
    if (!query || query.length < 1) return;
    
    const q = query.toLowerCase().trim();
    
    // Filtrar solo Pokémon que empiezan con el query
    const filtered = allPokemon.filter(pokemon => 
        (pokemon.name || '').toLowerCase().startsWith(q)
    );
    
    displayTeamSuggestions(filtered);
}

/**
 * Muestra sugerencias de Pokémon
 */
function displayTeamSuggestions(suggestions) {
    const list = document.getElementById('existingSuggestions');
    list.innerHTML = '';
    
    // Ordenar alfabéticamente
    suggestions.sort((a, b) => (a.name || '').localeCompare((b.name || ''), 'es'));
    
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
    console.log('selectExistingPokemon called with:', pokemonName);
    document.getElementById('existingPokeName').value = pokemonName;
    document.getElementById('existingSuggestions').classList.remove('active');
    
    // Cargar datos del Pokémon
    fetch(`${BASE_PATH}/api/pokemon/search?name=${encodeURIComponent(pokemonName)}`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data) {
                console.log('Pokemon data fetched:', data.data);
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
    console.log('🟢 displayExistingPokemonInfo LLAMADO con:', pokemon);
    const info = document.getElementById('existingPokemonInfo');
    const pokemonName = pokemon.name || pokemon.basePokemonName || 'Desconocido';
    const pokemonId = pokemon.id || pokemon.basePokemonId || '?';
    
    const html = `
        <div class="pokemon-info-section">
            ${pokemon.image ? `<img src="${pokemon.image}" alt="${pokemonName}" style="max-width: 200px; max-height: 200px;">` : 'SIN IMAGEN'}
            <div style="margin-top: 10px;">
                <strong>${pokemonName}</strong> (#${pokemonId})
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
    console.log('🟢 HTML a insertar:', html);
    info.innerHTML = html;
    console.log('🟢 innerHTML establecido, contenido actual:', info.innerHTML);
}

/**
 * Carga habilidades del Pokémon existente
 */
function loadExistingAbilities(pokemonName) {
    const section = document.getElementById('existingAbilitiesSection');
    const select = document.getElementById('existingAbility');
    
    console.log('🔎 Verificando elementos DOM:');
    console.log('   section existe:', !!section);
    console.log('   select existe:', !!select);
    if (section) console.log('   section.style.display:', section.style.display);
    if (select) console.log('   select.style.display:', select.style.display);
    
    if (!pokemonName || pokemonName.trim() === '') {
        section.style.display = 'none';
        return;
    }
    
    const url = `${BASE_PATH}/api/team/pokemon/abilities/${encodeURIComponent(pokemonName)}`;
    console.log('🔍 Cargando habilidades para:', pokemonName);
    console.log('📍 URL:', url);
    
    fetch(url)
        .then(response => {
            console.log('📡 Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('✅ Data recibido:', data);
            
            if (!data.success) {
                console.warn('❌ Response no exitosa:', data);
                section.style.display = 'none';
                return;
            }
            
            if (!data.data || data.data.length === 0) {
                console.warn('⚠️ No hay habilidades en los datos');
                section.style.display = 'none';
                return;
            }
            
            // Filtrar habilidades (priorizando no ocultas)
            const abilities = data.data;
            console.log('📋 Todas las habilidades:', abilities);
            allAbilitiesForCurrentPokemon = abilities;
            const normalAbility = abilities.find(a => !a.isHidden);
            const selectedAbility = normalAbility || abilities[0];
            
            console.log('🎯 Habilidad seleccionada:', selectedAbility);
            console.log('🎯 Nombre:', selectedAbility?.name);
            console.log('🎯 Original:', selectedAbility?.original);
            
            // Mostrar sección y establecer habilidad
            console.log('👁️ Haciendo visible la sección...');
            section.style.display = 'block';
            console.log('👁️ Después de display=block:', section.style.display);
            
            // Construir opciones para TODAS las habilidades
            let html = '';
            abilities.forEach(ability => {
                const abilityName = ability.name || ability.original || 'Sin nombre';
                const isSelected = (ability === selectedAbility) ? 'selected' : '';
                html += `<option value="${abilityName}" ${isSelected}>${abilityName}</option>`;
            });
            
            console.log('📝 HTML a insertar (todas las habilidades):', html);
            select.innerHTML = html;
            console.log('✨ Habilidades establecidas en el select');
            console.log('📝 Select value después:', select.value);
            console.log('📝 Select options:', select.options.length, 'opciones');
            for (let i = 0; i < select.options.length; i++) {
                console.log(`   Opción ${i}:`, select.options[i].value, '=', select.options[i].text);
            }
            // Filtro de habilidades por prefijo (empieza con)
            const abilitySearch = document.getElementById('existingAbilitySearch');
            if (abilitySearch) {
                abilitySearch.value = '';
                abilitySearch.oninput = (e) => filterExistingAbilities(e.target.value);
            }
        })
        .catch(error => {
            console.error('💥 Error al cargar habilidades:', error);
            section.style.display = 'none';
        });
}

/**
 * Carga movimientos del Pokémon existente
 */
function loadExistingMoves(pokemonName) {
    const section = document.getElementById('existingMovesSection');
    const movesList = document.getElementById('existingMovesList');
    const searchInput = document.getElementById('existingMoveSearch');
    
    fetch(`${BASE_PATH}/api/team/pokemon/moves/${encodeURIComponent(pokemonName)}`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data && data.data.length > 0) {
                section.style.display = 'block';
                allAvailableMoves = data.data;
                
                // Solo limpiar si NO estamos editando (preservar movimientos seleccionados)
                if (!editingPokemonId) {
                    selectedMovesForTeam = [];
                }
                
                renderExistingMovesList(allAvailableMoves);
                
                // Event listener para búsqueda de movimientos
                searchInput.addEventListener('input', (e) => filterExistingMoves(e.target.value));
            } else {
                section.style.display = 'none';
            }
        })
        .catch(err => {
            console.error(err);
            section.style.display = 'none';
        });
}

/**
 * Filtra habilidades disponibles por prefijo (empieza con)
 */
function filterExistingAbilities(query) {
    const q = (query || '').trim().toLowerCase();
    const select = document.getElementById('existingAbility');
    if (!select) return;
    const source = allAbilitiesForCurrentPokemon || [];
    if (!source.length) return;
    // Si no hay query, restaurar lista completa con preferencia de habilidad normal
    if (!q) {
        const normalAbility = source.find(a => !a.isHidden);
        const selectedAbility = normalAbility || source[0];
        let html = '';
        source.forEach(ability => {
            const abilityName = ability.name || ability.original || 'Sin nombre';
            const isSelected = (ability === selectedAbility) ? 'selected' : '';
            html += `<option value="${abilityName}" ${isSelected}>${abilityName}</option>`;
        });
        select.innerHTML = html;
        return;
    }
    // Filtrar por startsWith y reconstruir
    const filtered = source.filter(a => (a.name || a.original || '').toLowerCase().startsWith(q));
    let html = '';
    filtered.forEach(ability => {
        const abilityName = ability.name || ability.original || 'Sin nombre';
        html += `<option value="${abilityName}">${abilityName}</option>`;
    });
    select.innerHTML = html;
}

/**
 * Filtra los movimientos disponibles
 */
function filterExistingMoves(query) {
    const q = (query || '').trim().toLowerCase();
    
    // Filtrar solo movimientos que empiezan con el query
    const filtered = allAvailableMoves.filter(move => 
        (move.name || move).toLowerCase().startsWith(q)
    );
    
    // Ordenar alfabéticamente
    filtered.sort((a, b) => {
        const aName = (typeof a === 'string' ? a : (a.name || a.original || '')).toLowerCase();
        const bName = (typeof b === 'string' ? b : (b.name || b.original || '')).toLowerCase();
        return aName.localeCompare(bName, 'es');
    });
    
    renderExistingMovesList(filtered);
}

/**
 * Renderiza la lista de movimientos del Pokémon existente
 */
function renderExistingMovesList(moves) {
    const container = document.getElementById('existingMovesList');
    container.innerHTML = '';
    
    moves.forEach((move, idx) => {
        const moveName = typeof move === 'string' ? move : (move.name || move.original);
        const li = document.createElement('li');
        const isSelected = selectedMovesForTeam.includes(moveName);
        li.className = 'move-item' + (isSelected ? ' selected' : '');
        li.style.marginBottom = '8px';
        
        li.innerHTML = `
            <input type="checkbox" class="move-checkbox" value="${moveName}" ${isSelected ? 'checked' : ''}>
            <span class="move-name">${moveName}</span>
        `;
        
        const checkbox = li.querySelector('.move-checkbox');
        checkbox.addEventListener('change', (e) => {
            toggleExistingMove(moveName, e.target.checked);
            // Actualizar clase del contenedor
            if (e.target.checked) {
                li.classList.add('selected');
            } else {
                li.classList.remove('selected');
            }
        });
        
        // Hacer que todo el contenedor sea clickeable
        li.addEventListener('click', (e) => {
            if (e.target !== checkbox) {
                checkbox.click();
            }
        });
        
        container.appendChild(li);
    });
}

/**
 * Alterna un movimiento seleccionado
 */
function toggleExistingMove(moveName, isChecked) {
    if (isChecked) {
        if (selectedMovesForTeam.length < 4) {
            selectedMovesForTeam.push(moveName);
        } else {
            // No permitir más de 4 movimientos
            document.querySelectorAll('.move-checkbox').forEach(cb => {
                if (cb.value === moveName) cb.checked = false;
            });
        }
    } else {
        selectedMovesForTeam = selectedMovesForTeam.filter(m => m !== moveName);
    }
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
            moves: selectedMovesForTeam.slice(0, 4),
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
        
        // Obtener los tipos de los selects
        const type1 = document.getElementById('customType1').value;
        const type2 = document.getElementById('customType2').value;
        
        if (!type1) {
            showError('El tipo 1 es obligatorio');
            return;
        }
        
        const typeString = type2 ? `${type1}, ${type2}` : type1;
        
        // Obtener el nombre del Pokémon base del input (si existe)
        const basePokemonNameInput = document.getElementById('customBasePokeName').value.trim();
        
        // Determinar imagen y basePokemon
        let finalImage = 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/default.png';
        let basePokemonNameValue = basePokemonNameInput; // Usar el valor del input
        let basePokemonIdValue = null;
        
        // Si hay un Pokémon base seleccionado, usar sus datos
        if (customBasePokemon && customBasePokemon.name) {
            finalImage = customBasePokemon.image || finalImage;
            basePokemonIdValue = customBasePokemon.id || null;
            console.log('🎨 Guardando con Pokémon base:', basePokemonNameValue, 'Imagen:', finalImage);
        } else if (basePokemonNameInput) {
            // Si solo hay texto en el input, buscar el Pokémon en allPokemon
            const foundPokemon = allPokemon.find(p => p.name === basePokemonNameInput);
            if (foundPokemon) {
                finalImage = foundPokemon.image || finalImage;
                basePokemonIdValue = foundPokemon.id || null;
            }
        }
        
        pokemonData = {
            isCustom: true,
            nickname: nickname,
            basePokemonName: basePokemonNameValue,
            basePokemonId: basePokemonIdValue,
            hp: parseInt(document.getElementById('customHP').value),
            attack: parseInt(document.getElementById('customAtk').value),
            defense: parseInt(document.getElementById('customDef').value),
            spAtk: parseInt(document.getElementById('customSpAtk').value),
            spDef: parseInt(document.getElementById('customSpDef').value),
            speed: parseInt(document.getElementById('customSpeed').value),
            ability: document.getElementById('customAbility').value,
            moves: moves,
            type: typeString,
            image: finalImage
        };
    }
    
    // Agregar o actualizar en el equipo
    const teamId = currentTeam.id || 'default';
    
    if (editingPokemonId) {
        // Estamos editando un pokémon existente
        fetch(`${BASE_PATH}/api/team/${teamId}/pokemon/${editingPokemonId}/update`, {
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
                showSuccess('¡Pokémon actualizado!');
            } else {
                showError(data.error || 'Error al actualizar Pokémon');
            }
        })
        .catch(err => showError('Error: ' + err.message));
    } else {
        // Creando un pokémon nuevo
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
        <div class="team-pokemon-card" onclick="showTeamPokemonDetail('${pokemon.id}')">
            <img src="${pokemon.image}" alt="${pokemon.nickname}" onerror="this.style.display='none'">
            <div class="nickname">${pokemon.nickname}</div>
            <div class="pokemon-type">${pokemon.type || 'Sin tipo'}</div>
            <div class="stat-row" style="font-size: 0.85em; margin-bottom: 10px;">
                <span>HP: <strong>${pokemon.hp}</strong></span>
                <span>Atk: <strong>${pokemon.attack}</strong></span>
            </div>
            <div class="team-pokemon-actions" onclick="event.stopPropagation();">
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
    if (!currentTeam) return;
    
    const pokemon = currentTeam.members.find(p => p.id === pokemonId);
    if (!pokemon) return;
    
    console.log('Editando Pokémon:', pokemon);
    
    // Establecer ID de pokémon siendo editado
    editingPokemonId = pokemonId;
    
    // Cambiar botón a "Guardar"
    const saveBtn = document.querySelector('button[onclick="savePokemonToTeam()"]');
    if (saveBtn) saveBtn.textContent = 'Guardar Cambios';
    
    // Abrir modal de edición
    const modal = document.getElementById('createPokemonModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Determinar si es un pokémon existente o personalizado
    if (pokemon.isCustom) {
        // Mostrar formulario de pokémon personalizado
        switchPokemonType('custom');
        
        // Rellenar campos
        document.getElementById('customNickname').value = pokemon.nickname || '';
        document.getElementById('customHP').value = pokemon.hp || 100;
        document.getElementById('customAtk').value = pokemon.attack || 100;
        document.getElementById('customDef').value = pokemon.defense || 100;
        document.getElementById('customSpAtk').value = pokemon.spAtk || 100;
        document.getElementById('customSpDef').value = pokemon.spDef || 100;
        document.getElementById('customSpeed').value = pokemon.speed || 100;
        document.getElementById('customAbility').value = pokemon.ability || '';
        document.getElementById('customMoves').value = (pokemon.moves || []).join(', ');
        
        // Cargar tipos
        const types = (pokemon.type || '').split(', ').filter(t => t);
        const type1Elem = document.getElementById('customType1');
        const type2Elem = document.getElementById('customType2');
        if (type1Elem) type1Elem.value = types[0] || '';
        if (type2Elem) type2Elem.value = types[1] || '';
        
        // Rellenar Pokémon base si existe
        if (pokemon.basePokemonName) {
            document.getElementById('customBasePokeName').value = pokemon.basePokemonName;
            const basePokemon = allPokemon.find(p => p.name === pokemon.basePokemonName);
            if (basePokemon) {
                customBasePokemon = basePokemon;
            }
        }
    } else {
        // Mostrar formulario de pokémon existente
        switchPokemonType('existing');
        
        // Precargar movimientos seleccionados
        selectedMovesForTeam = pokemon.moves || [];
        
        // Rellenar campo de búsqueda
        document.getElementById('existingPokeName').value = pokemon.basePokemonName || '';
        
        // Crear un objeto completo del Pokémon base con todos sus datos
        selectedBasePokemon = {
            name: pokemon.basePokemonName,
            id: pokemon.basePokemonId,
            hp: pokemon.hp,
            attack: pokemon.attack,
            defense: pokemon.defense,
            spAtk: pokemon.spAtk,
            spDef: pokemon.spDef,
            speed: pokemon.speed,
            type: pokemon.type,
            image: pokemon.image
        };
        
        // Mostrar la información del Pokémon (incluyendo imagen)
        displayExistingPokemonInfo(selectedBasePokemon);
        loadExistingAbilities(pokemon.basePokemonName);
        loadExistingMoves(pokemon.basePokemonName);
        
        // Establecer la habilidad actual en el select
        if (pokemon.ability) {
            document.getElementById('existingAbility').value = pokemon.ability;
        }
    }
}

/**
 * Muestra detalles grandes de un Pokémon del equipo
 */
function showTeamPokemonDetail(pokemonId) {
    if (!currentTeam) return;
    
    const pokemon = currentTeam.members.find(p => p.id === pokemonId);
    if (!pokemon) return;
    
    const typeColors = {
        'Normal': '#A8A77A', 'Fuego': '#EE8130', 'Agua': '#6390F0', 'Eléctrico': '#F7D02C',
        'Planta': '#7AC74C', 'Hielo': '#96D9D6', 'Lucha': '#C22E28', 'Veneno': '#A33EA1', 'Tierra': '#E2BF65',
        'Volador': '#A98FF3', 'Psíquico': '#F95587', 'Bicho': '#A6B91A', 'Roca': '#B6A136', 'Fantasma': '#735797',
        'Dragón': '#6F35FC', 'Siniestro': '#705746', 'Acero': '#B7B7CE', 'Hada': '#D685AD'
    };
    
    const types = (pokemon.type || '').split(',').map(t => t.trim()).filter(Boolean);
    const badge = t => `<span class="badge" style="background:${typeColors[t]||'#888'}">${t}</span>`;
    
    const maxStat = 255;
    const percent = v => Math.max(0, Math.min(100, Math.round((v / maxStat) * 100)));
    
    const html = `
        <div class="infobox">
            <div class="infobox-header">
                <div>
                    <div class="infobox-title">${pokemon.nickname}</div>
                    <div class="infobox-subtitle">${pokemon.isCustom ? 'Pokémon Personalizado' : pokemon.basePokemonName}</div>
                </div>
            </div>
            ${pokemon.image ? `
            <div class="infobox-image">
                <img src="${pokemon.image}" alt="${pokemon.nickname}">
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
                    <span class="stat-label">${label}</span>
                    <div class="stat-bar">
                        <div class="stat-bar-fill" style="width: ${percent(val)}%; background: linear-gradient(90deg, #667eea, #764ba2)"></div>
                    </div>
                    <span class="stat-value">${val}</span>
                </div>
                `).join('')}
            </div>
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                <div style="margin-bottom: 15px;">
                    <strong>Habilidad:</strong> ${pokemon.ability || 'N/A'}
                </div>
                <div>
                    <strong>Ataques:</strong>
                    <ul style="list-style: none; padding: 10px 0; margin: 0;">
                        ${(pokemon.moves || []).map(move => `<li style="padding: 5px 0;">• ${move}</li>`).join('')}
                    </ul>
                </div>
            </div>
        </div>
    `;
    
    const modal = document.getElementById('teamPokemonDetailModal');
    const body = document.getElementById('teamPokemonDetailBody');
    body.innerHTML = html;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

/**
 * Cierra el modal de detalles del Pokémon del equipo
 */
function closeTeamPokemonDetail() {
    const modal = document.getElementById('teamPokemonDetailModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
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
    loadAllPokemon();
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
    
    // Cerrar modales al hacer clic en el overlay
    const createPokemonModal = document.getElementById('createPokemonModal');
    if (createPokemonModal) {
        const overlay = createPokemonModal.querySelector('.modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', closeCreatePokemonModal);
        }
    }
    
    const teamDetailModal = document.getElementById('teamPokemonDetailModal');
    if (teamDetailModal) {
        const overlay = teamDetailModal.querySelector('.modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', closeTeamPokemonDetail);
        }
    }
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
