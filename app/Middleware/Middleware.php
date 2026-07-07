<?php
namespace Middlewares;

use App\Models\User;
use Bpjs\Framework\Helpers\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Bpjs\Framework\Helpers\Session;  // <-- Gunakan Session, bukan Auth

class Middleware
{
    public function handle()
    {
        $token = $this->getTokenFromRequest();
        
        if (!$token) {
            Response::json([
                'status' => 401,
                'message' => 'Token tidak ditemukan'
            ], 401);
            exit();
        }

        $user = $this->validateAndGetUser($token);
        
        if (!$user) {
            Response::json([
                'status' => 401,
                'message' => 'Token tidak valid'
            ], 401);
            exit();
        }

        // ==========================================
        // SET USER KE SESSION (bukan Auth)
        // ==========================================
        Session::set('user_id', $user->id);
        Session::set('user_name', $user->name);
        Session::set('user_role', $user->role);
        return true;
    }

    private function getTokenFromRequest()
    {
        $headers = getallheaders();
        
        $cookieToken = cookie_get('token');
        if ($cookieToken) {
            return $cookieToken;
        }

        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (strpos($authHeader, 'Bearer ') === 0) {
                return substr($authHeader, 7);
            }
            return $authHeader;
        }

        return null;
    }

    private function validateAndGetUser($token)
    {
        try {
            $jwtSecret = env('JWT_SECRET');
            
            if (strlen($jwtSecret) < 16) {
                $jwtSecret = 'koperasi_stanley_secret_key_2024_';
            }
            
            $decoded = JWT::decode($token, new Key($jwtSecret, 'HS256'));
            
            // Cari user
            $user = null;
            
            // Cari berdasarkan user_id
            if (isset($decoded->user_id)) {
                $user = User::find($decoded->user_id);
            }
            
            // Cari berdasarkan sub (uuid)
            if (!$user && isset($decoded->sub)) {
                $user = User::query()->where('id','=',$decoded->sub)->first();
            }

            if (!$user) {
                Response::json([
                    'status' => 401,
                    'message' => 'User tidak ditemukan'
                ], 401);
                return null;
            }

            return $user;
            
        } catch (\Exception $e) {
            Response::json([
                'status' => 401,
                'message' => 'Token tidak valid'
            ], 401);
            return null;
        }
    }
}