<?php

namespace App\Filament\Pages;

use App\Models\Tour;
use App\Models\User;
use Filament\Pages\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;

class TourReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationGroup = 'Reporting';

    protected static string $view = 'filament.pages.tour-report';

    public ?array $data = [];

    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole('super admin'), 403);
        $this->form->fill();
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super admin');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date_from')
                    ->label('From Date')
                    ->live(),
                DatePicker::make('date_to')
                    ->label('To Date')
                    ->live(),
                Select::make('driver_id')
                    ->label('Driver')
                    ->options(User::role('driver')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live(),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function getFilteredToursProperty()
    {
        $data = $this->form->getState();

        $query = Tour::query()->with(['user', 'vehicle']);

        if (!empty($data['date_from'])) {
            $query->whereDate('created_at', '>=', $data['date_from']);
        }

        if (!empty($data['date_to'])) {
            $query->whereDate('created_at', '<=', $data['date_to']);
        }

        if (!empty($data['driver_id'])) {
            $query->where('user_id', $data['driver_id']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Print PDF Report')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->action('generateReport'),
        ];
    }

    public function generateReport()
    {
        $data = $this->form->getState();
        $tours = $this->filtered_tours; // uses the getFilteredToursProperty

        $pdf = Pdf::loadView('reports.tours', ['tours' => $tours, 'filters' => $data]);
        return response()->streamDownload(fn () => print($pdf->output()), 'tour-report.pdf');
    }
}
