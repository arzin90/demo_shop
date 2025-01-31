<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\Customer\CheckTokenRequest;
use App\Api\V1\Requests\Customer\CustomerIndexRequest;
use App\Api\V1\Requests\Customer\CustomerStoreRequest;
use App\Api\V1\Requests\Customer\CustomerUpdateRequest;
use App\Api\V1\Requests\Customer\VerifyWithPasswordRequest;
use App\Api\V1\Services\CustomerService;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends BaseApiController
{
    /**
     * @param CustomerService $service
     */
    public function __construct(private readonly CustomerService $service)
    {
    }

    /**
     * Display a listing of the customers.
     *
     * @param CustomerIndexRequest $request
     * @return JsonResponse
     */
    public function index(CustomerIndexRequest $request): JsonResponse
    {
        return $this->sendResponse($this->service->getData($request->validated()));
    }

    /**
     * Create new customer
     *
     * @param CustomerStoreRequest $request
     * @return JsonResponse
     */
    public function store(CustomerStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $customer = $this->service->store($request->validated());
            $customer->notify(new VerifyEmailNotification());

            DB::commit();
            return $this->sendResponseMessage(__('messages.customer.added'), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

        return $this->sendInternalError();
    }

    /**
     * Display the customer information.
     */
    public function show(int $id): JsonResponse
    {
        return $this->sendResponse($this->service->byId($id));
    }

    /**
     * Update the customer information.
     *
     * @param CustomerUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CustomerUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $this->service->update($request->validated(), $id);

            return $this->sendResponseMessage(__('messages.customer.updated'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return $this->sendInternalError();
    }

    /**
     * @param CheckTokenRequest $request
     * @return JsonResponse
     */
    public function checkToken(CheckTokenRequest $request): JsonResponse
    {
        $data = $request->validated();

        $checkToken = $this->service->checkToken($data['token']);

        if (!$checkToken['isValid']) {
            return $this->sendError($checkToken['message'], Response::HTTP_FORBIDDEN);
        }

        return $this->sendResponseMessage($checkToken['message']);
    }

    /**
     * Verify email and set password
     *
     * @param VerifyWithPasswordRequest $request
     * @return JsonResponse
     */
    public function verify(VerifyWithPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $verifyWithPassword = $this->service->verifyWithPassword($data['token'], $data['password']);

            if (!$verifyWithPassword['isValid']) {
                return $this->sendError($verifyWithPassword['message'], Response::HTTP_FORBIDDEN);
            }

            return $this->sendResponseMessage($verifyWithPassword['message']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return $this->sendInternalError();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->destroy($id);
            return $this->sendResponseMessage(__('messages.customer.deleted'));
        } catch (ModelNotFoundException $e) {
            return $this->sendError();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return $this->sendInternalError();
    }
}
