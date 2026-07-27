<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Grade;

class GradeService
{
    /**
     * Recalculate the recap grade for every student enrolled in the given course.
     */
    public static function recalculateForCourse(Course $course): void
    {
        $studentIds = $course->enrollments()->pluck('student_id');

        foreach ($studentIds as $studentId) {
            $assignmentScore = \App\Models\Submission::whereHas('assignment', fn ($q) => $q->where('course_id', $course->id))
                ->where('student_id', $studentId)
                ->whereNotNull('score')
                ->avg('score');

            $quizScore = \App\Models\QuizAttempt::whereHas('quiz', fn ($q) => $q->where('course_id', $course->id))
                ->where('student_id', $studentId)
                ->whereNotNull('score')
                ->avg('score');

            $examScore = \App\Models\ExamAttempt::whereHas('exam', fn ($q) => $q->where('course_id', $course->id))
                ->where('student_id', $studentId)
                ->whereNotNull('score')
                ->avg('score');

            $components = array_filter([$assignmentScore, $quizScore, $examScore], fn ($v) => $v !== null);
            $finalScore = count($components) > 0 ? array_sum($components) / count($components) : null;

            Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'course_id' => $course->id,
                    'academic_year_id' => $course->academic_year_id,
                ],
                [
                    'assignment_score' => $assignmentScore,
                    'quiz_score' => $quizScore,
                    'exam_score' => $examScore,
                    'final_score' => $finalScore,
                    'letter_grade' => $finalScore !== null ? Grade::letterFor($finalScore) : null,
                ]
            );
        }
    }
}
