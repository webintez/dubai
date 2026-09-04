<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meeting;
use Carbon\Carbon;

class MeetingSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate to ensure clean set of 10 meetings
        Meeting::query()->delete();

        // ==========================================
        // 3 PAST MEETINGS (TIME PAST)
        // ==========================================
        Meeting::create([
            'title' => 'Dubai International Crypto & Web3 Sovereign Summit',
            'description' => 'Closed-door investor briefing on UAE institutional digital asset regulations, VARA compliance, and sovereign crypto treasury strategies.',
            'link' => 'https://zoom.us/j/90123456781',
            'duration' => '60 Mins',
            'password' => 'VARA2026',
            'price' => '250 AED',
            'thumbnail' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?auto=format&fit=crop&w=800&q=80',
            'start_time' => Carbon::now()->subDays(2),
        ]);

        Meeting::create([
            'title' => 'Downtown Dubai Luxury Penthouse & Off-Plan Showcase',
            'description' => 'Exclusive walkthrough of newly released prime sky penthouses adjacent to Dubai Mall, with guaranteed ROI forecasts and payment plans.',
            'link' => 'https://zoom.us/j/90123456782',
            'duration' => '45 Mins',
            'password' => 'DOWNTOWN99',
            'price' => '120 AED',
            'thumbnail' => 'https://images.unsplash.com/photo-1546412414-e1885259563a?auto=format&fit=crop&w=800&q=80',
            'start_time' => Carbon::now()->subDay()->subHours(3),
        ]);

        Meeting::create([
            'title' => 'UAE Golden Visa Family Wealth Advisory Briefing',
            'description' => 'Comprehensive guidance on 10-year Golden Visa eligibility criteria, tax optimization, and wealth preservation structures under UAE law.',
            'link' => 'https://meet.google.com/xyz-golden-visa',
            'duration' => '90 Mins',
            'password' => 'GOLDEN2026',
            'price' => '180 AED',
            'thumbnail' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80',
            'start_time' => Carbon::now()->subHours(5),
        ]);

        // ==========================================
        // 3 LIVE MEETINGS (HAPPENING RIGHT NOW)
        // ==========================================
        Meeting::create([
            'title' => 'Dubai Prime Waterfront Villas & Palm Jumeirah Summit',
            'description' => 'Executive live briefing on beachfront mansions, private marina berths, and high-yielding off-plan luxury acquisitions in Palm Jumeirah.',
            'link' => 'https://zoom.us/j/98472910384',
            'duration' => '60 Mins',
            'password' => 'PALMVIP77',
            'price' => '150 AED',
            'thumbnail' => 'https://images.unsplash.com/photo-1580674684081-7617fbf3d745?auto=format&fit=crop&w=800&q=80',
            'start_time' => Carbon::now()->subMinutes(15), // Started 15 mins ago, duration 60 mins -> LIVE
        ]);

        Meeting::create([
            'title' => 'Burj Khalifa Sky Club VIP Business Networking',
            'description' => 'High-net-worth individual closed network session covering Dubai DIFC corporate setups, banking, and strategic partnerships.',
            'link' => 'https://meet.google.com/abc-dxbv-ipn',
            'duration' => '90 Mins',
            'password' => 'SKYCLUB2026',
            'price' => '300 AED',
            'thumbnail' => 'https://images.unsplash.com/photo-1509316975850-ff9c5deb0cd9?auto=format&fit=crop&w=800&q=80',
            'start_time' => Carbon::now()->subMinutes(30), // Started 30 mins ago, duration 90 mins -> LIVE
        ]);

        Meeting::create([
            'title' => 'DIFC Offshore Hedge Fund & Private Equity Roundtable',
            'description' => 'Exclusive live discussion with tier-one fund managers on capital deployment, private credit, and venture capital syndication in the GCC.',
            'link' => 'https://zoom.us/j/84920194821',
            'duration' => '45 Mins',
            'password' => 'DIFCFUND88',
            'price' => '200 AED',
            'thumbnail' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
            'start_time' => Carbon::now()->subMinutes(10), // Started 10 mins ago, duration 45 mins -> LIVE
        ]);

        // ==========================================
        // 4 UPCOMING MEETINGS (SCHEDULED FOR FUTURE)
        // ==========================================
        Meeting::create([
            'title' => 'Palm Jumeirah Ultra-Luxury Mega-Yacht Advisory',
            'description' => 'Masterclass on bespoke yacht chartering, marina operations, and VIP maritime hospitality across the Arabian Gulf.',
            'link' => 'https://zoom.us/j/77291048211',
            'duration' => '60 Mins',
            'password' => 'YACHT2026',
            'price' => '180 AED',
            'thumbnail' => 'https://images.unsplash.com/photo-1569263979104-865ab7cd8d17?auto=format&fit=crop&w=800&q=80',
            'start_time' => Carbon::now()->addHours(2), // Starts in 2 hours -> UPCOMING
        ]);

        Meeting::create([
            'title' => 'Dubai Royal Desert Reserve & Helitour VIP Briefing',
            'description' => 'Personalized concierge orientation for ultra-exclusive desert safari reserves, conservation club access, and VIP helicopter transfers.',
            'link' => 'https://meet.google.com/helitour-dubai-vip',
            'duration' => '45 Mins',
            'password' => 'HELI2026',
            'price' => '140 AED',
            'thumbnail' => 'https://images.unsplash.com/photo-1518684079-3c830dcef090?auto=format&fit=crop&w=800&q=80',
            'start_time' => Carbon::now()->addHours(6), // Starts in 6 hours -> UPCOMING
        ]);

        Meeting::create([
            'title' => 'Dubai AI & Global Technology Investors Executive Masterclass',
            'description' => 'In-depth executive panel analyzing UAE national AI initiatives, enterprise incubation incentives, and high-growth MENA tech ventures.',
            'link' => 'https://zoom.us/j/66192847291',
            'duration' => '90 Mins',
            'password' => 'AITECH2026',
            'price' => '220 AED',
            'thumbnail' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
            'start_time' => Carbon::now()->addDay()->setTime(14, 0), // Tomorrow at 2 PM -> UPCOMING
        ]);

        Meeting::create([
            'title' => 'Emirates Private Aviation & Jet Charter Closed Session',
            'description' => 'Private aviation briefing on fractional ownership, empty leg flights, and VIP airport terminal privileges at Al Maktoum International.',
            'link' => 'https://meet.google.com/emirates-jet-vip',
            'duration' => '120 Mins',
            'password' => 'PRIVATEJET88',
            'price' => '350 AED',
            'thumbnail' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?auto=format&fit=crop&w=800&q=80',
            'start_time' => Carbon::now()->addDay()->setTime(17, 30), // Tomorrow at 5:30 PM -> UPCOMING
        ]);
    }
}
