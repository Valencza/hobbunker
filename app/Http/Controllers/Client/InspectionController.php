<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\InspectionCrew;
use App\Models\InspectionDetail;
use App\Models\MasterShip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InspectionController extends Controller
{
    public function index()
    {
        return redirect()->route('inspection.create');

        return view('client.pages.inspection.index');
    }
    public function create()
    {
        $date = Carbon::now()->toDateString();
        $masterShips =  MasterShip::latest()
        ->get();

        return view('client.pages.inspection.create', compact(
            'date',
            'masterShips'
        ));
    }

    public function store(){
        $inspection = Inspection::create([
            'master_user_uuid' => Auth::user()->uuid,
            'master_ship_uuid' => request('master_ship_uuid'),
            'date' => request('date'),
        ]);

        foreach(request('crew') as $index => $item){
            if($item){
                InspectionCrew::create([
                    'inspection_uuid'=> $inspection->uuid,
                    'name' => $item,
                    'position' => request('position')[$index],
                ]);
            }
        }

        $files = request()->file('deck_photo');
        foreach($files as $file){
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/inspection/decks'), $fileName);
            $filePath = "images/inspection/decks/$fileName";
    
            InspectionDetail::create([
                'inspection_uuid'=> $inspection->uuid,
                'type'=> 'deck',
                'photo' => $filePath,
                'description' => request('deck_description')
            ]);
        }

        $files = request()->file('platform_photo');
        foreach($files as $file){
            $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/inspection/platforms'), $fileName);
        $filePath = "images/inspection/platforms/$fileName";

        InspectionDetail::create([
                    'inspection_uuid'=> $inspection->uuid,
            'type'=> 'platform',
            'photo' => $filePath,
            'description' => request('platform_description')
        ]);
        }
        

        $files = request()->file('kitchen_photo');
        foreach($files as $file){
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/inspection/kitchens'), $fileName);
            $filePath = "images/inspection/kitchens/$fileName";
    
            InspectionDetail::create([
                        'inspection_uuid'=> $inspection->uuid,
                'type'=> 'kitchen',
                'photo' => $filePath,
                'description' => request('kitchen_description')
            ]);
    
        }
        
        $files = request()->file('meachine_photo');
        foreach($files as $file){
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/inspection/meachines'), $fileName);
            $filePath = "images/inspection/meachines/$fileName";
    
            InspectionDetail::create([
                        'inspection_uuid'=> $inspection->uuid,
                'type'=> 'meachine',
                'photo' => $filePath,
                'description' => request('meachine_description')
            ]);
        }
      

        $files = request()->file('safety_photo');
        foreach($files as $file){
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/inspection/safties'), $fileName);
        $filePath = "images/inspection/safties/$fileName";

        InspectionDetail::create([
                    'inspection_uuid'=> $inspection->uuid,
            'type'=> 'safety',
            'photo' => $filePath,
            'description' => request('safety_description')
        ]);
       }

            $files = request()->file('navigation_photo');
            foreach($files as $file){
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/inspection/navigations'), $fileName);
        $filePath = "images/inspection/navigations/$fileName";

        InspectionDetail::create([
                    'inspection_uuid'=> $inspection->uuid,
            'type'=> 'navigation',
            'photo' => $filePath,
            'description' => request('navigation_description')
        ]);

        }
        $files = request()->file('medicine_photo');
        foreach($files as $file){
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/inspection/medicines'), $fileName);
        $filePath = "images/inspection/medicines/$fileName";

        InspectionDetail::create([
                    'inspection_uuid'=> $inspection->uuid,
            'type'=> 'medicine',
            'photo' => $filePath,
            'description' => request('medicine_description')
        ]);
       }

        return redirect()->route('home')->with('success', 'Inspeksi Kapal Berhasil Dibuat');
    }
}
