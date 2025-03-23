<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(): JsonResponse
    {
        $settings = $this->settingService->getAllSettings();
        return response()->json($settings);
    }

    public function update(Request $request, $key): JsonResponse
    {
        $setting = $this->settingService->updateSetting($key, $request->value);
        return response()->json($setting);
    }
}