<?php

namespace App\Services;

use App\Models\Payment;

class PaymentService
{
    public function getAllPayments()
    {
        return Payment::with(['invoice', 'invoice.client'])->get();
    }

    public function getPaymentById($id)
    {
        return Payment::with(['invoice','invoice.client'])->findOrFail($id);
    }

    public function updatePayment($id, array $data)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($data);
        return $payment->fresh();
    }

    public function deletePayment($id)
    {
        $payment = Payment::findOrFail($id);
        return $payment->delete();
    }
}