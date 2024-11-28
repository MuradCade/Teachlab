# Welcome To TeachLab

---

TeachLab is a platform that help teachers do thier work such as attendance and assignment collection.

Currently TeachLab is on version 1.0

## What is the problem this software addressing

online class are every consuming for the teachers , becauase it requires alot of work beside teaching , teachers have to keep track on attendance and assignment submissions and that will take alot of time and effort , teachlab helps teachers to organize student informations and keep track of thier attendance and assignment submission, we help teacher to focus on teaching while we do the heavy work.

# License

TeachLab License is MIT, feel free to download the project and use it locally.

## How to setup TeachLab

1. Clone this reposotory
  
  ```git
  git clone https://github.com/MuradCade/Teachlab.git
  ```
  
2. Take the database file and import it in your mysql database
  
3. Model folder contains file called dbcon.php , it contains database connection in oder to change the configuration go to env.php file its inside the directory as dbcon.php file, change database configuration.
  
4. In order to send email you will need to create google app name and insert your email and secrect code in env.php file environment variable are name the following :-
  
  ```env
  EMAIL_GOGLE_APP_SECRET_KEY = 'your secret key'
  EMAIL = 'your email'
  ```
  
5. install all packages using composer, write the following command.
  
  ```composer
  composer install
  ```
  

## Features of TeachLab

1. send email when creating account and when recoving account
  
2. handling excel files , importing them and exporting them using phpspreadsheet package.
