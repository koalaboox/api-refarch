Koalaboox API reference architecture
===

This example application is under the [MIT license](LICENSE), so you can use it or part of it freely as a base for your own implementation.

## Documentation
- How to connect: [connect.koalaboox.com/documentation](https://connect.koalaboox.com/documentation)
- API reference: [developer.koalaboox.com](http://developer.koalaboox.com)
- API dashboard: [connect.koalaboox.com/dashboard](https://connect.koalaboox.com/dashboard)

## Installation
**Prerequisites**: First, you need to create an application in the [API dashboard](https://connect.koalaboox.com/dashboard). 
Copy your application ID and your application password for later use.

Clone the repository:
```bash
$ git clone https://github.com/koalaboox/api-refarch.git 
$ cd api-refarch
```

Install the dependencies:
```bash
$ composer install
```

Edit the `.env` file:
```ini
KOALABOOX_API_URL=https://connect.koalaboox.com
KOALABOOX_API_APP_ID=[YOUR APPLICATION ID]
KOALABOOX_API_APP_SECRET=[YOUR APPLICATION SECRET]
```

Launch the test server:
```bash
$ php artisan serve
```