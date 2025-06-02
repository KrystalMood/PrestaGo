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
        $totalUsers = \App\Models\UserModel::count();
        $newUsers = \App\Models\UserModel::whereDate('created_at', '>=', now()->subDays(30))->count();
        $activeUsers = $totalUsers;

        // Hitung statistik prestasi terverifikasi
        $totalAchievements = \App\Models\AchievementModel::count();
        $verifiedAchievements = \App\Models\AchievementModel::verified()->count();
        $pendingVerifications = \App\Models\AchievementModel::pending()->count();
        
        // Hitung persentase verifikasi
        $verificationPercentage = $totalAchievements > 0 ? round(($verifiedAchievements / $totalAchievements) * 100) : 0;
        
        // Persentase dari 30 hari yang lalu untuk perbandingan
        $thirtyDaysAgo = now()->subDays(30);
        $oldTotalAchievements = \App\Models\AchievementModel::whereDate('created_at', '<', $thirtyDaysAgo)->count();
        $oldVerifiedAchievements = \App\Models\AchievementModel::verified()
            ->whereDate('verified_at', '<', $thirtyDaysAgo)
            ->count();
        $oldVerificationPercentage = $oldTotalAchievements > 0 ? round(($oldVerifiedAchievements / $oldTotalAchievements) * 100) : 0;
        
        // Hitung tren (selisih antara persentase saat ini dan lama)
        $percentageTrend = $verificationPercentage - $oldVerificationPercentage;
        $trendDirection = $percentageTrend >= 0 ? '↗︎' : '↘︎';
        $trendText = $trendDirection . ' ' . abs($percentageTrend) . '% (30 hari)';

        // Statistik kompetisi aktif
        $activeCompetitions = \App\Models\CompetitionModel::where(function($query) {
            $query->ongoing()->orWhere('status', 'upcoming');
        })->count();
        
        $newCompetitionsThisMonth = \App\Models\CompetitionModel::whereDate('created_at', '>=', now()->startOfMonth())->count();

        // Ambil aktivitas terbaru dari database
        $activities = \App\Services\ActivityService::getLatest(5);
        
        // Ambil data statistik prestasi dari database
        $achievementStats = \App\Services\AchievementStatisticsService::getStatisticsData();

        $stats = [
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                'title' => 'Total Pengguna',
                'value' => $totalUsers,
                'trend' => '↗︎ ' . $newUsers . ' (30 hari)'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Prestasi Terverifikasi',
                'value' => $verificationPercentage . '%',
                'trend' => $trendText
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'title' => 'Verifikasi Tertunda',
                'value' => $pendingVerifications,
                'trend' => 'Perlu perhatian'
            ],
            [
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',
                'title' => 'Kompetisi Aktif',
                'value' => $activeCompetitions,
                'trend' => '↗︎ ' . $newCompetitionsThisMonth . ' ditambahkan bulan ini'
            ]
        ];

        return view('admin.dashboard', compact('stats', 'totalUsers', 'newUsers', 'activeUsers', 'activities', 'achievementStats'));
    }

    public function studentDashboard()
    {
        return view('student.dashboard');
    }

    public function lecturerDashboard()
    {
        return view('dosen.dashboard');
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
