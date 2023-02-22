<?php

namespace App\Http\Controllers\ngo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ngoExport;
use App\Exports\yearExport;
use Excel;
class ngoController extends Controller
{
    //

    public function totalSubmissions(Request $request){
        $ngo_id=$request->ngo_id;
        $dashboard = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
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


    public function ngoName(Request $request){
        
        $ngo_id=$request->ngo_id;
        $ngo_name = DB::table('ngos')
                ->where('id','=',$ngo_id)
                ->select('ngo_name')
                ->value('groupName');
                return response()->json($ngo_name);
    }

    public function getUserName(Request $request){
        
        $user_id=$request->user_id;
        $name = DB::table('users')
                ->where('id','=',$user_id)
                ->select('first_name','last_name')
               ->get();
                return response()->json($name);
    }

    public function Iptp(Request $request){
        $ngo_id=$request->ngo_id;
        $iptp = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->select(DB::raw('
        SUM(iptp_one) as "IPTp one",
        SUM(iptp_two) as "IPTp two",
        SUM(iptp_three) as "IPTp three",
        SUM(iptp_four) as "IPTp four",
        SUM(iptp_five) as "IPTp five"
        '))
     
        ->get();
        return response()->json($iptp);
    }

    public function sensitization(Request $request){
        $ngo_id=$request->ngo_id;
        $iptp = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
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
        $media = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->select(DB::raw('
        SUM(video_shows) as "video shows",
        SUM(mobile_van) as "mobile vans",
        SUM(radio_discussion) as "radio discussions",
        SUM(information_center) AS "community information center"
     
        '))
        ->get();
        return response()->json($media);
    }

    public function lastFiveEntries(Request $request){
        $ngo_id=$request->ngo_id;
        $entries = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->select('schools','radio_discussion','mobile_van')
        ->orderBy('id', 'desc')
        ->limit(5)
        ->get();

        return response()->json($entries);
    }

    public function makeEntry(Request $request){
        $data = json_decode($request->data, true);
        $date=date("Y-m-d");
        $expiry_date=Date('y:m:d', strtotime('+10 days'));
      //echo var_dump($data);
        $ngo_id=$data["param"]["ngo_id"];
       
        $churches= $data["param"]['churches'];
        $church_people_reached=$data["param"]["church_people_reached"];
        
        
        $mosques= $data["param"]['mosques'];
        $mosque_people_reached=$data["param"]["mosque_people_reached"];
        $town_hall= $data["param"]['town_hall'];
        $town_hall_people_reached=$data["param"]["town_hall_people_reached"];
        $schools= $data["param"]['schools'];
        $school_people_reached=$data["param"]["schools_people_reached"];
    
        $video_shows= $data["param"]['video_shows'];
        $community_announcements= $data["param"]['community_announcements'];
        $radio= $data["param"]['radio'];
        $van= $data["param"]['van'];
        $entry_month_year= $data["param"]['month'];
        $iec_reached= $data["param"]['iec_reached'];
        $pregnant_women_identified= $data["param"]['pregnant_women_identified'];
        $pregnant_women_followed= $data["param"]['pregnant_women_followed'];
        $iptp_one= $data["param"]['iptp_one'];
        $iptp_two= $data["param"]['iptp_two'];
        $iptp_three= $data["param"]['iptp_three'];
        $iptp_four= $data["param"]['iptp_four'];
        $iptp_five= $data["param"]['iptp_five'];
        
        $entry = DB::table('ngo_entries')->insert(
            [
                'entry_month_year'=>$entry_month_year,'ngo_id' => $ngo_id,'churches'=>$churches,'church_people_reached'=>$church_people_reached,'mosques'=>$mosques,'mosque_people_reached'=>$mosque_people_reached,'town_halls'=>$town_hall,
               'town_hall_people_reached'=>$town_hall_people_reached,'schools'=>$schools,'school_people_reached'=>$school_people_reached,'video_shows'=>$video_shows,'information_center'=>$community_announcements,
                'radio_discussion'=>$radio,'mobile_van'=>$van,'iec_reached'=>$iec_reached,'pregnant_women_identified'=>$pregnant_women_identified,
                'pregnant_women_followed_up'=>$pregnant_women_followed,'iptp_one'=>$iptp_one,'iptp_two'=>$iptp_two,'iptp_three'=>$iptp_three,
                'iptp_four'=>$iptp_four,'iptp_five'=>$iptp_five,'expiry_date'=>$expiry_date 
            ]);

        if($entry){
            
            $res = array("ok" => "Entry was sucessful");
            return response()->json($res);
        }
    }

    public function editEntry(Request $request){
        $entry_id=$request->entry_id;
        $entries = DB::table('ngo_entries')
        ->where('id','=',$entry_id)
        ->select('community_durbar','churches','mosques','town_halls','markets','funerals','video_shows',
        'information_center','radio_discussion','mobile_van','iec_reached','pregnant_women_identified','pregnant_women_followed_up',
        'iptp_one','iptp_two','iptp_three','iptp_four','iptp_five'
        )
        ->first();

        if($entries){
            return response()->json($entries);
        }
       
    }

    public function getDataById(Request $request){
        $entry_id=$request->entry_id;
        $entries = DB::table('ngo_entries')
        ->where('id','=',$entry_id)
        ->select('id','churches','church_people_reached','mosques','mosque_people_reached','town_halls','town_hall_people_reached','schools','school_people_reached','video_shows',
        'information_center','radio_discussion','mobile_van','iec_reached','pregnant_women_identified','pregnant_women_followed_up',
        'iptp_one','iptp_two','iptp_three','iptp_four','iptp_five','expiry_date'
        )
        ->get();
        if($entries){
            return response()->json($entries);
        }
    }

    public function selectAll(Request $request){
        $ngo_id=$request->ngo_id;
        $entries = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->select(DB::raw('
        id,YEAR(entry_month_year) AS year,MONTHNAME(entry_month_year) AS month,churches,mosques,town_halls,expiry_date
     
        '))
        ->get();
        return response()->json($entries);
    }

    public function selectByYear(Request $request){
        $ngo_id=$request->ngo_id;
        $year=$request->year;
        $entries = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->whereYear('entry_month_year', '=', $year)
      
        ->select(DB::raw('
        id,YEAR(entry_month_year) AS year,MONTHNAME(entry_month_year) AS month,churches,mosques,town_halls,expiry_date
     
        '))
        ->get();
        return response()->json($entries);
    }


    public function export(Request $request){
      
        {

            $ngo_id=$request->ngo_id;
            return (new ngoExport($ngo_id))->download('all-data.xlsx');
    
          
        }
    }

    public function exportByYear(Request $request){
      
        {

            $ngo_id=$request->ngo_id;
            $year=$request->year;
            return (new yearExport($ngo_id,$year))->download("$year.xlsx");
    
          
        }
    }

    public function updateEntry(Request $request){
        $data = json_decode($request->data, true);
   
        $id=$data["param"]["id"];
        $churches= $data["param"]['churches'];
        $church_people_reached=$data["param"]["church_people_reached"];
        
        
        $mosques= $data["param"]['mosques'];
        $mosque_people_reached=$data["param"]["mosque_people_reached"];
        $town_hall= $data["param"]['town_halls'];
        $town_hall_people_reached=$data["param"]["town_hall_people_reached"];
        $schools= $data["param"]['schools'];
        $school_people_reached=$data["param"]["school_people_reached"];
    
        $video_shows= $data["param"]['video_shows'];
        $community_announcements= $data["param"]['information_center'];
        $radio= $data["param"]['radio_discussion'];
        $van= $data["param"]['mobile_van'];
        $iec_reached= $data["param"]['iec_reached'];
        $pregnant_women_identified= $data["param"]['pregnant_women_identified'];
        $pregnant_women_followed= $data["param"]['pregnant_women_followed_up'];
        $iptp_one= $data["param"]['iptp_one'];
        $iptp_two= $data["param"]['iptp_two'];
        $iptp_three= $data["param"]['iptp_three'];
        $iptp_four= $data["param"]['iptp_four'];
        $iptp_five= $data["param"]['iptp_five'];
        $affected = DB::table('ngo_entries')
              ->where('id', $id)
              ->update(['churches'=>$churches,'church_people_reached'=>$church_people_reached,'mosques'=>$mosques,'mosque_people_reached'=>$mosque_people_reached,'town_halls'=>$town_hall,'town_hall_people_reached'=>$town_hall_people_reached,'schools'=>$schools,'school_people_reached'=>$school_people_reached,'video_shows'=>$video_shows,
              'information_center'=>$community_announcements,'radio_discussion'=>$radio,'mobile_van'=>$van,'iec_reached'=>$iec_reached,'pregnant_women_identified'=>$pregnant_women_identified,'pregnant_women_followed_up'=>$pregnant_women_followed,
              'iptp_one'=>$iptp_one,'iptp_two'=>$iptp_two,'iptp_three'=>$iptp_three,'iptp_four'=>$iptp_four,'iptp_five'=>$iptp_five]);

              
        if($affected){
            $res = array("ok" => "Entry was updated sucessfully");
            return response()->json($res);
        }

        else{
            $res = array("ok" => "Entry was updated sucessfully");
            return response()->json($res); 
        }
    }


    public function peopelReached(Request $request){
        $ngo_id=$request->ngo_id;
        $people_reached = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
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
        $iptp = DB::table('ngo_entries')
        ->where('ngo_id','=',$ngo_id)
        ->select(DB::raw('
        SUM(pregnant_women_identified) as "Pregnant women identified",
        SUM(pregnant_women_followed_up) as "Pregnant women followed up"
     
        '))
        ->get();
        return response()->json($iptp);
    }

    public function getYear(Request $request){
        $ngo_id=$request->ngo_id;
        $year = DB::table('ngo_entries')
                ->select(DB::raw('DISTINCT YEAR(entry_month_year) AS years'))
                ->get();

                return response()->json($year);
    }

    public function getUserCredentials(Request $request){
        
        $user_id=$request->user_id;
        $uname = DB::table('users')
                ->where('id','=',$user_id)
                ->value('username');
                return response()->json($uname);
    }
   
    public function updateAccount(Request $request){
        $data = json_decode($request->data, true);
        $username= $data["param"]['username'];
        $password= bcrypt($data["param"]['password']);
        $update = DB::table('users')
        ->where('username', $username)
        ->update(['username'=>$username,'password'=>$password]);

        if($update){
            $res = array("ok" => "Acccount was updated sucessfully");
            return response()->json($res);
        }
    }
}
