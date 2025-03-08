<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class FormController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function index(Request $request): JsonResponse
    {
        $log = new Logger('feedback');
        $log->pushHandler(new StreamHandler(storage_path('logs/feedback.log'), Logger::INFO));
        $validatedData = $this->validate($request, [
            'fullName' => 'required|string',
            'mobile' => 'required|string',
            'email' => 'email|nullable',
            'address' => 'string|nullable',
            'file' => 'file|nullable',
        ]);
        $validatedData['file'] = $request->file('file')?->getClientOriginalName();
        if (str_contains($validatedData['email'], '@gmail.com')) {
            return response()->json(['message' => 'Регистрация пользователей с таким почтовым адресом невозможна.'], 400);
        }
        $log->info('Форма отправлена', $validatedData);
        return response()->json(['message' => 'Форма успешно обработана.'], 200);
    }
}
