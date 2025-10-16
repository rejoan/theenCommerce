# Order Fulfillment System

Laravel system powers a multi‑vendor commerce platform
1. The order and its items are saved atomically (with rollback on failure).
2. The application triggers domain events and observers.
3. Independent listeners update balances, log audit trails, and enqueue asynchronous jobs

## Getting Started

**To get started, follow the instructions below**

To get a local copy up and running follow these simple steps.

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
http://127.0.0.1:8000/api/auth/login
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


## Authors

👤 **Rejoanul Alam**

- Github: [@githubhandle](https://github.com/rejoan)
