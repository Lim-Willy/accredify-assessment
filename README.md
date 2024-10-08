# accredify Technical Assessment

1. [Installation](#Installation)
2. [Entity Relation Diagram](#ERD)
3. [FlowChart](#Flowchart)
4. [Testing](#Testing)
5. [Additional Information](#Additional-Information)


## Installation
After Cloning the repository into your local machine, create a new .env file with the .env.example file that was provided in the repository

Although an App key is provided, it would be safer for you to regenerate it with
```bash
php artisan key:generate
```

install the dependencies with composer. 
If you do not have composer you may install it at https://getcomposer.org/download/
```bash
composer install
```
After that you may migrate and seed the database tables and user data (be sure a local MySQL database on port 3306 is active)
```bash
php artisan migrate --seed
```
The seeding will provide a user record with the credentials below
email    = test@email.com
password = 123123123

Because we uses Laravel Breeze to save time for Authentication, you will need to install npm dependencies and build it
if you do not have npm and/or node you could refer to this site to download and install
https://docs.npmjs.com/downloading-and-installing-node-js-and-npm?ref=sfeir.dev

```bash
npm install
npm run build
```
after that you may run the application by using laragon / herd, place them in their designated folders for projects and you may start using it after starting the respective application up.
if using laragon or herd to run the project usually you will be able to access the project with the url: accredify-assessment.test

note* currently the Frontend have issues serving with php artisan serve / php -S commands. Due to time constraints i could not investigate it in depth yet on the issue. 
But the postman with the API will have no issues regardless it is ran with laragon / herd or with php artisan serve.

## ERD
``` mermaid
erDiagram
    user {
        int id
        string email
        string password
        datetime email_verified_at
        string remember_token
        datetime created_at
        datetime updated_at
    }
    file_verification_results {
        int id
        int user_id
        string file_types
        string verification_Results
        string issuer_name
        string recipient_name
        string recipient_email
        int timestamp
        datetime created_at
        datetime updated_at
    }
    user || --o{file_verification_results: "has many"
```

## Flowchart
```mermaid
    flowchart TD
    A[Login Page] -->|Login Success| B(Dashboard)
    A --> |Login Failed|A
    B --> C[File Verification Page]
    C --> |Upload file| D{verify & store result}
    D -->|Correct File| E[Verified]
    D -->|missing recipient| F[invalid_recipient]
    D -->|missing issuer| G[invalid_issuer]
    D --> |tampered file| H[invalid_signature]
```

## Testing
The testing is done with PhPUnit, there are 2 kinds of testing codes that are currently available, Feature and unit testing
Feature testing include all of the FrontEnd testing for login, profile, registration .etc as part of laravel Breeze with Inertia setup
Unit Testing includes testing with the services that are involved with verifying the file uploaded into the system

to trigger all testing you may run this composer command
``` bash
composer test
```
if you wished to test a specific testCase file you may specify the file name along with the command as such
``` bash
composer test tests/Unit/FileVerificationTest.php
```
note* Because of the usage of `refreshDatabase`, the database will be wiped after running tests, so its recommended to try and run test on a different database than the one that will be used for the site, or it would be recommended to reseed the database after testing to be able to have a default user data that will be seed

## Additional Information
you may refer to the POSTMAN API documentations with this link
https://documenter.getpostman.com/view/29577252/2sA3rwNaB6

additionally the postman collection file can be obtain from this drive,
https://drive.google.com/drive/folders/1wvXell39IpRoiKpSAYlhnBTFk_72mrfQ?usp=sharing
