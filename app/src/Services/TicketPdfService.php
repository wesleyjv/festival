<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use TCPDF;

/**
 * TicketPdfService – generates a PDF ticket document for a completed order.
 *
 * Uses the TCPDF library (already in composer.json) for both PDF rendering
 * and QR-code generation via its built-in write2DBarcode() method.
 * Each ticket is rendered on its own page.
 */
class TicketPdfService
{
    /**
     * Generate a PDF with one page per ticket in the order.
     *
     * Each page shows the order number, ticket name, quantity, price,
     * a scannable QR code, and the plain-text ticket code.
     *
     * @param  Order  $order The order whose items should be rendered.
     * @return string Raw PDF bytes (suitable for streaming or saving).
     */
    public function generatePdf(Order $order): string
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator('Festival App');
        $pdf->SetTitle('Tickets - ' . $order->orderNumber);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false);

        foreach ($order->items as $item) {
            if ($item->ticket === null) {
                continue;
            }

            $pdf->AddPage();

            $qrData = 'TICKET-' . $item->ticket->ticketCode;

            // Order number at the top
            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetTextColor(120, 120, 120);
            $pdf->Cell(0, 8, 'Order: ' . $order->orderNumber, 0, 1, 'L');
            $pdf->Ln(6);

            // Ticket name
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('helvetica', 'B', 24);
            $pdf->MultiCell(0, 14, $item->ticket->name, 0, 'L');
            $pdf->Ln(4);

            // Divider line
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
            $pdf->Ln(6);

            // Quantity and price
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(95, 8, 'Quantity: ' . $item->quantity, 0, 0, 'L');
            $pdf->Cell(95, 8, 'Price: ' . number_format($item->price, 2) . ' EUR', 0, 1, 'L');
            $pdf->Ln(10);

            // QR code – centred, large
            $style = [
                'border'  => false,
                'padding' => 2,
                'fgcolor' => [0, 0, 0],
                'bgcolor' => [255, 255, 255],
            ];
            $qrSize = 60;
            $qrX = ($pdf->getPageWidth() - $qrSize) / 2;
            $pdf->write2DBarcode($qrData, 'QRCODE,M', $qrX, $pdf->GetY(), $qrSize, $qrSize, $style);
            $pdf->SetY($pdf->GetY() + $qrSize + 6);

            // Ticket code below QR
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 8, $item->ticket->ticketCode, 0, 1, 'C');
        }

        return $pdf->Output('tickets.pdf', 'S');
    }
}