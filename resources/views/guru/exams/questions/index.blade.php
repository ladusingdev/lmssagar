@extends('layouts.app')
@section('title', 'Soal Ujian')
@section('page-title', 'Soal Ujian: ' . $exam->title)
@section('content')
<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('guru.exams.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
            <a href="{{ route('guru.exams.questions.create', $exam) }}" class="btn btn-brand btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah Soal</a>
        </div>
        @forelse($questions as $i => $question)
            <div class="border rounded p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="badge bg-secondary mb-2">Soal {{ $i + 1 }} - {{ $question->type === 'essay' ? 'Essay' : 'Pilihan Ganda' }} - {{ $question->score }} poin</span>
                        <p class="mb-2">{{ $question->question }}</p>
                        @if($question->type === 'multiple_choice')
                            <ul class="list-unstyled small mb-0">
                                @foreach($question->options() as $key => $option)
                                    <li class="{{ $question->correct_option === $key ? 'text-success fw-semibold' : '' }}">{{ $key }}. {{ $option }} @if($question->correct_option === $key)<i class="fa-solid fa-check ms-1"></i>@endif</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="table-actions">
                        <a href="{{ route('guru.exams.questions.edit', [$exam, $question]) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('guru.exams.questions.destroy', [$exam, $question]) }}" method="POST" class="d-inline" data-confirm-delete="Hapus soal ini?">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state"><i class="fa-solid fa-file-shield"></i><p>Belum ada soal untuk ujian ini.</p></div>
        @endforelse
    </div>
</div>
@endsection
