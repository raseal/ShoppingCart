# Shopping Cart
## Application description
Simple shopping cart exercise using decoupled **Symfony**, **DDD** and **CQRS**.

You can interact with this app via the following use-cases:
| Endpoint                      | Verb   | Descriptions                                                                                     |
|-------------------------------|--------|--------------------------------------------------------------------------------------------------|
| products                      | POST   | Create a product (you must specify in the payload the `name`, `price` and `offer_price` values)  |
| products/{id}                 | GET    | Find a product that matches with the provided identifier                                         |
| products/                     | GET    | Get all products                                                                                 |
| carts/                        | POST   | Create an empty cart                                                                             |
| carts/{id}                    | GET    | Find a cart that matches with the provided identifier                                            |
| carts/                        | GET    | Get all carts                                                                                    |
| carts/{cartId}/items/         | POST   | Add a product (you must specify in the payload the `itemId` and the `quantity`)                  |  
| carts/{cartId}/items/{itemId} | PUT    | Update an added product (you must specify in the payload the `quantity`)                         |
| carts/{cartId}/items/{itemId} | DELETE | Delete an added product                                                                          |

## Running the application
Run `make build` first, in order to build the environment and install all the dependencies. Then you can use any of the following:
- `make mimi`: This command will create all database tables
- `make start`: This command will start the app
- `make stop`: This command will stop the app
- `make shell`: This command will open a shell inside the `app-container`
- `make mysql`: This command will open a shell inside the `mysql` container

