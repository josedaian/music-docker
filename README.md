# Music Docker
### Services
- Nginx
- PHP 7.2
- Slim PHP
- Redis 6.2.6

## How to install
After cloning the repository and being inside the project folder, run the following commands:
```sh
cp .env.example .env
docker-compose up
```

### Optional Step
In .env file you can set custom ports for services:
- Nginx (default: 8000)
- Redis (default: 6379)

For the Spotify API to work it is necessary to set the values inside the services/.env file:
- CLIENT_ID
- CLIENT_SECRET

## License

MIT
