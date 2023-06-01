# News Center Backend 
# Laravel 10 Backend Project

This is the backend project for the news aggregator application. It provides the server-side functionality and API endpoints required for the application to function. Built with Laravel 10, PHP 8, and Sanctum for authentication.

## Table of Contents

- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Configuration](#configuration)
- [API Documentation](#api-documentation)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

## Technologies Used

- Laravel 10: PHP framework for building web applications
- PHP 8: Programming language used for server-side development
- Sanctum: Laravel package for API authentication
- Other libraries and dependencies as necessary

## Installation

1. Clone the repository: `git clone <repository-url>`
2. Change into the project directory: `cd backend`
3. Install the dependencies: `composer install`
4. Copy the example environment file: `cp .env.example .env`
5. Generate the application key: `php artisan key:generate`

## Configuration

1. Configure the following environment variables in the `.env` file:
    - `APP_URL`: URL of the backend application
    - `DB_CONNECTION`: Database connection (e.g., `mysql`)
    - `DB_HOST`: Database host
    - `DB_PORT`: Database port
    - `DB_DATABASE`: Database name
    - `DB_USERNAME`: Database username
    - `DB_PASSWORD`: Database password
    - Other configuration variables as required

2. Configure Sanctum for API authentication:
    - Enable Sanctum in `config/auth.php` file
    - Run migrations: `php artisan migrate`
    - Generate Sanctum API tokens: `php artisan sanctum:install`


## Deployment

To deploy the backend application:

1. Set up a web server (e.g., Apache, Nginx) with PHP 8 support.
2. Configure the web server to point to the public directory of the project.
3. Set the appropriate file permissions for storage and cache directories.
4. Configure the production-ready environment variables in the production environment.
5. Run migrations: `php artisan migrate`
6. Serve the application using the web server.

## Contributing

Contributions to this project are welcome! If you'd like to contribute, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature/bug fix: `git checkout -b feature/your-feature-name`
3. Make the necessary changes and commit them: `git commit -m 'Add some feature'`
4. Push the branch to your forked repository: `git push origin feature/your-feature-name`
5. Submit a pull request detailing your changes.

## License

[MIT License](LICENSE)

