@echo off
echo Setting up AgriconnectKE...

REM Check if Composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo Error: Composer is not installed!
    echo Please install Composer from https://getcomposer.org/
    exit /b 1
)

REM Check if PHP is installed
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo Error: PHP is not installed!
    echo Please install PHP from https://windows.php.net/download/
    exit /b 1
)

REM Check if Node.js is installed
where node >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo Error: Node.js is not installed!
    echo Please install Node.js from https://nodejs.org/
    exit /b 1
)

echo Installing Composer dependencies...
composer install

echo Installing NPM packages...
npm install

echo Building frontend assets...
npm run dev

echo Creating .env file if it doesn't exist...
if not exist .env (
    copy .env.example .env
    php artisan key:generate
)

echo Setting up storage link...
php artisan storage:link

echo Running database migrations...
php artisan migrate

echo Seeding the database...
php artisan db:seed

echo.
echo Setup completed!
echo.
echo To start the application:
echo 1. Make sure MySQL is running
echo 2. Run 'php artisan serve'
echo 3. Visit http://localhost:8000 in your browser
echo.