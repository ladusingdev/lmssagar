<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\Exam;

$classes = ClassRoom::where('name', 'like', '%X TKJ 1%')->orWhere('name', 'like', '%X%TKJ%1%')->get();
echo "=== Kelas yang mirip 'X TKJ 1' ===\n";
foreach ($classes as $c) {
    echo "id={$c->id} name={$c->name} academic_year_id={$c->academic_year_id} students_count=".$c->students()->count()."\n";
}

echo "\n=== Courses untuk kelas-kelas itu ===\n";
foreach ($classes as $c) {
    $courses = Course::where('class_id', $c->id)->with('subject')->get();
    foreach ($courses as $co) {
        echo "course_id={$co->id} class_id={$co->class_id} subject={$co->subject->name} teacher_id={$co->teacher_id} enrollments=".$co->enrollments()->count()."\n";
    }
}

echo "\n=== Materi terbaru (5) ===\n";
foreach (Material::latest()->limit(5)->get() as $m) {
    echo "id={$m->id} course_id={$m->course_id} title={$m->title} is_published=".($m->is_published?'1':'0')."\n";
}

echo "\n=== Tugas terbaru (5) ===\n";
foreach (Assignment::latest()->limit(5)->get() as $a) {
    echo "id={$a->id} course_id={$a->course_id} title={$a->title} is_published=".($a->is_published?'1':'0')."\n";
}

echo "\n=== Kuis terbaru (5) ===\n";
foreach (Quiz::latest()->limit(5)->get() as $q) {
    echo "id={$q->id} course_id={$q->course_id} title={$q->title} is_published=".($q->is_published?'1':'0')." start={$q->start_time} end={$q->end_time}\n";
}

echo "\n=== Ujian terbaru (5) ===\n";
foreach (Exam::latest()->limit(5)->get() as $e) {
    echo "id={$e->id} course_id={$e->course_id} title={$e->title} is_published=".($e->is_published?'1':'0')." start={$e->start_time} end={$e->end_time}\n";
}
