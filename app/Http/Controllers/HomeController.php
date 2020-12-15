<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App;
use App\User;
use Auth;
use File;
use DB;
use App\Models\Form55Details;
use App\Models\Form14Details;
use App\Models\AmendmentsDetails;
use App\Models\GnDivisions;
use App\Models\Form14Header;
use App\Models\AmendmentsNewDetails;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function lang($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
    public function form12($id) {
        try {
            $fileType=Input::get('type');
            if($fileType=='12th-sentence')
            {
            $form_12=DB::table('form_12')
            ->leftjoin('provinces','form_12.province_id','=','provinces.id')
            ->leftjoin('districts','form_12.district_id','=','districts.id')
            ->leftjoin('ag_divisions','form_12.gn_division','=','ag_divisions.id')
            ->select('form_12.*','provinces.province_name','districts.districts_name','ag_divisions.ag_name','districts.sinhala_name as dissinhala','ag_divisions.sinhala_name as agsinhala','provinces.sinhala_name as prosinhala')
            ->where('form_12.id',$id)
            ->first();
            $gn_name_engish=null;
            $gn_name_sinhala=null;
            foreach(explode(',',$form_12->gn_division) as $str)
            {
                if($str!="")
                {
                $gn=GnDivisions::where('id',$str)->first();
                $gn_name_engish=$gn_name_engish.','.$gn->gn_name;
                $gn_name_sinhala=$gn_name_sinhala.','.$gn->sinhala_name;
                }
            }
            $file ='12form.txt';
           //$myfile = fopen(public_path()."/upload/".$file, "w") or die("Unable to open file!");
            $myfile = fopen(base_path()."/upload/".$file, "w") or die("Unable to open file!");
            if(trans('sentence.lang')=='EN')
            {
                $txt="NOTICE CALLING FOR CLAIMS TO LAND PARCELS.
                \n
Registration  of  Title Act, No.  21 of  1998\n
                  (Section 12)\n
                \n
NOTICE No : ".$form_12->ref_no." ".$form_12->districts_name." District
                \n
It is hereby notified that any person having a claim to the owner-ship or possession or interest to one or more of the parcels of lands reflected in the Cadastral Map No.".$form_12->map_no." made by the Surveyor-General under Section 11 of the Registration of Title Act, No. 21 of 1998 relating to the village of ". $form_12->village ."or any part thereof, situated within the Grama Niladhari Division of No.".$gn_name_engish. " the Divisional Secretariat Division of".$form_12->districts_name. " in the District of ".$form_12->districts_name." in the Province of ".$form_12->province_name ." submit his claim to the undersigned before XXth XXXXX,XXXX In the event of failure  to submit any claim before the above-mentioned date, action will be taken to hold an exparte inquiry to determine title to the land and accordingly publish such determination in the Government Gazette in terms of Section 14 of the above Act.
\n
The Cadastral Map No.".$form_12->map_no." referred to above may be perused at the relevant Grama Niladhari Office, Divisional Secretariat, District Survey Office, Office of the Commissioner of Title Settlement, or the Surveyor - General’s office.
\n
Further information may be obtained from the Grama Niladhari,the Divisional Secretary, or the Commissioner of Title Settlement.
\n
        K.A.K. RANJITH DHARMAPALA,\n
        Commissioner of Title Settlement\n
        \n
Land Title Settlement Department,\n
No.1200/6, “Mihikatha Medura”,\n
Rajamalwatta Road,\n
Battaramulla\n
xxth xxxx, xxxx ";
            }
            else
            {
                $txt="ඉඩම් කැබලි වලට හිමිකම්පෑම් ඉදිරිපත් කරන ලෙස දැනුම්දීම.
\n
1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනත\n
        (12 වැනි වගන්තිය)
                \n
දැන්වීම් අංක :". $form_12->ref_no." ".$form_12->dissinhala ." දිස්ත්‍රික්කය
                \n
".$form_12->prosinhala." පළාතේ ".$form_12->dissinhala ." දිස්ත්‍රික්කයේ ".$form_12->agsinhala." ප්‍රාදේශීය ලේකම් කොට්ඨාශයේ".$gn_name_sinhala." ග්‍රාම නිලධාරි කොට්ඨාශය තුළ පිහිටි ".$form_12->village. " ගමට හෝ ඉන් කොටසකට හෝ කළාප අංක ".$form_12->block_no. " ට අදාලව 1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනතේ 11 වැනි වගන්තිය යටතේ මිනුම්පතිවරයා විසින් සාදන ලද අංක ".$form_12->map_no." දරන කැඩැස්තර සිතියමේ දක්වා ඇති ඉඩම් කොටස් එකක හෝ ඊට වැඩි ගණනක හෝ අයිතියට නැතහොත් සන්තකයට හෝ සම්බන්ධතාවයකට හිමිකම් පාන කවර වුවද තැනැත්තෙකු විසින් තම හිමිකම්පෑම xxxx xxxxx xx වැනි දිනට පෙර, පහත අත්සන් කරන අය වෙත ඉදිරිපත් කළ යුතුය. එකී දිනට පෙර යම් හිමිකම් පෑමක් ඉදිරිපත් කිරීම පැහැර හරිනු ලැබුවහොත්, ඒ ඉඩමේ අයිතිය සම්බන්ධයෙන් ඒක පාක්ෂික පරීක්ෂණයක් පවත්වනු ලබන අතර, එහි දී ගනු ලබන තීරණය ඉහත සඳහන් පනතේ 14 වැනි වගන්තිය ප්‍රකාර ගැසට් පත්‍රයේ පළ කරනු ලැබේ‍.
                 \n
ඉහත සඳහන් අංක ".$form_12->map_no." දරන කැඩැස්තර සිතියම, අදාළ ග්‍රාම නිලධාරී කාර්යාලයේ දී, ප්‍රාදේශීය මහ ලේකම් කාර්යාලයේ දී, දිස්ත්‍රික් මිනුම් කාර්යාලයේ දී, හිමිකම් නිරවුල් කිරීමේ කොමසාරිස්වරයාගේ කාර්යාලයේ දී, හෝ මිනුම්පති වරයාගේ කාර්යාලයේ දී පරීක්ෂා කරනු ලැබිය හැකිය.
                \n
වැඩි විස්තර, ග්‍රාම නිලධාරිගෙන්, ප්‍රාදේශීය ලේකම්වරයාගෙන්, හෝ හිමිකම් නිරවුල් කිරීමේ කොමසාරිස්වරයාගෙන් ලබා ගත හැකිය.
                \n
                    පී.එම්.එච්. ප්‍රියදර්ශනී,\n
                    හිමිකම් නිරවුල් කිරීමේ කොමසාරිස් (රා.ආ.)\n
                    \n
2019 ජූලි මස 10 වැනි දින,\n
බත්තරමුල්ල‍,\n
රජමල්වත්ත පාර, “මිහිකත මැඳුර”,\n
අංක 1200/6,\n
ඉඩම් හිමිකම් නිරවුල් කිරීමේ දෙපාර්තමේන්තුවේ දී ය.";
            }
            fwrite($myfile, $txt);
            fclose($myfile);
            }
            if($fileType=='55th-sentence')
            {
                $form_55Header=DB::table('form_55_header')
                ->leftjoin('gn_divisions','form_55_header.gn_division_id','=','gn_divisions.id')
                ->leftjoin('provinces','form_55_header.province_id','=','provinces.id')
                ->leftjoin('districts','form_55_header.district_id','=','districts.id')
                ->leftjoin('ag_divisions','form_55_header.ag_division_id','=','ag_divisions.id')
                ->select('form_55_header.*','provinces.province_name','districts.districts_name','gn_divisions.gn_name','ag_divisions.ag_name')
                ->where('form_55_header.id',$id)
                ->first();
                $txt='';
                $txt = "Province:".$form_55Header->province_name."\tDistrict:".$form_55Header->districts_name."\t AG Division:".$form_55Header->ag_name."\tGN Division:".$form_55Header->gn_name."\tVillage:".$form_55Header->village."\tName of the Deceased:".$form_55Header->name_of_the_deceased."\n\n";
                $form_55Details=Form55Details::Where('form_55_header_id',$form_55Header->id)->get();
                foreach($form_55Details as $element)
                {
                    $txt=$txt."\n\tMap Number:".$element->map_no."\tBlock Number:".$element->block_no."\tLot No:".$element->lot_no."\tSize:".$element->size."\tCertificate Number:".$element->certificate_number."\tRegisterd Office:".$element->registerd_office;
                }
                $file ='55form.txt';
                $myfile = fopen(base_path()."/upload/".$file, "w") or die("Unable to open file!");
                fwrite($myfile, $txt);
                fclose($myfile);

            }
            if($fileType=='14th-sentence')
            {
                $form_14Header=DB::table('form_14_header')
                ->leftjoin('gn_divisions','form_14_header.ag_division_id','=','gn_divisions.id')
                ->leftjoin('provinces','form_14_header.province_id','=','provinces.id')
                ->leftjoin('districts','form_14_header.district_id','=','districts.id')
                ->leftjoin('ag_divisions','form_14_header.ag_division_id','=','ag_divisions.id')
                ->select('form_14_header.*','provinces.province_name','provinces.sinhala_name as pvsinhala','districts.districts_name','districts.sinhala_name as drsinhala','gn_divisions.gn_name','gn_divisions.sinhala_name as gnsinhala','ag_divisions.ag_name','ag_divisions.sinhala_name as agsinhala')
                ->where('form_14_header.id',$id)
                ->first();
                $gn_name_engish=null;
                $gn_name_sinhala=null;
                foreach(explode(',',$form_14Header->gn_division_id) as $str)
                {
                    if($str!="")
                    {
                    $gn=GnDivisions::where('id',$str)->first();
                    $gn_name_engish=$gn_name_engish.','.$gn->gn_name;
                    $gn_name_sinhala=$gn_name_sinhala.','.$gn->sinhala_name;
                    }
                }
                $txt1='';
                $string_lotNo='';
                $txt2='';
                $file ='14form.txt';
                $myfile = fopen(base_path()."/upload/".$file, "w") or die("Unable to open file!");
              //  $myfile = fopen(public_path()."/upload/".$file, "w") or die("Unable to open file!");
                if(trans('sentence.lang')=='EN')
                {
                $form_14Details=Form14Details::Where('form_14_Header_id',$form_14Header->id)->get();
                foreach($form_14Details as $key=> $element)
                {
                    if($element->lot_no)
                    {
                        $string_lotNo=$string_lotNo.','.$element->lot_no;
                    }
                }
                $string_lotNo=substr($string_lotNo,1);
                $txt1="REGISTRATION OF TITLE ACT No. 21 OF 1998\n
\n
Declaration of Determination of the Commissioner of Title Settlement under Section 14\n
                    \n
BY virtue of the powers vested in me under Section 14 of the Registration of Title Act, No.21 of 1998, I, the undersigned, hereby declare my determination as set out in the Schedule appended hereto in regard to the  title to parcel of Land No.".$string_lotNo." of Block ".$form_14Header->block_no.", contained in the Cadastral Map No. ".$form_14Header->map_no.", situated in the Village of ".$form_14Header->village_name." within the Grama Niladhari Division of No. ".$gn_name_engish." in the Divisional Secretary's Division of ".$form_14Header->ag_name.", in the District of ".$form_14Header->districts_name.", in the Province of ".$form_14Header->province_name.", referred to in Notice No. ".$form_14Header->file_no." calling for claims to land parcels which was duly published in the Gazette No. ".$form_14Header->gazetted_no."  of ".$form_14Header->gazetted_date." in terms of Section 12 of the Registration of Title Act, No.21 of 1998.
                    \n
K.A.K. Ranjith Dharmapala,\n
Commissioner of Title Settlement.\n
Land Title Settlement Departmenr\n
No 1200/6, Mihikatha Medura,\n
Rajamalwatta Road,\n
Battaramulla\n
xxth xxxx, xxxx\n";

                foreach($form_14Details as $key=> $element)
                {
                    $txt2=$txt2."\n"
."\n
\n
        \n".$element->lot_no." \t".$element->size."  ".$element->name." 	".$element->nic_number."	".$element->ownership_type."	".$element->class."	   ".$element->mortgages."    ".$element->other_boudages."
                    ".$element->addres." 		                    	                          
                    ";
                }
$txt2=$txt2."\n"
."\n
".$form_14Header->file_no;
            }
            else
            {
            $form_14Details=Form14Details::Where('form_14_Header_id',$form_14Header->id)->get();
            foreach($form_14Details as $key=> $element)
            {
                if($element->lot_no)
                {
                    $string_lotNo=$string_lotNo.','.$element->lot_no;
                }
            }
            $string_lotNo=substr($string_lotNo,1);
            $txt1="1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනත\n
            \n
14 වැනි වගන්තිය යටතේ හිමිකම් නිරවුල් කිරීමේ කොමසාරිස්ගේ තීරණ ප්‍රකාශය\n
            \n
".$form_14Header->pvsinhala." පළාතේ ".$form_14Header->drsinhala." දිස්ත්‍රික්කයේ ".$form_14Header->agsinhala." ප්‍රාදේශීය ලේකම් කොට්ඨාසයේ ".$gn_name_sinhala." ග්‍රාම නිලධාරි කොට්ඨාසය තුළ පෝහද්දරමුල්ල  නමැති ගමේ පිහිටියා වූ ද, අංක ".$form_14Header->map_no." දරන කැඩැස්තර සිතියමේ කලාප අංක ".$form_14Header->block_no." හි කැබලි අංක ".$string_lotNo." දරන ඉඩම් කොටස ලෙස පෙන්නුම් කොට ඇත්තා වූ ද, හිමිකම් පෑම් ඉදිරිපත් කරන ලෙස දැනුම් දෙමින් 1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනතේ 12 වැනි වගන්තිය ප්‍රකාර XXXX.XX.XX වැනි දින අංක ".$form_14Header->gazetted_no." දරන ගැසට් පත්‍රයේ යථා පරිදි පළකරන ලද අංක ".$form_14Header->file_no." දරන දැන්වීමේ සඳහන් කොට ඇත්තා වූ ද, ඉඩම් කොටස්වල අයිතිය සම්බන්ධයෙන් මෙහි උපලේඛනයේ දැක්වෙන මාගේ තීරණ 1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනතේ 14 වැනි වගන්තියෙන් පහත අත්සන් කරන මා වෙත පවරා ඇති බලතල ප්‍රකාර, මම මෙයින් ප්‍රකාශ කරමි.\n
            \n                
කේ.ඒ.කේ. රංජිත් ධර්මපාල,\n
හිමිකම් නිරවුල් කිරීමේ කොමසාරිස්.\n
                            \n
2018 ඔක්තෝබර් මස 15 වැනි දින,\n
මිහිකත මැදුර, අංක 1200/6,\n
රජමල්වත්ත පාර,\n
බත්තරමුල්ල\n
ඉඩම් හිමිකම් නිරවුල් කිරීමේ දෙපාර්තමේන්තුවේ දී ය.\n";

            foreach($form_14Details as $key=> $element)
            {
                $txt2=$txt2."\n"
."\n
\n
    \n".$element->lot_no." \t".$element->size."  ".$element->name." 	".$element->nic_number."	".$element->ownership_type."	".$element->class."	   ".$element->mortgages."   ".$element->other_boudages."
                     ".$element->addres." 		                    	        	
                     ";
            }
            $txt2=$txt2."\n"
."\n
".$form_14Header->file_no;

            }
                fwrite($myfile, $txt1.$txt2);
                fclose($myfile);
            }
            if($fileType=='amendments')
            {
        
                $amendments=DB::table('amendments_header')
                ->leftjoin('gn_divisions','amendments_header.gn_division_id','=','gn_divisions.id')
                ->leftjoin('provinces','amendments_header.province_id','=','provinces.id')
                ->leftjoin('districts','amendments_header.district_id','=','districts.id')
                ->leftjoin('ag_divisions','amendments_header.ag_division_id','=','ag_divisions.id')
                ->select('amendments_header.*','provinces.province_name','provinces.sinhala_name as pvsinhala','districts.districts_name','districts.sinhala_name as drsinhala','gn_divisions.gn_name','gn_divisions.sinhala_name as gnsinhala','ag_divisions.ag_name','ag_divisions.sinhala_name as agsinhala')
                ->where('amendments_header.id',$id)
                ->first();
                $gn_name_engish=null;
                $gn_name_sinhala=null;
                foreach(explode(',',$amendments->gn_division_id) as $str)
                {
                    if($str!="")
                    {
                    $gn=GnDivisions::where('id',$str)->first();
                    $gn_name_engish=$gn_name_engish.','.$gn->gn_name;
                    $gn_name_sinhala=$gn_name_sinhala.','.$gn->sinhala_name;
                    }
                }
                $form14=Form14Header::join('form_14_detail','form_14_header.id','=','form_14_detail.form_14_Header_id')
                ->where('form_14_header.map_no',$amendments->map_no)->where('form_14_header.block_no',$amendments->block_no)->where('form_14_detail.lot_no',$amendments->lot_no)
                ->where('form_14_header.rejected',0)->where('form_14_detail.rejected',0)
                ->select('form_14_detail.*','form_14_header.gazetted_no','form_14_header.gazetted_date')
                ->first();
                $form14_new=AmendmentsNewDetails::where('amendments_header_id',$id)->orderBy('id', 'asc')->get();
                $txt='';
                for($i=0;$i<sizeof($form14_new);$i++ )
                {
                    
                    if($i==0)
                    {
                        
               $txt="සංශෝධන\n
               \n
1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනත\n
\n
(14 වන වගන්තිය)\n
\n
උක්ත ශීර්ෂය යටතේ ".date('Y-m-d',strtotime( $form14->gazetted_date))." දින අංක".$form14->gazetted_no." දරණ ශ්‍රී ලංකා ප්‍රජාතාන්ත්‍රික සමාජවාදී ජනරජයේ අති විශේෂ ගැසට් පත්‍රයේ පළ කරන ලද ".$amendments->pvsinhala."".$amendments->drsinhala." දිස්ත්‍රික්කයේ ".$amendments->agsinhala." ප්‍රාදේශීය ලේකම් කොට්ඨාශයේ ".$gn_name_sinhala."ග්‍රාම නිලධාරී කොට්ඨාශය තුළ".$amendments->village."නමැති ගමේ පිහිටියා වූ ද,අංක".$amendments->map_no."දරන කැඩැස්තර සිතියමේ කලාප අංක".$amendments->block_no."හි කැබලි අංක".$amendments->lot_no."දරන ඉඩම් කොටස සම්බන්ධයෙන් එහි උපලේඛනයෙහි;
\n
“අයිතිකරුගේ/අයිතිකරුවන්ගේ සම්පූර්ණ නම/නම් සහ ලිපිනය”, “ජාතික හැඳුනුම්පත් අංකය” සහ “විනිශ්චයන් සහ ඉන්ජෙන්ෂන් තහනම් අපේක්ෂාවෙන් ඇති උකස් සහ බැඳීම් පිළිබඳ විස්තර ” තීරුවන සඳහන් “".$form14->name.",".$form14->addres."”,“".$form14->nic_number."” සහ “".$form14->mortgages."” යන්න ඉවත් කර “ ".$form14_new[0]->name.",".$form14_new[0]->addres."”,“".$form14_new[0]->nic_number."” සහ “".$form14_new[0]->mortgages."” යන්න ඇතුලත් කර සංශෝධනය කරනු ලැබේ.\n
\n
\n
\n
        පී.එම්.එච්. ප්‍රියදර්ශනී,\n
        හිමිකම් නිරවුල් කිරීමේ කොමසාරිස් ජනරාල්\n
        \n
        \n
2019 සැප්තැම්බර් මස 05 වැනි දින,
බත්තරමුල්ල,
රජමල්වත්ත පාර,
මිහිකත මැදුර, අංක 1200/6,
ඉඩම් හිමිකම් නිරවුල් කිරීමේ දෙපාර්තමේන්තුවේ දී ය.";
                    }
                    else
                    {
        
                        $txt=$txt.
"සංශෝධන\n
\n
1998 අංක 21 දරන හිමිකම් ලියාපදිංචි කිරීමේ පනත\n
         \n
(14 වන වගන්තිය)\n
         \n
උක්ත ශීර්ෂය යටතේ ".date('Y-m-d',strtotime( $form14->gazetted_date))." දින අංක".$form14->gazetted_no." දරණ ශ්‍රී ලංකා ප්‍රජාතාන්ත්‍රික සමාජවාදී ජනරජයේ අති විශේෂ ගැසට් පත්‍රයේ පළ කරන ලද ".$amendments->pvsinhala."".$amendments->drsinhala." දිස්ත්‍රික්කයේ ".$amendments->agsinhala." ප්‍රාදේශීය ලේකම් කොට්ඨාශයේ ".$gn_name_sinhala."ග්‍රාම නිලධාරී කොට්ඨාශය තුළ".$amendments->village."නමැති ගමේ පිහිටියා වූ ද,අංක".$amendments->map_no."දරන කැඩැස්තර සිතියමේ කලාප අංක".$amendments->block_no."හි කැබලි අංක".$amendments->lot_no."දරන ඉඩම් කොටස සම්බන්ධයෙන් එහි උපලේඛනයෙහි;
         \n
         “අයිතිකරුගේ/අයිතිකරුවන්ගේ සම්පූර්ණ නම/නම් සහ ලිපිනය”, “ජාතික හැඳුනුම්පත් අංකය” සහ “විනිශ්චයන් සහ ඉන්ජෙන්ෂන් තහනම් අපේක්ෂාවෙන් ඇති උකස් සහ බැඳීම් පිළිබඳ විස්තර ” තීරුවන සඳහන් “".$form14_new[$i-1]->name.",".$form14_new[$i-1]->addres."”,“".$form14_new[$i-1]->nic_number."” සහ “".$form14_new[$i-1]->mortgages."” යන්න ඉවත් කර “ ".$form14_new[$i]->name.",".$form14_new[$i]->addres."”,“".$form14_new[$i]->nic_number."” සහ “".$form14_new[$i]->mortgages."” යන්න ඇතුලත් කර සංශෝධනය කරනු ලැබේ.\n
         \n
         \n
         \n
            පී.එම්.එච්. ප්‍රියදර්ශනී,\n
            හිමිකම් නිරවුල් කිරීමේ කොමසාරිස් ජනරාල්\n
                 \n
                 \n
2019 සැප්තැම්බර් මස 05 වැනි දින,
බත්තරමුල්ල,
රජමල්වත්ත පාර,
මිහිකත මැදුර, අංක 1200/6,
ඉඩම් හිමිකම් නිරවුල් කිරීමේ දෙපාර්තමේන්තුවේ දී ය.";
         
                    }
               
                }
                $file ='Amendments.txt';
               // $myfile = fopen(public_path()."/upload/".$file, "w") or die("Unable to open file!");
                $myfile = fopen(base_path()."/upload/".$file, "w") or die("Unable to open file!");
                fwrite($myfile, $txt);
                fclose($myfile);
            }
            //return response()->download(public_path()."/upload/".$file,$file,['Content-Type'=>'application/text']); 
            return response()->download(base_path()."/upload/".$file,$file,['Content-Type'=>'application/text']);
    } catch (\Exception $e) {
           dd($e);
       }
     }
}
