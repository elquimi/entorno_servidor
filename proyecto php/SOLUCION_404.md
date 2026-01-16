# 🔴 GUÍA DE SOLUCIÓN - Página No Encontrada (404)

## ❌ Problema: "Página No Encontrada" al acceder

Si ves un error **404 Not Found** o la página no se carga, sigue estos pasos:

---

## ✅ SOLUCIÓN RÁPIDA

### Paso 1: Verificar que WAMP esté corriendo
1. Busca el icono de WAMP en la bandeja de tareas (parte inferior derecha)
2. Haz clic en él
3. Verifica que todos los servicios estén de color **VERDE**:
   - ✅ Apache
   - ✅ PHP
   - ✅ MySQL (opcional)

Si están en rojo o naranja, haz clic en "Start All Services"

---

### Paso 2: Verificar la URL correcta

**❌ URLs INCORRECTAS (no funcionan):**
```
http://localhost/proyecto%20php/
http://localhost/proyecto_php/
http://localhost/proyectophp/
http://127.0.0.1/proyecto php/
```

**✅ URL CORRECTA (usa esta):**
```
http://localhost/proyecto php/
```

**O si usas puerto diferente:**
```
http://localhost:3306/proyecto php/
http://localhost:8080/proyecto php/
```

---

### Paso 3: Limpiar caché del navegador

Presiona estas teclas a la vez:
```
Ctrl + Shift + R  (Windows)
Cmd + Shift + R   (Mac)
```

Esto fuerza la recarga y limpia la caché.

---

### Paso 4: Verificar permisos de carpetas

Windows mantiene los permisos automáticamente, pero si aún hay problema:

1. Click derecho en la carpeta `proyecto php`
2. Propiedades → Seguridad
3. Verifica que tu usuario tenga permisos de "Lectura" y "Lectura y Ejecución"

---

## 🔍 DIAGNÓSTICO AVANZADO

### Si lo anterior no funciona, sigue estos pasos:

#### 1. Abrir la consola del navegador
Presiona **F12** en tu navegador:
- Abre la pestaña **"Console"** (Consola)
- Busca cualquier mensaje de error (texto rojo)
- **Copia el error completo**

#### 2. Verificar que los archivos existen

Abre el Explorador de archivos y verifica:
```
C:\wamp64\www\temp\proyecto php\
├── index.php              ✓ ¿Existe?
├── public\
│   └── index.html        ✓ ¿Existe?
├── src\
│   ├── controllers\      ✓ ¿Existe?
│   ├── models\          ✓ ¿Existe?
│   └── services\        ✓ ¿Existe?
└── .htaccess            ✓ ¿Existe?
```

Si falta alguno de estos, el proyecto no funciona.

#### 3. Verificar logs de Apache

1. Abre WAMP → Apache → error.log
2. Busca errores relacionados con `proyecto php`
3. Anota el error exacto

#### 4. Verificar configuración de WAMP

1. WAMP → Apache → httpd.conf
2. Busca la línea que contenga `DocumentRoot`
3. Debe ser algo como:
   ```
   DocumentRoot "c:\wamp64\www"
   ```

---

## 🛠️ SOLUCIONES COMUNES

### Problema 1: "Apache no está iniciado"
**Solución:**
1. Haz clic en WAMP
2. Selecciona "Start All Services"
3. Espera 5-10 segundos
4. Intenta de nuevo

### Problema 2: "Puerto 80 en uso"
**Síntoma:** WAMP no inicia o Apache está en naranja/rojo

**Solución:**
1. WAMP → Apache → Service → Install Service
2. Luego WAMP → Apache → Service → Start Service

### Problema 3: "Archivo no encontrado 404"
**Síntoma:** Se ve la carpeta pero no carga index.html

**Solución:**
1. Asegúrate que `public/index.html` existe
2. Haz Ctrl+Shift+R para limpiar caché
3. Verifica la consola (F12) para errores

### Problema 4: "Las peticiones a API no funcionan"
**Síntoma:** Se ve la página pero no busca Pokémon

**Solución:**
1. Abre F12 → Network (Red)
2. Intenta buscar un Pokémon
3. Mira si las peticiones a `/api/...` están fallando
4. Si falta `.htaccess`, cópialo a la raíz del proyecto

---

## 🌐 PRUEBA DE CONECTIVIDAD

### Verificar que todo funciona:

**1. Página HTML carga:**
```
http://localhost/proyecto php/
→ Debe ver el título "🔴 Pokémon Calculator"
```

**2. Estilos CSS carga:**
```
Las pestañas deben estar coloreadas y con diseño moderno
```

**3. JavaScript funciona:**
Abre F12 → Console
```
Debe estar vacía (sin errores rojos)
```

**4. API funciona:**
```
http://localhost/proyecto php/api/pokemon/search?name=pikachu
→ Debe ver JSON con datos de Pikachu
```

---

## 📋 CHECKLIST FINAL

Antes de reportar un problema, verifica:

- [ ] WAMP está corriendo (todos los servicios en verde)
- [ ] URL es exacta: `http://localhost/proyecto php/`
- [ ] Presionaste Ctrl+Shift+R para limpiar caché
- [ ] Todos los archivos existen en sus carpetas
- [ ] Abriste F12 → Console y no hay errores rojos
- [ ] `.htaccess` existe en la raíz del proyecto
- [ ] Tienes conexión a internet (para PokéAPI)

---

## 🆘 SI NADA FUNCIONA

Intenta esto:

### Opción 1: Reiniciar WAMP
1. Click en WAMP → "Stop All Services"
2. Espera 3 segundos
3. Click en WAMP → "Start All Services"
4. Espera 10 segundos
5. Abre `http://localhost/proyecto php/`

### Opción 2: Reinstalar Apache
1. WAMP → Apache → Service → Remove Service
2. WAMP → Apache → Service → Install Service
3. WAMP → Apache → Service → Start Service
4. Intenta de nuevo

### Opción 3: Usar diferente puerto

Si el puerto 80 está ocupado, cambia a 3306:

1. WAMP → Apache → httpd.conf
2. Busca: `Listen 80`
3. Cambia a: `Listen 3306`
4. Accede a: `http://localhost:3306/proyecto php/`

---

## 📞 MÁS AYUDA

Si después de todo esto aún no funciona:

1. **Verifica los logs:**
   - WAMP → Apache → error_log
   - WAMP → Apache → access_log

2. **Abre la consola F12 y copia:**
   - Console (errores)
   - Network (peticiones fallidas)

3. **Verifica que exista `.htaccess`**
   - Debe estar en: `C:\wamp64\www\temp\proyecto php\.htaccess`

4. **Intenta acceder a un archivo específico:**
   ```
   http://localhost/proyecto php/public/index.html
   → Debería cargar la página
   ```

---

## ✨ ÚLTIMA OPCIÓN: Usar URL directa a public

Si nada funciona, puedes acceder directamente a:
```
http://localhost/proyecto php/public/index.html
```

Pero recuerda que las búsquedas no funcionarán sin el routing correcto.

---

**Generado:** 2025-12-10
**Estado:** Listo para usar
