<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Derma;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestDonationsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Derma Terkini Yang Berjaya ';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Derma::query()->where('status_bayaran', 'Berjaya')->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Masa')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('penderma.nama_penderma')
                    ->label('Penderma')
                    ->default('Hamba Allah'),
                Tables\Columns\TextColumn::make('kempen.tajuk_kempen')
                    ->label('Kempen')
                    ->limit(40),
                Tables\Columns\TextColumn::make('amaun_derma')
                    ->label('Sumbangan')
                    ->money('MYR')
                    ->color('success')
                    ->weight('bold'),
            ])
            ->paginated(false);
    }
}
