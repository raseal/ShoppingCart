# Shopping Cart
## Application description
Simple shopping cart exercise using decoupled **Symfony**, **DDD** and **CQRS**.

You can interact with this app via the following use-cases:
| Endpoint      | Verb | Descriptions                                                                                     |
|---------------|------|--------------------------------------------------------------------------------------------------|
| products      | POST | Create a product (you must specify in the payload the `name`, `price` and `offer_price` values)  |
| products/{id} | GET  | Find a product that matches with the provided identifier                                         |
| products/     | GET  | Get all products                                                                                 |
| carts/        | POST | Create an empty cart                                                                             |
| carts/{id}    | GET  | Get all available carts                                                                          |

## Running the application
Run `make build` first, in order to build the environment and install all the dependencies. Then you can use any of the following:
- `make mimi`: This command will create all database tables
- `make start`: This command will start the app
- `make stop`: This command will stop the app
- `make shell`: This command will open a shell inside the `app-container`
- `make mysql`: This command will open a shell inside the `mysql` container

