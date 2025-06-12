# Installation Guide - PrestaGo

This guide provides detailed instructions for installing and configuring the PrestaGo application in various environments.

## Local Development Environment

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js (v14 or higher) & NPM
- MySQL/MariaDB 5.7 or higher
- Git

### Step-by-Step Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/username/sipp.git
   cd sipp
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Configure environment variables**
   ```bash
   cp .env.example .env
   ```
   
   Edit the `.env` file with your database credentials and other settings:
   ```
   APP_NAME=PrestaGo
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=prestago
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Create database**
   Create a database in MySQL/MariaDB with the name specified in your `.env` file.

7. **Run database migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

8. **Create symbolic link for storage**
   ```bash
   php artisan storage:link
   ```

9. **Start development servers**
   ```bash
   # In one terminal, run Vite development server
   npm run dev
   
   # In another terminal, run Laravel development server
   php artisan serve
   ```

10. **Access the application**
    Open your browser and visit: http://localhost:8000

## Production Environment

### Server Requirements

- PHP 8.1 or higher
- Composer
- Node.js (v14 or higher) & NPM
- MySQL/MariaDB 5.7 or higher
- Web server (Nginx or Apache)
- SSL certificate for HTTPS

### Production Installation

1. **Clone the repository on your production server**
   ```bash
   git clone https://github.com/username/sipp.git
   cd sipp
   ```

2. **Install PHP dependencies for production**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Install JavaScript dependencies and build assets**
   ```bash
   npm install --production
   npm run build
   ```

4. **Configure environment variables**
   ```bash
   cp .env.example .env
   ```
   
   Edit the `.env` file with your production settings:
   ```
   APP_NAME=PrestaGo
   APP_ENV=production
   APP_KEY=
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=prestago_prod
   DB_USERNAME=prod_username
   DB_PASSWORD=prod_password
   
   CACHE_DRIVER=file
   SESSION_DRIVER=file
   QUEUE_CONNECTION=sync
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Create symbolic link for storage**
   ```bash
   php artisan storage:link
   ```

8. **Set folder permissions**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

9. **Configure Web Server**

   **For Nginx:**
   Create a new site configuration in `/etc/nginx/sites-available/prestago`:
   ```nginx
   server {
       listen 80;
       server_name yourdomain.com;
       root /path/to/sipp/public;
       
       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";
       
       index index.php;
       charset utf-8;
       
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
       
       location = /favicon.ico { access_log off; log_not_found off; }
       location = /robots.txt  { access_log off; log_not_found off; }
       
       error_page 404 /index.php;
       
       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }
       
       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```
   
   Enable the site:
   ```bash
   ln -s /etc/nginx/sites-available/prestago /etc/nginx/sites-enabled/
   nginx -t
   systemctl reload nginx
   ```
   
   **For Apache:**
   Ensure mod_rewrite is enabled:
   ```bash
   a2enmod rewrite
   ```
   
   Create a new site configuration in `/etc/apache2/sites-available/prestago.conf`:
   ```apache
   <VirtualHost *:80>
       ServerName yourdomain.com
       DocumentRoot /path/to/sipp/public
       
       <Directory /path/to/sipp/public>
           Options -Indexes +FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
       
       ErrorLog ${APACHE_LOG_DIR}/prestago-error.log
       CustomLog ${APACHE_LOG_DIR}/prestago-access.log combined
   </VirtualHost>
   ```
   
   Enable the site:
   ```bash
   a2ensite prestago.conf
   systemctl reload apache2
   ```

10. **Configure SSL (optional but recommended)**
    Use Let's Encrypt's Certbot to set up SSL:
    ```bash
    certbot --nginx -d yourdomain.com
    # or for Apache
    certbot --apache -d yourdomain.com
    ```

11. **Schedule cron job for Laravel Scheduler**
    Add this to your crontab:
    ```
    * * * * * cd /path/to/sipp && php artisan schedule:run >> /dev/null 2>&1
    ```

12. **Clear caches**
    ```bash
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    ```

## Docker Installation (Alternative)

If you prefer Docker for development or deployment, follow these steps:

1. **Clone the repository**
   ```bash
   git clone https://github.com/username/sipp.git
   cd sipp
   ```

2. **Configure environment**
   ```bash
   cp .env.example .env
   ```
   
   Make sure Docker-specific settings are correctly configured.

3. **Build and start Docker containers**
   ```bash
   docker-compose up -d
   ```

4. **Install dependencies and setup application**
   ```bash
   docker-compose exec app composer install
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate --seed
   docker-compose exec app php artisan storage:link
   docker-compose exec app npm install
   docker-compose exec app npm run dev
   ```

5. **Access the application**
   Open your browser and visit: http://localhost:8000

## Troubleshooting

### Common Installation Issues

1. **Composer Memory Limit Errors**
   If you encounter memory limit issues with Composer, try:
   ```bash
   COMPOSER_MEMORY_LIMIT=-1 composer install
   ```

2. **Permission Issues**
   Ensure proper permissions for storage and cache directories:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

3. **Database Connection Issues**
   Verify your database credentials in `.env` and ensure your database server is running.

4. **500 Server Errors**
   Check your web server logs and Laravel logs in `storage/logs/laravel.log` for details.

5. **Missing Dependencies**
   Ensure all required PHP extensions are installed:
   ```bash
   # For Ubuntu/Debian
   sudo apt-get install php8.1-mbstring php8.1-xml php8.1-gd php8.1-curl php8.1-mysql php8.1-zip
   
   # For CentOS/RHEL
   sudo yum install php-mbstring php-xml php-gd php-curl php-mysql php-zip
   ```

## Post-Installation

After installing PrestaGo, follow these steps:

1. **Create an admin user** (if not created by seeders)
   ```bash
   php artisan make:admin
   ```

2. **Configure mail settings** in `.env` for notifications:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_username
   MAIL_PASSWORD=your_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="noreply@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```

3. **Configure additional features** as needed, such as:
   - Queue system for background processing
   - Redis for improved caching
   - Storage configuration for file uploads 