# Hamsam Project

## Description

This Laravel project is designed to be fully Dockerized, incorporating MySQL for relational data storage, Redis for caching and session management, and MongoDB for NoSQL use cases. It aims to provide a robust, scalable, and easy-to-deploy framework for web applications.

## Prerequisites

Before you begin, ensure you have installed all of the following on your development machine:
- Docker
- Docker Compose
- Git (optional, for cloning the repository)

## Setup Instructions

1. **Clone the Repository** (if applicable)

   If the project is hosted in a Git repository, clone it using:

   git clone http://git.trustin.ir/sam/hamsam/backend.git
   cd backend

2. **Environment Configuration**

   Copy the `.env.example` file to a new file named `.env` in the `src` directory. Adjust the environment variables in the `.env` file according to your local or production settings, particularly for database connections and other services like Redis and MongoDB.
   
   cp src/.env.example src/.env


3. **Build and Start the Docker Containers**

   From the root of your project directory, run the following command to build and start all services defined in your `docker-compose.yml`:

   docker-compose up --build -d


This command builds the Docker images if they don't exist and starts the containers in detached mode.

4. **Install Dependencies**

   After the containers are up and running, install the Laravel dependencies via Composer:
   docker-compose exec app composer install


5. **Generate Application Key**

   Generate a new Laravel application key:
   docker-compose exec app php artisan key:generate


6. **Run Migrations**

   Migrate your databases to set up your initial schema:
   docker-compose exec app php artisan migrate


## Deploying Your Application

To deploy your application, ensure all your services are configured correctly in `docker-compose.yml`, including environment variables for production in your `.env` file.

For cloud deployments, you might need to push your Docker images to a registry and update your service definitions to pull from that registry. Ensure your cloud environment has Docker and Docker Compose installed and follow the cloud provider's instructions to deploy Dockerized applications.

## Additional Notes

- To stop and remove all containers, use `docker-compose down`.
- For production deployments, ensure you are not exposing sensitive ports or data to the public.
- Always secure your application with HTTPS in production by configuring your reverse proxy or load balancer accordingly.

## Contributing

We welcome contributions! Please feel free to submit pull requests or open issues for improvements or bug fixes.

## License

Specify your project's license here, or state if it's proprietary and closed source.
