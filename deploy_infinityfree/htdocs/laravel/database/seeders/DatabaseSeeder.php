<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Department;
use App\Models\Discussion;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\Teacher;
use App\Models\User;
use App\Services\GradeService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $this->command?->info('Seeding academic structure...');
        $academicYear = $this->seedAcademicYears();
        $departments = $this->seedDepartments();
        $subjects = $this->seedSubjects();

        $this->command?->info('Seeding users (admin, teachers, students)...');
        $this->seedAdmin();
        $teachers = $this->seedTeachers(10);
        $classes = $this->seedClasses($departments, $academicYear, $teachers, 20);
        $students = $this->seedStudents($classes, $departments, 100);

        $this->command?->info('Seeding teaching assignments & enrollments...');
        $courses = $this->seedCourses($classes, $subjects, $teachers, $academicYear);
        $this->seedEnrollments($courses);
        $schedules = $this->seedSchedules($courses, $academicYear);

        $this->command?->info('Seeding learning content (materials, assignments, quizzes, exams)...');
        $this->seedMaterials($courses, $teachers);
        $this->seedAssignmentsAndSubmissions($courses);
        $this->seedQuizzesAndExams($courses);

        $this->command?->info('Seeding attendances & grades...');
        $this->seedAttendances($schedules);
        $this->seedGrades($courses);

        $this->command?->info('Seeding announcements & discussions...');
        $this->seedAnnouncements($classes, $courses);
        $this->seedDiscussions($courses);

        $this->seedSettings();

        $this->command?->info('Database seeding completed successfully.');
    }

    private function seedAcademicYears(): AcademicYear
    {
        $previous = AcademicYear::factory()->create([
            'name' => '2024/2025', 'semester' => 'Genap',
            'start_date' => '2025-01-01', 'end_date' => '2025-06-30', 'is_active' => false,
        ]);

        $active = AcademicYear::factory()->create([
            'name' => '2025/2026', 'semester' => 'Ganjil',
            'start_date' => '2025-07-01', 'end_date' => '2025-12-31', 'is_active' => true,
        ]);

        return $active;
    }

    private function seedDepartments()
    {
        $names = [
            'Rekayasa Perangkat Lunak' => 'RPL',
            'Teknik Komputer dan Jaringan' => 'TKJ',
            'Multimedia' => 'MM',
            'Akuntansi' => 'AK',
            'Otomatisasi dan Tata Kelola Perkantoran' => 'OTKP',
            'Teknik Kendaraan Ringan' => 'TKR',
        ];

        return collect($names)->map(fn ($code, $name) => Department::create([
            'name' => $name,
            'code' => $code,
            'description' => "Kompetensi Keahlian {$name}",
        ]))->values();
    }

    private function seedSubjects()
    {
        $subjects = [
            'MTK' => 'Matematika',
            'BINA' => 'Bahasa Indonesia',
            'BING' => 'Bahasa Inggris',
            'PAI' => 'Pendidikan Agama',
            'PPKN' => 'Pendidikan Pancasila',
            'PWEB' => 'Pemrograman Web',
            'BSD' => 'Basis Data',
            'PBO' => 'Pemrograman Berorientasi Objek',
            'JARKOM' => 'Jaringan Komputer',
            'DKV' => 'Desain Grafis',
            'PKWU' => 'Produk Kreatif dan Kewirausahaan',
            'SISKOM' => 'Sistem Komputer',
            'IPAS' => 'IPAS',
            'SEJIND' => 'Sejarah Indonesia',
            'SENBUD' => 'Seni Budaya',
            'PJOK' => 'PJOK',
        ];

        return collect($subjects)->map(fn ($name, $code) => Subject::create([
            'name' => $name,
            'code' => $code,
            'description' => "Mata pelajaran {$name}",
        ]))->values();
    }

    private function seedAdmin(): User
    {
        $admin = User::factory()->create([
            'name' => 'Administrator LMS',
            'email' => 'admin@smkn9garut.sch.id',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        return $admin;
    }

    private function seedTeachers(int $count)
    {
        return collect(range(1, $count))->map(function ($i) {
            $user = User::factory()->create([
                'email' => "guru{$i}@smkn9garut.sch.id",
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('guru');

            return Teacher::factory()->create(['user_id' => $user->id]);
        });
    }

    private function seedClasses($departments, AcademicYear $academicYear, $teachers, int $total)
    {
        $gradeLevels = ['X', 'XI', 'XII'];
        $classes = collect();
        $rombelCounter = [];

        for ($i = 0; $i < $total; $i++) {
            $department = $departments[$i % $departments->count()];
            $grade = $gradeLevels[$i % count($gradeLevels)];
            $key = "{$department->code}-{$grade}";
            $rombelCounter[$key] = ($rombelCounter[$key] ?? 0) + 1;

            $classes->push(ClassRoom::create([
                'name' => "{$grade} {$department->code} {$rombelCounter[$key]}",
                'grade_level' => $grade,
                'department_id' => $department->id,
                'academic_year_id' => $academicYear->id,
                'homeroom_teacher_id' => $teachers[$i % $teachers->count()]->id,
            ]));
        }

        return $classes;
    }

    private function seedStudents($classes, $departments, int $total)
    {
        $perClass = (int) ceil($total / $classes->count());
        $students = collect();
        $counter = 1;

        foreach ($classes as $class) {
            for ($i = 0; $i < $perClass && $students->count() < $total; $i++) {
                $user = User::factory()->create([
                    'email' => "siswa{$counter}@smkn9garut.sch.id",
                    'password' => Hash::make('password'),
                ]);
                $user->assignRole('siswa');

                $students->push(Student::factory()->create([
                    'user_id' => $user->id,
                    'class_id' => $class->id,
                    'department_id' => $class->department_id,
                ]));

                $counter++;
            }
        }

        return $students;
    }

    private function seedCourses($classes, $subjects, $teachers, AcademicYear $academicYear)
    {
        $courses = collect();

        foreach ($classes as $class) {
            $classSubjects = $subjects->random(6);

            foreach ($classSubjects as $subject) {
                $courses->push(Course::create([
                    'subject_id' => $subject->id,
                    'teacher_id' => $teachers->random()->id,
                    'class_id' => $class->id,
                    'academic_year_id' => $academicYear->id,
                ]));
            }
        }

        return $courses;
    }

    private function seedEnrollments($courses): void
    {
        $rows = [];
        $now = now();

        foreach ($courses as $course) {
            $studentIds = ClassRoom::find($course->class_id)->students()->pluck('id');
            foreach ($studentIds as $studentId) {
                $rows[] = [
                    'student_id' => $studentId,
                    'course_id' => $course->id,
                    'enrolled_at' => $now,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            Enrollment::insert($chunk);
        }
    }

    private function seedSchedules($courses, AcademicYear $academicYear)
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $timeSlots = [['07:00', '08:30'], ['08:30', '10:00'], ['10:15', '11:45'], ['12:30', '14:00'], ['14:00', '15:30']];

        $schedules = collect();
        $classSlotIndex = [];

        foreach ($courses as $course) {
            $key = $course->class_id;
            $slot = $classSlotIndex[$key] ?? 0;
            $day = $days[$slot % count($days)];
            $time = $timeSlots[intdiv($slot, count($days)) % count($timeSlots)];
            $classSlotIndex[$key] = $slot + 1;

            $schedules->push(Schedule::create([
                'class_id' => $course->class_id,
                'course_id' => $course->id,
                'academic_year_id' => $academicYear->id,
                'day_of_week' => $day,
                'start_time' => $time[0],
                'end_time' => $time[1],
                'room' => 'R-'.str_pad((string) (($course->class_id % 12) + 1), 2, '0', STR_PAD_LEFT),
            ]));
        }

        return $schedules;
    }

    private function seedMaterials($courses, $teachers): void
    {
        $types = ['pdf', 'ppt', 'video', 'link'];

        foreach ($courses->random(min(60, $courses->count())) as $course) {
            foreach (range(1, 2) as $i) {
                Material::create([
                    'course_id' => $course->id,
                    'teacher_id' => $course->teacher_id,
                    'title' => "Materi {$i}: ".fake()->sentence(4),
                    'description' => fake()->paragraph(),
                    'type' => $types[array_rand($types)],
                    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                    'is_published' => true,
                ]);
            }
        }
    }

    private function seedAssignmentsAndSubmissions($courses): void
    {
        foreach ($courses->random(min(60, $courses->count())) as $course) {
            $assignment = Assignment::create([
                'course_id' => $course->id,
                'teacher_id' => $course->teacher_id,
                'title' => 'Tugas: '.fake()->sentence(4),
                'description' => fake()->paragraph(),
                'deadline' => now()->addDays(rand(-10, 14)),
                'max_score' => 100,
                'allow_late' => (bool) rand(0, 1),
                'is_published' => true,
            ]);

            $students = $course->students()->inRandomOrder()->limit(rand(2, 5))->get();
            foreach ($students as $student) {
                $isGraded = (bool) rand(0, 1);
                Submission::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                    'file_name' => 'jawaban-'.$student->id.'.pdf',
                    'note' => fake()->sentence(),
                    'submitted_at' => now()->subDays(rand(0, 5)),
                    'score' => $isGraded ? rand(60, 100) : null,
                    'feedback' => $isGraded ? fake()->sentence() : null,
                    'graded_by' => $isGraded ? $course->teacher_id : null,
                    'graded_at' => $isGraded ? now() : null,
                    'status' => $isGraded ? 'graded' : 'submitted',
                ]);
            }
        }
    }

    private function seedQuizzesAndExams($courses): void
    {
        foreach ($courses->random(min(40, $courses->count())) as $course) {
            $quiz = Quiz::create([
                'course_id' => $course->id,
                'teacher_id' => $course->teacher_id,
                'title' => 'Kuis: '.fake()->sentence(3),
                'description' => fake()->sentence(),
                'duration_minutes' => 20,
                'start_time' => now()->subDays(3),
                'end_time' => now()->addDays(7),
                'shuffle_questions' => true,
                'show_result_immediately' => true,
                'is_published' => true,
            ]);

            $questions = collect();
            foreach (range(1, 5) as $i) {
                $questions->push(QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'type' => 'multiple_choice',
                    'question' => "Soal {$i}: ".fake()->sentence(8).'?',
                    'option_a' => fake()->word(), 'option_b' => fake()->word(),
                    'option_c' => fake()->word(), 'option_d' => fake()->word(),
                    'correct_option' => 'A',
                    'score' => 20,
                    'order' => $i,
                ]));
            }

            foreach ($course->students()->inRandomOrder()->limit(3)->get() as $student) {
                $attempt = QuizAttempt::create([
                    'quiz_id' => $quiz->id, 'student_id' => $student->id,
                    'started_at' => now()->subHour(), 'submitted_at' => now(),
                    'status' => 'graded',
                ]);

                $total = 0;
                foreach ($questions as $question) {
                    $selected = fake()->randomElement(['A', 'B', 'C', 'D']);
                    $correct = $selected === $question->correct_option;
                    $score = $correct ? $question->score : 0;
                    $total += $score;
                    QuizAnswer::create([
                        'quiz_attempt_id' => $attempt->id, 'quiz_question_id' => $question->id,
                        'selected_option' => $selected, 'is_correct' => $correct, 'score' => $score,
                    ]);
                }
                $attempt->update(['score' => $total]);
            }
        }

        foreach ($courses->random(min(40, $courses->count())) as $course) {
            $exam = Exam::create([
                'course_id' => $course->id,
                'teacher_id' => $course->teacher_id,
                'title' => 'Ujian: '.fake()->sentence(3),
                'description' => fake()->sentence(),
                'duration_minutes' => 60,
                'start_time' => now()->subDays(3),
                'end_time' => now()->addDays(14),
                'shuffle_questions' => true,
                'passing_score' => 75,
                'is_published' => true,
            ]);

            $questions = collect();
            foreach (range(1, 5) as $i) {
                $questions->push(ExamQuestion::create([
                    'exam_id' => $exam->id,
                    'type' => 'multiple_choice',
                    'question' => "Soal {$i}: ".fake()->sentence(8).'?',
                    'option_a' => fake()->word(), 'option_b' => fake()->word(),
                    'option_c' => fake()->word(), 'option_d' => fake()->word(),
                    'correct_option' => 'A',
                    'score' => 20,
                    'order' => $i,
                ]));
            }

            foreach ($course->students()->inRandomOrder()->limit(3)->get() as $student) {
                $attempt = ExamAttempt::create([
                    'exam_id' => $exam->id, 'student_id' => $student->id,
                    'started_at' => now()->subHour(), 'submitted_at' => now(),
                    'status' => 'graded',
                ]);

                $total = 0;
                foreach ($questions as $question) {
                    $selected = fake()->randomElement(['A', 'B', 'C', 'D']);
                    $correct = $selected === $question->correct_option;
                    $score = $correct ? $question->score : 0;
                    $total += $score;
                    ExamAnswer::create([
                        'exam_attempt_id' => $attempt->id, 'exam_question_id' => $question->id,
                        'selected_option' => $selected, 'is_correct' => $correct, 'score' => $score,
                    ]);
                }
                $attempt->update(['score' => $total, 'is_passed' => $total >= $exam->passing_score]);
            }
        }
    }

    private function seedAttendances($schedules): void
    {
        $rows = [];
        $now = now();

        foreach ($schedules as $schedule) {
            $studentIds = ClassRoom::find($schedule->class_id)->students()->pluck('id');

            for ($week = 0; $week < 4; $week++) {
                $date = $now->copy()->subWeeks($week)->format('Y-m-d');
                foreach ($studentIds as $studentId) {
                    $rows[] = [
                        'schedule_id' => $schedule->id,
                        'student_id' => $studentId,
                        'recorded_by' => $schedule->course->teacher_id,
                        'date' => $date,
                        'status' => fake()->randomElement(['hadir', 'hadir', 'hadir', 'hadir', 'izin', 'sakit', 'alpha']),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            Attendance::insert($chunk);
        }
    }

    private function seedGrades($courses): void
    {
        foreach ($courses as $course) {
            GradeService::recalculateForCourse($course);
        }
    }

    private function seedAnnouncements($classes, $courses): void
    {
        $admin = User::role('admin')->first();

        foreach (range(1, 5) as $i) {
            Announcement::create([
                'user_id' => $admin->id,
                'title' => 'Pengumuman Sekolah: '.fake()->sentence(4),
                'content' => fake()->paragraph(),
                'type' => 'sekolah',
                'is_published' => true,
                'published_at' => now()->subDays(rand(0, 10)),
            ]);
        }

        foreach ($courses->random(5) as $course) {
            Announcement::create([
                'user_id' => $course->teacher->user_id,
                'title' => 'Pengumuman Guru: '.fake()->sentence(4),
                'content' => fake()->paragraph(),
                'type' => 'guru',
                'course_id' => $course->id,
                'class_id' => $course->class_id,
                'is_published' => true,
                'published_at' => now()->subDays(rand(0, 10)),
            ]);
        }
    }

    private function seedDiscussions($courses): void
    {
        foreach ($courses->random(5) as $course) {
            $discussion = Discussion::create([
                'course_id' => $course->id,
                'user_id' => $course->teacher->user_id,
                'title' => fake()->sentence(6),
                'body' => fake()->paragraph(),
            ]);

            foreach ($course->students()->inRandomOrder()->limit(2)->get() as $student) {
                $discussion->allComments()->create([
                    'user_id' => $student->user_id,
                    'body' => fake()->sentence(10),
                ]);
            }
        }
    }

    private function seedSettings(): void
    {
        Setting::set('school_name', 'SMKN 9 Garut', 'school');
        Setting::set('school_address', 'Jl. Raya Wanaraja, Kabupaten Garut, Jawa Barat', 'school');
        Setting::set('school_phone', '(0262) 123456', 'school');
        Setting::set('school_email', 'info@smkn9garut.sch.id', 'school');
        Setting::set('principal_name', 'Drs. H. Kepala Sekolah, M.Pd.', 'school');
    }
}
