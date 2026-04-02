<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use App\Models\Business;
use App\Models\Event;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $pendingBusinesses = Business::pending()->count();
        $pendingEvents = Event::pending()->count();

        return [
            Stat::make('Pending Approvals', $pendingBusinesses + $pendingEvents)
                ->description($pendingBusinesses . ' businesses, ' . $pendingEvents . ' events')
                ->color($pendingBusinesses + $pendingEvents > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-clock'),

            Stat::make('Upcoming Events', Event::approved()->upcoming()->count())
                ->description('Approved & scheduled')
                ->color('primary')
                ->icon('heroicon-o-calendar-days'),

            Stat::make('Active Businesses', Business::approved()->count())
                ->description(User::where('role', 'business_owner')->count() . ' business owners')
                ->color('success')
                ->icon('heroicon-o-building-storefront'),

            Stat::make('Published Posts', BlogPost::published()->count())
                ->description(BlogPost::draft()->count() . ' drafts')
                ->color('primary')
                ->icon('heroicon-o-document-text'),
        ];
    }
}
