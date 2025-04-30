# BOOKS-API
A Laravel-based API for getting books info, built with Clean Architecture principles and running in a Dockerized environment.

## Table of Contents
- [Demo](#demo)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Testing](#testing)


## Demo

- **App Healthcheck**: [/api/healthcheck](https://sometestnewdomain.co.uk/api/healthcheck)
- **Swagger Documentation**: [/api/v1/doc](https://sometestnewdomain.co.uk/api/v1/doc#/Books%20History)
- **Books History API**: [/api/v1/books/history](https://sometestnewdomain.co.uk/api/v1/books/history)

## Requirements
To run this project locally, you need the following tools installed:

1. **Make** (Simplifies running common tasks)
    - [Install Make](https://www.gnu.org/software/make/).

2. **Docker & Docker Compose** (Runs the application and its services in containers)
    - [Install Docker](https://docs.docker.com/get-docker/) & [Install Docker Compose](https://docs.docker.com/compose/install/).

## Installation

1. Clone the repository:
```bash
git clone git@github.com:gaalferov/books-api.git
```
2. Navigate to the project directory:
```bash
cd books-api
```
3. Copy the example env file from the root project and add your API key:
```bash
cp .env.example .env

NYTIMES_API_KEY=your_api_key_here
```
4. Build and start the Docker containers:
```bash
make build-and-run

# Available commands:
# • build-and-run             Build and run DEV docker containers
# • stop                      Stop all DEV docker containers
# • test                      Run all tests
# • pint-test                 Run Pint test
# • pint-fix                  Run Pint fix
```

## Usage
- **App Healthcheck**: [/api/healthcheck](http://localhost:8080/api/healthcheck) (localhost)
- **Swagger Documentation**: [/api/v1/doc](http://localhost:8080/api/v1/doc) (localhost)
- **Books History API**: [/api/v1/books/history](http://localhost:8080/api/v1/books/history) (localhost)


## Testing
- **Unit Tests**: Run unit tests:
```bash
make test
```
- **Pint**: Run Code Style Checker:
```bash
make pint-test
```
