# Shopping Cart
## Application description
Simple shopping cart exercise using decoupled **Symfony**, **DDD** and **CQRS**.

The cart has the following restrictions:
- Only allows 10 different products
- You cannot buy more than 50 units of the same product

You can interact with this app via the following use-cases:
| Endpoint                         | Verb   | Descriptions                                                                                     |
|----------------------------------|--------|--------------------------------------------------------------------------------------------------|
| products                         | POST   | Create a product (you must specify in the payload the `name`, `price` and `offer_price` values)  |
| products/{id}                    | GET    | Find a product that matches with the provided identifier                                         |
| products                         | GET    | Get all products                                                                                 |
| carts                            | POST   | Create an empty cart                                                                             |
| carts/{id}                       | GET    | Find a cart that matches with the provided identifier                                            |
| carts                            | GET    | Get all carts                                                                                    |
| carts/{cartId}/items             | POST   | Add a product (you must specify in the payload the `product_id` and the `quantity`)              |

## Running the application
Run `make build` first, in order to build the environment and install all the dependencies. Then you can use any of the following:
- `make mimi`: This command will create all database tables
- `make start`: This command will start the app
- `make stop`: This command will stop the app
- `make shell`: This command will open a shell inside the `app-container`
- `make mysql`: This command will open a shell inside the `mysql` container

