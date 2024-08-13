<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* MODELS */
use App\Models\User;
use App\Models\Role;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;

/* OTHER */
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestController extends Controller {
    private function getAllTestTemplates() {
        $query = Test::query();

        if( request('search') ) {
            $query->where('name', 'LIKE', '%'.request('search').'%');
        }

        return $query->where('deleted', false)->paginate(15);
    }

    public function index() {
        return Inertia::render('Tests/Templates/Index', [
            'response' => [],
            'filters' => [
                'search' => request('search') ? request('search') : '',
            ],
            'tests' => self::getAllTestTemplates(),
        ]);
    }

    public function create() {
        $hollandQuestions = Question::all();
        $testTemplates = Test::get();

        return Inertia::render('Tests/Templates/Create', [
            'response' => [],
            'hollandQuestions' => $hollandQuestions,
            'testTemplate' => [],
            'templateDropdownList' => $testTemplates
        ]);
    }

    public function store(Request $request) {
        
        $testTemplate = new Test();
        $testTemplate->name = $request->name;
        $testTemplate->description = $request->description;
        $testTemplate->type = $request->type;
        $testTemplate->category = 1;
        $testTemplate->deleted = false;
        $testTemplate->save();

        switch( $testTemplate->type ) {
            case 1:
                self::storeCustomTestTemplate( $request );
                break;
            default:
                break;
        }

        // if( $request->type === 0 ) {
        //     foreach( $request->$questionList as $question ) {
        //         $ques = new Question( );
        //         $ques->title = $question['title'];
        //         $ques->description = "";
        //         $ques->type = -1;    // -1 indicates that it is a custom question.
        //         $ques->save();
    
        //         DB::insert('insert into question_test (test_id, question_id) values (?, ?)', [$testId, $ques->id ]);
        //     } 
        // } 

        // if( $request->type === 1 ) {
        //     foreach( $questionList as $question ) {
        //     $ques = new Question( );
        //     $ques->title = $question['title'];
        //     $ques->description = "";
        //     $ques->type = -1;    // -1 indicates that it is a custom question.
        //     $ques->save();

        //     DB::insert('insert into question_test (test_id, question_id) values (?, ?)', [$testId, $ques->id ]);
        // } 
        // }  

        return redirect()->route( 'tests.index' )->with([
            'status' => 'success',
            'message'=> 'Test successfully created.'
        ]);
    }

    private function storeCustomTestTemplate( Request $request ) {

    }

    public function show( $testTemplateId ) {
        $test = Test::with('questions')->find($testTemplateId);

        if (!$test) {
            // Return a 404 response if the test is not found
            return response()->json(['error' => 'Test not found'], 404);
        }

        return Inertia::render('Tests/Templates/Show', [
            'test' => $test
        ]);
    }

    public function edit($id){
        // Error :: Id was not found.
        if( !isset($request->id) ) {
            return redirect()->route( 'tests' )
                ->with(['message'=> 'Oups, something went wrong. Test was not updated [CODE 011].']);
        }

        // Error :: Id is not a number.
        if( !is_int($request->id) ) {
            return redirect()->route( 'tests' )
                ->with(['message'=> 'Oups, something went wrong. Test was not updated [CODE 012].']);
        }

        // Find the test with that ID.
        $test = Test::find( $request->id );
  
        // Error :: User was not found.
        if( is_null($test) ) {
            return redirect()->route( 'tests' )
                ->with(['message'=> 'Oups, something went wrong. Test was not updated [CODE 013].']);
        }

        // Update user information.
        $test->name = $request->name;
        $test->description = $request->description;
        $test->category = $request->category;
        $test->type = $request->type;
        $test->deleted = false;
        $test->save();
        
        return redirect()->route( 'users' )
            ->with(['message'=> 'User successfully updated.']);
    }

    public function update(Request $request, $id){
        $test = Test::find( $id );
        
        $testQuestionIdList = DB::table('question_test')
            ->where('test_id', $test->id)
            ->get()
            ->pluck('question_id')
            ->toArray();

       

        $questionList = DB::table('questions')
            ->whereIn('id', $testQuestionIdList)->get();

     

        return Inertia::render('Tests/Templates/Update', [
            'test' => $test,
            'questions' => $questionList
        ]);
    }

    /***
     * Soft delete a test template.
     */
    public function destroy( $testId ) {
        if( empty($testId) || $testId <= 0 ) return;

        // Apply soft delete to test template.
        $test = Test::find( $testId );
        $test->deleted = true;
        $test->save();

        return redirect()->route( 'tests' )
            ->with('message', 'Test successfully deleted.');
    }
}