<?php

namespace App\Filament\User\Resources\FinancialResource\RelationManagers;

use App\Models\Invoice;
use App\Models\Website;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceRelationManager extends RelationManager
{
    protected static string $relationship = 'transaction';
    protected static ?string $title = 'Invoice';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('invoice_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('invoice')
            ->columns([
                Tables\Columns\TextColumn::make('invoice.invoice_id')
                    ->label('Invoice ID'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('View')->button()->color('success')
                    ->modalHeading('Invoice')
                    ->icon('heroicon-o-eye')
                    // ->modalContent(fn ($record) => view('filament.components.invoice', ['invoice' => $record->invoice]))
                    ->modalContent(function ($record) {
                        $invoice = Invoice::where('id', $record->id)->first();
                        $website = Website::select('inv_title', 'inv_logo', 'invoice_bank', 'inv_email', 'inv_phone')->first();
                        if ($website->inv_logo) {
                            $imagePath = public_path('storage/' . $website->inv_logo);

                            if (file_exists($imagePath)) {
                                $mimeType = mime_content_type($imagePath);
                                $imageData = base64_encode(file_get_contents($imagePath));

                                $imageSrc = 'data:' . $mimeType . ';base64,' . $imageData;
                            } else {
                                $imageSrc = '';
                            }
                        } else {
                            $imageSrc = '';
                        }

                        $invoiceID = $invoice->invoice_id;
                        $fileName = "{$invoiceID}-{$website->inv_title}.pdf";
                        return view('print', compact('invoice', 'fileName', 'website', 'imageSrc'));
                    })
                    ->modalButton('Close')
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false),
                Tables\Actions\Action::make('Print')->button()->color('success')
                    // ->requiresConfirmation()
                    ->modalIcon('heroicon-o-printer')
                    ->icon('heroicon-o-printer')
                    ->action(fn ($record) => self::printInvoice($record->id)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function printInvoice($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $website = Website::select('inv_title', 'inv_logo', 'invoice_bank', 'inv_email', 'inv_phone')->first();
        if ($website->inv_logo) {
            $imagePath = public_path('storage/' . $website->inv_logo);

            if (file_exists($imagePath)) {
                $mimeType = mime_content_type($imagePath);
                $imageData = base64_encode(file_get_contents($imagePath));

                $imageSrc = 'data:' . $mimeType . ';base64,' . $imageData;
            } else {
                $imageSrc = '';
            }
        } else {
            $imageSrc = '';
        }

        $invoiceID = $invoice->invoice_id;
        $fileName = "{$invoiceID}-{$website->inv_title}.pdf";
        $pdf = Pdf::loadView('print', compact('invoice', 'fileName', 'website', 'imageSrc'));
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isRemoteEnabled', true);

        $pdf->render();

        return response()->streamDownload(fn () => print($pdf->output()), $fileName);
    }
}
