<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Front\UserRegisterRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/start';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('protect_against_spam')->only('register');
    }

    public function showRegistrationForm()
    {
        return view('front.auth.register');
    }

    public function register(UserRegisterRequest $request)
    {
        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'action' => 'redirect',
                'destination' => $this->redirectTo,
            ]);
        }

        session()->put('success', 'Вы успешно зарегистрировались!');

        return $this->registered($request, $user)
            ?: redirect('/account');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $attributes)
    {
        $password = $attributes['password'];
        $user = User::create([
            'name' => $attributes['name'],
            'last_name' => $attributes['last_name'] ?? null,
            'email' => $attributes['email'],
            'password' => Hash::make($password),

            'phone' => $attributes['phone'],
            'birthday' => $attributes['birthday'] ?? null,
            'data' => $attributes['data'] ?? [],
        ]);
        $user->assignRole('client');

        return $user;
    }
}
