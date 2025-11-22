# AgriconnectKE

AgriconnectKE is a comprehensive web platform designed to bridge the gap between farmers, buyers, and drivers in the agricultural supply chain. It facilitates a seamless marketplace for agricultural products, enabling farmers to sell directly to buyers and coordinate deliveries through registered drivers.

## Features

### 🧑‍🌾 For Farmers
- **Dashboard**: Overview of sales, products, and bids.
- **Product Management**: Create, update, and delete product listings.
- **Bid Management**: View, accept, or reject bids from buyers.
- **Sales Tracking**: Monitor sales performance and order history.

### 🛒 For Buyers
- **Marketplace**: Browse and search for fresh agricultural products.
- **Bidding System**: Place bids on products and negotiate prices.
- **Shopping Cart**: Manage selected items and proceed to checkout.
- **Secure Checkout**: Process payments and finalize orders.
- **Order Tracking**: Track the status of orders and deliveries in real-time.

### 🚚 For Drivers
- **Delivery Dashboard**: View assigned deliveries and routes.
- **Status Updates**: Update delivery status (e.g., Picked Up, Delivered).
- **Location Tracking**: Share real-time location for accurate tracking.
- **Availability**: Toggle availability status for new delivery assignments.

### 🛡️ For Admins
- **System Dashboard**: Monitor overall platform activity and statistics.
- **User Management**: Manage farmer, buyer, and driver accounts.
- **Oversight**: View all products, orders, and deliveries.

## Technology Stack

- **Framework**: [Laravel 12](https://laravel.com)
- **Language**: PHP 8.2+
- **Frontend**: Blade Templates, Vanilla CSS / Bootstrap
- **Database**: MySQL / SQLite
- **Testing**: PHPUnit

## Installation

Follow these steps to set up the project locally:

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/AgriconnectKE.git
   cd AgriconnectKE
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies and build assets**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Setup**
   Copy the example environment file and configure your database settings:
   ```bash
   cp .env.example .env
   ```
   Update `.env` with your database credentials (DB_DATABASE, DB_USERNAME, etc.).

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Run Migrations**
   Set up the database tables:
   ```bash
   php artisan migrate
   ```

7. **Serve the Application**
   ```bash
   php artisan serve
   ```
   The application will be available at `http://localhost:8000`.

## Running Tests

The project includes a comprehensive test suite covering features, unit tests, and authentication flows.

To run all tests:
```bash
php artisan test
```

To run a specific test file:
```bash
php artisan test tests/Feature/AuthenticationTest.php
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
