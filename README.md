# Sogexia Hiring Server

This project is a simple server for Sogexia candidates. It serves mock data for the Battle card game

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Set any string for the API_TOKEN in file `.env` (ex. API_TOKEN=MySecret2024!)
3. Run `docker compose build --no-cache` to build fresh images
4. Run `docker compose up --pull always -d --wait` to set up and start
5. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
6. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Api Doc

The Api documentation is available in docs/openapi.yaml or in the browser https://localhost/api/v1/docs

## Credits

This project is based on Symfony Docker https://github.com/dunglas/symfony-docker
Original README.md has been moved into README-SYMFONY-DOCKER.md
