# Plesk Panel Light

Plesk Panel Light is a lightweight, modular management tool designed to interact with the Plesk API. This project was developed as part of a job application task and serves as a foundation for building a more comprehensive Plesk management panel.

---

## Features

### Task Fulfillment

-   **Create new domains** via the Plesk API.
-   **List existing domains** with details:
    -   Domain name
    -   Creation date
    -   Status.

### Modular Design

-   The project is designed to allow future extensions and integration of additional features.
-   Built with a focus on flexibility and reusability.

### RESTful API

-   All core functionalities are accessible via a RESTful API.
-   The API is fully documented using Swagger, providing an interactive interface for testing and exploration.

### Technology Stack

-   **Backend**: Laravel with Sanctum for authentication.
-   **Frontend**: Vue.js with Inertia.js for seamless single-page application behavior.
-   **Styling**: Tailwind CSS for responsive and modern UI design.
-   **API Documentation**: Swagger for interactive API exploration.
-   **Containerization**: Docker and Docker Compose for a consistent development environment.

---

## Challenges and Solutions

### Transaction-Safe Domain Creation

The Plesk API processes domain creation and user creation in separate steps. If user data is invalid, the domain remains, leading to inconsistencies.

**Solution**:

-   The `webspace->create` method was extended to include an optional transaction-safe mode.
-   If user creation fails, the domain is automatically deleted to maintain consistency.

### Domain Status Changes

Plesk handles status changes differently depending on whether they are made via the API or directly in the GUI.

**Solution**:

-   A status switch was implemented to ensure consistent handling of status changes through the API.
-   The system is designed to manage status exclusively via Plesk Panel Light.

---

## Vision: Extendability Through Plugins

A long-term vision for Plesk Panel Light includes converting modules, tests, and frontend components into standalone plugins. These plugins could:

-   Be installed, activated, or updated via an integrated store.
-   Allow users to tailor the system to their specific needs.

This approach ensures that the panel remains modular, easy to maintain, and extendable without impacting the core structure.

---

## Installation and Setup

### Using Docker Compose

1. **Clone the Repository**:

    ```bash
    git clone <repository-url>
    cd plesk-panel-light
    ```

2. **Start the Development Environment**:

    ```bash
    docker-compose up -d
    ```

3. **Run Migrations**:

    ```bash
    docker-compose exec app php artisan migrate
    ```

4. **Start Frontend Development Server**:

    ```bash
    docker-compose exec app npm run dev --watch
    ```

5. **Create a User**:

    - Open `http://localhost:8087/register` in your browser to create an initial user.
    - For production environments, restrict access to `/register` via the reverse proxy.

    > **Tip:** For the current version, applying basic authentication to `/register` is a pragmatic solution. In future versions, admin account creation will be possible through the `.env` file, making `/register` accessible only to logged-in users.

The application will be accessible at `http://localhost:8087`.

### Manual Installation

1. **Clone the Repository**:

    ```bash
    git clone <repository-url>
    cd plesk-panel-light
    ```

2. **Install Dependencies**:

    ```bash
    composer install
    npm install
    ```

3. **Set Up Environment**:

    - Copy `.env.example` to `.env`:
        ```bash
        cp .env.example .env
        ```
    - Update the `.env` file with your database and Plesk API credentials.

4. **Run Migrations**:

    ```bash
    php artisan migrate
    ```

5. **Start Development Servers**:

    - Backend: Run Laravel's server:
        ```bash
        php artisan serve
        ```
    - Frontend: Compile and watch assets:
        ```bash
        npm run dev
        ```

6. **Create a User**:

    - Open `http://localhost:8000/register` in your browser to create an initial user.
    - Restrict access to `/register` via the reverse proxy in production environments.

    > **Tip:** For now, applying basic authentication to `/register` is a pragmatic solution. Future versions will allow admin account creation through the `.env` file.

---

## Usage

### Access the Dashboard

Once the application is running, navigate to `http://localhost:8087` (if using Docker Compose) or `http://localhost:8000` (manual setup) to access the dashboard.

### API Documentation

Visit `http://localhost:8087/api/documentation` (Docker Compose) or `http://localhost:8000/api/documentation` (manual setup) to view the Swagger UI and explore the available API endpoints.

---

## Deploy to Production with Plesk

After deploying the latest version of the application using the integrated Git deployment in Plesk, perform the following steps to optimize and prepare the application for production:

1. **In the Composer Interface**:

    - Run: `install --optimize-autoloader --no-dev`

2. **In the PHP Artisan Interface**:

    - Run the following commands:
        - `config:cache`
        - `route:cache`
        - `view:cache`
        - `optimize`
        - `migrate --force`

3. **In the Node.js NPM Interface**:

    - Run: `run-scripts build`

4. **In the PHP Interface**:
    - Ensure your PHP settings and extensions are configured for production, including appropriate memory limits and error logging.

---
