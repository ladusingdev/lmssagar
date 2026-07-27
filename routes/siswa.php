<?php

use App\Http\Controllers\Siswa\AnnouncementController;
use App\Http\Controllers\Siswa\AssignmentController;
use App\Http\Controllers\Siswa\AttendanceController;
use App\Http\Controllers\Siswa\CourseController;
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\DiscussionController;
use App\Http\Controllers\Siswa\ExamController;
use App\Http\Controllers\Siswa\GradeController;
use App\Http\Controllers\Siswa\MaterialController;
use App\Http\Controllers\Siswa\QuizController;
use App\Http\Controllers\Siswa\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'verified', 'role:siswa'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('courses', [CourseController::class, 'index'])->name('courses.index');

    Route::get('materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('materials/{material}', [MaterialController::class, 'show'])->name('materials.show');
    Route::get('materials/{material}/download', [MaterialController::class, 'download'])->name('materials.download');

    Route::get('assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
    Route::post('assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');

    Route::get('quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('quizzes/{quiz}/start', [QuizController::class, 'start'])->name('quizzes.start');
    Route::get('quizzes/{quiz}/attempt', [QuizController::class, 'attempt'])->name('quizzes.attempt');
    Route::post('quizzes/{quiz}/answer', [QuizController::class, 'answer'])->name('quizzes.answer');
    Route::post('quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('quizzes/{quiz}/result', [QuizController::class, 'result'])->name('quizzes.result');

    Route::get('exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
    Route::post('exams/{exam}/start', [ExamController::class, 'start'])->name('exams.start');
    Route::get('exams/{exam}/attempt', [ExamController::class, 'attempt'])->name('exams.attempt');
    Route::post('exams/{exam}/answer', [ExamController::class, 'answer'])->name('exams.answer');
    Route::post('exams/{exam}/submit', [ExamController::class, 'submit'])->name('exams.submit');
    Route::get('exams/{exam}/result', [ExamController::class, 'result'])->name('exams.result');

    Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');

    Route::resource('discussions', DiscussionController::class);
    Route::post('discussions/{discussion}/comments', [DiscussionController::class, 'storeComment'])->name('discussions.comments.store');

    Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
});
