# Getting Started with PrestaGo

This guide will help you quickly get started with the PrestaGo application.

## Prerequisites

Before working with PrestaGo, you'll need:

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL/MariaDB database
- Git (for cloning the repository)

## Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/username/sipp.git
   cd sipp
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   
4. **Configure database**
   Edit the `.env` file to set your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=prestago
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Compile assets and start the server**
   ```bash
   npm run dev
   php artisan serve
   ```

7. **Access the application**
   Open your browser and go to: http://localhost:8000

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Dosen | dosen@example.com | password |
| Mahasiswa | mahasiswa@example.com | password |

## Next Steps

- Explore the [User Guide](./user-guide.md) to learn about features for each role
- Read the [Development Guide](./development-guide.md) if you plan to contribute
- Check the [API Reference](./api-reference.md) if you're integrating with the system 