## Laravel Livewire Excel Upload with Progressbar

This is sample project, that explains how to import Excel files with progress bar

Steps to run project

- `cp .env.example .env`.
- `composer install`.
- `php artisan key:generate`.
- `php artisan migrate:fresh --seed`.
- `npm install && npm run dev`.
- `php artisan serve`.

I used `QUEUE_CONNECTION` as database where we store and process the queues.

To process queue, make sure you run `php artisan queue:work` for local testing.

Login credentials used in seeder:

Email : `test@example.com`
Password: `123456`
