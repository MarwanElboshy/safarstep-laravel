<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Serve static assets if .htaccess fails
Route::get('assets/{path}', function ($path) {
    $file = public_path('assets/' . $path);
    if (file_exists($file)) {
        return response()->file($file);
    }
    abort(404);
})->where('path', '.*');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Session-based login (web guard)
Route::post('/login', function () {
    $credentials = request()->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (!Auth::attempt($credentials)) {
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    request()->session()->regenerate();

    // Persist tenant context in session for web routes
    $user = Auth::user();
    if ($user && $user->tenant_id) {
        session()->put('tenant_id', $user->tenant_id);
    }

    return redirect()->intended(route('dashboard'));
})->name('login.post');

// Logout route to end dashboard session cleanly
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Development auto-login helper
Route::get('/dev-login', function () {
    if (app()->environment(['local', 'development'])) {
        $user = User::where('tenant_id', 1)->first();
        if ($user) {
            Auth::login($user);
            session()->put('tenant_id', $user->tenant_id);
            return redirect('/dashboard')->with('success', 'Logged in as ' . $user->name);
        }
        return redirect('/')->with('error', 'No users found');
    }
    abort(404);
})->name('dev.login');

// Debug route to check auth status
Route::get('/debug-auth', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'user' => Auth::user(),
        'session_id' => session()->getId(),
        'tenant_id' => session()->get('tenant_id'),
        'session_driver' => config('session.driver')
    ]);
});

// Dashboard pages require authentication and tenant context
Route::middleware(['auth', 'tenant', 'enforce-tenant'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', ['nav' => 'dashboard', 'pageTitle' => 'Dashboard']);
    })->name('dashboard');

    Route::get('/dashboard/users', function () {
        return view('users', ['nav' => 'users', 'pageTitle' => 'Users & Roles']);
    })->name('dashboard.users');

    Route::get('/dashboard/users/management', function () {
        return view('users', ['nav' => 'users', 'pageTitle' => 'Users Management']);
    })->name('dashboard.users.management');

    Route::get('/dashboard/users/rbac', function () {
        return view('rbac', ['nav' => 'users', 'pageTitle' => 'Roles & Permissions']);
    })->name('dashboard.users.rbac');

    Route::get('/dashboard/departments', function () {
        return view('departments', ['nav' => 'departments', 'pageTitle' => 'Departments']);
    })->name('dashboard.departments');

    Route::get('/dashboard/companies', function () {
        return view('companies.index', ['nav' => 'companies', 'pageTitle' => 'Companies']);
    })->name('dashboard.companies');

    // Offers
    Route::get('/dashboard/offers', function () {
        return view('offers.index', ['nav' => 'offers', 'pageTitle' => 'Offers']);
    })->name('offers.index');

    Route::get('/dashboard/offers/create', function () {
        $user = auth()->user();
        $tenant = $user->tenant;
        $departments = $tenant->departments()->get();
        
        return view('offers.create', [
            'nav' => 'offers',
            'pageTitle' => 'Create Offer',
            'departments' => $departments,
        ]);
    })->name('offers.create');

    Route::get('/dashboard/offers/{offer}', function (\App\Models\Offer $offer) {
        $user = auth()->user();
        
        // Tenant isolation check
        if ($offer->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized');
        }
        
        return view('offers.show', [
            'nav' => 'offers',
            'pageTitle' => $offer->title,
            'offer' => $offer,
        ]);
    })->name('offers.show');

    Route::get('/dashboard/notifications-demo', function () {
        return view('notifications-demo', ['nav' => 'dashboard', 'pageTitle' => 'Notifications Demo']);
    })->name('dashboard.notifications-demo');
});
