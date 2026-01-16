# INSTRUCCIONES DE INSTALACIÓN Y DEPLOYMENT

## 🖥️ Para Desarrollo Local (WAMP)

### Requisitos
- WAMP instalado y funcionando
- PHP 7.2 o superior
- Navegador moderno (Chrome, Firefox, Safari, Edge)

### Pasos de Instalación

1. **Copiar proyecto a carpeta WAMP**
   ```
   C:\wamp64\www\proyecto php\
   ```

2. **Verificar que WAMP esté corriendo**
   - Haz clic en el icono de WAMP en la bandeja
   - Todos los servicios deben estar verdes (Apache, PHP)

3. **Acceder a la aplicación**
   ```
   http://localhost/proyecto php/
   ```

4. **¡Listo!** La aplicación está lista para usar

### Solución de Problemas en Localhost

**Problema:** "404 Not Found"
- **Solución:** Verifica que la URL sea correcta
  ```
  ✓ http://localhost/proyecto php/
  ✗ http://localhost/proyecto%20php/
  ✗ http://localhost/proyecto_php/
  ```

**Problema:** "Cannot connect to server"
- **Solución:** Inicia WAMP
  - Haz clic en el icono en la bandeja de tareas
  - Espera a que todos los servicios estén verdes

**Problema:** "API not responding"
- **Solución:** Verifica tu conexión a internet
  - La app necesita internet para PokéAPI
  - Prueba en otra pestaña: https://pokeapi.co/

---

## 🌐 Para Producción (Servidor Web)

### Requisitos
- Servidor web (Apache, Nginx, etc.)
- PHP 7.2+ instalado
- Acceso SSH o FTP
- Conexión a internet

### Configuración en el Servidor

1. **Subir archivos al servidor**
   ```bash
   # Vía FTP o SCP
   scp -r ./proyecto php usuario@servidor.com:/var/www/html/
   ```

2. **Configurar permisos**
   ```bash
   chmod 755 /var/www/html/proyecto php
   chmod 644 /var/www/html/proyecto php/*.php
   chmod -R 755 /var/www/html/proyecto php/src
   chmod -R 755 /var/www/html/proyecto php/public
   ```

3. **Crear carpetas de logs y caché (si se usan)**
   ```bash
   mkdir -p /var/www/html/proyecto php/logs
   mkdir -p /var/www/html/proyecto php/cache
   chmod 775 /var/www/html/proyecto php/logs
   chmod 775 /var/www/html/proyecto php/cache
   ```

4. **Actualizar config.php para producción**
   ```php
   define('ENABLE_HTTPS', true);  // Cambiar a true
   define('LOG_ERRORS', true);
   ```

