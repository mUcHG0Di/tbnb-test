# Product Management System

This project is a technical test implementation for a job offer. It's a CRUD (Create, Read, Update, Delete) system for managing products, built with Laravel, Inertia, and Vue.js. The system includes a bulk update feature and implements proper validations.

## Features

- Full CRUD operations for products
- Bulk update functionality
- Form validations
- Responsive design

## Tech Stack

- Backend: Laravel
- Frontend: Vue.js
- Routing: Inertia.js
- Styling: Tailwind CSS (optional, adjust if you're using a different CSS framework)

## Prerequisites

- PHP >= 7.4
- Composer
- Node.js >= 14.x
- NPM or Yarn

## Installation

1. Clone the repository:
git clone https://github.com/your-username/product-management-system.git

2. Navigate to the project directory:
cd product-management-system

4. Install JavaScript dependencies:
npm install

5. Copy the .env.example file to .env and configure your environment variables:
cp .env.example .env

6. Generate an application key:
php artisan key:generate

7. Run database migrations:
php artisan migrate

8. (Optional) Seed the database with sample data:
php artisan db:seed

## Running the Application

1. Start the Laravel development server:
php artisan serve

2. In a separate terminal, compile and hot-reload frontend assets:
npm run dev

3. Visit `http://localhost:8000` in your browser to access the application.

## Usage

### Product CRUD

- Navigate to the Products page to view all products
- Use the "Add Product" button to create a new product
- Click on a product to view its details
- Use the "Edit" button to modify a product
- Use the "Delete" button to remove a product

### Bulk Update

1. On the Products page, select multiple products using the checkboxes
2. Click the "Bulk Update" button
3. Modify the desired fields in the bulk update form
4. Submit the form to update all selected products simultaneously

## Validation

The application implements the following validations:

- Product name: Required, maximum 255 characters
- Description: Optional, maximum 1000 characters
- Price: Required, numeric, minimum value 0
- Stock: Required, integer, minimum value 0

## Testing

Run the test suite with:
php artisan test

## Contributing

This project is a technical test and is not open for contributions. However, feedback and suggestions are welcome.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
