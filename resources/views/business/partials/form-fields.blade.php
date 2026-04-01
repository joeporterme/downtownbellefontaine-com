{{-- Business Information --}}
<div>
    <label for="name" class="block text-sm font-medium text-theme-secondary">Business Name *</label>
    <input type="text" name="name" id="name" value="{{ old('name', $business->name ?? '') }}" required
        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
    @error('name')
        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="description" class="block text-sm font-medium text-theme-secondary">Description</label>
    <textarea name="description" id="description" rows="4"
        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">{{ old('description', $business->description ?? '') }}</textarea>
    @error('description')
        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
    @enderror
</div>

{{-- Categories --}}
<div>
    <label class="block text-sm font-medium text-theme-secondary mb-2">Categories</label>
    <div class="grid grid-cols-2 gap-2">
        @foreach($categories as $category)
            <label class="flex items-center">
                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                    {{ in_array($category->id, old('categories', isset($business) ? $business->categories->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                    class="rounded border-theme text-primary-600 focus:ring-primary-500">
                <span class="ml-2 text-sm text-theme-secondary">{{ $category->name }}</span>
            </label>
        @endforeach
    </div>
</div>

<hr class="my-6 border-theme">
<h3 class="text-lg font-medium text-theme-primary">Location</h3>

<div>
    <label for="address" class="block text-sm font-medium text-theme-secondary">Street Address</label>
    <input type="text" name="address" id="address" value="{{ old('address', $business->address ?? '') }}"
        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
    @error('address')
        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label for="city" class="block text-sm font-medium text-theme-secondary">City</label>
        <input type="text" name="city" id="city" value="{{ old('city', $business->city ?? 'Bellefontaine') }}"
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
    </div>
    <div>
        <label for="state" class="block text-sm font-medium text-theme-secondary">State</label>
        <input type="text" name="state" id="state" value="{{ old('state', $business->state ?? 'OH') }}" maxlength="2"
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
    </div>
    <div>
        <label for="zip" class="block text-sm font-medium text-theme-secondary">ZIP</label>
        <input type="text" name="zip" id="zip" value="{{ old('zip', $business->zip ?? '') }}"
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
    </div>
</div>

<hr class="my-6 border-theme">
<h3 class="text-lg font-medium text-theme-primary">Contact Information</h3>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="phone" class="block text-sm font-medium text-theme-secondary">Phone</label>
        <input type="tel" name="phone" id="phone" value="{{ old('phone', $business->phone ?? '') }}"
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('phone')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-theme-secondary">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $business->email ?? '') }}"
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('email')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
</div>

<div>
    <label for="website" class="block text-sm font-medium text-theme-secondary">Website</label>
    <input type="url" name="website" id="website" value="{{ old('website', $business->website ?? '') }}" placeholder="https://"
        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
    @error('website')
        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
    @enderror
</div>

<hr class="my-6 border-theme">
<h3 class="text-lg font-medium text-theme-primary">Social Media</h3>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="facebook_url" class="block text-sm font-medium text-theme-secondary">Facebook</label>
        <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $business->facebook_url ?? '') }}" placeholder="https://facebook.com/..."
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('facebook_url')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="instagram_url" class="block text-sm font-medium text-theme-secondary">Instagram</label>
        <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $business->instagram_url ?? '') }}" placeholder="https://instagram.com/..."
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('instagram_url')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="tiktok_url" class="block text-sm font-medium text-theme-secondary">TikTok</label>
        <input type="url" name="tiktok_url" id="tiktok_url" value="{{ old('tiktok_url', $business->tiktok_url ?? '') }}" placeholder="https://tiktok.com/@..."
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('tiktok_url')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="snapchat_url" class="block text-sm font-medium text-theme-secondary">Snapchat</label>
        <input type="url" name="snapchat_url" id="snapchat_url" value="{{ old('snapchat_url', $business->snapchat_url ?? '') }}" placeholder="https://snapchat.com/add/..."
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('snapchat_url')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="x_url" class="block text-sm font-medium text-theme-secondary">X (Twitter)</label>
        <input type="url" name="x_url" id="x_url" value="{{ old('x_url', $business->x_url ?? '') }}" placeholder="https://x.com/..."
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('x_url')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
</div>
