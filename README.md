# Personal Laravel 9 queues try out

1) Install new laravel project
2) Generate Model
```sh
php artisan make:model Sale -m
```
3) Update the model by adding a variable
```sh
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Sale extends Model
{
    use HasFactory;
    protected $guarded = [];
}
```
4) Create `sales` table by migration
5) Create route for `index` page and `post` for uploading file
6) Create controller `SaleController`
7) Create `view` for uploading page
8) Create `job`
```sh
php artisan make:job SaleCsvProcess
```
9) Setup the `job` before using it
```sh
php artisan queue:batches-table
```
10) Setup `queue`
```sh
php artisan queue:table
```
then 
```sh
php artisan migrate
```
11) Update `QUEUE_CONNECTION=sync` in `.env` file to `QUEUE_CONNECTION=database`
12) Run this command before uploading file
```sh
php artisan queue:work
```
