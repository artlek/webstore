# Webstore application
Web store is a simple online store application.
You can contribute or develop it, but do not use it for commercial purpose. 
The application is created based on Symfony 6 using MySQL database.
## Features
- adding products to product list,
- browsing products,
- adding products to the cart,
- placing an order for products (savin cart),
- changing order statuses,
- changing product prices and descriptions,
- updating product photos,
- showing order calculating,
- managing VAT rates and units of measure.
## How to use it
Is recommended use Docker to try this app.
1. Install Docker
2. Download this repository and unzip it
3. Open app folder in terminal
4. Build image in Docker by command: <code>docker-compose build</code> and then <code>docker-compose up -d</code>
5. Open app container by command: <code>docker exec -it webstore-fpm-1 bash</code>
6. Install vendors: <code>composer install</code>
7. Make database migrations: <code>php bin/console doctrine:migrations:migrate --no-interaction</code>
8. Load fixtures (optional): <code>php bin/console doctrine:fixtures:load --no-interaction</code>
9. Open localhost:10302 in your webbrowser

version: 1.0.0