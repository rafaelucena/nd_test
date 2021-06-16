# Secure Information Storage REST API

### Project setup

* Add `secure-storage.localhost` to your `/etc/hosts`: `127.0.0.1 secure-storage.localhost`

* Run `make init` to initialize project

* Open in browser: http://secure-storage.localhost:8000/item Should get `Full authentication is required to access this resource.` error, because first you need to make `login` call (see `postman_collection.json` or `SecurityController` for more info).

### Flush the database to default data

make flush

### Run tests

make tests

### API credentials

* User: john
* Password: maxsecure

### Postman requests collection

You can import all available API calls to Postman using `postman_collection.json` file

### Endpoints

(All of the json parameters must be passed as form-data by postman)
## List
GET - /item
Returns the list of all items, once logged

## Show
GET - /item/{id}
Returns the item json or an error if the item does not exist

## Create
POST - /item - {"data": "new item data"}
Creates a new item into the database and return an empty array if done so

## Update
PUT - /item - {"id": 1, "data", "update item data"}
Updates an existing item from the database and return an empty array if done so

## Delete
DELETE - /item/{id}
Deletes an existing item from the database and return an empty array if done so
