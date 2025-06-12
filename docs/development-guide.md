# Development Guide - PrestaGo

This guide provides information for developers who want to contribute to the PrestaGo application.

## Development Environment Setup

### Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Git

### Local Development Setup

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
   Edit the `.env` file to set your database credentials.

5. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Start development servers**
   ```bash
   # In one terminal, run Vite development server
   npm run dev
   
   # In another terminal, run Laravel development server
   php artisan serve
   ```

## Project Structure

- **app/**: Contains the core code of the application
  - **Http/Controllers/**: Controller classes
  - **Models/**: Eloquent models
  - **Services/**: Service classes for business logic
  - **Providers/**: Service providers
- **database/**: Database migrations, seeders, and factories
- **resources/**: Frontend assets and Blade templates
  - **js/**: JavaScript files
  - **css/**: CSS and Tailwind files
  - **views/**: Blade templates
- **routes/**: Application routes
  - **web.php**: Web routes
  - **api.php**: API routes
- **public/**: Publicly accessible files
- **tests/**: Test cases

## Coding Standards

### PHP

We follow the PSR-12 coding standard. You can check your code with:

```bash
composer check-style
```

And automatically fix many issues with:

```bash
composer fix-style
```

### JavaScript/CSS

We use ESLint and Prettier. Check your code with:

```bash
npm run lint
```

And fix issues with:

```bash
npm run lint:fix
```

## Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run a specific test
php artisan test --filter=AchievementTest

# Run with coverage report
php artisan test --coverage
```

### Creating Tests

- Place feature tests in `tests/Feature/`
- Place unit tests in `tests/Unit/`
- Name test methods descriptively, e.g., `test_user_can_create_achievement()`

## Git Workflow

1. **Create a new branch for each feature or bug fix**
   ```bash
   git checkout -b feature/achievement-validation
   ```

2. **Make your changes and commit**
   ```bash
   git add .
   git commit -m "Add validation rules for achievement creation"
   ```

3. **Pull latest changes from main branch**
   ```bash
   git pull origin main
   ```

4. **Push your branch and create a pull request**
   ```bash
   git push origin feature/achievement-validation
   ```

5. **Wait for code review and merge**

## Database Migrations

### Creating Migrations

```bash
php artisan make:migration create_table_name_table
```

### Running Migrations

```bash
# Run all pending migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations and run them again
php artisan migrate:fresh
```

## API Development

- All API endpoints should be properly documented (see [API Reference](./api-reference.md))
- Use resource classes for consistent JSON responses
- Add proper validation rules in form request classes
- Implement appropriate error handling
- Use status codes correctly

## Frontend Development

### Tailwind CSS

We use Tailwind CSS for styling:

```bash
# Watch for changes and compile CSS
npm run dev

# Build for production
npm run build
```

### JavaScript

- Use ES6+ features
- Structure complex components logically
- Comment complex logic
- Consider performance implications

## Deployment

### Production Environment

1. **Clone the repository**
   ```bash
   git clone https://github.com/username/sipp.git
   cd sipp
   ```

2. **Install dependencies for production**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install --production
   ```

3. **Set up environment for production**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   
   Configure your production settings in `.env`

4. **Compile assets**
   ```bash
   npm run build
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Configure web server**
   Set up Nginx or Apache to point to the public directory

### Useful Deployment Commands

- **Clear caches**
  ```bash
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear
  ```

- **Restart queue workers (if applicable)**
  ```bash
  php artisan queue:restart
  ```

## Troubleshooting

### Common Issues

- **Composer memory limit**: Add `COMPOSER_MEMORY_LIMIT=-1` before commands
- **File permissions**: Ensure storage/ and bootstrap/cache/ are writable
- **Database connections**: Check credentials and ensure database server is running

## Getting Help

If you need assistance, you can:

1. Check the README and documentation
2. Create an issue on the repository
3. Contact the development team at [contact@example.com] 