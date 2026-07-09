@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Dashboard</h1>
            <p class="text-gray-400 mt-1">Welcome back, <span class="text-cyan-400">{{ Auth::user()->name }}</span>!</p>
        </div>
        <div class="hidden sm:block">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                <span class="w-2 h-2 bg-cyan-400 rounded-full mr-2 animate-pulse"></span>
                Online
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-gray-800/40 backdrop-blur-xl border border-gray-700/50 rounded-3xl p-6 shadow-xl lg:col-span-2">
            <h2 class="text-xl font-bold text-white mb-6">Activity Overview</h2>
            
            @if (session('status'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl mb-6 flex items-center gap-3" role="alert">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif
            
            <div class="flex flex-col items-center justify-center h-64 border-2 border-dashed border-gray-700/50 rounded-2xl bg-gray-800/20">
                <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mb-4 border border-gray-700">
                    <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <p class="text-gray-400 font-medium">No recent activity to display.</p>
                <p class="text-gray-500 text-sm mt-1">Your activities will show up here once you start using the platform.</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-gradient-to-br from-cyan-900/40 to-blue-900/40 backdrop-blur-xl border border-cyan-500/20 rounded-3xl p-6 shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-all"></div>
                <h3 class="text-lg font-bold text-white mb-4 relative z-10">Quick Actions</h3>
                <div class="space-y-3 relative z-10">
                    <button class="w-full flex items-center justify-between bg-gray-900/60 hover:bg-gray-800 border border-gray-700 hover:border-cyan-500/50 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-300 transition-all group/btn">
                        Edit Profile
                        <svg class="w-4 h-4 text-gray-500 group-hover/btn:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <button class="w-full flex items-center justify-between bg-gray-900/60 hover:bg-gray-800 border border-gray-700 hover:border-blue-500/50 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-300 transition-all group/btn">
                        Account Settings
                        <svg class="w-4 h-4 text-gray-500 group-hover/btn:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
