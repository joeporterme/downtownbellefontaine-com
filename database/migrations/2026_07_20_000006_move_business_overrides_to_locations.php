<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Move genuine business-level listing overrides onto their primary location
     * so the listing image can prefer the location's Street View / override over
     * the featured photo without a legacy field getting in the way. Overrides
     * that merely duplicate the featured image are left (they're harmless and
     * the display falls back to the featured image anyway).
     */
    public function up(): void
    {
        $businesses = DB::table('businesses')
            ->whereNotNull('listing_image')
            ->where('listing_image', '!=', '')
            ->get(['id', 'listing_image', 'listing_image_credit', 'featured_image']);

        foreach ($businesses as $b) {
            if ($b->listing_image === $b->featured_image) {
                continue; // just a duplicate of the featured image
            }

            $loc = DB::table('business_locations')->where('business_id', $b->id)->where('is_primary', true)->first()
                ?? DB::table('business_locations')->where('business_id', $b->id)->first();

            if (! $loc || filled($loc->listing_image)) {
                continue; // no location, or it already has its own override
            }

            DB::table('business_locations')->where('id', $loc->id)->update([
                'listing_image' => $b->listing_image,
                'listing_image_credit' => $b->listing_image_credit,
            ]);

            DB::table('businesses')->where('id', $b->id)->update([
                'listing_image' => null,
                'listing_image_credit' => null,
            ]);
        }
    }

    public function down(): void
    {
        // One-way data move; nothing to reverse.
    }
};
