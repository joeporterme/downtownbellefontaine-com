<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Import Status Section --}}
        @if(!empty($importStatus))
            <x-filament::section>
                <x-slot name="heading">Import Status</x-slot>

                @if(($importStatus['status'] ?? '') === 'queued')
                    <div class="bg-info-50 dark:bg-info-900/50 border border-info-200 dark:border-info-800 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="animate-pulse w-4 h-4 bg-info-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium text-info-800 dark:text-info-200">Import Queued</p>
                                <p class="text-sm text-info-600 dark:text-info-400">{{ $importStatus['message'] ?? 'Waiting to start...' }}</p>
                            </div>
                        </div>
                    </div>
                @elseif(($importStatus['status'] ?? '') === 'running')
                    <div class="bg-warning-50 dark:bg-warning-900/50 border border-warning-200 dark:border-warning-800 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="animate-spin w-5 h-5 border-2 border-warning-500 border-t-transparent rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium text-warning-800 dark:text-warning-200">Import Running</p>
                                <p class="text-sm text-warning-600 dark:text-warning-400">{{ $importStatus['message'] ?? 'Processing...' }}</p>
                            </div>
                        </div>
                        @if(isset($importStatus['percent']))
                            <div class="w-full bg-warning-200 dark:bg-warning-800 rounded-full h-3">
                                <div class="bg-warning-500 h-3 rounded-full transition-all duration-500" style="width: {{ $importStatus['percent'] }}%"></div>
                            </div>
                            <p class="text-xs text-warning-600 dark:text-warning-400 mt-1">
                                {{ $importStatus['current'] ?? 0 }} / {{ $importStatus['total'] ?? 0 }} ({{ $importStatus['percent'] }}%)
                            </p>
                        @endif
                    </div>
                @elseif(($importStatus['status'] ?? '') === 'completed')
                    <div class="bg-success-50 dark:bg-success-900/50 border border-success-200 dark:border-success-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <x-heroicon-o-check-circle class="w-6 h-6 text-success-500 mr-3 flex-shrink-0" />
                            <div class="flex-1">
                                <p class="font-medium text-success-800 dark:text-success-200">Import Completed</p>
                                <p class="text-sm text-success-600 dark:text-success-400 mb-3">{{ $importStatus['message'] ?? 'Done!' }}</p>

                                @if(isset($importStatus['results']))
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
                                        <div class="bg-white dark:bg-gray-800 rounded p-2 text-center">
                                            <div class="text-lg font-bold">{{ $importStatus['results']['stats']['total'] ?? 0 }}</div>
                                            <div class="text-xs text-gray-500">Total</div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 rounded p-2 text-center">
                                            <div class="text-lg font-bold text-success-600">{{ $importStatus['results']['stats']['imported'] ?? 0 }}</div>
                                            <div class="text-xs text-gray-500">Imported</div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 rounded p-2 text-center">
                                            <div class="text-lg font-bold text-warning-600">{{ $importStatus['results']['stats']['skipped'] ?? 0 }}</div>
                                            <div class="text-xs text-gray-500">Skipped</div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 rounded p-2 text-center">
                                            <div class="text-lg font-bold text-danger-600">{{ $importStatus['results']['stats']['errors'] ?? 0 }}</div>
                                            <div class="text-xs text-gray-500">Errors</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif(($importStatus['status'] ?? '') === 'failed')
                    <div class="bg-danger-50 dark:bg-danger-900/50 border border-danger-200 dark:border-danger-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <x-heroicon-o-x-circle class="w-6 h-6 text-danger-500 mr-3 flex-shrink-0" />
                            <div>
                                <p class="font-medium text-danger-800 dark:text-danger-200">Import Failed</p>
                                <p class="text-sm text-danger-600 dark:text-danger-400">{{ $importStatus['error'] ?? $importStatus['message'] ?? 'Unknown error' }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </x-filament::section>
        @endif

        {{-- Preview Section --}}
        @if($showPreview && !isset($previewData['error']) && !isset($previewData['needs_refresh']))
            <x-filament::section>
                <x-slot name="heading">Import Preview</x-slot>
                <x-slot name="description">Data found in SQL files ready for import</x-slot>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-primary-50 dark:bg-primary-900/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                            {{ $previewData['total_businesses'] ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Businesses to Import</div>
                    </div>

                    <div class="bg-primary-50 dark:bg-primary-900/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                            {{ $previewData['total_categories'] ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Categories</div>
                    </div>

                    <div class="bg-primary-50 dark:bg-primary-900/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                            {{ $previewData['total_images'] ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Images to Download</div>
                    </div>

                    <div class="bg-warning-50 dark:bg-warning-900/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-warning-600 dark:text-warning-400">
                            {{ $previewData['existing_businesses'] ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Existing Businesses</div>
                    </div>
                </div>

                {{-- Categories --}}
                @if(!empty($previewData['categories']))
                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-3">Categories to Import</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($previewData['categories'] as $category)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                                    {{ $category['categoryTitle'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Sample Businesses --}}
                @if(!empty($previewData['sample_businesses']))
                    <div>
                        <h3 class="text-lg font-medium mb-3">Sample Businesses (First 10)</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Name</th>
                                        <th class="px-4 py-2 text-left">Email</th>
                                        <th class="px-4 py-2 text-left">Address</th>
                                        <th class="px-4 py-2 text-left">Categories</th>
                                        <th class="px-4 py-2 text-center">Image</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($previewData['sample_businesses'] as $business)
                                        <tr>
                                            <td class="px-4 py-2 font-medium">{{ $business['name'] }}</td>
                                            <td class="px-4 py-2 text-gray-500">
                                                @if($business['email'])
                                                    {{ $business['email'] }}
                                                @else
                                                    <span class="text-warning-500 italic">Auto-generate</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-gray-500">{{ Str::limit($business['address'] ?? '', 30) }}</td>
                                            <td class="px-4 py-2 text-gray-500">{{ Str::limit($business['categories'] ?? '', 30) }}</td>
                                            <td class="px-4 py-2 text-center">
                                                @if($business['has_image'])
                                                    <x-heroicon-o-check-circle class="w-5 h-5 text-success-500 inline" />
                                                @else
                                                    <x-heroicon-o-x-circle class="w-5 h-5 text-gray-400 inline" />
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(($previewData['total_businesses'] ?? 0) > 10)
                            <p class="text-sm text-gray-500 mt-2">
                                ... and {{ ($previewData['total_businesses'] ?? 0) - 10 }} more businesses
                            </p>
                        @endif
                    </div>
                @endif
            </x-filament::section>
        @elseif(isset($previewData['needs_refresh']))
            <x-filament::section>
                <x-slot name="heading">Load Preview</x-slot>

                <div class="text-center py-8">
                    <x-heroicon-o-document-magnifying-glass class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                    <p class="text-gray-500 dark:text-gray-400 mb-4">{{ $previewData['message'] ?? 'Click Load Preview to scan the SQL files.' }}</p>
                    <p class="text-sm text-gray-400">This will parse the SQL files in the resources folder and show you what will be imported.</p>
                </div>
            </x-filament::section>
        @elseif(isset($previewData['error']))
            <x-filament::section>
                <x-slot name="heading">Error</x-slot>

                <div class="bg-danger-50 dark:bg-danger-900/50 border border-danger-200 dark:border-danger-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-danger-500 mr-3 flex-shrink-0" />
                        <div>
                            <p class="font-medium text-danger-800 dark:text-danger-200">{{ $previewData['error'] }}</p>
                            @if(isset($previewData['files_needed']))
                                <p class="mt-2 text-sm text-danger-700 dark:text-danger-300">Required files:</p>
                                <ul class="mt-1 text-sm text-danger-600 dark:text-danger-400 list-disc list-inside">
                                    @foreach($previewData['files_needed'] as $file)
                                        <li>resources/{{ $file }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </x-filament::section>
        @endif

        {{-- Instructions --}}
        <x-filament::section collapsible collapsed>
            <x-slot name="heading">Instructions</x-slot>

            <div class="prose dark:prose-invert max-w-none text-sm">
                <h4>How to use the Business Import:</h4>
                <ol>
                    <li><strong>Load Preview:</strong> Click "Load Preview" to scan the SQL files and see what will be imported.</li>
                    <li><strong>Start Import:</strong> Click "Start Import" to begin. The import runs in the background.</li>
                    <li><strong>Monitor Progress:</strong> Click "Refresh Status" to see the current progress.</li>
                </ol>

                <h4>What the import does:</h4>
                <ul>
                    <li>Creates user accounts for each business (uses email if available, generates temp email if not)</li>
                    <li>Generates AI-powered business descriptions using the brand voice</li>
                    <li>Geocodes addresses via Google Maps for accurate coordinates</li>
                    <li>Downloads and stores featured images</li>
                    <li>Creates business locations with coordinates</li>
                    <li>Assigns categories from the legacy data</li>
                    <li>Auto-approves all imported businesses</li>
                </ul>

                <h4>CLI Alternative:</h4>
                <p>You can also run the import via command line:</p>
                <pre><code>php artisan businesses:import</code></pre>
                <p>Use <code>--dry-run</code> to preview without making changes.</p>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
