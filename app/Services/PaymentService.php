<?php

namespace App\Services;
use App\Services\Invoice\InvoiceCalculator;
use App\Models\Invoice;
use App\Models\Payment;

class PaymentService
{
        /**
     * @var Invoice
     */
    private $invoice;
    /** @var Money */
    private $price;
    /** @var int  */
    private $sum;

    public function __construct(Invoice $invoice)
    {
        $calculator = app(InvoiceCalculator::class, ['invoice' => $invoice]);

        $this->invoice = $invoice;
        $this->price = $calculator->getTotalPrice();
        $this->sum = (int) $this->invoice->payments()->sum('amount');
        //$this->remise = $calculator->getRemise();
    }

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