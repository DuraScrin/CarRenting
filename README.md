# Car Rental Application

This is a web application for car rental services built using PHP, HTML, CSS, and MariaDB. The application allows users to browse available cars, make bookings, and manage their accounts. Administrators can manage users and car inventory.

## Project Structure

```
car-rental-app
├── public
│   ├── index.php          # Entry point of the application
│   ├── css
│   │   └── styles.css     # CSS styles for the application
│   └── .htaccess          # Apache server configuration
├── src
│   ├── config
│   │   └── database.php   # Database connection settings
│   ├── controllers
│   │   ├── AuthController.php   # Handles user authentication
│   │   ├── CarController.php    # Manages car-related operations
│   │   ├── BookingController.php # Handles booking operations
│   │   └── AdminController.php   # Manages administrative tasks
│   ├── models
│   │   ├── User.php        # Represents the user entity
│   │   ├── Car.php         # Represents the car entity
│   │   └── Booking.php     # Represents the booking entity
│   ├── routes
│   │   └── web.php        # Application routes
│   └── views
│       ├── layouts
│       │   ├── header.php  # HTML header layout
│       │   └── footer.php  # HTML footer layout
│       ├── home.php        # Home page HTML
│       ├── cars.php        # Available cars page HTML
│       ├── booking.php     # Booking page HTML
│       ├── login.php       # Login page HTML
│       └── admin.php       # Admin dashboard HTML
├── database
│   └── schema.sql         # SQL schema for database setup
├── composer.json           # Composer configuration file
├── .env.example            # Environment variables template
├── .htaccess               # Application server configuration
└── README.md               # Project documentation
```

## Installation

1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Set up the database using the `schema.sql` file located in the `database` directory.
4. Configure the database connection in `src/config/database.php`.
5. Install dependencies using Composer:
   ```
   composer install
   ```
6. Upload the project files to your Linux server via FTP.

## Usage

- Access the application through your web browser by navigating to the URL where the application is hosted.
- Users can register, log in, view available cars, and make bookings.
- Administrators can manage users and car inventory through the admin dashboard.

## Shared Hosting / FTP Layout

This project now includes a `public_html` folder for shared hosting. Upload the project so the hosting account looks like this:

```
account-root/
├── public_html/
│   ├── index.php
│   ├── .htaccess
│   ├── css/
│   │   └── styles.css
│   └── images/
├── src/
├── database/
└── .env.example
```

### Deployment steps

1. Upload the entire project folders to your hosting account root via FTP.
2. Make sure `public_html` is the web-accessible folder on the server.
3. Keep `src`, `database`, and config files **outside** `public_html`.
4. Put your car images in `public_html/images/`.
5. The app entry point is `public_html/index.php`, which loads the app from `../src`.

If your host only gives access to `public_html`, you will need to upload the app folders into the hosting account root first, then keep `public_html` as the public-facing directory.

## License

This project is licensed under the MIT License.