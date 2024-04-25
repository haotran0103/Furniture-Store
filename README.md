# Furniture-Store


Welcome to Furniture-Store! This project is a modern web application for managing a furniture store, built with Laravel and Vue.js.

## Installation

### Prerequisites

- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) (npm 10.2.5)
- [Laravel](https://laravel.com/) (Laravel Framework 10.44.0)

### Getting Started

1. Clone the repository:

    ```bash
    git clone https://github.com/your-username/Furniture-Store.git
    ```

2. Navigate to the project directory:

    ```bash
    cd Furniture-Store
    ```

3. Install PHP dependencies:

    ```bash
    composer install
    ```

4. Install JavaScript dependencies:

    ```bash
    npm install
    ```

5. Compile assets:

    ```bash
    npm run dev
    ```

6. Set up environment variables by creating a `.env` file and filling in your configuration.

7. Generate an application key:

    ```bash
    php artisan key:generate
    ```

8. Run database migrations and seeders:

    ```bash
    php artisan migrate --seed
    ```

9. Start the development server:

    ```bash
    php artisan serve
    ```

10. Visit `http://localhost:8000` in your browser to view the application.

## Features

- **User Management:** Registration, login, and user authentication, User role assignment (Admin, Seller, Buyer).
- **Product Management:** Adding, editing, deleting products, Categorizing products by category, Inventory management including quantity and sold products, Integrating product tagging system, facilitating user search and filtering by tags.
- **Category Management:** Adding, editing, deleting product categories, Hierarchical categorization of products (parent categories, subcategories).
- **Order Management:** Creating, viewing details, and updating order status, Managing customer information related to orders.
- **Cart Management:** Adding products to cart, Updating product quantities in the cart, Removing products from the cart.
- **Slider Management:** Adding, editing, deleting images and information on the homepage slider.
- **Product Search and Filtering:** Enabling users to search products by name, category, tags, Filtering products by price, category, tags.
- **Promotions and Discount Codes Management:** Creating and managing promotional programs, discount codes.
- **User Activity Tracking:** Storing and analyzing user activities on the website to enhance user experience and security.


## Screenshots

Include screenshots of your application here to showcase its user interface.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)
