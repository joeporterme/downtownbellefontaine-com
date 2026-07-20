<?php

namespace Database\Seeders;

use App\Models\PressItem;
use Illuminate\Database\Seeder;

class PressSeeder extends Seeder
{
    /**
     * Backfill the press mentions that were previously hard-coded on the Media page.
     * Keyed by URL so it can be re-run without duplicating.
     */
    public function run(): void
    {
        $items = [
            ['Mother and daughter open Bellefontaine coffee shop', 'https://www.10tv.com/article/news/local/boomtown-ohio/mother-and-daughter-open-bellefontaine-coffee-shop/530-52019299-cb58-4197-bb51-0286fba93c38', '10TV Boomtown Ohio', '2026-01-15'],

            ['A central Ohio organization is setting the standard for small-town revitalization', 'https://www.wosu.org/show/all-sides/2025-09-23/a-central-ohio-organization-is-setting-the-standard-for-small-town-revitalization', 'WOSU', '2025-09-23'],
            ['Pizza, beer and coffee are keys to success in Bellefontaine, one of Ohio\'s best small towns', 'https://www.cleveland.com/travel/2025/09/pizza-beer-and-coffee-are-keys-to-success-in-bellefontaine-one-of-ohios-best-small-towns.html', 'Cleveland.com', '2025-09-10'],
            ['From Ohio Pizza Shop to World Stage', 'https://columbusunderground.com/from-ohio-pizza-shop-to-world-stage-c1/', 'Columbus Underground', '2025-09-10'],
            ['Bellefontaine is Ohio\'s Most Loveable Downtown', 'https://ohio.org/travel-inspiration/articles/storytelling-bellefontaine-most-loveable-downtown', 'Ohio.org', '2025-07-14'],
            ["'Small but mighty': How a central Ohio city became a blueprint for reviving America's small towns", 'https://www.10tv.com/article/news/local/boomtown-ohio/bellefontaine-blueprint-for-reviving-americas-small-towns/530-d7da1833-e998-40d3-9b17-ee79686d52dd', '10TV Boomtown Ohio', '2025-07-10'],

            ["America's 'Oldest Concrete Street' Is A Historic Midwest Gem Near Cute Cafes And Shops", 'https://www.islands.com/1742518/america-oldest-concrete-street-bellefontaine-ohio-historic-midwest-gem-cafe-shop/', 'Islands', '2024-12-25'],
            ['Actors Noah Centineo and Will Poulter make Ohio trip', 'https://www.dispatch.com/story/entertainment/trend/2024/12/03/noah-centineo-and-will-poulter-make-special-appearance-at-ohio-bar/76739515007/', 'The Columbus Dispatch', '2024-12-03'],
            ['Destination Shopping at the Christmas Capital of Ohio, Downtown Bellefontaine!', 'https://www.wdtn.com/living-dayton/living-dayton-sponsored-content/destination-shopping-at-the-christmas-capital-of-ohio-downtown-bellefontaine/', 'WDTN', '2024-11-26'],
            ['Experience the Magic of The Holidays in Downtown Bellefontaine, an Enchanting Ohio Christmas Town', 'https://www.onlyinyourstate.com/trip-ideas/ohio/downtown-bellefontaine-christmas-town-oh', 'Only In Your State', '2024-10-23'],
            ['7 Fun Things to Do in Bellefontaine, Ohio', 'https://whatshouldwedotodaycolumbus.com/things-to-do-in-bellefontaine-ohio/', 'What Should We Do Today Columbus', '2024-10-07'],
            ['Rainbow Row Creates a New Bellefontaine Landmark', 'https://www.ohiomagazine.com/travel/article/bellefontaine-s-rainbow-row-brings-vibrant-new-downtown-addition', 'Ohio Magazine', '2024-01-15'],

            ['Rainbow Row project finalized in downtown Bellefontaine', 'https://www.examiner.org/rainbow-row-project-finalized-in-downtown-bellefontaine/', 'The Examiner', '2023-08-21'],
            ['Economic Development: Private Investments Brings Big Change to Bellefontaine', 'https://www.bizjournals.com/columbus/news/2023/06/29/economic-development-downtown-bellefontaine.html', 'Columbus Business First', '2023-06-29'],

            ['Best Hometowns 2022: Bellefontaine', 'https://www.ohiomagazine.com/ohio-life/best-hometowns/article/best-hometowns-2022-bellefontaine', 'Ohio Magazine', '2022-11-01'],
            ["Take A Stroll Down Ohio's Oldest Paved Road For A Picture Perfect Day", 'https://www.onlyinyourstate.com/ohio/oldest-paved-road-oh/', 'Only In Your State', '2022-07-03'],
            ['The New Bellefontaine: Locals Bring New Life to Small Town Through Downtown Rejuvenation', 'https://columbusunderground.com/the-new-bellefontaine-locals-bring-new-life-to-small-town-through-downtown-rejuvenation-we1/', 'Columbus Underground', '2022-04-29'],

            ["Ohio developer Small Nation finds opportunity in state's small cities", 'https://www.bizjournals.com/columbus/news/2021/10/22/small-nation-bellefontaine-expanding.html', 'Columbus Business First', '2021-10-22'],
            ['Bellefontaine Is A Shining Example of Urban Redevelopment', 'https://www.greaterohio.org/blog/2021/9/10/bellefontaine-as-a-shining-example-of-what-urban-redevelopment-can-look-like', 'Greater Ohio', '2021-09-21'],
        ];

        foreach ($items as [$title, $url, $source, $date]) {
            PressItem::updateOrCreate(
                ['url' => $url],
                ['title' => $title, 'source' => $source, 'published_date' => $date, 'is_active' => true],
            );
        }
    }
}
