<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = $request->session()->get("admin");
        if($admin == null){
           return redirect()->to(url("/admin/auth"))->with("error", "Pengguna belum login");
        }
        \request()->setUserResolver(function()use($admin){
            return $admin;
        });

        return $next($request);
    }
}
