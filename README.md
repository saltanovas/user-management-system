# User Management System :hammer_and_wrench:

Wow, that was a lot to learn in less than one week... :dizzy_face: The web application was made for an internship :crossed_fingers:

## Technologies used

- Backend: PHP 8.0.5, Symfony 5.2.7, Doctrine.
- Frontend: Twig, Bootstrap, jQuery.

## How to use

- Clone the repository.
- Edit a database credentials in the *.env* file.
- Run ***composer install***
- Run ***php bin/console doctrine:migrations:migrate***
- Run the web server (I was using Apache).

## Important notes

- The group can only be deleted if it does not have any users :exclamation:
- The table cell is clickable. It shows a list of users in the group or a list of groups that user belongs to :sunglasses:
- The table lacks pagination :sob:
