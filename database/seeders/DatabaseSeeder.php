<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\ViolationType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $adminRole   = Role::create(['name' => 'admin',           'display_name' => 'Administrator',    'description' => 'Full system access']);
        $officerRole = Role::create(['name' => 'officer',          'display_name' => 'Police Officer',   'description' => 'Vehicle registration and management']);
        $financeRole = Role::create(['name' => 'finance_officer',  'display_name' => 'Finance Officer',  'description' => 'Payment and revenue management']);

        // Admin user
        User::create([
            'role_id'      => $adminRole->id,
            'name'         => 'System Administrator',
            'badge_number' => 'ADM-001',
            'email'        => 'admin@divms.ug',
            'phone'        => '+256700000001',
            'rank'         => 'Superintendent',
            'department'   => 'Administration',
            'password'     => Hash::make('Admin@1234'),
            'is_active'    => true,
        ]);

        // Sample Officer
        User::create([
            'role_id'      => $officerRole->id,
            'name'         => 'Officer John Mugisha',
            'badge_number' => 'OFF-101',
            'email'        => 'officer@divms.ug',
            'phone'        => '+256700000002',
            'rank'         => 'Inspector',
            'department'   => 'Traffic',
            'password'     => Hash::make('Officer@1234'),
            'is_active'    => true,
        ]);

        // Sample Finance Officer
        User::create([
            'role_id'      => $financeRole->id,
            'name'         => 'Finance Officer Sarah Nakato',
            'badge_number' => 'FIN-201',
            'email'        => 'finance@divms.ug',
            'phone'        => '+256700000003',
            'rank'         => 'Finance Officer',
            'department'   => 'Finance',
            'password'     => Hash::make('Finance@1234'),
            'is_active'    => true,
        ]);

        // Violation Types (UGX)
        $violations = [
            ['code'=>'VT001','name'=>'Overspeed / Reckless Driving',     'description'=>'Exceeding speed limits or reckless driving','base_fine'=>200000,'daily_storage_fee'=>10000],
            ['code'=>'VT002','name'=>'Driving Without License',           'description'=>'Operating a vehicle without a valid license','base_fine'=>150000,'daily_storage_fee'=>8000],
            ['code'=>'VT003','name'=>'Drunk Driving (DUI)',               'description'=>'Driving under influence of alcohol or drugs','base_fine'=>500000,'daily_storage_fee'=>15000],
            ['code'=>'VT004','name'=>'Unregistered / Stolen Vehicle',     'description'=>'Vehicle without valid registration or reported stolen','base_fine'=>300000,'daily_storage_fee'=>10000],
            ['code'=>'VT005','name'=>'Obstructing Traffic',              'description'=>'Vehicle causing traffic obstruction','base_fine'=>100000,'daily_storage_fee'=>5000],
            ['code'=>'VT006','name'=>'Illegal Parking',                  'description'=>'Parking in restricted/no-parking zone','base_fine'=>50000,'daily_storage_fee'=>3000],
            ['code'=>'VT007','name'=>'Expired Road License',             'description'=>'Operating vehicle with expired road license','base_fine'=>80000,'daily_storage_fee'=>4000],
            ['code'=>'VT008','name'=>'Overloading',                      'description'=>'Vehicle exceeding maximum load capacity','base_fine'=>250000,'daily_storage_fee'=>12000],
            ['code'=>'VT009','name'=>'Unroadworthy Vehicle',             'description'=>'Vehicle in unsafe mechanical condition','base_fine'=>120000,'daily_storage_fee'=>6000],
            ['code'=>'VT010','name'=>'Hit and Run',                      'description'=>'Leaving scene of accident without reporting','base_fine'=>400000,'daily_storage_fee'=>15000],
        ];

        foreach ($violations as $v) {
            ViolationType::create($v);
        }
    }
}