5. **Configurar SSL (HTTPS)**
   - Obtener certificado SSL (Let's Encrypt es gratuito)
   - Configurar Apache/Nginx para HTTPS
   - Redirigir HTTP → HTTPS

### Ejemplo: Configuración Apache (.htaccess)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /proyecto php/
    
    # Redirigir a HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Permitir acceso a public/
    RewriteCond %{REQUEST_FILENAME} -f [OR]
    RewriteCond %{REQUEST_FILENAME} -d
    RewriteRule ^ - [L]
</IfModule>
```

### Ejemplo: Configuración Nginx

```nginx
server {
    listen 443 ssl http2;
    server_name pokemon-calc.com;
    
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/key.key;
    
    root /var/www/html/proyecto php/public;
    index index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

# Redirigir HTTP a HTTPS
server {
    listen 80;
    server_name pokemon-calc.com;
    return 301 https://$server_name$request_uri;
}
```

---

## 🐳 Deployment con Docker

### Dockerfile

```dockerfile
FROM php:7.4-apache

WORKDIR /var/www/html

# Copiar proyecto
COPY . .

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar rewrite module
RUN a2enmod rewrite

# Copiar configuración Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
```

### docker-compose.yml

```yaml
version: '3.8'

services:
  web:
    build: .
    ports:
      - "80:80"
    volumes:
      - ./:/var/www/html
    environment:
      - PHP_ENV=production
```

### Ejecutar con Docker

```bash
docker-compose up -d
```

---

## 🚀 Deployment Automático (CI/CD)

### GitHub Actions

```yaml
name: Deploy to Server

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Deploy to Server
        run: |
          mkdir -p ~/.ssh
          echo "${{ secrets.SSH_KEY }}" > ~/.ssh/id_rsa
          chmod 600 ~/.ssh/id_rsa
          ssh-keyscan -H ${{ secrets.SERVER_HOST }} >> ~/.ssh/known_hosts
          scp -r ./* ${{ secrets.SERVER_USER }}@${{ secrets.SERVER_HOST }}:/var/www/html/proyecto php/
          ssh ${{ secrets.SERVER_USER }}@${{ secrets.SERVER_HOST }} "chmod -R 755 /var/www/html/proyecto php"
```

---

## ✅ Checklist de Deployment

### Antes de Subir a Producción

- [ ] Cambiar `ENABLE_HTTPS` a `true` en config.php
- [ ] Cambiar `display_errors` a `0`
- [ ] Configurar `log_errors` adecuadamente
- [ ] Cambiar `ENABLE_CACHE` a `true`
- [ ] Crear carpetas de logs y caché
- [ ] Configurar permisos correctamente
- [ ] Instalar certificado SSL
- [ ] Probar todos los endpoints
- [ ] Verificar que PokéAPI es accesible
- [ ] Configurar backups automáticos

### Después de Subir a Producción

- [ ] Verificar que el sitio carga correctamente
- [ ] Probar búsqueda de Pokémon
- [ ] Probar comparación
- [ ] Revisar logs de errores
- [ ] Verificar certificado SSL
- [ ] Monitorear rendimiento
- [ ] Configurar alertas de errores

---

## 📊 Monitoreo

### Logs

```bash
# Ver logs de errores PHP
tail -f /var/www/html/proyecto php/logs/errors.log

# Ver logs de Apache
tail -f /var/log/apache2/error.log
tail -f /var/log/apache2/access.log
```

### Health Check

```bash
# Verificar que el servidor responde
curl -I https://pokemon-calc.com/

# Verificar un endpoint
curl https://pokemon-calc.com/api/pokemon/search?name=pikachu
```

---

## 🔐 Seguridad

### Recomendaciones

1. **HTTPS obligatorio**
   - Usar certificados SSL válidos
   - Redirigir HTTP → HTTPS

2. **Validación de entrada**
   - Sanitizar nombres de Pokémon ✓ (ya implementado)
   - Validar rangos de estadísticas ✓ (ya implementado)

3. **Rate Limiting**
   - Implementar límite de solicitudes
   - Prevenir abuso de API

4. **Headers de Seguridad**
   ```php
   header("X-Content-Type-Options: nosniff");
   header("X-Frame-Options: DENY");
   header("X-XSS-Protection: 1; mode=block");
   ```

5. **CORS**
   - Configurar CORS apropiadamente
   - Permitir solo dominios de confianza

---

## 📞 Soporte

### Si algo falla

1. **Revisar logs**
   ```bash
   tail -f logs/errors.log
   ```

2. **Verificar permisos**
   ```bash
   ls -la /var/www/html/proyecto php
   ```

3. **Verificar PHP**
   ```bash
   php -v
   php -m  # Ver extensiones
   ```

4. **Verificar conectividad**
   ```bash
   curl https://pokeapi.co/api/v2/pokemon/1
   ```

---

## 📚 Referencias

- [PHP Documentation](https://www.php.net/)
- [PokéAPI Documentation](https://pokeapi.co/)
- [Apache Documentation](https://httpd.apache.org/)
- [Nginx Documentation](https://nginx.org/)
- [Let's Encrypt](https://letsencrypt.org/)

---

Última actualización: 2025-12-10
