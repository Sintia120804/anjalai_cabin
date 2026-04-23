<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $userFromGoogle = Socialite::driver('google')
                ->stateless()
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => base_path('cacert.pem')]))
                ->user();
            
            $userFromDb = User::where('google_id', $userFromGoogle->getId())
                              ->orWhere('email', $userFromGoogle->getEmail())
                              ->first();

            if (!$userFromDb) {
                $userFromDb = User::create([
                    'name'      => $userFromGoogle->getName(),
                    'email'     => $userFromGoogle->getEmail(),
                    'google_id' => $userFromGoogle->getId(),
                    'password'  => bcrypt(Str::random(16)),
                    'role'      => 'pengunjung', // Default role
                ]);
            } else {
                // Update google_id if user already exists by email
                if (is_null($userFromDb->google_id)) {
                    $userFromDb->update(['google_id' => $userFromGoogle->getId()]);
                }
            }

            Auth::login($userFromDb);
            session()->regenerate();

            return redirect()->intended('/');
            
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Gagal login dengan Google: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
