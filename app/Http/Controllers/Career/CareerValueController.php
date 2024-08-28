<?php

namespace App\Http\Controllers\Career;

use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Career\RiasecCode;
use App\Models\Career\CareerValue;

use stdClass;

class CareerValueController extends Controller
{
    private function getCareerValues() {
        $query = CareerValue::query();

        if( request('search') ) {
            $query->where('name', 'LIKE', '%'.request('search').'%');
        }

        if( request('filters') ) {
            // TODO ..
        }

        $query->with(['riasecCodes' => function($query) {
            $query->pluck('career_value_riasec_code.riasec_code_id');
        }]);

        $careerValues = $query->where('deleted', false);

        return $careerValues;
    }

    public function index()
    {
        return Inertia::render('Careers/Values/Index', [
            'response' => [],
            'filters' => [
                'search' => request('search') ? request('search') : '',
                'role' => request('role') ? request('role') : '',
                'status' => request('status') ? request('status') : "2"
            ],
            'careerValues' => self::getCareerValues(),
            'search' => request('search') ? request('search') : ''
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hollandCodes = RiasecCode::select('id', 'code as name', 'description')->get();

        return Inertia::render('Careers/Values/CreateEdit', [
            'response' => [],
            'value' => null,
            'hollandCodes' => $hollandCodes
        ]);
    }

    public function edit( $id ) {
        $hollandCodes = RiasecCode::select('id', 'code as name', 'description')->get();

        $value = CareerValue::with(['riasecCodes:id'])
            ->select('id', 'name', 'description', 'status')
            ->find($id);

        $value->hollandCodes = $value->riasecCodes
            ->pluck('id')
            ->toArray();

        unset($value->riasecCodes);

        return Inertia::render('Careers/Values/CreateEdit', [
            'response' => [],
            'value' => $value,
            'hollandCodes' => $hollandCodes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $value = new CareerValue();
        $value->name = trim($request->name);
        $value->status = 1;
        $value->description = trim($request->description);
        $value->save();

        $value->riasecCodes()->attach( $request->hollandCodes );

        return redirect()
            ->route('career-values.index')
            ->with([
                'message'=> 'Το career value δημιθουργήθηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }

    public function update(Request $request)
    {
        $value = CareerValue::find( $request->id );

        if( is_null($value) ) {
            return redirect()->route( 'career-values.index' )
                ->with(['message'=> 'Oups, something went wrong. Career value was not updated.']);
        }
        
        $value->name = trim($request->name);
        $value->description = trim($request->description);
        $value->status = $request->status;
        $value->save();

        if( isset($request->hollandCodes) ) {
            $value->riasecCodes()->sync( $request->hollandCodes );
        }else {
            $value->riasecCodes()->detach();
        }

        return redirect()
            ->route('career-values.index')
            ->with([
                'message'=> 'Η αξία ενημερώθηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }

    public function destroy( $id )
    {
        $value = Careervalue::find( $id );
        $value->delete();

        return redirect()
            ->route('career-values.index')
            ->with([
                'message'=> 'Η αξία διαγράφηκε με επιτυχία!',
                'status' => 'success'
            ]);
    }

    // public function approve( Request $request )
    // {
    //     $career = CareerValue::find( $request->id );
    //     $career->status = 2;
    //     $career->save();

    //     return Inertia::render('Values/Index', [
    //         'response' => [
    //             'status' => 'success',
    //             'message' => 'Η αξία ενημερώθηκε με επιτυχία.'
    //         ],
    //         'careerValues' => self::getCareerValues(),
    //         'search' => request('search') ? request('search') : ''
    //     ]);
    // }
}
