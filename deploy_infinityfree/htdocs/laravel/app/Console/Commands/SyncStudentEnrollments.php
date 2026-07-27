<?php

namespace App\Console\Commands;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Student;
use App\Notifications\GeneralNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SyncStudentEnrollments extends Command
{
    protected $signature = 'enrollments:sync';

    protected $description = 'Backfill missing enrollments (and catch-up notifications) for students whose class already has teaching assignments (courses)';

    public function handle(): int
    {
        $created = 0;

        Student::whereNotNull('class_id')->with('user')->each(function (Student $student) use (&$created) {
            $existingCourseIds = $student->enrollments()->pluck('course_id');

            $missingCourseIds = Course::where('class_id', $student->class_id)
                ->whereNotIn('id', $existingCourseIds)
                ->pluck('id');

            if ($missingCourseIds->isEmpty()) {
                return;
            }

            $student->enrollments()->createMany(
                $missingCourseIds->map(fn ($courseId) => ['course_id' => $courseId, 'enrolled_at' => now()])->all()
            );

            $this->notifyBackfilledCourses($student, $missingCourseIds);

            $created += $missingCourseIds->count();
        });

        $this->info("Selesai. {$created} enrollment baru dibuat.");

        return self::SUCCESS;
    }

    private function notifyBackfilledCourses(Student $student, Collection $courseIds): void
    {
        Assignment::whereIn('course_id', $courseIds)
            ->where('is_published', true)
            ->with('course.subject')
            ->each(function (Assignment $assignment) use ($student) {
                $student->user->notify(new GeneralNotification(
                    'Tugas Baru',
                    "Tugas baru \"{$assignment->title}\" telah ditambahkan untuk {$assignment->course->subject->name}.",
                    route('siswa.assignments.show', $assignment)
                ));
            });

        Announcement::whereIn('course_id', $courseIds)
            ->where('is_published', true)
            ->each(function (Announcement $announcement) use ($student) {
                $student->user->notify(new GeneralNotification('Pengumuman Guru', $announcement->title, route('dashboard')));
            });
    }
}
