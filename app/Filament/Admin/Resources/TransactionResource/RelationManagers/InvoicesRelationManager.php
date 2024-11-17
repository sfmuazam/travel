<?php

namespace App\Filament\Admin\Resources\TransactionResource\RelationManagers;

use App\Models\Invoice;
use App\Models\Website;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoice';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('invoice_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('invoices_id')
            ->columns([
                TextColumn::make('invoice_id')
                    ->label('Invoice ID'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
                TextColumn::make('expires_at')
                    ->label('Expires At')
                    ->dateTime(),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'cancelled' => 'Cancel',
                            'pending' => 'Unpaid',
                            'paid' => 'Paid',
                            default => $state,
                        };
                    }),
                ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('View')->button()->color('success')
                    ->modalHeading('Invoice')
                    ->icon('heroicon-o-eye')
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
                    ->modalSubmitAction(false)
                    ,
                Tables\Actions\Action::make('Print')->button()->color('success')
                    // ->requiresConfirmation()
                    ->modalIcon('heroicon-o-printer')
                    ->icon('heroicon-o-printer')
                    ->action(fn ($record) => self::printInvoice($record->id))
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

        // $pdf->render();

        return response()->streamDownload(fn () => print($pdf->output()), $fileName);
    }
}
