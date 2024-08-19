Countries REST API
This project is a REST API that consumes data from the REST Countries API, providing endpoints for retrieving information about countries, regions, and languages. The application also includes caching, HTTP request handling, and basic authentication.

Endpoints
GET /api/countries
Returns a list of all countries.

GET /api/countries/:code
Returns details of a specific country by its code.

GET /api/regions
Returns a list of all regions.

GET /api/languages
Returns a list of all languages.

Features
Caching:
The application caches the data locally for an hour using a cache component to reduce API calls and improve performance.

HTTP Requests:
Guzzle is used to simplify sending HTTP requests to the REST Countries API.

Basic Authentication:
1. HTTPS enabled
2. Basic authentication is implemented to secure the API.
3rd Party integration options can also be considered

Installation
To set up this project locally, follow these steps:

Clone the repository:

Copy code
git clone <repository-url>
Navigate to the project directory:

Copy code
cd <project-directory>
Install the dependencies:

Copy code
composer install
Usage
Once the installation is complete, you can start the application and access the endpoints via your preferred API client.

This README should give a clear overview of your project and provide necessary setup instructions for others.
