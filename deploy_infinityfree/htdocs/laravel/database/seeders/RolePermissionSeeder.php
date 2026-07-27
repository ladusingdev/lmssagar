<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'users', 'teachers', 'students', 'departments', 'classes', 'subjects',
            'academic-years', 'materials', 'assignments', 'quizzes', 'exams',
            'grades', 'attendances', 'schedules', 'announcements', 'discussions',
            'reports', 'settings', 'activity-logs',
        ];

        $permissions = [];
        foreach ($modules as $module) {
            foreach (['view', 'create', 'update', 'delete'] as $action) {
                $permissions[] = "{$action}-{$module}";
            }
        }

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions);

        $guru = Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        $guru->syncPermissions([
            'view-students', 'view-classes', 'view-subjects',
            'view-materials', 'create-materials', 'update-materials', 'delete-materials',
            'view-assignments', 'create-assignments', 'update-assignments', 'delete-assignments',
            'view-quizzes', 'create-quizzes', 'update-quizzes', 'delete-quizzes',
            'view-exams', 'create-exams', 'update-exams', 'delete-exams',
            'view-grades', 'create-grades', 'update-grades',
            'view-attendances', 'create-attendances', 'update-attendances',
            'view-schedules', 'view-announcements', 'create-announcements', 'update-announcements',
            'view-discussions', 'create-discussions',
        ]);

        $siswa = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        $siswa->syncPermissions([
            'view-materials', 'view-assignments', 'view-quizzes', 'view-exams',
            'view-grades', 'view-attendances', 'view-schedules', 'view-announcements',
            'view-discussions', 'create-discussions',
        ]);
    }
}
