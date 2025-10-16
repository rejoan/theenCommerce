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
php artisan migrate
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```


## Authors

👤 **Rejoanul Alam**

- Github: [@githubhandle](https://github.com/rejoan)
