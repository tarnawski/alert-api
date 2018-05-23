Alert Api
===========

### Getting started
In order to set application up you must follow by steps:

1. Go to project directory:
```bash
cd search-api
```
2. Build Docker images:
```bash
docker-compose build
```
3. Start Docker container:
```bash
docker-compose up -d
```
4. Copy configuration file:
```bash
cp .env.dist .env
```
5. Create database schema
```bash
docker exec alert_api_php php bin/console d:s:u --force
```

