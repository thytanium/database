# States console command
## db:states

This command is built for two things:
1. Create a database migration for `states` table.
2. Seed `states` database table with predefined states.

### Migration

`php artisan db:states`

The generated migration will be placed in `database/migrations` of your Laravel project.

### Seed

`php artisan db:states --seed`

Will run `Thytanium\Database\Seeders\StateSeeder` to fill your database with predefined states.

Once you have you have migrated your `states` database table you can use the very helpful [HasState](../Traits/HasState.md) trait.