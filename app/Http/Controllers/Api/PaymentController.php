<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Payment;
//use App\Services\InvoiceCalculator;
use App\Services\Invoice\InvoiceCalculator;
use Illuminate\Support\Facades\Log;

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
    $payment = Payment::findOrFail($id);
    $calculator = app(InvoiceCalculator::class, ['invoice' => $payment->invoice]);

    // Récupérer la somme des paiements effectués (hors paiement en modification)
    $totalpaye = Payment::where('invoice_id', $payment->invoice_id)
                        ->where('id', '!=', $id) // Exclure le paiement en cours de modification
                        ->sum('amount');

    // Récupérer le prix total de la facture
    $totalprice = $calculator->getTotalPrice()->getAmount();

    // Nouveau montant du paiement
    $newAmount = (float) $request->input('amount');

    Log::info('Totalprice: ' . $totalprice);
    Log::info('Totalpaye (hors paiement en cours): ' . $totalpaye);
    Log::info('NewAmount: ' . $newAmount);

    // Vérification du montant
    if (($totalpaye + $newAmount) > $totalprice) {
        return response()->json([
            'error' => 'Le montant total des paiements ne peut pas dépasser le montant total de la facture.'
        ], 400);
    }

    // Mise à jour du paiement
    $payment = $this->paymentService->updatePayment($id, $request->all());
    return response()->json($payment);
}



    public function destroy($id): JsonResponse
    {
        $this->paymentService->deletePayment($id);
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
