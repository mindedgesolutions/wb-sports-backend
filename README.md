All Wb Services pages (Website and Admin panel) are prefixed with : Wb
All Wb Services components (Website and Admin panel) are prefixed with : Wbc

All Wb Sports pages (Website and Admin panel) are prefixed with: Spp
All Wb Sports components (Website and Admin panel) are prefixed with: Spc

## How to run the project

Backend:
Run composer install
Run php artisan key:generate
Run php artisan migrate
Run php artisan migrate:fresh (only when no tables)
Run php artisan db:seed to run seeders, if any

For this project:

1. php artisan db:seed --class=RoleSeeder
2. php artisan db:seed --class=UserSeeder

For passport run: php artisan passport:keys

Run: php artisan optimize:clear

php artisan passport:client --personal

Update values of the following in .env:

PASSPORT_PERSONAL_ACCESS_CLIENT_ID="client-id-value"
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET="unhashed-client-secret-value"

Run: php artisan optimize:clear

Run php artisan serve

Frontend:
Run npm install
Run npm run dev

<!-- .env file contents start --------------------- -->

APP_NAME='West Bengal Youth & Sports Department'
APP_ENV=local
APP_KEY=base64:6y6U8qcE1cJZWeHrCchqRoo2oeBAEGFrbn2rcw4XYqg=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5433
DB_DATABASE=wb_sports_db
DB_USERNAME=postgres
DB_PASSWORD=postgres

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

PASSPORT_PERSONAL_ACCESS_CLIENT_ID=9e7df460-b84d-4a48-8b5b-7bf02f857f68
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=O8QFKnfysr3ryoVTXfjvT6OKHHQbcY3tYqBiiPa2

# Looking to send emails in production? Check out our Email API/SMTP product!

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=0e64cdfb5de5ba
MAIL_PASSWORD=f4d3609bbf104d

<!-- .env file contents end --------------------- -->
