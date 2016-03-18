-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2015 at 08:44 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `speed`
--

-- --------------------------------------------------------

--
-- Table structure for table `lightraceracers`
--

CREATE TABLE IF NOT EXISTS `lightraceracers` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `speed` decimal(16,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `fun_fact` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Dumping data for table `lightraceracers`
--

INSERT INTO `lightraceracers` (`id`, `name`, `speed`, `image`, `fun_fact`) VALUES
(5, 'Nolan%20Ryan%20Fastball', '100.00', 'fastball.png', 'Fastball%20pitches%20in%20excess%20of%20105%20MPH%20risk%20tearing%20the%20ulnar%20(elbow)%20collateral%20ligament%20of%20the%20pitcher.'),
(6, 'Water Skier', '120.00', 'waterskier.png', 'Water%20skiing%20was%20invented%20by%20an%2018-year-old%20using%20two%20boards%20and%20some%20clothesline%20in%201922.'),
(7, 'Peregrine Falcon', '242.00', 'peregrine_falcon.png', 'The%20fastest%20animal%20in%20the%20world%2C%20the%20Peregrine%20falcon%20has%20been%20trained%20by%20humans%20to%20hunt%20for%20birds%20for%20over%203000%20years.'),
(8, 'Danica Patrick', '212.00', 'danica_patrick.png', '155%20beats%20per%20minute%3B%20A%20NASCAR%20racer''s%20heartbeat%20of%20155%20beats%20per%20minute%20is%20the%20same%20as%20that%20of%20a%20Marathon%20runner.'),
(9, 'Paintball Pellet', '200.00', 'paintball.png', 'While%20these%20soft%20grape-sized%20balls%20are%20filled%20with%20washable%20paint%2C%20they%20travel%20at%20170%20to%20204%20miles%20per%20hour%20%E2%80%93%20Splat!%20Ouch!'),
(10, 'SR-71 Blackbird', '2100.00', 'blackbird.png', 'The%20SR-71%20flew%20so%20fast%20that%20parts%20of%20its%20titanium%20skin%20heated%20up%20to%20over%20950%C2%BA%20F%20(510%C2%BAC).'),
(11, 'Brook Trout', '23.00', 'brook_trout.png', 'The%20brook%20trout%20is%20so%20popular%20among%20fisherman%20that%20it%20is%20the%20state%20fish%20in%20nine%20different%20states%2C%20including%20Virginia.'),
(12, 'RMS%20Titanic', '28.00', 'titanic.png', 'On%20its%20first%20%E2%80%93%20and%20last%20%E2%80%93%20voyage%20the%20Titanic%20was%20trying%20to%20break%20the%20speed%20record%20for%20crossing%20the%20Atlantic.%20Despite%20six%20warnings%20of%20sea%20ice%2C%20it%20was%20still%20traveling%20at%20near%20its%20maximum%20speed%2C%2026%20miles%20per%20hour%2C%20when%20it%20hit%20the%20iceberg.'),
(13, 'USS%20Virginia%20(SSN-774)', '29.00', 'uss_virginia.png', 'The%20USS%20Virginia%20(SSN-774)%2C%20the%20%E2%80%9Cfast%20attack%E2%80%9D%20sub%2C%20is%20longer%20than%20a%20football%20field.'),
(14, 'Eastern Cottontail', '20.00', 'eastern_cottontail.png', 'When%20pursued%20by%20predators%2C%20an%20Eastern%20Cottontail%20jumps%20sideways%20to%20break%20its%20scent%20trail.'),
(15, 'North American Dragonfly', '18.00', 'north_american_dragonfly.png', 'Dragonflies%20can%20hover%20like%20a%20helicopter%20and%20then%20dart%20off%20instantly%20in%20any%20direction%2C%20even%20backwards.%20300%20Million%20years%20ago%20some%20dragonflies%20had%20a%20wingspan%20of%20two%20feet.'),
(16, 'Big-Eared Bat', '40.00', 'big_eared_bat.png', 'Big-eared%20bat%E2%80%99s%20%E2%80%98big%20ears%E2%80%99%20make%20up%20nearly%20a%20quarter%20of%20their%20length.'),
(17, 'North American Jet Stream', '60.00', 'north_american_jet_stream.png', 'Jet%20streams%20move%20mostly%20west%20to%20east%2C%20and%20can%20add%20up%20to%20100%20mph%20to%20an%20airplane''s%20speed.'),
(18, 'Gulf Stream', '100.00', 'gulf_stream.png', 'Benjamin%20Franklin%20helped%20chart%20and%20name%20the%20Gulf%20Stream%2C%20which%20allowed%20sea%20captains%20to%20reduce%20travel%20time%20to%20and%20from%20England%20and%20the%20US%20by%20two%20weeks.'),
(19, 'Dump Truck on the Freeway', '65.00', 'dump_truck.png', 'Dump%20trucks%20get%20approximately%203%20miles%20per%20gallon.'),
(20, 'Human Sneeze', '40.00', 'sneeze.png', 'Humans%20do%20not%20sneeze%20when%20they%20are%20asleep.'),
(21, 'AMC Gremlin', '80.00', 'gremlin.png', '%E2%80%9CGremlin%E2%80%9D%20means%20a%20small%20gnome%20responsible%20for%20mechanical%20failures.'),
(22, 'Atlantic Sailfish', '68.00', 'atlantic_sailfish.png', 'At%2068%20mph%2C%20the%20Sailfish%20is%20the%20fastest%20fish%20in%20the%20ocean.'),
(23, 'Cheetah', '70.00', 'cheetah.png', 'Cheetahs%20can%20accelerate%200-60%20mph%20in%20three%20seconds%2C%20faster%20than%20a%20Stingray%20or%20a%20Porsche.'),
(24, 'Commercial Jet', '560.00', 'commercial_jet.png', ''),
(25, 'Olympic Sprinter - Usain Bolt', '27.80', 'sprinter.png', 'At%20top%20speed%2C%20Usain%20Bolt%20can%20run%20the%20length%20of%20a%20basketball%20court%20in%20less%20than%202.5%20seconds.'),
(26, 'F-35 Lightning', '1200.00', 'f17_lightning.png', 'The%20F-35%20contains%20approximately%20300%2C000%20individual%20parts.'),
(27, 'Cardinal', '25.00', 'cardinal.png', 'Male%20cardinals%20can%20sing%20up%20to%20200%20songs%20in%20an%20hour.'),
(28, 'Snapping Turtle', '2.40', 'snapping_turtle.png', 'The%20Snapping%20turtle%20can%20extend%20its%20head%20and%20strike%20as%20fast%20as%20a%20rattlesnake.'),
(29, 'Homing Pidgeon', '90.00', 'homing_pidgeon.png', 'In%20just%20one%20day%2C%20a%20champion%20racing%20pigeon%20can%20fly%20home%20from%20400-600%20miles%20away.'),
(30, 'Wild Turkey', '35.00', 'wild_turkey.png', 'The%20wild%20turkey%E2%80%99s%20bald%20head%20can%20change%20color%20in%20seconds%20with%20excitement%20or%20emotion.'),
(31, 'Red-Tail Hawk', '121.20', 'red-tail_hawk.png', 'A%20Red-tailed%20hawk%20can%20spot%20a%20mouse%20from%20100%20feet%20up%20in%20the%20air%E2%80%94about%20ten%20stories%20high.'),
(32, 'Bald Eagle', '35.00', 'bald_eagle.png', 'Bald%20eagles%20build%20large%20nests%2C%20sometimes%20weighing%20as%20much%20as%20a%20ton.'),
(33, 'Canada Goose', '55.00', 'canada_goose.png', 'Canada%20geese%20can%20fly%20to%20an%20altitude%20of%20at%20least%2029%2C000%20ft%20during%20migrations.'),
(34, 'Masai Warrior Chased by Rhinoceros', '18.40', 'masai_warrior.png', 'Custom%20dictates%20that%20a%20Maasai%20warrior%20can%20never%20eat%20or%20drink%20alone%2C%20only%20with%20at%20least%20one%20other%20warrior.'),
(35, 'African Rhinoceros', '30.00', 'african_rhinoceros.png', 'A%20group%20of%20rhinoceroses%20is%20called%20a%20crash.'),
(36, 'Indian Elephant', '25.00', 'indian_elephant.png', 'Elephants%20normally%20sleep%20only%202%20or%203%20hours%20each%20day.'),
(37, 'African Elephant', '24.90', 'african_elephant.png', 'African%20elephants%20can%20eat%20up%20to%20660%20pounds%20and%20drink%20up%20to%2050%20gallons%20in%20a%20single%20day.'),
(38, 'Red Fox', '30.00', 'red_fox.png', 'The%20red%20fox%20can%20make%2028%20different%20sounds%20to%20communicate.'),
(39, 'Gray Squirrel', '15.00', 'gray_squirrel.png', 'Each%20year%20a%20squirrel%20typically%20stores%20food%20in%20several%20thousand%20hiding%20places%20or%20caches.'),
(40, 'Monarch Butterfly', '12.00', 'butterfly.png', 'All%20butterflies%2C%20including%20the%20Monarch%2C%20taste%20with%20their%20feet.'),
(41, 'Bottlenose Dolphin', '22.00', 'bottlenose_dolphin.png', 'Large%20pods%20of%20dolphins%20can%20have%201%2C000%20members%20or%20more.'),
(42, 'Secretariat', '37.00', 'secretariat.png', 'Secretariat%20was%20the%20was%20the%20only%20non-human%20on%20ESPN%E2%80%99s%20100%20Greatest%20Athletes%20of%20the%20Twentieth%20Century.'),
(44, 'Lionel Messi Kick', '65.00', 'soccer_kick.png', 'An%20average%20soccer%20player%20runs%20about%207%20miles%20in%20a%2090-minute%20match.'),
(45, 'North African Ostrich', '40.00', 'north_african_ostrich.png', 'The%20Ostrich%20is%20the%20world%E2%80%99s%20largest%20bird.'),
(46, 'Greyhound', '43.00', 'greyhound.png', 'The%20greyhound%20is%20one%20of%20the%20most%20ancient%20breeds%2C%20dating%20back%20to%20ancient%20Egypt.'),
(47, 'Tsunami', '600.00', 'tsunami.png', 'In%202004%2C%20the%20Indian%20Ocean%20tsunami%20was%20caused%20by%20an%20earthquake%20with%20the%20energy%20of%2023%2C000%20atomic%20bombs.%20That%20is%20more%20energy%20than%20in%20all%20earthquakes%20on%20the%20planet%20in%20the%20last%2025%20years%20combined.'),
(48, 'Pyroclastic Flow', '450.00', 'pyroclastic_flow.png', 'The%20temperature%20of%20the%20tephra%20(gas%20and%20rock)%20in%20a%20pyroclastic%20flow%20can%20exceed%201800%20F%2C%20higher%20than%20the%20melting%20point%20of%20silver.'),
(49, 'Pronghorn Antelope', '55.00', 'pronghorn_antelope.png', 'Pronghorn%20antelope%20is%20the%20fastest%20land%20animal%20in%20the%20western%20hemisphere.'),
(50, 'Michael Phelps', '4.70', 'michael_phelps.png', ''),
(53, 'Olympic Tennis player Serena Williams'' serve', '118.00', 'tennis_serve.png', ''),
(54, 'Human Walking', '3.10', 'human_walking.png', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
