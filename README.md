## This is the project for url shortener. it will take input a url and genearte a short url. when you click on short url it will redirect to orginal url.
## Project installation steps

- colne the project
- create .env file form .env.example
- generate new key using artisan comand
- modify the db configuration and app url on .env file
- run the command 

    ```composer update ```

    ```nmp update ```

    ```php artisan migrate ```

    ```php artisan db:seed --class=UserSeeder ```

- Start the application using command

    ```php artisan serve ```

    ```npm run dev ```