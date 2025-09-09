<?php

namespace App\Services\Auth;

use App\Models\CompanyStructure;
use App\Models\User;
use Hash;

class AuthService
{
    public function login($data)
    {
        if (!auth()->attempt($data))
            return \response()->json([
                'success' => 0,
                'message' => 'Пароль или Email не верный',
                'error' => '-'
            ]);

        $user = auth()->user();

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'role_id' => auth()->user()->role->permissions->contains('slug', 'user_add') ? 1 : 0,
            'lastname' => $user->lastname,
            'role' => $user->role->slug,
            'role_name' => $user->role->name,
            'super' => in_array($user->role->id, [1, 8]),
            'tg_token' => $user->tg_token,
        ];

        $token = auth()->user()->createToken('API Token')->plainTextToken;

        $isRobot = $user->role->permissions->contains('slug', 'robot');

        if ($isRobot)
            return [
                'token' => explode('|', $token)[1],
            ];

        return [
            'laravel_session' => session()->getId(),
            'xsrf-token' => csrf_token(),
            'success' => true,
            'data' => $data,
            'token' => explode('|', $token)[1],
        ];
    }



    public function logout($data)
    {
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $data = $headers['Authorization'];
            $data = \explode(' ', $data)[1];
            $token = \Laravel\Sanctum\PersonalAccessToken::findToken($data);
            if ($token) {
                $user = $token->tokenable;
                $user->tokens()->delete();
            }
            return \response()->json([
                'status' => true,
                'success' => 1,
                'message' => 'Вы вышли из системы',
                'dd' => $headers['Authorization'],
            ]);
        }
    }



    public function register($data = [])
    {
        $user = User::create([
            'name' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'phone' => isset($data['phone']) ? preg_replace("/[^,.0-9]/", '', $data['phone']) : ''
        ]);

        if (isset($data['appeals']))
            $user->appeals()->sync($data['appeals']);

        if (isset($data['trafic_appeals']))
            $user->trafic_appeals()->sync($data['trafic_appeals']);

        $this->saveStructures($user, $data['structures']);

        return $user;
    }



    public function update(User $user, $data = [])
    {
        $arr = [
            'name' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'role_id' => $data['role_id'],
            'phone' => $data['phone'], //isset($data['phone']) ? preg_replace("/[^,.0-9]/", '', $data['phone']) : ''
        ];

        if (isset($data['password']) && $data['password'] != '')
            $arr['password'] = Hash::make($data['password']);

        $user->fill($arr)->save();

        if (isset($data['appeals']))
            $user->appeals()->sync($data['appeals']);

        if (isset($data['trafic_appeals']))
            $user->trafic_appeals()->sync($data['trafic_appeals']);

        $this->saveStructures($user, $data['structures']);

        return $user;
    }



    public function check($data = [])
    {
        return response()->json([
            'data1' => auth()->user()->role->permissions,
            'data' => 1
        ]);
    }


    
    private function saveStructures(User $user, array $structures)
    {
        if ($structures) {
            \App\Models\UserCompanyStructure::where('user_id', $user->id)->delete();
            foreach ($structures as $item) {
                $companyStructures = CompanyStructure::find($item);
                \App\Models\UserCompanyStructure::create([
                    'user_id' => $user->id,
                    'company_structure_id' => $companyStructures->id,
                    'company_id' => $companyStructures->company_id
                ]);
            }
        }
    }
}
