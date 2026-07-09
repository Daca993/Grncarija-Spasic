<?php

namespace App\Domain\Order\Mail;

use App\Domain\Order\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nova porudžbina #' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-placed',
        );
    }

    public function attachments(): array
    {
        $this->order->load('items');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Porudžbina');

        $sheet->setCellValue('A1', 'Red.');
        $sheet->setCellValue('B1', 'Proizvod');
        $sheet->setCellValue('C1', 'Veličina');
        $sheet->setCellValue('D1', 'Cena');
        $sheet->setCellValue('E1', 'Količina');
        $sheet->setCellValue('F1', 'Ukupno');
        $row = 2;
        foreach ($this->order->items as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->product_name);
            $sheet->setCellValue('C' . $row, $item->size_label_with_cm);
            $sheet->setCellValue('D' . $row, (float) $item->price);
            $sheet->setCellValue('E' . $row, $item->quantity);
            $sheet->setCellValue('F' . $row, (float) $item->subtotal);
            $row++;
        }
        $sheet->setCellValue('E' . $row, 'UKUPNO:');
        $sheet->setCellValue('F' . $row, (float) $this->order->total);

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/temp/order-' . $this->order->order_number . '.xlsx');
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        $writer->save($path);

        return [
            Attachment::fromPath($path)->as('porudzbina-' . $this->order->order_number . '.xlsx'),
        ];
    }
}
