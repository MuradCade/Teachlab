# Welcome to TeachLab

---

TeachLab is a platform that helps teachers manage essential tasks such as attendance tracking and assignment collection.

Currently, TeachLab is on version 1.0.

## What Problem Does This Software Address?

Online classes can be overwhelming for teachers, as they involve significant administrative work beyond teaching. Educators must track attendance and manage assignment submissions, which can be time-consuming. TeachLab helps teachers organize student information and efficiently monitor attendance and assignment submissions, allowing them to focus more on teaching while we handle the administrative workload.

# License

TeachLab is licensed under the MIT License. Feel free to download the project and use it locally.

## How to Set Up TeachLab

1. Clone this repository:
  
  ```bash
   git clone https://github.com/MuradCade/Teachlab.git
  ```
  
2. Import the database file into your MySQL database.
  
3. The `Model` folder contains a file called `dbcon.php`, which handles database connections. To change the configuration, update the `env.php` file located in the same directory as `dbcon.php`.
  
4. To send emails, create a Google app and insert your email and secret code into the `env.php` file. The environment variables are named as follows:
  
  ```bash
  EMAIL_GOOGLE_APP_SECRET_KEY='your secret key'
  EMAIL='your email'
  ```
  
5. Install all packages using Composer by running the following command:
  
  ```bash
  composer install
  ```
