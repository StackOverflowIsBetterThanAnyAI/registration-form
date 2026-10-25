# Requirements

## 1. Database Setup

Create a MySQL database with a `users` table containing the following columns:

- `id`
- `username`
- `password`
- `created_at`
- `age`

## 2. Registration (`register`)

- Allow new users to register.
- Check if the chosen username is already taken.
- Sanitize all form inputs.
- Forward successful registrations to the home screen.

## 3. Login (`login`)

- Authenticate user credentials against hashed passwords stored in the database.
- Validate all form inputs.
- Forward regular users to the home screen upon successful login.
- Forward admin user to the admin screen upon successful login.

## 4. Home Screen (`home`)

- Greet the logged-in user.
- Allow regular users to update their own age.
- Provide a logout option.

## 5. Admin Screen (`admin`)

- Display all existing records from the `users` table.
- Provide functionality for the admin to delete users from the database.
- Provide a logout option.

## 6. Architecture

- Use templates for views to keep layout components reusable.
