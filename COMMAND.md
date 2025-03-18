# Backend - ESDS Command Documentation

## Initial Setup

### 1. Composer Installation & Updates

```bash
# If composer.lock doesn't exist - Fresh Installation
docker compose run --rm composer install

# If composer.lock exists - Update Dependencies
docker compose run --rm composer update
```
### 2. Environment Setup
```bash
# 1. Copy .env.example to .env
cp .env.example .env

# 2. Configure your .env file with appropriate settings
# - Database credentials
# - App settings
# - Mail configuration
# - Other environment-specific variables
```

## Building and Configuration

### 2. Build Docker Containers
```bash
# Build and start containers in detached mode
docker compose up -d --build nginx --force-recreate

# Alternative command if experiencing build issues
# DOCKER_BUILDKIT=0 docker compose up --build nginx --force-recreate
```


### 10. Stop All Services
```bash
# Stop all running containers and remove networks
docker compose down
```

## Available Services

The project includes the following Docker services:

1. **nginx** - Web Server
   - Ports: 80, 443
   - Dependencies: php, pgsql

2. **php** - PHP-FPM Service
   - Mounts project root
   - Uses environment from .env

3. **composer** - PHP Dependency Manager
   - Working directory: /var/www/html
   - For managing PHP packages

3. **phpunit** - Testing Service
   - For running PHP unit tests
   - Working directory: /var/www/html

4. **pgsql** - PostgreSQL Database
   - Port: 5432 (configurable via DB_PORT)
   - Includes backup restoration capability
   - Uses custom Dockerfile with timezone configuration

## Troubleshooting

If you encounter any issues:

1. Ensure all containers are running:
```bash
docker compose ps
```

2. Check container logs:
```bash
# View logs for a specific service
docker compose logs [service_name]

# Available service names:
# - nginx
# - php
# - composer
# - phpunit
# - pgsql
```

3. Common issues:
   - Database connection issues: Verify .env database credentials

## Notes

- Always ensure your .env file is properly configured before running migrations
- Keep your composer dependencies up to date
- Regularly clear cache when making configuration changes
- Check logs if you encounter any issues
- Make sure all required ports are available on your system
- All services are connected through the 'esds' network



## Docker prune all data

```shell
docker system df
docker stop $(docker ps -aq)  # Bütün konteynerləri dayandır
docker volume rm -f $(docker volume ls -q)  # Bütün volume-ləri sil
docker rm -vf $(docker ps -aq)  # Bütün konteynerləri sil
docker rmi -f $(docker images -aq)  # Bütün image-ləri sil
docker network rm $(docker network ls -q)  # Bütün network-ləri sil
docker system prune -a --volumes -f
docker system df
```
