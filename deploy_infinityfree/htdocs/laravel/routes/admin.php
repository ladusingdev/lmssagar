<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\ClassRoomController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DiscussionController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AcademicYearController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('students', StudentController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('classes', ClassRoomController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('academic-years', AcademicYearController::class);
    Route::post('academic-years/{academic_year}/activate', [AcademicYearController::class, 'activate'])->name('academic-years.activate');
    Route::resource('courses', CourseController::class)->except(['show']);

    Route::resource('materials', MaterialController::class);
    Route::get('materials/{material}/download', [MaterialController::class, 'download'])->name('materials.download');

    Route::resource('assignments', AssignmentController::class);
    Route::resource('quizzes', QuizController::class);
    Route::resource('quizzes.questions', \App\Http\Controllers\Admin\QuizQuestionController::class)
        ->parameters(['questions' => 'question'])
        ->except(['show']);
    Route::resource('exams', ExamController::class);
    Route::resource('exams.questions', \App\Http\Controllers\Admin\ExamQuestionController::class)
        ->parameters(['questions' => 'question'])
        ->except(['show']);

    Route::resource('grades', GradeController::class)->only(['index', 'edit', 'update']);
    Route::post('grades/{course}/recalculate', [GradeController::class, 'recalculate'])->name('grades.recalculate');
    Route::resource('attendances', AttendanceController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('discussions', DiscussionController::class)->only(['index', 'show', 'destroy']);
    Route::delete('discussions/{discussion}/comments/{comment}', [DiscussionController::class, 'destroyComment'])->name('discussions.comments.destroy');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/teachers/pdf', [ReportController::class, 'teachersPdf'])->name('reports.teachers.pdf');
    Route::get('reports/teachers/excel', [ReportController::class, 'teachersExcel'])->name('reports.teachers.excel');
    Route::get('reports/students/pdf', [ReportController::class, 'studentsPdf'])->name('reports.students.pdf');
    Route::get('reports/students/excel', [ReportController::class, 'studentsExcel'])->name('reports.students.excel');
    Route::get('reports/grades/pdf', [ReportController::class, 'gradesPdf'])->name('reports.grades.pdf');
    Route::get('reports/grades/excel', [ReportController::class, 'gradesExcel'])->name('reports.grades.excel');
    Route::get('reports/attendances/pdf', [ReportController::class, 'attendancesPdf'])->name('reports.attendances.pdf');
    Route::get('reports/attendances/excel', [ReportController::class, 'attendancesExcel'])->name('reports.attendances.excel');
    Route::get('reports/materials/pdf', [ReportController::class, 'materialsPdf'])->name('reports.materials.pdf');
    Route::get('reports/materials/excel', [ReportController::class, 'materialsExcel'])->name('reports.materials.excel');
    Route::get('reports/assignments/pdf', [ReportController::class, 'assignmentsPdf'])->name('reports.assignments.pdf');
    Route::get('reports/assignments/excel', [ReportController::class, 'assignmentsExcel'])->name('reports.assignments.excel');
    Route::get('reports/exams/pdf', [ReportController::class, 'examsPdf'])->name('reports.exams.pdf');
    Route::get('reports/exams/excel', [ReportController::class, 'examsExcel'])->name('reports.exams.excel');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::get('backup/{file}/download', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('backup/{file}', [BackupController::class, 'destroy'])->name('backup.destroy');
    Route::post('backup/restore', [BackupController::class, 'restore'])->name('backup.restore');
});
