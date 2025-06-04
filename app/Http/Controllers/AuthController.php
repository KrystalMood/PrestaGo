<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginUserAction;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;
    protected $loginAction;

    public function __construct(
        AuthService $authService,
        LoginUserAction $loginAction
    ) {
        $this->authService = $authService;
        $this->loginAction = $loginAction;
    }

    public function login()
    {
        if(Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($this->loginAction->execute($request)) {
            return $this->redirectBasedOnRole(Auth::user())
                ->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return back()
            ->withErrors(['email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.'])
            ->withInput($request->except('password'))
            ->with('error', 'Login gagal! Silakan periksa kembali email dan kata sandi Anda.');
    }

    /**
     * Redirect user based on their role
     *
     * @param \App\Models\UserModel $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnRole($user)
    {
        $role = $user->getRole();
        
        switch ($role) {
            case 'ADM':
                return redirect()->route('admin.dashboard');
            case 'MHS':
                return redirect()->route('student.dashboard');
            case 'DSN':
                return redirect()->route('lecturer.dashboard');
            default:
                return redirect()->route('login')
                    ->with('error', 'Akun Anda tidak memiliki peran yang valid. Silakan hubungi administrator.');
        }
    }

    public function adminDashboard()
    {
        $stats = [
            [
                'title' => 'Total Mahasiswa',
                'value' => \App\Models\UserModel::whereHas('level', function($query) {
                    $query->where('level_kode', 'MHS');
                })->count(),
                'icon' => '<svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
                'trend' => 'Semua waktu'
            ],
            [
                'title' => 'Total Dosen',
                'value' => \App\Models\UserModel::whereHas('level', function($query) {
                    $query->where('level_kode', 'DSN');
                })->count(),
                'icon' => '<svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
                'trend' => 'Semua waktu'
            ],
            [
                'title' => 'Prestasi Terverifikasi',
                'value' => \App\Models\AchievementModel::where('status', 'verified')->count(),
                'icon' => '<svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'trend' => 'Semua waktu'
            ],
            [
                'title' => 'Kompetisi Aktif',
                'value' => \App\Models\CompetitionModel::where('status', 'active')->count(),
                'icon' => '<svg class="h-8 w-8 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'trend' => 'Saat ini'
            ]
        ];

        $achievementStats = $this->getAchievementStats();
        
        $activities = $this->getRecentActivities();

        return view('admin.dashboard', compact('stats', 'achievementStats', 'activities'));
    }

    private function getAchievementStats()
    {
        // Get achievements by type
        $byType = \App\Models\AchievementModel::where('status', 'verified')
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->get()
            ->toArray();

        // Get achievements by month for current year
        $byMonth = \App\Models\AchievementModel::where('status', 'verified')
            ->whereYear('created_at', date('Y'))
            ->selectRaw("MONTH(created_at) as month_num, DATE_FORMAT(created_at, '%b') as month, COUNT(*) as total")
            ->groupBy('month_num', 'month')
            ->orderBy('month_num')
            ->get()
            ->toArray();

        return [
            'byType' => $byType,
            'byMonth' => $byMonth
        ];
    }

    /**
     * Get recent system activities for the dashboard
     *
     * @return array
     */
    private function getRecentActivities()
    {
        // Use ActivityService if available
        if (class_exists('\App\Services\ActivityService')) {
            return app(\App\Services\ActivityService::class)->getLatest(5);
        }
        
        // Check if ActivityModel exists
        if (class_exists('\App\Models\ActivityModel')) {
            return \App\Models\ActivityModel::with('user')
                ->latest()
                ->take(5)
                ->get();
        }
        
        // If ActivityModel doesn't exist, create a collection with sample activities
        return collect([
            (object)[
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'message' => 'Prestasi baru telah diverifikasi',
                'created_at' => now()->subHours(2),
                'formatted_time' => '2 jam yang lalu'
            ],
            (object)[
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
                'message' => 'Pengguna baru telah mendaftar',
                'created_at' => now()->subHours(5),
                'formatted_time' => '5 jam yang lalu'
            ],
            (object)[
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
                'message' => 'Kompetisi baru telah ditambahkan',
                'created_at' => now()->subDay(),
                'formatted_time' => '1 hari yang lalu'
            ]
        ]);
    }

    public function studentDashboard()
    {
        return view('student.dashboard');
    }

    public function lecturerDashboard()
    {
        return view('Dosen.dashboard');
    }

    public function logout(Request $request)
    {
        $this->authService->logout();

        return redirect()->route('login')
            ->with('info', 'Anda telah berhasil keluar.');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }
}
