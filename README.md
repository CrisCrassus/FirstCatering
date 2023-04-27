<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# First Catering
This repository consists of an API made using Laravel 10.  

## Packages & Versions

- **Laravel 10.9.0**
- **Tailwind 3.3.2**
- **Vue JS 3.2.47**
- **Vite 4.3.3**

## Setup

- Once the repository has been cloned, run composer install
- Upon completion, run php artisan migrate --seed
- Once tables have been migrated and seeded, this project is ready to start using.

## Using the api
- Refer to /routes/api.php for a list of available routes.
- The majority of routes require authentication and therefore need a bearer token in order to access.
- Token's can be retrieved by hitting the endpoint 'api/sign-in' with an appropriate card identifier (found in the Cards table)
- Once a card has been authenticated, please hit the route 'api/pin-verification', proving a user id and the associated porrect pin number.  Once this is verified as correct, a personal access token will be created and will a lifespan of 15 minutes.
