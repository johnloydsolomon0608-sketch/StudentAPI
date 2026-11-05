<?php
namespace Database\Seeders;

use App\Models\{Department, Employee};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [];
        foreach (['IT', 'HR', 'Finance'] as $name) {
            $departments[] = Department::firstOrCreate(['name' => $name]);
        }
        $names = [['Juan','Dela Cruz'], ['Maria','Santos'], ['John Loyd','Solomon'],
            ['Ana','Reyes'], ['Carlo','Garcia'], ['Bea','Ramos'], ['Luis','Mendoza'],
            ['Mia','Flores'], ['Nico','Cruz'], ['Joy','Dizon'], ['Paolo','Rivera'], ['Lea','Lim']];
        foreach ($names as $i => [$first, $last]) {
            Employee::firstOrCreate(['email' => 'employee'.($i + 1).'@example.test'], [
                'first_name' => $first, 'last_name' => $last, 'position' => 'Staff',
                'department_id' => $departments[$i % 3]->id,
            ]);
        }
    }
}
