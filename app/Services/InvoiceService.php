<?php

namespace App\Services;

use App\Models\Invoice;

class InvoiceService
{
    public function getAllInvoices()
    {
        return Invoice::with(['client', 'invoiceLines', 'payments', 'offer'])->get();
    }

    public function getInvoiceById($id)
    {
        return Invoice::with(['client', 'invoiceLines', 'payments', 'offer'])->findOrFail($id);
    }
}