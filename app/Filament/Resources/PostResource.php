<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title'),
                Forms\Components\DateTimePicker::make('published_at')
                    ->native(false)
                    ->timezone(
                        auth()->user()->timezone
                    ),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->timezone(
                        auth()->user()->timezone
                    ),
            ])
            ->filters([
                Tables\Filters\Filter::make('published_at')
                    ->label('Published Date')
                    ->form([
                        Forms\Components\DateTimePicker::make('from')
                            ->label('Published Date From')
                            ->timezone(
                                auth()->user()->timezone
                            )
                            ->native(false),
                        Forms\Components\DateTimePicker::make('to')
                            ->label('Published Date To')
                            ->timezone(
                                auth()->user()->timezone
                            )
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $query
                            ->when($data['from'], function (Builder $q, string $from) {
                                return $q->where('published_at', '>=', $from);
                            })
                            ->when($data['to'], function (Builder $q, string $to) {
                                return $q->where('published_at', '<=', $to);
                            });
                    })
                    ->indicateUsing(function (array $data) {
                        $indicatorLabels = collect();

                        if ($data['from']) {
                            $indicatorLabels->add('from ' . $data['from']);
                        }

                        if ($data['to']) {
                            $indicatorLabels->add('to ' . $data['to']);
                        }

                        if ($indicatorLabels->isEmpty()) {
                            return null;
                        }

                        return 'Published date ' . $indicatorLabels->implode(' ');
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
