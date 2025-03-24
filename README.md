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
