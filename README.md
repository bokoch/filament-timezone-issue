# Filament Demo
### Demo for reproducing issue with timezone in filters
Steps to reproduce:
1. Clone this repository:
```bash 
git clone https://github.com/bokoch/filament-timezone-issue.git
```
2. Copy .env.example to .env:
```bash 
cp .env.example .env
```
3. Start docker containers:
```bash 
docker-compose up -d
```
4. Install composer dependencies:
```bash 
composer install
```
5. Run migrations:
```bash 
./vendor/bin/sail artisan migrate
```
6. Run seeders:
```bash 
./vendor/bin/sail artisan db:seed
```
7. Go to http://laravel.test/admin and login in 
with credentials:
Email: test@example.com
Password: password
8. Go to http://laravel.test/admin/posts
9. On the posts page open filters menu and pick date for
"**Published Date From**" or/and "**Published Date To**"
10. After picked values will be applied, you will see them in browser
url string and filter indicator. After that refresh the browser page with applied filters
and you will see that picked filter date is increasing
by configured timezone with each page refresh.
