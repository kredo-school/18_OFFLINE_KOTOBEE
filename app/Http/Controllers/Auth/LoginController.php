<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use Illuminate\Foundation\Auth\AuthenticatesUsers;
    use Illuminate\Support\Facades\Auth;
    // use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */    

    // ログインした際のダイレクト先
    // protected $redirectTo = '/game/select';    
    // protected $redirectTo = '/home';
    protected function redirectTo()
    {
        /** @var User $user */        
        $user = Auth::user();

        // もしグループ管理者だった場合、複数あるグループのうちの最も古いグループに移動する
        $group = $user->my_groups()->oldest('created_at')->first();

        if ($group) {
            return route('group.dashboard', ['group_id' => $group->id]);
        }

        return '/game/select';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}