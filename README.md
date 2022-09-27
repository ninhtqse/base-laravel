## VERSION
- LARAVEL: 8.84.23
- PHP: 8.1
- Mysql: 8.0
## START
    1. cp .env.example .env
    2. composer update
    3. php artisan passport:keys

## Generate Coverage Testing

REQUIRED
- xdebug.mode=coverage

RUN 
- ./vendor/bin/phpunit --coverage-html coverage

## PACKAGE SUPPORTS

Package support API

- [ninhtqse/api-consumer](https://packagist.org/packages/ninhtqse/api-consumer).
- [ninhtqse/architect](https://packagist.org/packages/ninhtqse/architect).
- [ninhtqse/bruno](https://packagist.org/packages/ninhtqse/bruno).
- [ninhtqse/distributed-laravel](https://packagist.org/packages/ninhtqse/distributed-laravel).
- [ninhtqse/genie](https://packagist.org/packages/ninhtqse/genie).
- [ninhtqse/heimdal](https://packagist.org/packages/ninhtqse/heimdal).
- [laravel/passport](https://packagist.org/packages/laravel/passport).
- [squizlabs/php_codesniffer](https://packagist.org/packages/squizlabs/php_codesniffer).
- [sendgrid/sendgrid](https://packagist.org/packages/sendgrid/sendgrid).
- [maatwebsite/excel](https://packagist.org/packages/maatwebsite/excel).
- [predis/predis](https://packagist.org/packages/predis/predis).
- [spatie/laravel-permission](https://packagist.org/packages/spatie/laravel-permission).
- [barryvdh/laravel-debugbar](https://packagist.org/packages/barryvdh/laravel-debugbar).

## RUN TESTING CONVENSION

- php artisan convension {folder or file}

Auto Fixed 

- php artisan convension {folder or file} --fix

## DOCUMENT WIKI
- cd /public/wiki/main && npm start
- {domain}/docs/wiki

## DOCUMENT DATABASE
- {domain}/docs/database

## RULE CODE

| #   | Name          | Rule        |
| --- | ------------- | ----------- |
| 1   | Name relationship model | + Tên hàm: snake_case <br> + Với quan hệ 1 vs n: tên sẽ chứa s ở cuối hàm <br> + Với quan hệ 1 vs 1 tên không chứa s |
| 2   | Name Class      | PascalCase          |
| 3   | Name Function   | - Type: camelCase <br>- CRUD <br>+ Lấy thông tin cả bảng: getAll() <br>+ Lấy thông tin 1 bản ghi: getById()<br>+ Tạo mới: create()<br>+ Cập nhật thông tin: update() <br>+ Xóa thông tin: delete() <br> - Helper Function <br> + Cú pháp: snake_case         |
| 4   | Name Variable   | camelCase           |
| 5   | Name Route API   | Đặt theo tên table     |
| 6   | Tên file migrate   | - Đặt tên theo cú pháp sau: <br> + create_{table_name}_table   |
| 7   | Tên file Seeder   | - Đặt tên theo cú pháp sau: <br> + {TableName}Seeder   |
| 8   | Tên file Blade   | - Đặt tên theo cú pháp: snake_case   |

## RULE FLOW GIT
| #   | Name          | Rule        |
| --- | ------------- | ----------- |
| 1   | Convension | Chạy kiểm tra Convension trước khi push code  |
| 2   | Branh name | - Khi phát triển: <br> + feature/{mã màn hình}/{id_task} <br> - Khi cần fix trực tiếp trên staging: <br> + hotfix/{mã_màn_hình}/{id_task} <br> - Khi fix bug: <br> + fixbug/{mã_màn_hình}/{id_bug} |
