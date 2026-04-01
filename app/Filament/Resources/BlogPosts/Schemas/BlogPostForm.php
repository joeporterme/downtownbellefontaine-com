<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Models\Author;
use App\Models\BlogCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                // Left column - Main content (2/3 width)
                Section::make('Post Content')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, $operation) {
                                if ($operation === 'create') {
                                    $set('slug', \Illuminate\Support\Str::slug($state));
                                    $set('seo_title', $state);
                                }
                            }),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->helperText('Leave blank to auto-generate'),
                        RichEditor::make('content')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('blog/content')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                    ]),

                // Right column - Settings (1/3 width)
                Section::make('Post Settings')
                    ->columnSpan(1)
                    ->schema([
                        FileUpload::make('featured_image')
                            ->image()
                            ->disk('public')
                            ->directory('blog/featured')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                            ])
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1920')
                            ->imageResizeTargetHeight('1080')
                            ->maxSize(5120)
                            ->helperText('Recommended: 1920x1080px'),
                        Select::make('blog_category_id')
                            ->label('Category')
                            ->options(BlogCategory::active()->ordered()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                return BlogCategory::create([
                                    'name' => $data['name'],
                                    'slug' => \Illuminate\Support\Str::slug($data['name']),
                                ])->id;
                            }),
                        Select::make('author_id')
                            ->label('Author')
                            ->options(Author::active()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                return Author::create([
                                    'name' => $data['name'],
                                    'slug' => \Illuminate\Support\Str::slug($data['name']),
                                ])->id;
                            }),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->default('draft')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->default(now())
                            ->helperText('Future date = scheduled'),
                    ]),

                Section::make('Tag Businesses & Categories')
                    ->description('Link this post to businesses and business categories')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('businesses')
                            ->label('Tagged Businesses')
                            ->relationship('businesses', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->optionsLimit(50),
                        Select::make('businessCategories')
                            ->label('Tagged Business Categories')
                            ->relationship('businessCategories', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                    ])
                    ->collapsible(),

                Section::make('SEO Settings')
                    ->description('Search engine optimization settings')
                    ->columnSpanFull()
                    ->columns(1)
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO Title')
                            ->maxLength(60)
                            ->helperText('Recommended: 50-60 characters'),
                        Textarea::make('seo_description')
                            ->label('SEO Description')
                            ->maxLength(160)
                            ->rows(3)
                            ->helperText('Recommended: 150-160 characters'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
