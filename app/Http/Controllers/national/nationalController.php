<?php

namespace App\Http\Controllers\national;

use App\Exports\exportAll;
use App\Exports\totalYearExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class nationalController extends Controller
{

    public function totalSubmissions(Request $request)
    {

        $dashboard = DB::table('ngo_entries')

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

    public function Iptp()
    {
        $iptp = DB::table('ngo_entries')
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

    public function sensitization()
    {
        $iptp = DB::table('ngo_entries')
            ->select(DB::raw('
        SUM(churches) as "churches",
        SUM(mosques) as "mosques",
        SUM(town_halls) as "town_halls"

        '))
            ->get();
        return response()->json($iptp);
    }

    public function mediaEngagement()
    {
        $media = DB::table('ngo_entries')
            ->select(DB::raw('
        SUM(video_shows) as "video shows",
        SUM(mobile_van) as "mobile vans",
        SUM(radio_discussion) as "radio discussions",
        SUM(information_center) AS "community information center"

        '))
            ->get();
        return response()->json($media);
    }

    public function peopelReached()
    {
        $people_reached = DB::table('ngo_entries')
            ->select(DB::raw('SUM(church_people_reached) as "church",
        SUM(mosque_people_reached) as "mosque",
        SUM(town_hall_people_reached) as "town hall",
        SUM(school_people_reached) as "schools"
        '))

            ->get();
        return response()->json($people_reached);
    }

    public function followUp(Request $request)
    {
        $ngo_id = $request->ngo_id;
        $iptp = DB::table('ngo_entries')
            ->select(DB::raw('
        SUM(pregnant_women_identified) as "Pregnant women identified",
        SUM(pregnant_women_followed_up) as "Pregnant women followed up"

        '))
            ->get();
        return response()->json($iptp);
    }

    public function addUser(Request $request)
    {
        $data = json_decode($request->data, true);
        $first_name = $data["param"]['first_name'];
        $last_name = $data["param"]['last_name'];
        $username = $data["param"]['username'];
        $password = bcrypt($data["param"]['password']);
        $ngo_id = $data["param"]['ngo_id'];
        $user_id = $data["param"]['user_id'];

        $entry = DB::table('users')->insert(
            [
                'first_name' => $first_name, 'last_name' => $last_name, 'level_id' => 2, 'username' => $username, 'password' => $password,
                'ngo_id' => $ngo_id,
            ]);

        if ($entry) {
            $res = array("ok" => "User has been added successfully");
            return response()->json($res);
        }
    }

    public function addNgo(Request $request)
    {
        $data = json_decode($request->data, true);
        $region_id = $data['region_id'];
        $district_id = $data['district_id'];
        $ngo_name = $data['ngo_name'];

        $entry = DB::table('ngos')->insert(
            [
                'region_id' => $region_id, 'district_id' => $district_id, 'ngo_name' => $ngo_name,
            ]);

        if ($entry) {
            $res = array("ok" => "User has been added successfully");
            return response()->json($res);
        }
    }

    public function getNgos(Request $request)
    {

        $ngos = DB::table('ngos')
            ->select('id', 'ngo_name'
            )
            ->get();

        if ($ngos) {
            return response()->json($ngos);
        }

    }

    public function exportTotalByYear(Request $request)
    {

        {

            $year = $request->year;
            return (new totalYearExport($year))->download("$year.xlsx");

        }
    }

    public function export()
    {

        {

            return (new exportAll())->download('all-data.xlsx');

        }
    }

    public function selectAll()
    {

        $entries = DB::table('ngo_entries')
            ->join('ngos', 'ngo_entries.ngo_id', '=', 'ngos.id')
            ->select(DB::raw('
        ngo_entries.id,ngos.ngo_name,YEAR(entry_month_year) AS year,MONTHNAME(entry_month_year) AS month,churches,mosques,town_halls,expiry_date

        '))
            ->get();
        return response()->json($entries);
    }

    public function selectByYear(Request $request)
    {

        $year = $request->year;
        $entries = DB::table('ngo_entries')
            ->whereYear('entry_month_year', '=', $year)

            ->select(DB::raw('
        id,YEAR(entry_month_year) AS year,MONTHNAME(entry_month_year) AS month,churches,mosques,town_halls,expiry_date

        '))
            ->get();
        return response()->json($entries);
    }
}
