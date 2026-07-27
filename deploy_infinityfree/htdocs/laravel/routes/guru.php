<?php

use App\Http\Controllers\Guru\AnnouncementController;
use App\Http\Controllers\Guru\AssignmentController;
use App\Http\Controllers\Guru\AttendanceController;
use App\Http\Controllers\Guru\CourseController;
use App\Http\Controllers\Guru\DashboardController;
use App\Http\Controllers\Guru\DiscussionController;
use App\Http\Controllers\Guru\ExamController;
use App\Http\Controllers\Guru\ExamQuestionController;
use App\Http\Controllers\Guru\GradeController;
use App\Http\Controllers\Guru\MaterialController;
use App\Http\Controllers\Guru\QuizController;
use App\Http\Controllers\Guru\QuizQuestionController;
use App\Http\Controllers\Guru\ScheduleController;
use App\Http\Controllers\Guru\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('guru')->name('guru.')->middleware(['auth', 'verified', 'role:guru'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');

    Route::resource('materials', MaterialController::class);
    Route::get('materials/{material}/download', [MaterialController::class, 'download'])->name('materials.download');

    Route::resource('assignments', AssignmentController::class);
    Route::get('assignments/{assignment}/submissions', [SubmissionController::class, 'index'])->name('assignments.submissions.index');
    Route::get('submissions/{submission}/grade', [SubmissionController::class, 'edit'])->name('submissions.grade');
    Route::put('submissions/{submission}/grade', [SubmissionController::class, 'update'])->name('submissions.grade.update');

    Route::resource('quizzes', QuizController::class);
    Route::resource('quizzes.questions', QuizQuestionController::class)
        ->parameters(['questions' => 'question'])
        ->except(['show']);
    Route::get('quizzes/{quiz}/results', [QuizController::class, 'results'])->name('quizzes.results');
    Route::get('quizzes/{quiz}/attempts/{attempt}/review', [QuizController::class, 'reviewAttempt'])->name('quizzes.attempts.review');
    Route::put('quizzes/{quiz}/attempts/{attempt}/review', [QuizController::class, 'updateAttemptScore'])->name('quizzes.attempts.review.update');

    Route::resource('exams', ExamController::class);
    Route::resource('exams.questions', ExamQuestionController::class)
        ->parameters(['questions' => 'question'])
        ->except(['show']);
    Route::get('exams/{exam}/results', [ExamController::class, 'results'])->name('exams.results');
    Route::get('exams/{exam}/attempts/{attempt}/review', [ExamController::class, 'reviewAttempt'])->name('exams.attempts.review');
    Route::put('exams/{exam}/attempts/{attempt}/review', [ExamController::class, 'updateAttemptScore'])->name('exams.attempts.review.update');

    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');

    Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
    Route::post('grades/{course}/recalculate', [GradeController::class, 'recalculate'])->name('grades.recalculate');

    Route::resource('announcements', AnnouncementController::class);
    Route::resource('discussions', DiscussionController::class);
    Route::post('discussions/{discussion}/comments', [DiscussionController::class, 'storeComment'])->name('discussions.comments.store');
});
