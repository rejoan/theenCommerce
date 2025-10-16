# Order Fulfillment System

Laravel system powers a multi‑vendor commerce platform
1. The order and its items are saved atomically (with rollback on failure).
2. The application triggers domain events and observers.
3. Independent listeners update balances, log audit trails, and enqueue asynchronous jobs

## Getting Started

To get up and running follow these simple steps.

- git clone the repo

```
git@github.com:rejoan/theenCommerce.git && cd theenCommerce
```

- Install all the required packages with

```
composer install
```

- Rename `.env.example` as `.env` 

```
php artisan key:generate
php artisan migrate
composer require nesbot/carbon
php artisan install:api
php artisan migrate:fresh --seed
php artisan serve
```

Then send POST request to
```
base_url/api/auth/login
params
email:buyer_rejoan@example.com
password:123456
```

### response data
```
{
  "message": "Login sucessfully",
  "error": false,
  "data": {
    "user_email": "buyer_rejoan@example.com",
    "token": "token"
  }
}
```
Now using above returned token you may use in endpoints as bearer token

### How to place an order
Use following format data and send through postman to `base_url/api/orders` [dont forget to use bearer token in header for auth]
```
{
"buyer_id": 2,
"items": [
    {"product_id": 1, "quantity": 2},
    {"product_id": 2, "quantity": 1}
  ]
}
```
### Response data

```
{
  "message": "Order placed successfully.",
  "order_id": 5,
  "order_number": "ORD-1760645398-2X8D52"
}
```

Queue worker needs to be started as 
```
php artisan queue:work
```

## Authors

👤 **Rejoanul Alam**

- Github: [@githubhandle](https://github.com/rejoan)
