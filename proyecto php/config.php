<?php
/**
 * Configuración de la aplicación
 * Variables globales y constantes
 */

// ==================== CONFIGURACIÓN GENERAL ====================
define('APP_NAME', 'Pokémon Calculator');
define('APP_VERSION', '1.0.0');
define('APP_AUTHOR', 'Tu Nombre');

// ==================== CONFIGURACIÓN DE LA BASE DE DATOS ====================
// En caso de usar base de datos en el futuro
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pokemon_db');

// ==================== CONFIGURACIÓN DE LA API ====================
define('POKEAPI_BASE_URL', 'https://pokeapi.co/api/v2/pokemon');
define('POKEAPI_TIMEOUT', 10); // segundos

// ==================== CONFIGURACIÓN DE SEGURIDAD ====================
define('ENABLE_HTTPS', false); // Cambiar a true en producción
define('MAX_SEARCH_RESULTS', 10);

// ==================== CONFIGURACIÓN DE LOGGING ====================
define('LOG_ERRORS', true);
define('LOG_FILE', __DIR__ . '/../logs/errors.log');

// ==================== DATOS LOCALES ====================
// Usa un dataset local en lugar de llamar a PokéAPI
define('USE_LOCAL_DATA', true);
define('LOCAL_DATA_PATH', BASE_PATH . '/database/pokemon.json');

// ==================== CONFIGURACIÓN DE CACHÉ ====================
define('ENABLE_CACHE', false); // Cambiar a true para mejorar rendimiento
define('CACHE_DIR', __DIR__ . '/../cache/');
define('CACHE_EXPIRY', 3600); // 1 hora

// ==================== MANEJO DE ERRORES ====================
if (LOG_ERRORS) {
    ini_set('log_errors', 1);
    ini_set('error_log', LOG_FILE);
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ==================== TIMEZONE ====================
date_default_timezone_set('America/Mexico_City'); // Cambiar según tu zona horaria

// ==================== SESSION ====================
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

?>
