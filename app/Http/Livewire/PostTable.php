<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Post;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class PostTable extends DataTableComponent
{
    protected $model = Post::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make(__('main.id'), "id")
                ->sortable(),
            Column::make(__('main.name'), "name")
                ->sortable()
                ->searchable(),
            Column::make(__('main.user'), "user.name")
                ->sortable()
                ->searchable(),
            Column::make(__('main.date_created'), "created_at")
                ->sortable()
                ->searchable()
                ->format(function ($value) {
                    return \Carbon\Carbon::parse($value)->format('m D Y');
                }),
            Column::make(__('main.updated_at'), "updated_at")
                ->sortable()
                ->searchable()
                ->format(function ($value) {
                    return \Carbon\Carbon::parse($value)->format('m D Y');
                }),
            Column::make(__('main.status'), 'status')
                ->sortable()
                ->searchable(),
            Column::make(__('main.theme'), 'theme')
                ->sortable()
                ->searchable(),
            Column::make(__('main.views'), 'views')
                ->sortable()
                ->searchable(),
            Column::make(__('main.actions'))
                ->label(
                    function ($row, Column $column) {
                        $actions = '
                        <div class="hs-dropdown relative inline-block [--placement:bottom-right]">
                            <button id="hs-table-dropdown-' . $row->id . '" type="button" class="hs-dropdown-toggle py-1.5 px-2 inline-flex justify-center items-center gap-2 rounded-md text-gray-700 align-middle focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:text-gray-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                            </svg>
                            </button>
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden mt-2 divide-y divide-gray-200 min-w-[10rem] z-10 bg-white shadow-2xl rounded-xl p-2 mt-2 dark:divide-gray-700 dark:bg-gray-800 dark:border dark:border-gray-700" aria-labelledby="hs-table-dropdown-' . $row->id . '">
                                <div class="py-2 first:pt-0 last:pb-0">
                                    <a href="' . route('admin.posts.edit', $row) . '" class="flex items-center gap-x-3 w-full py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                        ' . __('main.edit') . '
                                    </a>
                                </div>
                                <div class="py-2 first:pt-0 last:pb-0">
                                    <button class="flex items-center gap-x-3 w-full py-2 px-3 rounded-md text-sm text-red-600 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-red-500 dark:hover:bg-gray-700" data-hs-overlay="#delete-' . $row->id . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                        </svg>
                                        ' . __('main.delete') . '
                                    </button>
                                </div>
                            </div>
                        </div>';
                        return $actions;
                    }
                )->html(),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->setFilterPillTitle('Status')
                ->setFilterPillValues([
                    'Active' => 'Active',
                    'Wait' => 'Wait',
                    'Draft' => 'Draft',
                    'Rejecte' => 'Rejecte',
                ])
                ->options([
                    '' => 'All',
                    'Active' => 'Active',
                    'Wait' => 'Wait',
                    'Draft' => 'Draft',
                    'Rejecte' => 'Rejecte',
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value === 'Active') {
                        $builder->where('posts.status', 'Active'); // Specify the table name before the column name
                    } elseif ($value === 'Wait') {
                        $builder->where('posts.status', 'Wait');
                    } elseif ($value === 'Draft') {
                        $builder->where('posts.status', 'Draft');
                    } elseif ($value === 'Rejecte') {
                        $builder->where('posts.status', 'Rejecte');
                    }
                }),
        ];
    }
}
