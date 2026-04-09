<?php

namespace App\Services;

use App\Models\Order;

class InvoicePdfService
{
    /**
     * Generate a synthetic invoice PDF from an order.
     * In a real app, this would use a library like Dompdf or TCPDF.
     */
    public function generatePdf(Order $order): string
    {
        // Placeholder implementation for PDF generation
        $invoiceContent = "INVOICE\n";
        $invoiceContent .= "Invoice Number: INV-" . str_replace('ORD-', '', $order->orderNumber) . "\n";
        $invoiceContent .= "Order Number: " . $order->orderNumber . "\n";
        $invoiceContent .= "Date: " . $order->date->format('Y-m-d H:i:s') . "\n";
        $invoiceContent .= "Total Amount: EUR " . number_format($order->totalAmount, 2) . "\n";
        $invoiceContent .= "VAT (9%): EUR " . number_format($order->totalAmount * 0.09, 2) . "\n";
        $invoiceContent .= "Total including VAT: EUR " . number_format($order->totalAmount * 1.09, 2) . "\n\n";
        $invoiceContent .= "Items:\n";

        foreach ($order->items as $item) {
            $name = $item->ticket->name ?? 'Ticket';
            $invoiceContent .= "- $name x {$item->quantity}: EUR " . number_format($item->price * $item->quantity, 2) . "\n";
        }

        return $invoiceContent; // Returning a string as fake PDF bytes
    }
}
