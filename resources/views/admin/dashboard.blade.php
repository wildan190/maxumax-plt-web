@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang kembali, ' . auth()->user()->name . '!')

@section('content')
<div>
    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Stat Card -->
        <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                    Total Pengguna
                </h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px; color: var(--text-light);">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary); margin: 0;">1,250</p>
            <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.5rem 0 0;">+42 bulan ini</p>
        </div>

        <!-- Stat Card -->
        <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                    Aktivitas Hari Ini
                </h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px; color: var(--text-light);">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
            <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary); margin: 0;">328</p>
            <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.5rem 0 0;">Interaksi baru</p>
        </div>

        <!-- Stat Card -->
        <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                    Tingkat Pertumbuhan
                </h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px; color: var(--text-light);">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 17"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
            </div>
            <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary); margin: 0;">12.5%</p>
            <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.5rem 0 0;">Bulan lalu vs bulan ini</p>
        </div>

        <!-- Stat Card -->
        <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem; transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                    Performa
                </h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px; color: var(--text-light);">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="19" cy="12" r="1"></circle>
                    <circle cx="5" cy="12" r="1"></circle>
                </svg>
            </div>
            <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary); margin: 0;">98.2%</p>
            <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.5rem 0 0;">Uptime sistem</p>
        </div>
    </div>

    <!-- Recent Activity -->
    <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin: 0 0 1.5rem;">Aktivitas Terbaru</h2>
        
        <div style="space-y: 1rem;">
            @for ($i = 1; $i <= 5; $i++)
                <div style="display: flex; align-items: flex-start; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-gray);">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--light-gray); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px; color: var(--primary);">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div style="flex: 1;">
                        <p style="font-weight: 600; color: var(--primary); margin: 0; font-size: 0.95rem;">Pengguna baru mendaftar</p>
                        <p style="color: var(--text-light); font-size: 0.875rem; margin: 0.25rem 0 0;">John Doe baru saja membuat akun</p>
                        <p style="color: var(--text-light); font-size: 0.75rem; margin: 0.5rem 0 0;">{{ now()->subHours($i)->diffForHumans() }}</p>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>
@endsection
