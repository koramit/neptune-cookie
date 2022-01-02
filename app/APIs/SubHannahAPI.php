<?php

namespace App\APIs;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubHannahAPI
{
    public function authenticate($login, $password)
    {
        $password = str_replace('+', 'BuAgSiGn', $password);
        $password = str_replace('%', 'PeRcEnTsIgN', $password);
        $password = str_replace('&', 'LaEsIgN', $password);
        $password = str_replace('=', 'TaOkUbSiGn', $password);

        $data = $this->makePost('auth', ['login' => $login, 'password' => $password]);
        if (! $data || ! $data['ok']) { // error: $data = null
            return [
                'found' => false,
                'message' => ($data['status'] ?? 500) === 400 ? __('auth.failed') : __('service.failed'),
            ];
        }

        if (! isset($data['found']) || ! $data['found']) {
            $data['message'] = $data['message'] ?? __('auth.failed');
            unset($data['UserInfo']);
            unset($data['body']);

            return $data;
        }

        return [
            'ok' => $data['ok'],
            'found' => $data['found'],
            'username' => $data['login'],
            'name' => $data['full_name'],
            'name_en' => $data['full_name_en'],
            'email' => $data['email'],
            'org_id' => $data['org_id'],
            'tel_no' => $data['tel_no'] ?? null,
            'document_id' => null,
            'org_division_name' => $data['division_name'],
            'org_position_title' => $data['position_name'],
            'remark' => $data['remark'],
            'password_expires_in_days' => $data['password_expires_in_days'],
        ];
    }

    public function getUserById($id)
    {
        $data = $this->makePost('user-by-id', ['org_id' => $id]);

        if (! $data || ! $data['ok']) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        if (! isset($data['found']) || ! $data['found']) {
            $data['message'] = $data['message'] ?? __('auth.failed');
            unset($data['UserInfo']);
            unset($data['body']);

            return $data;
        }

        return $data;
    }

    protected function makePost($url, $data)
    {
        $headers = ['app' => config('services.SUBHANNAH_API_NAME'), 'token' => config('services.SUBHANNAH_API_TOKEN')];
        try {
            $response = Http::timeout(2)
                            ->withHeaders($headers)
                            ->withOptions(['verify' => ! str_contains(config('services.SUBHANNAH_API_URL'), '10.7.')])
                            ->retry(5, 100, fn ($exception) => $exception instanceof ConnectionException)
                            ->post(config('services.SUBHANNAH_API_URL').$url, $data);
        } catch (Exception $e) {
            $errorsInAWhile = Cache::remember('connection-errors-in-a-while', 60, fn () => 0) + 1;
            Cache::increment('connection-errors-in-a-while');
            if ($errorsInAWhile > 3) {
                Log::error($url.'@hannah '.$e->getMessage());
            }

            return ['ok' => false];
        }

        if ($response->successful()) {
            return $response->json();
        }

        return ['ok' => false];
    }
}
