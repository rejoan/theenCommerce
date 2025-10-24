## Laravel E-Commerce platform [Architechtural Project]

- The order and its items are saved atomically (with rollback on failure)
- Triggers OrderPlaced events and observers
- Listeners update seller balances, log audit, and enqueue asynchronous jobs
- Product & Order repository used as DB operation with DB seeder for basic test
- No direct DB calls inside controllers
- `/api/orders` API created to take order with all validation
- Create Order and OrderItems within a single DB transaction
- Dispatches GenerateInvoiceJob for invoice file under `storage/app/private/invoices/` & retry 3 times gracefully
- Laravel Sanctum for API auth
- Policies to ensure orders view authorization

## Getting Started

To get up and running follow these simple steps.

- git clone the repo

```
git clone git@github.com:rejoan/theenCommerce.git && cd theenCommerce
```

- Install all the required packages with

```
composer install
```

- Rename `.env.example` as `.env` and make a DB name `theencommerce`

```
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Then send POST request to (using postman)
```
base_url/api/auth/login
params
email:take_mail_from_db
password:123456
role:seller_or_buyer
```

### response
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
### response

```
{
  "message": "Order placed successfully.",
  "order_id": 5,
  "order_number": "ORD-1760645398-2X8D52"
}
```


### How to view an order details by seller [Auth with Policy]
Use GET method and send request to postman `base_url/api/orders/{order}`. Replace `{order}` by an order ID from DB

### response (if seller logged in and bearer token used in header)

```
{
  "id": 7,
  "order_number": "ORD-1760727644-R7FOFK",
  "user_id": 2,
  "total_amount": "694.00",
  "status": "pending",
  "invoice_generated_at": null,
   ....
  "items": [
    {
      "id": 13,
  ....
```

Queue worker needs to be started as 
```
php artisan queue:work
```

If queue Job not work or failed then restart required as 
```
php artisan queue:restart
```

Run following custom artisan command (cronjob possible) for generate invoice (Remember payment status in DB `orders` table should `paid`)
```
php artisan orders:process‐invoices
```