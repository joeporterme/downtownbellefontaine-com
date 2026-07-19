<?php

/**
 * Downtown Bellefontaine Historic Walking Tour — 14 stops.
 * Source: old site (downtownbellefontaine.com/historictour). Images pulled from
 * the WP media library and optimized into public/images/walking-tour/.
 * Coordinates geocoded once via OpenStreetMap/Nominatim.
 */

$photo = fn (string $slug) => "/images/walking-tour/photos/{$slug}.jpg";
$plaque = fn (string $slug) => "/images/walking-tour/plaques/{$slug}.png";
$logo = fn (string $slug) => "/images/walking-tour/sponsors/{$slug}.png";

return [
    'intro' => [
        'heading' => 'Historic Walking Tour',
        'body' => "Bellefontaine believes in preserving our city's rich history and sharing it with others. Here, the story of our small town is displayed through our proud, historic structures. Spanning architectural styles from the 1850s through brand-new construction, our buildings tell the tales of our people, our dedication to growth, and our vision for the future.\n\nAs you walk through our downtown, look for the bronze plaques displayed on the building fronts. They'll introduce you to a small piece of Bellefontaine's history, while you meet our amazing present-day merchants and learn to love the most lovable town in the state!",
    ],
    'map_pdf' => '/images/walking-tour/walking-tour-map.pdf',
    'sponsor_form' => 'https://share.hsforms.com/1JkJp81_1RTO5bBMffcqXoQbz0sv',
    'stops' => [
        [
            'order' => 1, 'slug' => 'historic-post-office', 'title' => 'Historic Post Office',
            'address' => '201 W. Chillicothe Ave.', 'lat' => 40.3598130, 'lng' => -83.7628610,
            'photo' => $photo('historic-post-office'), 'plaque' => $plaque('historic-post-office'),
            'body' => 'Dedicated in 1914, it was a "state of the art" Federal building of Neo-Classical style. The walls of the building are constructed of gray Ohio sandstone and the interior finished in marble and yellow pine. In 1963 the building was no longer able to serve the needs of the ever-changing postal service and in 1964 was replaced with a new post office on the opposite corner.',
            'sponsors' => [['name' => 'CoverLink Insurance', 'logo' => $logo('coverlink'), 'link' => 'https://coverlink.com/']],
        ],
        [
            'order' => 2, 'slug' => 'h-m-annat-dry-goods', 'title' => 'H.M. Annat Dry Goods',
            'address' => '210 W. Columbus Ave.', 'lat' => 40.3613884, 'lng' => -83.7621385,
            'photo' => $photo('h-m-annat-dry-goods'), 'plaque' => $plaque('h-m-annat-dry-goods'),
            'body' => "The building was erected in 1912 to house the new H.M. Annat Dry Goods, which for many years was doing business in the Opera Block. The second floor of the store was used by the Deisel Wemmer Company for a branch cigar factory. The company became the R.G. Dunn cigar company and during its peak employed 200-300 employees. In 1934 this building became a Montgomery Ward Store and continued to operate until 1986.",
            'sponsors' => [
                ['name' => 'Anytime Fitness', 'logo' => $logo('anytime-fitness'), 'link' => 'https://www.anytimefitness.com/'],
                ['name' => 'Loco Depot Training Station', 'logo' => $logo('loco-depot'), 'link' => 'https://www.facebook.com/locodepot/'],
            ],
        ],
        [
            'order' => 3, 'slug' => 'cozy-picture-palace', 'title' => 'Cozy Picture Palace',
            'address' => '208 W. Columbus Ave.', 'lat' => 40.3613835, 'lng' => -83.7620640,
            'photo' => $photo('cozy-picture-palace'), 'plaque' => $plaque('cozy-picture-palace'),
            'body' => 'As the silent film era was beginning, H.J. King built this moving picture theatre in 1913. It was the first theatre in Bellefontaine built specifically for this purpose; it housed the Cozy Theatre and later the Rialto. The highest grossing film ever released there was titled "Traffic in Souls." The film grossed approximately $450,000 and had a budget of only $5,700.',
            'sponsors' => [['name' => 'Logan County Visitors Bureau', 'logo' => $logo('logan-county'), 'link' => 'https://experiencelogancounty.com/']],
        ],
        [
            'order' => 4, 'slug' => 'gorges-building', 'title' => 'Gorges Building',
            'address' => '200 W. Columbus Ave.', 'lat' => 40.3613640, 'lng' => -83.7617660,
            'photo' => $photo('gorges-building'), 'plaque' => $plaque('gorges-building'),
            'body' => 'Built by Henry Gorges in 1896, this building has since been known as the Gorges Building. The building is mostly remembered for housing pharmacies. The Frazer Drug Store was there as early as 1914 and remained for many years. The Gorges Building offers spectacular views of West Columbus Ave. and South Detroit Street. The second floor was a popular destination for rooms for rent for daily railroad travelers to Bellefontaine.',
            'sponsors' => [],
        ],
        [
            'order' => 5, 'slug' => 'kauffman-building', 'title' => 'Kauffman Building',
            'address' => '135 W. Columbus Ave.', 'lat' => 40.3611463, 'lng' => -83.7609171,
            'photo' => $photo('kauffman-building'), 'plaque' => $plaque('kauffman-building'),
            'body' => 'The entire block is known as the Kauffman Block, built by Max Kauffman in 1907. He operated the Syndicate Department Store at this location through 1918. Kennedy Brothers furniture replaced the Syndicate until it was sold and became Armstrong & Allen Furniture in 1927. Armstrong & Allen was in business for many years until 1990. In 1992, Canterbury Coffee opened for two decades.',
            'sponsors' => [['name' => 'Small Nation', 'logo' => $logo('small-nation'), 'link' => 'https://smallnationstrong.com']],
        ],
        [
            'order' => 6, 'slug' => 'dietrich-building', 'title' => 'Dietrich Building',
            'address' => '114-116 N. Main St.', 'lat' => 40.3613208, 'lng' => -83.7596022,
            'photo' => $photo('dietrich-building'), 'plaque' => $plaque('dietrich-building'),
            'body' => "James Dietrich came to Bellefontaine in 1899 to establish his tailor business. Mr. Dietrich built two buildings at 114 and 116 N. Main. The first floors have been home to many businesses over the years. In the 1930s it was a jewelry store and from 1939-1954 it was Moore's Bakery, then Winan's and Randall's Bakery respectively. The express purpose of the second floor was to serve as a lodge for the Elks.",
            'sponsors' => [],
        ],
        [
            'order' => 7, 'slug' => 'the-wissler-building', 'title' => 'The Wissler Building',
            'address' => '108 S. Main St.', 'lat' => 40.3608709, 'lng' => -83.7598655,
            'photo' => $photo('the-wissler-building'), 'plaque' => $plaque('the-wissler-building'),
            'body' => "108 S. Main was rebuilt in 1856 after a devastating fire destroyed over two acres of downtown Bellefontaine. For a half-century until 1941, this building housed Wissler's Dry Goods. Over a year's time, Clarence Wissler built an airplane on the second floor of this building when his parents owned it in the 1910s. Wissler successfully flew the airplane over Main Street in 1922. After the early 1940s, it became Uhlman's Department Store, which remained in business for over 40 years.",
            'sponsors' => [['name' => 'Ohio Hi-Point Career Center', 'logo' => $logo('ohio-hi-point'), 'link' => 'https://www.ohiohipoint.com/']],
        ],
        [
            'order' => 8, 'slug' => 'the-j-c-penney-co', 'title' => 'The J.C. Penney Co.',
            'address' => '110 S. Main St.', 'lat' => 40.3609307, 'lng' => -83.7598594,
            'photo' => $photo('the-j-c-penney-co'), 'plaque' => $plaque('the-j-c-penney-co'),
            'body' => 'Built after the fire of 1856, Churchill Hardware Co. operated at this location for over 70 years. The front of the building was remodeled in 1909. The J.C. Penney Co. occupied the building from 1927 to 1959, when it moved to the Zerbee Building at 118 S. Main and remained for more than 25 years. J.C. Penney himself attended the grand opening of the new location.',
            'sponsors' => [['name' => 'W. Lewis Construction LLC', 'logo' => $logo('w-lewis'), 'link' => 'https://lewisconstructionohio.com/']],
        ],
        [
            'order' => 9, 'slug' => 'the-grand-opera-house-and-opera-block', 'title' => 'The Grand Opera House & Opera Block',
            'address' => '110 E. Court Ave.', 'lat' => 40.3603445, 'lng' => -83.7595873,
            'photo' => $photo('the-grand-opera-house-and-opera-block'), 'plaque' => $plaque('the-grand-opera-house-and-opera-block'),
            'body' => 'The Grand Opera House\'s first performance was on December 23, 1880, featuring a presentation of "The Chimes of Normandy." The auditorium could seat 956 patrons. Many big-name acts performed here, including Buffalo Bill Cody, John Philip Sousa, Harry Houdini, and Jenny Lind, to name a few. The storefronts on both Court Avenue and South Main Street have been filled with many businesses over the last 140 years, including Hutchin\'s Dry Cleaners, White\'s Music House, and Richelieu Pure Foods.',
            'sponsors' => [['name' => 'Edward Jones — Darin Olson', 'logo' => $logo('edward-jones'), 'link' => 'https://www.edwardjones.com/us-en/financial-advisor/darin-olson']],
        ],
        [
            'order' => 10, 'slug' => 'g-c-murphy-building', 'title' => 'The G.C. Murphy Building',
            'address' => '130 S. Main St.', 'lat' => 40.3607040, 'lng' => -83.7598823,
            'photo' => $photo('g-c-murphy-building'), 'plaque' => $plaque('g-c-murphy-building'),
            'body' => '130 S. Main, built in 1875, is the center section of what is known as the Buckeye Block. G.C. Murphy Co. was the most prominent tenant, occupying from 1917 to 1982. In 1956, the owners doubled the building\'s size to better accommodate the population of the city and a growing number of customers. Many customers recall fond memories of the deli counter, soda fountain, and the aroma of hot roasted nuts permeating throughout the area.',
            'sponsors' => [['name' => 'Community Health & Wellness Partners', 'logo' => $logo('chwp'), 'link' => 'https://chwpcares.org/']],
        ],
        [
            'order' => 11, 'slug' => 'the-strand-belle-theater', 'title' => 'The Strand / Belle Theater',
            'address' => '139 S. Main St.', 'lat' => 40.3597292, 'lng' => -83.7598767,
            'photo' => $photo('the-strand-belle-theater'), 'plaque' => $plaque('the-strand-belle-theater'),
            'body' => 'The building was erected in 1884 and converted into a movie theater in 1916 named The Strand. Sound equipment was installed in 1929 so that "talkies" could be shown. New equipment and air conditioning was added in 1950 and the name was changed to the Belle Theater. After the Belle closed, the building was occupied for many years as a Sears Roebuck Store. Most recently, the building was home to James Flooring for more than 20 years.',
            'sponsors' => [['name' => 'TDH Law', 'logo' => $logo('tdh-law'), 'link' => 'https://tdhlaw.com/']],
        ],
        [
            'order' => 12, 'slug' => 'the-canby-building', 'title' => 'The Canby Building',
            'address' => '144 S. Main St.', 'lat' => 40.3603230, 'lng' => -83.7599210,
            'photo' => $photo('the-canby-building'), 'plaque' => $plaque('the-canby-building'),
            'body' => 'Erected in 1912 by Edward Canby of Dayton, Ohio. Its developers intended the building to be a very modern, high-class structure for businesses. The Canby, as it was referred to, was originally occupied by Morris & Palmer Dry Goods on the first floor. The second floor was occupied by Buckeye Cement Company. The third floor was home to an operations terminal of The Cleveland, Cincinnati, Chicago and St. Louis Railroad until 1939. At the time, the Canby was known as the handsomest building between Cincinnati and Cleveland.',
            'sponsors' => [['name' => 'Citizens Federal Savings & Loan', 'logo' => $logo('citizens-federal'), 'link' => 'https://www.citizensfederalsl.com/']],
        ],
        [
            'order' => 13, 'slug' => 'shirks-logan-tire-co', 'title' => "Shirk's Logan Tire Co.",
            'address' => '121 W. Chillicothe Ave.', 'lat' => 40.3596639, 'lng' => -83.7604940,
            'photo' => $photo('shirks-logan-tire-co'), 'plaque' => $plaque('shirks-logan-tire-co'),
            'body' => "Built for Frank W. Shirk's Logan Tire Co. in 1928, which he owned and operated until 1956. Shirk's Logan Tire Co. served as the primary tire and car service company for Bellefontaine throughout the Great Depression and World War Two. This building sits across the street from the gorgeous Canby Building.",
            'sponsors' => [['name' => 'Liberty National Bank', 'logo' => $logo('liberty-national'), 'link' => 'https://www.myliberty.bank/']],
        ],
        [
            'order' => 14, 'slug' => 'the-patterson-building', 'title' => 'The Patterson Building',
            'address' => '222 S. Main St.', 'lat' => 40.3591427, 'lng' => -83.7598320,
            'photo' => $photo('the-patterson-building'), 'plaque' => $plaque('the-patterson-building'),
            'body' => 'Built by William T. Patterson at the new site of his marble and monument works in 1912, which he began in 1875. After his passing in 1919, his son ran the business for decades after. Many businesses, including a television store and stamp shop, have occupied this space since. The building still stands with its beautiful original marble.',
            'sponsors' => [['name' => 'Quest Federal Credit Union', 'logo' => $logo('quest-fcu'), 'link' => 'https://questfcu.com/']],
        ],
    ],
];
