<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\Customer\CheckTokenRequest;
use App\Api\V1\Requests\Customer\CustomerStoreRequest;
use App\Api\V1\Requests\Customer\VerifyWithPasswordRequest;
use App\Api\V1\Services\CustomerService;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * @param CustomerStoreRequest $request
     * @param CustomerService $service
     * @return JsonResponse
     */
    public function store(CustomerStoreRequest $request, CustomerService $service): JsonResponse
    {
        DB::beginTransaction();

        try {
            $customer = $service->store($request->validated());
            $customer->notify(new VerifyEmailNotification());

            DB::commit();
            return $this->sendResponse(['message' => __('messages.customer.added')], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

        return $this->sendInternalError();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * @param CheckTokenRequest $request
     * @param CustomerService $service
     * @return JsonResponse
     */
    public function checkToken(CheckTokenRequest $request, CustomerService $service): JsonResponse
    {
        $data = $request->validated();

        $checkToken = $service->checkToken($data['token']);

        if (!$checkToken['isValid']) {
            return $this->sendError($checkToken['message'], Response::HTTP_FORBIDDEN);
        }

        return $this->sendResponse(['message' => $checkToken['message']]);
    }

    /**
     * @param VerifyWithPasswordRequest $request
     * @param CustomerService $service
     * @return JsonResponse
     */
    public function verify(VerifyWithPasswordRequest $request, CustomerService $service): JsonResponse
    {
        $data = $request->validated();

        try {
            $verifyWithPassword = $service->verifyWithPassword($data['token'], $data['password']);

            if (!$verifyWithPassword['isValid']) {
                return $this->sendError($verifyWithPassword['message'], Response::HTTP_FORBIDDEN);
            }

            return $this->sendResponse(['message' => $verifyWithPassword['message']]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return $this->sendInternalError();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
