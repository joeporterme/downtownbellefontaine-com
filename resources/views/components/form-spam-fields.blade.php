{{-- Hidden spam protection fields --}}
{{-- Honeypot: invisible to people, irresistible to bots that fill every field. --}}
<div style="position: absolute; left: -9999px; top: -9999px; width: 1px; height: 1px; overflow: hidden;" aria-hidden="true">
    <label for="website_url">Leave this field empty</label>
    <input type="text" id="website_url" name="website_url" value="" tabindex="-1" autocomplete="off">
</div>
{{-- Server-signed load time (encrypted with APP_KEY, so it can't be forged).
     Rendered server-side so it works without JavaScript; bots that POST
     straight to the endpoint arrive without it. --}}
<input type="hidden" name="form_token" value="{{ \Illuminate\Support\Facades\Crypt::encryptString((string) time()) }}">
