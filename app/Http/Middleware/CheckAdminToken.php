<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminToken
{
    public function handle(Request $request, Closure $next)
    {
        // ถ้ามี session แล้วให้ผ่าน
        if (session('admin_authenticated') === true) {
            return $next($request);
        }

        // ถ้าเป็น POST และมี token ให้เช็ค
        if ($request->isMethod('post') && $request->has('admin_token')) {
            $adminToken = env('ADMIN_TOKEN', 'secret');
            
            if ($request->admin_token === $adminToken) {
                session(['admin_authenticated' => true]);
                return $next($request);
            }
            
            return back()->withErrors(['admin_token' => 'Token ไม่ถูกต้อง']);
        }

        // ถ้ายังไม่มี session ให้แสดงฟอร์ม token
        return response()->view('admin.auth-form');
    }
}