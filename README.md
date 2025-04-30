# BOOKS-API
[![CI Checks](https://github.com/gaalferov/books-api/actions/workflows/ci.yml/badge.svg)](https://github.com/gaalferov/books-api/actions/workflows/ci.yml)

A Laravel-based API for getting books info, built with Clean Architecture principles and running in a Dockerized environment.

## Table of Contents
- [Demo](#demo)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Testing](#testing)
- [Technical Details](#technical-details)


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

## Technical Details

- **PHP Version**: The application runs on **PHP 8.3** with strict types enabled.
- **Laravel Version**: Built using **Laravel 12**, adhering to modern PHP and Laravel best practices.
- **OPcache** + **CacheTool**: Enabled for optimized performance and faster execution (prod).
- **[GITHUB Actions](https://github.com/gaalferov/books-api/actions)**: CI/CD pipeline is set up using **GitHub Actions** for automated testing and deployment.
  - Run Tests for all commits and PRs.
  - Run Code Style Check for all commits and PRs.
  - Composer validation for all commits and PRs.
- **Caching**: API responses are cached using **Redis** for improved speed and reduced external API calls.
- **API Providers**: Supports multiple API providers for fetching book data, ensuring flexibility and extensibility.
- **Swagger Documentation**: Comprehensive API documentation is available via **Swagger**.
- **Dockerized Environment**: The entire application, including services like Redis, runs in **Docker** containers for easy setup and deployment.
- **Clean Architecture**: Designed with **Clean Architecture** principles, ensuring maintainability and scalability.
- **SOLID Principles**: The codebase strictly adheres to **SOLID** principles for high-quality, professional-grade code.
- **PSR-12 Coding Standard**: The project follows the **PSR-12** coding standard for consistency and readability.
- **Testing**: Fully tested.
    ```
      Tests:    42 passed (122 assertions)
    ```