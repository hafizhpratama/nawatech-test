
# Nawatech-Test
## Installation

Clone the project

```bash
  git clone https://github.com/hafizhpratama/nawatech-test.git
```

Go to the project directory

```bash
  cd nawatech-test
```

Install dependencies

```bash
  composer install
```

Copy the example env file and make the required configuration changes in the .env file


```bash
  cp .env.example .env
```

Generate a new application key


```bash
   php artisan key:generate
```

Run the database migrations (Set the database connection in .env before migrating)

```bash
    php artisan migrate
```

Run the database seeder

```bash
    php artisan db:seed --class=UsersOrdersSeeder
```

Start the local development server

```bash
    php artisan serve
```

You can now access the server at http://localhost:8000


## API Endpoints Project 1

The following endpoints are available in the API:

| Method | Endpoint     | Description                |Required Parameters   |
| :-------- | :------- | :------------------------- |:------------|
| POST | `/login` | Login user and get token | `email, password` |
| POST | `/register` | Register a new user | `name, email, password` |
| POST | `/orders` | Place a new order | `delivery_address, motorcycle, quantity, total_price` |
| POST | `/orders/cancel/{id}` | Cancel an existing order | `id` |
| DELETE | `/orders/delete/{id}` | Delete an existing order | `id` |
| GET | `/orders/export/csv/{id}` | Export an order to CSV file | `id` |

#### Authentication
Authentication is done using Laravel Sanctum. To access authenticated endpoints, include the Authorization header with the value Bearer {token}, where {token} is the access token returned by the /login or /register endpoints.

```bash
curl -X POST \
    http://localhost:8000/api/orders \
    -H 'Authorization: Bearer {token}' \
    -d '{
          "delivery_address": "123 Main St",
          "motorcycle": "Honda",
          "quantity": 1,
          "total_price": 100
        }'
```

        
## Documentation

[Database Documentation](https://drive.google.com/file/d/1vIwCF9HLJ4Bg_UdCE--ViLFHXXD59d_U/view)

[API Documentation](https://drive.google.com/file/d/1mZB0KsvlC43EwCWS6gbLDTPMGJLYxsmk/view)

## API Endpoints Project 2

The following endpoints are available in the API:

| Method | Endpoint     | Description                |
| :-------- | :------- | :------------------------- |
| GET | `/bookings` | manipulated json to your API |
