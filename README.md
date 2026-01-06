# Instructions

## Prerequisites

1. Run `composer install`
2. Run `npm install`
3. Rename `.env.example` to `.env`
4. Set queue connection to database - `QUEUE_CONNECTION=database`
5. Set APP_URL=http://localhost:8000
6. Generate APP_KEY - `php artisan key:generate`
7. Create a new SQLite database file inside the database folder name it `database.sqlite`
8. Run migration - `php artisan migrate`
9. Run the application - `php artisan serve` or `composer run dev`
10. Make sure queue worker is running - `php artisan queue:listen or php artisan queue:work`

```
Inside the project root directory find the sample CSV file: students_import_15000_records.csv use it to test the import functionality
```
