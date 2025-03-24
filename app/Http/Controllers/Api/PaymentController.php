<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Invoice\GenerateInvoiceStatus;

class PaymentController extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(): JsonResponse
    {
        $payments = $this->paymentService->getAllPayments();
        return response()->json($payments);
    }

    public function show($id): JsonResponse
    {
        $payment = $this->paymentService->getPaymentById($id);
        return response()->json($payment);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $payment = $this->paymentService->updatePayment($id, $request->all());
        
        app(GenerateInvoiceStatus::class, ['invoice' => $invoice])->createStatus();
        return response()->json($payment);
    }

    public function destroy($id): JsonResponse
    {
        $this->paymentService->deletePayment($id);
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}