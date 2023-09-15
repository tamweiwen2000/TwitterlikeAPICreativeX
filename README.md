# TwitterlikeAPICreativeX

This project is a simple Twitter clone that allows users to register, login, create tweets, update tweets, delete tweets, and logout for the Backend API Practical Test at CreativeX Co. Ltd. (Junior Laravel Developer Job Application). It is built using the Laravel framework.

## Getting Started

To get started with this project, you will need to have the following installed:

-   PHP 7.4+
-   Composer
-   MySQL
-   Laravel 10.x

---

Once you have these installed, you can follow these steps to set up the project:

1. Clone the repository to your local machine.
2. Run composer install to install the dependencies.
3. Create a database and configure the database connection in the .env file.
4. Run the database migrations by running php artisan migrate.
5. You can now start the application by running php artisan serve.
6. The application will be available at http://localhost:8000.

## API Documentation

The API documentation can be found in this link

-   https://documenter.getpostman.com/view/29653077/2s9YC7RWiT

## Testing

The project comes with a test suite that can be run using the following command:

```
$ php artisan test

```

## TASKS

Some features are still not finished.

### Create a simple Twitter-like app API

-   [x] Ability to register (required)
-   [x] Ability to login/logout (required)
-   [x] Create/update tweet (required)
-   [x] Upload file attachment(s) to a tweet (required)
-   [x] Delete tweet
-   [ ] Follow/unfollow a user
-   [ ] List followed user's tweet
-   [ ] List suggested users follow

### Tests Done

-   [x] Ability to register
-   [x] Ability to login
-   [x] Ability to logout
-   [x] Create
-   [x] Update tweet (no attachments)
-   [ ] Update tweet (with attachments)
-   [x] Upload file attachment(s) to a tweet
-   [x] Delete tweet
-   [ ] Follow/unfollow a user
-   [ ] List followed user's tweet
-   [ ] List suggested users follow
