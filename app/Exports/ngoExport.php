<?php

namespace App\Exports;

use App\ngo_entries;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
class ngoExport implements FromQuery ,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
 public $ngo_id;
    public function __construct(int $ngo_id)
    {
        $this->ngo_id = $ngo_id;
    }

    public function query()
    {
        return ngo_entries::query()
        ->where('ngo_id', '=', $this->ngo_id)
        ->select(DB::raw('YEAR(entry_month_year) AS year,MONTHNAME(entry_month_year) AS month,churches,church_people_reached,mosques,mosque_people_reached,
        town_halls,	town_hall_people_reached,schools,	school_people_reached,video_shows,information_center,radio_discussion,mobile_van,
        iec_reached,pregnant_women_identified,pregnant_women_followed_up,iptp_one,iptp_two,iptp_three,iptp_four,iptp_five
        '));
    }

    public function headings() : array
    {
        return [
           'YEAR',
            'MONTH',
            'Sensitization sessions at churches',
            'Number of people reached at church',
            'Sensitization sessions at Mosques',
            'Number of people reached at mosques',
            'Sensitization sessions at Community town halls',
            'Number of people reached at community town halls',
            'Sensitization sessions at schools',
            'Number of people reached at schools',
            'Video shows',
            'Community Announcements (Information Center)',
            'Radio discussions',
            'Mobile van announcements',
            'Total number of people reached through IE&C',
            'Total number of pregnant women identified',
            'Total number of pregnant women followed up',
            'IPTP1',
            'IPTP2',
            'IPTP3',
            'IPTP4',
            'IPTP5'

        ];
    }
 
}
