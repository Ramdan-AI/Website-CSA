<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Member;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    public function redirectTo()
    {
        if (auth()->check()) {
            return '/'.auth()->user()->role->slug.'/dashboard';
        }

        return '/login';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['success']);
        $this->middleware('auth')->only(['success']);
    }

    /**
     * Show the application registration form.
     *
     * @return View
     */
    public function showRegistrationForm()
    {
        $divisions = Division::active()->orderBy('name')->get();

        return view('auth.register', compact('divisions'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nim' => ['required', 'numeric', 'digits_between:6,20', 'unique:members,nim'],
            'semester' => ['required', 'integer', 'between:1,8'],
            'level' => ['nullable', 'integer', 'in:1,2,3'],
            'whatsapp' => ['required', 'string', 'regex:/^[0-9+\-\s]+$/', 'max:20'],
            'division_id' => ['required', 'exists:divisions,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.numeric' => 'NIM hanya boleh berisi angka.',
            'nim.digits_between' => 'NIM harus berupa 6-20 digit angka.',
            'nim.unique' => 'NIM sudah terdaftar. Silakan gunakan NIM yang berbeda.',
            'semester.required' => 'Silakan pilih semester kamu saat ini.',
            'semester.between' => 'Semester harus antara 1 sampai 8.',
            'level.in' => 'Tingkatan yang dipilih tidak valid.',
            'whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'division_id.required' => 'Silakan pilih divisi yang ingin kamu ikuti.',
            'division_id.exists' => 'Divisi yang dipilih tidak valid.',
        ]);

        $validator->after(function ($validator) use ($data) {
            $isLevelDivision = ! empty($data['division_id'])
                && Division::whereKey($data['division_id'])->value('has_levels');

            if ($isLevelDivision && blank($data['level'] ?? null)) {
                $validator->errors()->add('level', 'Silakan pilih tingkatan kamu untuk divisi ini.');
            }
        });

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        $defaultRoleId = Role::where('slug', 'anggota')->value('id');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'whatsapp' => $data['whatsapp'],
            'password' => Hash::make($data['password']),
            'role_id' => $defaultRoleId,
        ]);

        // Level hanya relevan untuk divisi bertingkat (mis. Pemrograman).
        $divisionHasLevels = Division::whereKey($data['division_id'])->value('has_levels');

        Member::create([
            'user_id' => $user->id,
            'division_id' => $data['division_id'],
            'name' => $user->name,
            'nim' => $data['nim'],
            'semester' => $data['semester'],
            'level' => $divisionHasLevels ? ($data['level'] ?? null) : null,
            'email' => $user->email,
            'phone' => $data['whatsapp'],
            'is_active' => true,
        ]);

        return $user;
    }

    /**
     * The user has been registered.
     *
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        return redirect()->route('register.success');
    }

    /**
     * Show the registration success and community onboarding page.
     *
     * @return View
     */
    public function success()
    {
        $user = auth()->user();
        $member = Member::with('division')->where('user_id', $user->id)->first();
        $setting = Setting::first();
        $whatsappGroupLink = $setting?->whatsapp_group_link;

        return view('auth.register-success', compact('user', 'member', 'whatsappGroupLink'));
    }
}
