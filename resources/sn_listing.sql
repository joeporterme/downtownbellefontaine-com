-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 13, 2026 at 10:28 PM
-- Server version: 5.7.44
-- PHP Version: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `downtown_wp`
--

-- --------------------------------------------------------

--
-- Table structure for table `sn_listing`
--

CREATE TABLE `sn_listing` (
  `listingId` int(3) NOT NULL,
  `listingTitle` varchar(50) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text,
  `email` varchar(75) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `latitude` decimal(11,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `dateCreated` datetime DEFAULT NULL,
  `dateModified` datetime DEFAULT NULL,
  `featured_flag` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sn_listing`
--

INSERT INTO `sn_listing` (`listingId`, `listingTitle`, `category`, `description`, `email`, `phone`, `address`, `website`, `facebook`, `instagram`, `latitude`, `longitude`, `dateCreated`, `dateModified`, `featured_flag`, `status`) VALUES
(1, 'A New Leaf Florist', '15', '*', 'Flowerfreaks07@yahoo.com', '(937) 592-5333', '111 N Main St, Bellefontaine, OH 43311, USA', 'https://anewleaffloristinc.com/', 'https://www.facebook.com/ANewLeafFloristInc/', '', 40.36156650, -83.76000970, NULL, NULL, '0', '1'),
(2, 'A Robbins Nest', '2', '*', '', '', '112 South Main Street, Bellefontaine, OH 43311, USA', 'https://a-robbins-nest-antiques-gifts.business.site/', 'https://www.facebook.com/ARobbinsNest/', '', 40.36077650, -83.76030260, NULL, NULL, '0', '1'),
(4, 'The Hanger Boutique', '2', '*', 'shophangerboutique@gmail.com', '', '120 S Main St, Bellefontaine, OH 43311, USA', 'https://the-hanger-boutique.com/', 'https://www.facebook.com/ShopHangerBoutique', 'https://www.instagram.com/hangerboutique_/', 40.36053010, -83.75996040, NULL, NULL, '0', '1'),
(6, 'Nest 1896', '2', 'Nest: a snug retreat or refuge; a resting place; home.\r\nNest 1896 is a farmhouse-inspired boutique, housed in a renovated historic building dating to 1896, in beautiful downtown Bellefontaine, Ohio.', 'Shopnest1896@gmail.com', '(937) 935-1731', '204 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.thenest1896.com/', 'https://www.facebook.com/profile.php?id=61554183690785', '', 40.36154610, -83.76182450, NULL, NULL, '0', '1'),
(7, 'The Olde Mint Antiques LLC', '2', '*', '', '(937) 292-7288', '135 W Columbus Ave, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/oldemintantiques', '', 40.36096260, -83.76114010, NULL, NULL, '0', '1'),
(8, 'Lee’s Comfort Shoes', '2', '*', 'leescomfort@embarqmail.com', '(937) 599-2250', '109 N Main St, Bellefontaine, OH 43311, USA', 'http://www.buckeyefoot.com/html/finding_us.html', '', '', 40.36149760, -83.76002010, NULL, NULL, '0', '1'),
(9, 'Tanger’s Furniture', '2', '*', '', '(937) 592-9751', '216 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://tangersfurniture.com/', 'https://www.facebook.com/tangersfurniture', '', 40.36146570, -83.76242670, NULL, NULL, '0', '1'),
(10, 'Stephen’s Fine Jewelry', '2', '*', 'sfj@bright.net', '(937) 592-3133', '140 W Columbus Ave, Bellefontaine, OH 43311, USA', 'http://www.stephensfinejewelryoh.com', '', '', 40.36135500, -83.76130100, NULL, NULL, '0', '1'),
(12, 'The Poppy Seed', '2', '*', '', '(937) 404-4085', '140 South Main Street, Bellefontaine, OH, USA', 'https://shopthepoppyseed.com/', 'https://www.facebook.com/ShopThePoppySeed', 'https://www.instagram.com/shopthepoppyseed/', 40.36004570, -83.76020330, NULL, NULL, '0', '1'),
(17, 'Six Hundred Downtown', '1', '*', '', '(937) 592-3133', '108 S Main St, Bellefontaine, OH 43311, USA', 'https://www.600downtown.com/', 'https://www.facebook.com/sixhundreddowntown', 'https://www.instagram.com/600downtown/', 40.36086700, -83.76010310, NULL, NULL, '0', '1'),
(20, 'S Canby St-Parking', '13', 'Parking for S Canby', '', '', 'S Canby St, Bellefontaine, OH 43311, USA', '', '', '', 40.36177800, -83.76069400, NULL, NULL, '0', '1'),
(21, '68 S Detroit St-Parking', '13', 'parking for 68 S Detroit St', '', '', '68 S Detroit St, Bellefontaine, OH 43311, USA', '', '', '', 40.36107500, -83.76190100, NULL, NULL, '0', '1'),
(22, 'Court Ave -Parking', '13', 'Parking for Court Ave', '', '', 'Court Ave 	Bellefontaine, OH 43311, USA 	40.360450, -83.761477', '', '', '', 40.36045000, -83.76147700, NULL, NULL, '0', '1'),
(23, '129 W Chillicothe Ave -Parking', '13', 'Parking for 129 W Chillicothe Ave', '', '', '129 W Chillicothe Ave, Bellefontaine, OH 43311, USA', '', '', '', 40.35937100, -83.76096200, NULL, NULL, '0', '1'),
(24, 'Rustic Boutique', '2', '*', 'rusticboutique@outlook.com', '(567) 356-1443', '130 S Main St, Suite 101, Bellefontaine, OH 43311', 'https://www.rusticbshop.com/', 'https://www.facebook.com/RusticBShop ', 'https://www.instagram.com/rustic_boutique_shop/', 40.36026620, -83.76037270, NULL, NULL, '0', '1'),
(25, 'Native Coffee Co', '1', '*', '', '(419) 733-5736', '111 South Detroit Street, Bellefontaine, OH, USA', 'https://www.nativecoffee.co/', 'https://www.facebook.com/nativecoffeeco', 'https://www.instagram.com/nativecoffee.co/ ', 40.36082170, -83.76147200, NULL, NULL, '0', '1'),
(26, 'Brewfontaine', '1', '*', 'cheers@brewfontaine.com', '(937) 404-9128', '211 S Main St, Bellefontaine, OH 43311, USA', 'https://www.brewfontaine.com/', 'https://www.facebook.com/brewfontaine', 'https://instagram.com/brewfontaine', 40.35935900, -83.75971470, NULL, NULL, '0', '1'),
(27, '2Gs BBQ', '1', '*', '', '(937) 210-8429', '116 N Main St, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/profile.php?id=100068979386559', 'https://www.instagram.com/2gsbarbeque/', 40.36168340, -83.75948960, NULL, NULL, '0', '1'),
(28, 'Putt & Play Golf Center', '23', '*', '', '(937) 404-1330', '209 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.puttplaygolfcenter.com/', 'https://www.facebook.com/ppgc17', 'https://www.instagram.com/putt_and_play_golf_center/ ', 40.36100080, -83.76231050, NULL, NULL, '0', '1'),
(29, 'Bear Bones Tattoo Gallery', '15', '*', '', '(937) 408-7964', '148 W Columbus Ave, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/bearbonestattoos', '', 40.36139140, -83.76146610, NULL, NULL, '0', '1'),
(30, 'Kiyomi Sushi Steakhouse', '1', '*', '', '(937) 599-6688', '120 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.kiyomisushi.com/', 'https://www.facebook.com/Kiyomi-sushi-1495947320467254', '', 40.36138670, -83.76053860, NULL, NULL, '0', '1'),
(31, 'Sweet Aromas Coffee', '1', '*', '', '(937) 599-2233', '120 E Court Ave, Bellefontaine, OH 43311, USA', 'https://www.sweetaromascoffee.com/', 'https://www.facebook.com/SweetAromasCoffee', 'https://www.instagram.com/sweetaromascoffee/ ', 40.36026630, -83.75901730, NULL, NULL, '0', '1'),
(32, 'The Fun Company', '2', '*', '', '(937) 599-2993', '136 S Main St, Bellefontaine, OH 43311, USA', 'https://btownfun.com/', 'https://www.facebook.com/btownfunco', 'https://www.instagram.com/btownfunco/ ', 40.36004430, -83.76020260, NULL, NULL, '0', '1'),
(33, 'Don\\\'s Downtown Diner', '1', '*', '', '(937) 599-4444', '208 S Main St, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/Donsdowntowndiner', '', 40.35940040, -83.76014450, NULL, NULL, '0', '1'),
(34, 'City Sweets and Creamery', '1', '*', '', '(937) 592-0097', '222 S Main St, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/citysweetsbellefontaine', 'https://www.instagram.com/citysweetscreamery/ ', 40.35904980, -83.76026900, NULL, NULL, '0', '1'),
(35, 'PeachTree Boutique', '2', '*', 'peachtree_gifts@outlook.com', '(937) 599-5599', '136 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.shopatpeachtree.com/', '', 'https://www.instagram.com/peachtree_boutique/ ', 40.36135520, -83.76114530, NULL, NULL, '0', '1'),
(36, 'Whit’s Frozen Custard', '1', '*', 'contact@whitsbellefontaine.com', '(937) 598-0086', '112 S Main St, Bellefontaine, OH 43311, USA', 'https://www.whitscustard.com/', ' https://www.facebook.com/WhitsBellefontaine', 'https://www.instagram.com/whitsbellefontaine/', 40.36077650, -83.76030260, NULL, NULL, '0', '1'),
(37, 'Holland Theatre', '18', '*', '', '(937) 592-9002', '127 E Columbus Ave, Bellefontaine, OH 43311, USA', 'http://thehollandtheatre.org/', 'https://www.facebook.com/hollandtheatre/', 'https://www.instagram.com/hollandtheatre/', 40.36152970, -83.75858010, NULL, NULL, '0', '1'),
(38, 'Anytime Fitness', '17', '*', '', '(937) 595-0303', '210 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.anytimefitness.com/gyms/2478/bellefontaine-oh-43311/', 'https://www.facebook.com/AnytimeBellefontaine', 'https://www.instagram.com/anytimebellefontaine/ ', 40.36150900, -83.76219420, NULL, NULL, '0', '1'),
(39, 'Roundhouse Depot Brewing Company', '1', '*', '', '(540) 323-1295', '217 W Chillicothe Ave, Bellefontaine, OH 43311, USA', 'https://www.roundhousedepotbrewing.com/', 'https://www.facebook.com/RoundhouseBrewing', 'https://www.instagram.com/roundhousedepotbrewing/ ', 40.35968210, -83.76287770, NULL, NULL, '0', '1'),
(40, 'Bella Vino Events & Wine Room', '18', '*', '', '(937) 844-6606', '112 S Main St Suite 2, Bellefontaine, OH 43311', 'http://bellavinoevents.com/', 'https://www.facebook.com/pages/category/Event/Bella-Vino-Events-Wine-Room-303838163760105/ ', 'https://www.instagram.com/bellavino_bellefontaine/ ', 40.36077650, -83.76030260, NULL, NULL, '0', '1'),
(41, 'Main Street Marketplace', '2', '*', '', '(937) 565-4580', '130 S Main St, Bellefontaine, OH 43311, USA', 'https://www.themainstreetmarketplace.com/', 'https://www.facebook.com/themainstreetmarketplace', '', 40.36026620, -83.76037270, NULL, NULL, '0', '1'),
(42, 'Homegrown Yoga', '17', '*', '', '(937) 404-1464', '138 W Columbus Ave, Bellefontaine, OH, USA', 'https://www.homegrownyoga.fit/', 'https://www.facebook.com/homegrownyoga937', 'https://www.instagram.com/homegrownyoga937/ ', 40.36148250, -83.76127280, NULL, NULL, '0', '1'),
(43, 'Just U’NeeQ', '2', '*', '', '(419) 733-7228', '134 S Main St, Bellefontaine, OH 43311, USA', 'https://justuneeq.com/', 'https://www.facebook.com/JustUNeeq', 'https://www.instagram.com/justuneeq_/ ', 40.36013300, -83.76014200, NULL, NULL, '0', '1'),
(44, 'Four Acre Clothing Company', '2', '*', '', '(937) 407-6524', '102 S Main St, Bellefontaine, OH 43311, USA', 'https://fouracreclothingco.com/', 'https://www.facebook.com/FourAcreClothingCo', 'https://www.instagram.com/fouracreclothingco/ ', 40.36098260, -83.76009460, NULL, NULL, '0', '1'),
(47, 'Comfort Inn', '3', '*', '', '(937) 595-0631', '260 Northview Dr, Bellefontaine, OH 43311, USA', 'https://www.choicehotels.com/ohio/bellefontaine/comfort-inn-hotels/oh087?source=gyxt', 'https://www.facebook.com/Comfort-Inn-203233183056453', '', 40.37961530, -83.75087120, NULL, NULL, '0', '1'),
(48, 'Super 8', '3', '*', '', '(937) 404-5820', '1117 N Main St, Bellefontaine, OH 43311, USA', 'https://www.wyndhamhotels.com/super-8/bellefontaine-ohio/super-8-bellefontaine/overview?CID=LC:SE::GGL:RIO:National:08319&iata=00093796', '', '', 40.37686600, -83.75753120, NULL, NULL, '0', '1'),
(49, 'The Loft Above', '3', '*', '', '(614) 383-8246', '109 North Detroit Street, Bellefontaine, OH 43311, USA', 'https://theloftabove.com/', 'https://www.facebook.com/loftabove/', '', 40.36157230, -83.76157750, NULL, NULL, '0', '1'),
(50, 'Undertone on Main', '16', '*', '', '(937) 651-5625', '130 S Main St, Suite 102, Bellefontaine, OH 43311', 'http://www.lockshopsalon.com/index.html', 'https://www.facebook.com/lockshopsalon', '', 40.36026620, -83.76037270, NULL, NULL, '0', '1'),
(52, 'Brinkman Hypnosis Solutions', '15', '*', '', '(937) 539-0595', '139 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://brinkmanhypnosissolutions.com/?blog=y', 'https://www.facebook.com/CertifiedHypnotistGenevaBrinkman', '', 40.36113340, -83.76122400, NULL, NULL, '0', '1'),
(54, 'The Morning Riot', '1', '*', '', '(937) 404-9013', '130 S Main St, Suite 111, Bellefontaine, OH 43311', '', 'https://www.facebook.com/themorningriotbrunch', ' https://www.instagram.com/themorningriot', 40.36026620, -83.76037270, NULL, NULL, '0', '1'),
(56, 'Small Nation', '15', '*', '', '(937) 565-4580', '130 S Main St, Suite B101, Bellefontaine, OH 43311', 'https://smallnationstrong.com/', 'https://www.facebook.com/smallnationstrong', 'https://www.instagram.com/smallnationstrong/ ', 40.36026620, -83.76037270, NULL, NULL, '0', '1'),
(59, 'Jazzercise', '17', '*', '', '(937) 592-5839', '130 S Main St, Suite 201, Bellefontaine, OH 43311', 'https://www.jazzercise.com/', 'https://www.facebook.com/Jazzercise.Bellefontaine/ ', '', 40.36026620, -83.76037270, NULL, NULL, '0', '1'),
(61, 'Allstate Insurance', '15', '*', '', '(937) 599-4858', '207 S Main St, Bellefontaine, OH 43311, USA', 'https://agents.allstate.com/pat-grove-bellefontaine-oh.html', 'https://www.facebook.com/PatGroveAgency/', '', 40.35941550, -83.75968820, NULL, NULL, '0', '1'),
(62, 'Hair by Rascals', '16', '*', '', '(937) 599-5800', '212 S Main St, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/Hair-By-Rascals-199393020148742', '', 40.35928580, -83.76022750, NULL, NULL, '0', '1'),
(63, 'PNC Bank', '15', '*', '', '(937) 592-8035', '145 S Main St, Bellefontaine, OH 43311, USA', 'https://www.pnc.com/en/personal-banking/topics/welcome-to-pnc.html#intro', '', '', 40.35983000, -83.75954380, NULL, NULL, '0', '1'),
(64, 'Dodd’s Wealth Advisors', '15', '*', '', '(937) 592-0200', '145 S Main St, Bellefontaine, OH 43311, USA', 'https://www.doddswealthadvisors.com/', '', '', 40.35983000, -83.75954380, NULL, NULL, '0', '1'),
(66, 'The Photo Booth', '15', '*', '', '(614) 596-2349', '137 North Main Street, Bellefontaine, OH 43311, USA', 'https://photoboothbtown.com/', 'https://www.facebook.com/susiesphotobooth', '', 40.36209410, -83.76022240, NULL, NULL, '0', '1'),
(68, 'Bellefontaine Dental', '15', '*', '', '(937) 592-1776', '137 W Chillicothe Ave, Bellefontaine, OH 43311, USA', 'https://bellefontainedental.com/', '', '', 40.35969170, -83.76138590, NULL, NULL, '0', '1'),
(69, 'Zimmerman Realty Ltd', '15', '*', '', '(937) 592-4896', '143 W Chillicothe Ave, Bellefontaine, OH 43311, USA', 'https://www.zimmermanrealty.com/', 'https://www.facebook.com/ZimmermanRealty/ ', 'https://www.instagram.com/zimmermanrealty/', 40.35959560, -83.76155500, NULL, NULL, '0', '1'),
(70, 'Edward Jones', '15', '*', '', '(937) 593-0292', '1400 S Main St, Suite A, Bellefontaine, OH 43311', 'https://www.edwardjones.com/index.html', '', '', 40.34107740, -83.76236390, NULL, NULL, '0', '1'),
(71, 'H&R Block', '15', '*', '', '(937) 599-2410', '120 S Main St, Bellefontaine, OH 43311, USA', 'https://www.hrblock.com/local-tax-offices/ohio/bellefontaine/120-s-main-st/33830?otppartnerid=9308&campaignid=pw_mcm_9308_9762&y_source=1_MzU5OTAyOC03MTUtbG9jYXRpb24uZ29vZ2xlX3dlYnNpdGVfb3ZlcnJpZGU%3D', 'https://www.facebook.com/HRBlockBellefontaineOH', '', 40.36052940, -83.75994970, NULL, NULL, '0', '1'),
(72, 'Belle Printing', '15', '*', '', '(937) 592-5161', '118 S Main St, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/BellePrinting', '', 40.36059310, -83.76050710, NULL, NULL, '0', '1'),
(73, 'JB and Company', '15', '*', '', '(937) 210-5171', '143 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://waglerbadenhop.com/', 'https://www.facebook.com/pages/category/Tax-Preparation-Service/Wagler-Badenhop-Professional-Services-Firm-224784474799680/', '', 40.36105770, -83.76143050, NULL, NULL, '0', '1'),
(74, 'American Solutions for Business', '15', '*', '', '(937) 593-1911', '2647 Ohio 47, Bellefontaine, OH, USA', 'https://home.americanbus.com/default.aspx', 'https://www.facebook.com/americanbus/ ', '', 40.36368610, -83.81014460, NULL, NULL, '0', '1'),
(76, 'Nationwide Insurance', '15', '*', '', '(937) 593-9065', '143 E Chillicothe Ave, Bellefontaine, OH 43311, USA', 'https://agency.nationwide.com/oh/bellefontaine/43311/jeffrey-l-erwin-10729398', 'https://www.facebook.com/erwininsuranceagency/', '', 40.35976930, -83.75815330, NULL, NULL, '0', '1'),
(77, 'United States Postal Service', '19', '*', '', '(800) 275-8777', '132 S Detroit St, Bellefontaine, OH 43311, USA', 'https://www.usps.com/', '', 'https://www.instagram.com/usps.official/ ', 40.36007560, -83.76199050, NULL, NULL, '0', '1'),
(78, 'Gates Brothers Logan County Glass Inc', '15', '*', '', '(937) 592-2882', '241 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.gatesbrothers.com/', '', '', 40.36105070, -83.76317090, NULL, NULL, '0', '1'),
(79, 'The Logan County Farmer’s Market', '2', '*', '', '(937) 404-1209', '142 W Chillicothe Ave, Bellefontaine, OH 43311, USA', 'https://www.logancountyfarmersmarket.com/', 'https://m.facebook.com/logancountyohiofarmersmarket/events/?ref=page_internal&mt_nav=0 ', '', 40.35978640, -83.76107690, NULL, NULL, '0', '1'),
(80, 'Minnich Law Offices, LLC', '15', '*', '', '(937) 592-2004', '112 W Columbus Ave, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/groups/271534013937', '', 40.36130670, -83.76032080, NULL, NULL, '0', '1'),
(81, 'Undertone Beauty Bar', '16', '*', '', '', '125 W Columbus Ave, Bellefontaine, OH 43311, USA', '', '', '', 40.36093250, -83.76089750, NULL, NULL, '0', '1'),
(82, 'Golden Reserve', '15', '*', '', '(937) 292-7832', '139 S Opera St suite b, Bellefontaine, OH, USA ', 'https://goldenreserve.com/', 'https://www.facebook.com/pages/Golden-Reserve/182264712658518 ', '', 40.36105330, -83.76039820, NULL, NULL, '0', '1'),
(83, 'Superior Moving Services', '15', '*', '', '(937) 935-1267', '301 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://superior-moving-services.business.site/', 'https://www.facebook.com/MovingtheSuperiorway', '', 40.36110600, -83.76368200, NULL, NULL, '0', '1'),
(85, 'Scout Title', '15', '*', '', '(937) 599-1131', '113 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://scouttitle.com/', 'https://www.facebook.com/scouttitle/ ', '', 40.36105080, -83.76035240, NULL, NULL, '0', '1'),
(86, 'AlerStallings, LLC', '15', '*', '', '(937) 404-5432', '139 S Opera St suite a, Bellefontaine, OH 43311, USA', 'https://www.alerstallings.com/', 'https://www.facebook.com/pg/AlerStallings/posts/ ', '', 40.36098550, -83.76036010, NULL, NULL, '0', '1'),
(87, 'Citizens Federal Savings and Loan Association', '15', '*', '', '(937) 593-0015', '100 N Main St, Bellefontaine, OH 43311, USA', 'https://www.citizensfederalsl.com/', 'https://www.facebook.com/CitizensFederalSL/', '', 40.36145320, -83.75948640, NULL, NULL, '0', '1'),
(88, 'Huntington Bank', '15', '*', '', '(937) 593-2010', '201 E Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.huntington.com/', 'https://www.facebook.com/Huntington-Bank-162513597105576/', '', 40.36130490, -83.75755180, NULL, NULL, '0', '1'),
(89, 'Ameriprise Financial', '15', '*', '', '(858) 769-3842', '101 N Main St, Bellefontaine, OH 43311', 'https://www.ameriprise.com/', 'https://www.facebook.com/Ameriprise/', '', 40.36132260, -83.76011870, NULL, NULL, '0', '1'),
(90, 'Cardinal Appraisals', '15', '*', '', '(937) 592-2021', '103 N Main St, Bellefontaine, OH 43311, USA', 'https://business.logancountyohio.com/list/member/cardinal-appraisals-scott-abraham-4496', '', '', 40.36136150, -83.76003170, NULL, NULL, '0', '1'),
(91, 'Don M Hilliker Company', '15', '*', '', '(937) 593-9015', '101 W Columbus Ave, Bellefontaine, OH 43311, USA', '', '', '', 40.36133700, -83.75992000, NULL, NULL, '0', '1'),
(92, 'Skin Sanctuary Boutique Spa', '16', '*', '', '(937) 844-7078', '105 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.skinsanctuaryohio.com/', 'https://www.facebook.com/skinsanctuaryohio', 'https://www.instagram.com/skinsanctuary.oh/ ', 40.36106140, -83.76012390, NULL, NULL, '0', '1'),
(93, 'Beasley Architecture & Design', '15', '*', '', '(937) 599-2323', '109 W Columbus Ave, Bellefontaine, OH 43311', 'http://beasleyarchitecture.com/', 'https://www.facebook.com/pages/category/Gardener/Beasley-Landscape-Architects-100659028399234/', '', 40.36101990, -83.76018750, NULL, NULL, '0', '1'),
(97, 'All-Around Awards', '2', '*', '', '(937) 592-6281', '127 E Chillicothe Ave, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/All-Around-Awards-154410107929200', '', 40.35985190, -83.75861800, NULL, NULL, '0', '1'),
(98, 'Alan Galvez Insurance, Ltd.', '15', '*', '', '(937) 592-4871', '134 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.galvezinsurance.com/', 'https://www.facebook.com/galvezinsurance', '', 40.36124500, -83.76113900, NULL, NULL, '0', '1'),
(101, 'JC Sports', '2', '*', '', '(937) 593-4420', '116 S Main St, Bellefontaine, OH 43311, USA', 'https://www.jcsportsohio.com/', 'https://www.facebook.com/JC-Sports-178382445537357', '', 40.36067590, -83.76031380, NULL, NULL, '0', '1'),
(102, 'Aunalytics', '15', '*', '', '(937) 593-7177', '128 W Columbus Ave, Bellefontaine, OH 43311 ', 'http://www.aunalytics.com/', 'https://www.facebook.com/Aunalytics/', 'https://www.instagram.com/aunalytics/', 40.36156340, -83.76087050, NULL, NULL, '0', '1'),
(103, 'Artistic Treasures', '2', '*', '', '(937) 592-0899', '116 West E Columbus Ave, Bellefontaine, OH 43311', '', 'https://www.facebook.com/Artistic-Treasures-Bellefontaine-OHIO-607369509320846', '', 40.36090650, -83.75951950, NULL, NULL, '0', '1'),
(105, 'Peak Performance Holistic Health Center', '17', '*', '', '(937) 407-6293', '105 N Main St, Bellefontaine, OH 43311, USA', 'https://www.peakperformancemassage.net/', 'https://www.facebook.com/PeakPerformanceHolisticHealth', '', 40.36141600, -83.75988300, NULL, NULL, '0', '1'),
(106, 'Modern Office Methods', '15', '*', '', '(937) 404-4094', '122 S Main St, Bellefontaine, OH 43311, USA', 'https://www.momnet.com/', 'https://www.facebook.com/ModernOfficeMethods', '', 40.36179580, -83.75945770, NULL, NULL, '0', '1'),
(107, 'Prescription Bliss', '15', '*', '', '(877) 792-5477', '111 W Columbus Ave, Bellefontaine, Ohio 43311, USA', 'https://www.prescriptionbliss.com/', 'https://www.facebook.com/prescriptionbliss', '', 40.36150900, -83.76219420, NULL, NULL, '0', '1'),
(108, 'Smith, Smith, Montgomery & Chamberlain, LLC', '15', '*', '', '(937) 593-8510', '112 N Main St, Bellefontaine, OH 43311, USA', 'https://ssmattys.com/', '', '', 40.36148210, -83.75947770, NULL, NULL, '0', '1'),
(109, 'Hickory Medical Direct Primary Care, LLC', '15', '*', '', '(937) 404-2488', '1125 Rush Ave, Bellefontaine, OH 43311, USA', 'https://hickorydpc.com/', 'https://www.facebook.com/hickorydpc', 'https://www.instagram.com/hickorydpc/', 40.37517950, -83.75352770, NULL, NULL, '0', '1'),
(110, 'Wren’s Florist & Greenhouse', '2', '*', '', '(937) 593-5015', '500 E Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.wrensflorist.com/', 'https://www.facebook.com/Wrens-Florist-Greenhouses-189073387182', '', 40.36013210, -83.75163990, NULL, NULL, '0', '1'),
(112, 'Robson Family Dentistry', '15', '*', '', '(937) 599-6115', '240 E Sandusky Ave, Bellefontaine, OH 43311, USA', 'https://robsonfamilydentistry.com/', 'https://www.facebook.com/RobsonFamilyDentistry', '', 40.36211100, -83.75630720, NULL, NULL, '0', '1'),
(113, 'Logan County Libraries', '19', '*', '', '(937) 599-4189', '220 N Main St, Bellefontaine, OH 43311, USA', 'https://logancountylibraries.org/', 'https://www.facebook.com/logancountylibraries/', 'https://www.instagram.com/logancolibrary/', 40.36368410, -83.75896620, NULL, NULL, '0', '1'),
(115, 'High Point Home Health Ltd.', '15', '*', '', '(937) 592-9800', '180 Reynolds Ave, Bellefontaine, OH 43311, USA', 'http://highpointhomehealth.com/home.html', 'https://www.facebook.com/High-Point-Home-Health-Ltd-151323974884392', '', 40.37506080, -83.75996300, NULL, NULL, '0', '1'),
(116, 'Hess Lumber Company', '2', '*', '', '(937) 592-1766', '300 E Columbus Ave, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/Hess-Lumber-Co-503074123069091', '', 40.36069580, -83.75589560, NULL, NULL, '0', '1'),
(117, 'Logan County Historical Center', '23', '*', '', '(937) 593-7557', '521 E Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.loganhistory.org/', 'https://www.facebook.com/logancountyhistorycenter', '', 40.36117560, -83.75074790, NULL, NULL, '0', '1'),
(118, 'Logan County Commissioners Office', '19', '*', '', '(937) 599-7283', '117 E Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.co.logan.oh.us/188/Commissioners', 'https://www.facebook.com/logancountychamber/', '', 40.36177890, -83.76012340, NULL, NULL, '0', '1'),
(119, 'Logan County Veterans Services', '19', '*', '', '(937) 599-4221', '121 S Opera St, Bellefontaine, OH 43311, USA', 'https://www.co.logan.oh.us/227/Veterans-Services', 'https://www.facebook.com/LoganCountyVeteransServices', '', 40.36094230, -83.75880680, NULL, NULL, '0', '1'),
(120, 'A Peaceful Place Massage Company', '17', '*', '', '(937) 489-8191', '200 West Columbus Avenue, Bellefontaine, OH, USA', '', 'https://www.facebook.com/apeacefulplacemassageco', 'https://www.instagram.com/apeacefulplacemassage/', 40.36150220, -83.76181690, NULL, NULL, '0', '1'),
(126, 'Loco Depot Training Station', '17', '*', '', '(937) 292-7842', '210 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://locodepotbellefontaine.com/', 'https://www.facebook.com/locodepot', 'https://www.instagram.com/locodepot/', 40.36150900, -83.76219420, NULL, NULL, '0', '1'),
(127, 'Watkins Electric & Communications, LLC', '15', '*', '', '(844) 592-8658', '207 W Columbus Ave, Bellefontaine, OH 43311, USA', 'http://www.watkinselectriconline.co/', 'https://www.facebook.com/pages/category/Electrician/Watkins-Electric-332899613547379/', '', 40.36118090, -83.76208040, NULL, NULL, '0', '1'),
(128, 'Jennifer Marie Errington, LISW-S, RPT', '15', '*', '', '(937) 441-3043', '208 W Columbus Ave, 2nd Floor, Bellefontaine, OH 43311', 'https://www.theravive.com/therapists/jennifer-marie-errington.aspx', '', '', 40.36152570, -83.76201130, NULL, NULL, '0', '1'),
(129, 'Ascend Consulting, LLC', '15', '*', '', '(937) 210-4868', '139 West Columbus Avenue, Bellefontaine, OH, USA', 'https://www.ascendllc.co/', 'https://www.facebook.com/ascendconsultingllc/', '', 40.36130499, -83.76119184, NULL, NULL, '0', '1'),
(130, 'Classic Windows Doors-N-More', '2', '*', '', '(937) 599-4636', '142 W Columbus Ave, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/pages/category/Garage-Door-Service/Classic-Windows-Doors-n-More-107730814117898/', '', 40.36148960, -83.76134930, NULL, NULL, '0', '1'),
(131, 'King of Kingz Barbershop', '16', '*', '', '(937) 407-6260', '104 N Detroit St, Bellefontaine, OH 43311, USA', '', 'https://www.facebook.com/kingofkingzbabershop', '', 40.36153650, -83.76146960, NULL, NULL, '0', '1'),
(132, 'The Flying Pepper Cantina', '1', '*', '', '(937) 508-1102', '137 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://www.wespendlocal.com/flyingpepper.html', 'https://www.facebook.com/TheFlyingPepper', 'https://www.instagram.com/theflyingpepperfoodtruck/ ', 40.36085280, -83.76119300, NULL, NULL, '0', '1'),
(134, 'Bellefontaine Police Department', '19', '*', '', '(937) 599-1010', '135 North Detroit Street, Bellefontaine, OH 43311, USA', 'https://www.ci.bellefontaine.oh.us/police.html', 'https://www.facebook.com/Bellefontaine-Police-Department-298809090302012', '', 40.36211830, -83.76153320, NULL, NULL, '0', '1'),
(135, 'Bellefontaine City & Mayors Office', '19', '*', '', '(937) 592-4376', '135 N Detroit St, Bellefontaine, Ohio 43311, USA', 'https://www.ci.bellefontaine.oh.us/', 'https://www.facebook.com/Bellefontaine-City-Council-109791217336789/', '', 40.36211830, -83.76153320, NULL, NULL, '0', '1'),
(136, 'Bellefontaine Fire Department', '19', '*', '', '(937) 599-6168', '210 W Sandusky Ave, Bellefontaine, OH 43311, USA', 'https://www.ci.bellefontaine.oh.us/fire--ems.html', 'https://www.facebook.com/Bellefontaine-Fire-Department-632104106887403', '', 40.36298820, -83.76203840, NULL, NULL, '0', '1'),
(137, 'BUILD Cowork + Space', '15', '*', '', '(937) 589-2600', '139 W Columbus Ave, Bellefontaine, OH 43311, USA', 'https://buildcowork.com/', 'https://www.facebook.com/buildcowork', 'https://www.instagram.com/buildcowork/', 40.36098920, -83.76129280, NULL, NULL, '0', '1'),
(138, 'The Syndicate', '1', 'The Syndicate. Logan County’s finest restaurant, event center, and caterer.', '', '(937) 210-5165', '213 S Main St, Bellefontaine, OH 43311, USA', 'https://syndicatedowntown.com/', 'https://www.facebook.com/syndicatedowntown/', 'https://www.instagram.com/syndicateohio/', 40.35918040, -83.75974530, NULL, NULL, '0', '1'),
(139, 'Transportation Museum', '23', '*', '', '(937) 593-7557', '521 East Columbus Avenue, Bellefontaine, OH, USA', 'https://www.loganhistory.org/transportation-museum', '', '', 40.36117560, -83.75074790, NULL, NULL, '0', '1'),
(141, 'Axe Ventura', '23', '-', 'BELLEFONTAINE@AXE-VENTURA.COM', '', '139 South Main Street, Bellefontaine, OH, USA', 'https://axe-ventura.com/bellefontaine/', 'https://www.facebook.com/AV.Bellefontaine', 'https://www.instagram.com/av.bellefontaine/', 40.35995780, -83.75943930, NULL, NULL, '0', '1'),
(142, 'Richwood Bank', '15', '-', '', '(740) 943-2317', '120 E Sandusky Ave, Bellefontaine, OH 43311, USA', 'https://richwoodbank.com/bellefontaine-location/', 'https://www.facebook.com/richwoodbank/', 'https://www.instagram.com/richwoodbank/', 40.36244040, -83.75913480, NULL, NULL, '0', '1'),
(145, 'Salon Platinum 121', '15', '-', '', '', '121 W Columbus Ave, Bellefontaine, OH, USA', '', 'https://www.facebook.com/salonplatinum121', 'https://www.instagram.com/salonplatinum121/', 40.36099460, -83.76060370, NULL, NULL, '0', '1'),
(146, 'Relax Nail Bar and Salon', '15', '-', '', '(937) 404-9010', '130 West Columbus Avenue, Bellefontaine, OH, USA', '', 'https://www.facebook.com/relaxnbs', 'https://www.instagram.com/relaxnbs/', 40.36133610, -83.76101150, NULL, NULL, '0', '1'),
(148, 'Farm Charm', '2', '-', '', '', '131 S Main St, Bellefontaine, OH, USA', '', 'https://www.facebook.com/farmcharm.vintag', 'https://www.instagram.com/farmcharm.vintage/', 40.36022950, -83.75969230, NULL, NULL, '0', '1'),
(149, 'Glitter and Grit', '2', '-', '', '', '129 S Main St Bellefontaine OH 43311', '', 'https://www.facebook.com/glitterandgritattheopera', 'https://www.instagram.com/glitterandgritattheopera/', 40.36025240, -83.75969850, NULL, NULL, '0', '1'),
(151, 'Queen of Hearts', '2', '-', '', '', '112 E Court Ave, Bellefontaine, OH, USA', '', 'https://www.facebook.com/queenofheartsdowntown', 'https://www.instagram.com/queenofheartsdowntown/', 40.36033980, -83.75929180, NULL, NULL, '0', '1'),
(153, 'The Craft Emporium', '2', '-', '', '', '120 West Chillicothe Avenue, Bellefontaine, OH, USA', '', 'https://www.facebook.com/profile.php?id=100083502785314', 'https://www.instagram.com/the_craft_emporium_llc/', 40.35988200, -83.76068250, NULL, NULL, '0', '1'),
(155, 'Rush Creek Art Studio', '23', '-', '', '', '116 West Chillicothe Avenue, Bellefontaine, OH, USA', '', 'https://www.facebook.com/dwightgreenbaumart', '', 40.35985410, -83.76057910, NULL, NULL, '0', '1'),
(157, 'Candy Craze', '2', '-', '', '', '129 West Columbus Avenue, Bellefontaine, OH, USA', '', 'https://www.facebook.com/profile.php?id=100086733071042', '', 40.36110290, -83.76092440, NULL, NULL, '0', '1'),
(160, 'The Naked Goat', '2', '-', '', '', '118 North Main Street, Bellefontaine, OH, USA', '', 'https://www.facebook.com/thenakedgoatdowntown', 'https://www.instagram.com/thenakedgoatdowntown/', 40.36187487, -83.75953028, NULL, NULL, '0', '1'),
(161, 'One-Eyed Cookie Lab', '1', '-', '', '', '118 E Court Ave, Bellefontaine, OH 43311, USA', 'https://www.oneeyedcookielab.com/', 'https://www.facebook.com/oneeyedcookielab', 'https://www.instagram.com/oneeyedcookielab/', 40.36047825, -83.75921786, NULL, NULL, '0', '1'),
(163, 'Salty Swan', '15', 'Tattoo and Piercing Shop', '', '', '114 West Chillicothe Avenue, Bellefontaine, OH, USA', '', 'https://www.facebook.com/profile.php?id=100077181140494', 'https://www.instagram.com/thesaltyswanstudio/', 40.36009020, -83.76049070, NULL, NULL, '0', '1'),
(164, 'The Flats', '3', '-', '', '', '120 West Chillicothe Avenue, Bellefontaine, OH, USA', 'https://theflatsdowntown.com/', '', '', 40.36006183, -83.76071469, NULL, NULL, '0', '1'),
(165, 'The Flats', '3', '-', '', '', '116 W Chillicothe Ave, Bellefontaine, OH 43311, USA', 'https://theflatsdowntown.com/', '', '', 40.35985214, -83.76057595, NULL, NULL, '0', '1'),
(167, 'Sofa Express', '2', '-', '', '', '217 West Columbus Avenue, Bellefontaine, OH, USA', 'https://www.sofa-xpress.com/', 'https://www.facebook.com/sofaxpress', '', 40.36114850, -83.76236260, NULL, NULL, '0', '1'),
(168, 'Triple Moon Designs', '2', 'In the Marketplace', '', '', '117 West Columbus Avenue, Bellefontaine, OH, USA', 'https://triplemoonshop.com/', 'https://www.facebook.com/triplemoondesignco', '', 40.36097500, -83.76062300, NULL, NULL, '0', '1'),
(169, 'Decode Zone', '23', 'In The Marketplace', '', '', '130 S Main St suite b105, Bellefontaine, OH, USA', 'https://www.decodezonegames.com/', 'https://www.facebook.com/decodezonegames/', '', 40.36026600, -83.76037300, NULL, NULL, '0', '1'),
(170, 'Wrock Bottom Bar', '1', '-', '', '', '121 West Chillicothe Avenue, Bellefontaine, OH, USA', '', 'https://www.facebook.com/people/Wrock-Bottom-Bar/61575758045882/', '', 40.35957380, -83.76072110, NULL, NULL, '0', '1'),
(171, 'Mystic Mama Rock + Jewelry', '2', '-', '', '', '110 East Court Avenue, Bellefontaine, OH, USA', '', 'https://facebook.com/mysticmamadowntown', 'https://instagram.com/mysticmamadowntown', 40.36034340, -83.75935130, NULL, NULL, '0', '1'),
(172, 'Two Babes Boutique', '2', '-', '', '', '118 W Chillicothe Ave, Bellefontaine, OH, USA', 'https://twobabesboutique.com', 'https://www.facebook.com/profile.php?id=61553125836443', 'https://www.instagram.com/thetwobabesboutique', 40.35990360, -83.76064230, NULL, NULL, '0', '1'),
(173, 'Opera Suites by BUILD', '15', '-', '', '(937) 589-2600', '112 E Court Ave, Bellefontaine, OH, USA', 'https://buildcowork.com/opera-suites-by-build/', 'https://www.facebook.com/operasuitesbybuild', '', 40.36033980, -83.75929180, NULL, NULL, '0', '1'),
(174, 'Lavender Undergound Salon & Grooming Parlor', '16', '-', '', '(937) 896-9331', '133 West Columbus Avenue, Bellefontaine, OH, USA', '', '', '', 40.36094160, -83.76111940, NULL, NULL, '0', '1'),
(175, 'The Butcher & Market', '1', '-', '', '(937) 388-9929', '214 S Main St, Bellefontaine, OH, USA', '', 'https://www.facebook.com/profile.php?id=61559874871261', 'https://www.instagram.com/thebutcherandmarket', 40.35923780, -83.76025860, NULL, NULL, '0', '1'),
(176, 'Modern Beauty Aesthetics and Wellness', '16', '-', '', '(937) 441-4874', '115 N Main St, Bellefontaine, OH, USA', 'https://modernbeautyaw.com/', 'https://www.facebook.com/profile.php?id=61551236782503', 'https://www.instagram.com/modernbeautyaw', 40.36160370, -83.75997830, NULL, NULL, '0', '1'),
(177, 'Legado Brazilian Jiu Jitsu', '17', '-', '', '(937) 553-4374', '210 W Columbus Ave suite 3, Bellefontaine, OH, USA', 'https://legadobjjschool.square.site/', 'https://www.facebook.com/p/Legado-Brazilian-Jiu-Jitsu-Bellefontaine-Ohio-61552516257636/', 'https://www.instagram.com/tmclegadobjj', 40.36150900, -83.76219420, NULL, NULL, '0', '1'),
(178, 'The Academy', '17', '-', '', '(937) 752-5530', '210 W Columbus Ave suite 5, Bellefontaine, OH, USA', 'https://www.betheacademy.com/', 'https://www.facebook.com/betheacademy', '', 40.36150900, -83.76219420, NULL, NULL, '0', '1'),
(179, 'Allways Brewin’', '1', '-', '', '', '200 W Columbus Ave, Bellefontaine, OH, USA', 'https://allways-brewin.square.site/', 'https://www.facebook.com/p/Allways-Brewin-61567479662011/', '', 40.36148690, -83.76177500, NULL, NULL, '0', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sn_listing`
--
ALTER TABLE `sn_listing`
  ADD PRIMARY KEY (`listingId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sn_listing`
--
ALTER TABLE `sn_listing`
  MODIFY `listingId` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
