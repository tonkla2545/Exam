<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\Admin\ImportController;

Route::middleware(['admin.token'])->group(function () {
    Route::get('/admin/import', [ImportController::class, 'form'])->name('admin.import.form');
    Route::post('/admin/import', [ImportController::class, 'upload'])->name('admin.import.upload');
});

Route::get('/', [ExamController::class, 'selectAgency'])
    ->name('agency.select');

Route::get('/agency/{agency}', [ExamController::class, 'selectTopic'])
    ->name('topic.select');

Route::get('/exam/start/{topic}', [ExamController::class, 'start'])
    ->name('exam.start');

Route::get('/exam/question', [ExamController::class, 'question'])
    ->name('exam.question');

Route::post('/exam/answer', [ExamController::class, 'answer'])
    ->name('exam.answer');

Route::get('/exam/finish', [ExamController::class, 'finish'])
    ->name('exam.finish');