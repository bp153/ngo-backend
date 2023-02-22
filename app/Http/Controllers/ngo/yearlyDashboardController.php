<?php

namespace App\Http\Controllers\ngo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class yearlyDashboardController extends Controller
{
    
    public function totalSubmissions(Request $request){
        $ngo_id=$request->ngo_id;
        $year=$request->year;
        $dashboard = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->whereYear('entry_month_year', '=', $year)
        ->select(DB::raw('COUNT(id) as total_entries, 
        
        SUM(churches) as church,
        SUM(mosques) as mosques,
        SUM(town_halls) as town_halls,
SUM(schools) as schools,
        SUM(video_shows) as video_shows,
        SUM(information_center) as information_center,
        SUM(radio_discussion) as radio_discussion,
        SUM(mobile_van) as mobile_van,
        SUM(iec_reached) as iec_reached,
        SUM(pregnant_women_identified) as pregnant_women_identified,
        SUM(pregnant_women_followed_up) as pregnant_women_followed_up,
        SUM(iptp_one) as iptp_one,
        SUM(iptp_two) as iptp_two,
        SUM(iptp_three) as iptp_three,
        SUM(iptp_four) as iptp_four,
        SUM(iptp_five) as iptp_five
        '))
        ->get();

        return response()->json($dashboard);
    }
    public function Iptp(Request $request){
        $ngo_id=$request->ngo_id;
        $year=$request->year;
        $iptp = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->whereYear('entry_month_year', '=', $year)
        ->select(DB::raw('
        SUM(iptp_one) as "iptp one",
        SUM(iptp_two) as "iptp two",
        SUM(iptp_three) as "iptp three",
        SUM(iptp_four) as "iptp four",
        SUM(iptp_five) as "iptp five"
        '))
     
        ->get();
        return response()->json($iptp);
    }

    public function sensitization(Request $request){
        $ngo_id=$request->ngo_id;
        $year=$request->year;
        $iptp = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->whereYear('entry_month_year', '=', $year)
        ->select(DB::raw('
        SUM(churches) as "churches",
        SUM(mosques) as "mosques",
        SUM(town_halls) as "durbars"
     
        '))
        ->get();
        return response()->json($iptp);
    }

    public function mediaEngagement(Request $request){
        $ngo_id=$request->ngo_id;
        $year=$request->year;
        $media = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->whereYear('entry_month_year', '=', $year)
        ->select(DB::raw('
        SUM(video_shows) as "video shows",
        SUM(mobile_van) as "mobile vans",
        SUM(radio_discussion) as "radio discussions",
        SUM(information_center) AS "community information center"
     
        '))
        ->get();
        return response()->json($media);
    }

    public function peopelReached(Request $request){
        $ngo_id=$request->ngo_id;
        $year=$request->year;
        $people_reached = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->whereYear('entry_month_year', '=', $year)
        ->select(DB::raw('SUM(church_people_reached) as "church",
        SUM(mosque_people_reached) as "mosque",
        SUM(town_hall_people_reached) as "durbars",
        SUM(school_people_reached) as "schools"
        '))
     
        ->get();
        return response()->json($people_reached);
    }
    
    public function followUp(Request $request){
        $ngo_id=$request->ngo_id;
        $year=$request->year;
        $iptp = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->whereYear('entry_month_year', '=', $year)
        ->select(DB::raw('
        SUM(pregnant_women_identified) as "Pregnant women identified",
        SUM(pregnant_women_followed_up) as "Pregnant women followed up"
     
        '))
        ->get();
        return response()->json($iptp);
    }
}
