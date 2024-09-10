<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\User;
use App\Models\Role;
use App\Models\Test;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Submission;
use App\Models\Career\Skill;
use App\Models\Interest;
use App\Models\CareerValue;
use App\Models\Career;
use App\Models\SessionRequest;

class TestSubmissionController extends Controller
{
    private function getSkillsByHollandCode( $hollandCode ) {
        $skills = Skill::join('riasec_code_skill', 'skills.id', '=', 'riasec_code_skill.skill_id')
            ->join('riasec_codes', 'riasec_code_skill.riasec_code_id', '=', 'riasec_codes.id')
            ->whereIn('riasec_codes.id', $hollandCode)
            ->distinct()
            ->get(['skills.id', 'skills.name', 'skills.description'])
            ->map(function ($skill) {
                $skill->isChecked = true;
                return $skill;
            });

        return $skills;
    }

    private function getInterestsByHollandCode( $hollandCode ) {
        $interests = Interest::join('interest_riasec_code', 'interests.id', '=', 'interest_riasec_code.interest_id')
            ->join('riasec_codes', 'interest_riasec_code.riasec_code_id', '=', 'riasec_codes.id')
            ->whereIn('riasec_codes.id', $hollandCode)
            ->distinct()
            ->get(['interests.id', 'interests.name', 'interests.description'])
            ->map(function ($interest) {
                $interest->isChecked = true;
                return $interest;
            });

        return $interests;
    }

    private function getCareerValuesByHollandCode( $hollandCode ) {
        $career_values = CareerValue::join('career_value_riasec_code', 'career_values.id', '=', 'career_value_riasec_code.career_value_id')
            ->join('riasec_codes', 'career_value_riasec_code.riasec_code_id', '=', 'riasec_codes.id')
            ->whereIn('riasec_codes.id', $hollandCode)
            ->distinct()
            ->get(['career_values.id', 'career_values.name', 'career_values.description'])
            ->map(function ($value) {
                $value->isChecked = true;
                return $value;
            });

        return $career_values;
    }

    private function getCareersByHollandCode( $hollandCode ) {
        $careers = Career::query()
            ->where('deleted', false)
            ->where('status', 2)
            ->orderBy('created_at', 'desc')
            ->with('riasecCodes')
            ->whereHas('riasecCodes', function ($query) use ($hollandCode) {
                $query->whereIn('symbol', $hollandCode);
            })
            ->paginate(10)
            ->through(function ($career) {
                return [
                    'id' => $career->id,
                    'title' => $career->title,
                    'description' => $career->description,
                    'riasec_codes' => $career->riasecCodes->pluck('symbol')
                ];
            })
            ->withQueryString();

        return $careers;
    }

    private function getAllTestResults() {
        $query = Submission::query();

        // Join users and tests tables to get user firstname/lastname and test name
        $query->leftjoin('users', 'users.id', '=', 'submissions.user_id');
        $query->leftjoin('tests', 'tests.id', '=', 'submissions.test_id');
        $query->select('submissions.*', 'users.firstname as user_firstname', 'users.lastname as user_lastname', 'users.id as user_id', 'tests.name as test_template_name');

        // Apply search filter if present
        if( request('search') ) {
            $search = request('search');
            $query->where(function($query) use ($search) {
                $query->where('users.firstname', 'LIKE', '%'.$search.'%')
                    ->orWhere('users.lastname', 'LIKE', '%'.$search.'%')
                    ->orWhere('users.email', 'LIKE', '%'.$search.'%');
            });
        }

        return $query->paginate(10);
    }

    public function index() {
        return Inertia::render('Tests/Submissions/Index', [
            'response' => [],
            'submissions' => self::getAllTestResults(),
            'filters' => [
                'search' => request('search') ? request('search') : '',
            ]
        ]);
    }

    public function create(){
        $tests = Role::all();
        $hollandQuestions = Question::all();

        return Inertia::render('Tests/Submissions/Create', [
            'tests' => $tests,
            'hollandQuestions' => $hollandQuestions
        ]);
    }
    public function storeMetaData( Request $request ) {
    
        //$userExist = self::userAlreadyExist( $request );
    
        // if( $userExist === true ) {
        //     // Get the user who has previously submitted a career test.
        //     $user = User::where('email', '=', $request->user['email'] )->first();
        //     $userId = $user->id;

        // }
        // $submissionId = 3;
        // $userId = 1;

        // $hollandCode = [1, 2, 6];

        //$filteredCareers = self::getCareersByHollandCode( $request->hollandCodeId );
   
        // testMetaData: {
        //    skills: props.skills,
        //    interests: props.interests,
        //    values: props.values

        if( $request->acceptSession === 1) {
            $user = User::where('id', '=', $request->user )->first();

            try {
                DB::beginTransaction();

                $sessionRequest = new SessionRequest();
                $sessionRequest->firstname = $user->firstname;
                $sessionRequest->lastname = $user->lastname;
                $sessionRequest->phone = $user->phone;
                $sessionRequest->email = $user->email;
        
                $sessionRequest->save();

                DB::commit();
            } catch (\Throwable $th) {

            }
        }

        $numberMapping = [
            1 => 'R',
            2 => 'I',
            3 => 'A',
            4 => 'S',
            5 => 'E',
            6 => 'C'
        ];

        $letters = array_map(function ($number) use ($numberMapping) {
            return $numberMapping[$number];
        }, $request->hollandCodeId);

        return redirect()->route('careers.list', ['code' => implode('',  $letters)]);
    }

    public function store( Request $request ) {
        $userExist = self::userAlreadyExist( $request );
        
        if( $userExist === true ) {
            // Get the user who has previously submitted a career test.
            $user = User::where('email', '=', $request->user['email'] )->first();
            $userId = $user->id;
        }else {
            // Store the user who submitted the career test.
            $user = new User();

            $user->username = explode("@", $request->user['email'])[0] . "_" . random_int(100000, 999999);
            $user->firstname = $request->user['firstname'];
            $user->lastname = $request->user['lastname'];
            $user->phone = $request->user['phone'];
            $user->email = $request->user['email'];
            $user->password = Hash::make("B@_T#RD9vS8tuh#%!@#^^");

            $user->save();
            $userId = $user->id;
            DB::insert('insert into role_user (role_id, user_id) values (?, ?)', [Role::where('name', 'Student')->first()->id, $user->id]);
        }

        // Create a new submission instance
        $submission = new Submission();
        $submission->user_id = $userId;
        $submission->test_id = $request->test['id'];
        $submission->save();
        $submissionId = $submission->id;

        foreach( $request->answers as $ans ) {
           if( !empty($ans['answer']) ) {

            $answer = new Answer();
            $answer->user_id = $userId;
            $answer->question_id = $ans['id'];
            $answer->submission_id = $submissionId;
            $answer->answer = $ans['answer'];
            $answer->save();
           }
        }
    
        $hollandCode = array_map(function($item) {
            return $item['id'];
        }, self::calculateRIASECByUserId( $userId, $submissionId ));

        $filteredSkills = self::getSkillsByHollandCode( $hollandCode );
        $filteredInterests = self::getInterestsByHollandCode( $hollandCode );
        $filteredValues = self::getCareerValuesByHollandCode( $hollandCode );
        $filteredCareers = self::getCareersByHollandCode( $hollandCode );
        $filteredHollandCodes = self::calculateRIASECByUserId( $userId, $submissionId );

        return Inertia::render('Tests/Submissions/Demo', [
            'userId' => $userId,
            'testSubmissionId' => $submissionId,
            'hollandCodeId' => $hollandCode,
            'hollandCodes' => $filteredHollandCodes,
            'skills' => $filteredSkills,
            'interests' => $filteredInterests,
            'values' => $filteredValues,
            'careers' => $filteredCareers,
        ]);
    }

    public function show( $submissionId ) {
        $submission = Submission::where('id', $submissionId)->get()->first();
        $testAnswers = Answer::where('submission_id', $submissionId)
            ->join('questions', 'questions.id', '=', 'answers.question_id')
            ->select('answers.*', 'questions.title', 'questions.type')
            ->get();

        return Inertia::render('Tests/Submissions/Show', [
            'answers' => $testAnswers,
            'riasec' => self::calculateRIASECByUserId( $submission->user_id, $submissionId ),
        ]);
    }

    public function calculateRIASECByUserId( $userId, $submissionId ): array {
        $results = DB::table('answers')
            ->join('questions', 'question_id', '=', 'questions.id')
            ->select('questions.type', DB::raw('SUM(answers.answer) as total'))
            ->where('user_id', $userId)
            ->where('submission_id', $submissionId)
            ->groupBy('questions.type')
            ->get();

        $riasec = [
            array( "id" => 1, "name" => 'Πρακτικός (Do-er)', "symbol" => 'R', "value" => 0, "color" => '#8B0000', "description" => 'Είναι πρακτικοί και αθλητικοί. Προτιμούν τις δραστηριότητες που απαιτούν φυσική δύναμη και επιδεξιότητα, όπως η μηχανική, η κατασκευή, η γεωργία, η αστυνομία και η στρατιωτική υπηρεσία.' ),
            array( "id" => 2, "name" => 'Ερευνητικός (Investigative)', "symbol" => 'I', "value" => 0, "color" => '#008080', "description" => 'Οι ερευνητικοί είναι αναλυτικοί, παρατηρητικοί και φιλόμαθοι. Προτιμούν τις δραστηριότητες που απαιτούν πολύ σκέψη και ανάλυση, όπως η έρευνα, η επιστημη, η τεχνολογία και η ιατρική.' ),
            array( "id" => 3, "name" => 'Καλλιτεχνικός (Artistic)', "symbol" => 'A', "value" => 0, "color" => '#FF69B4', "description" => 'Οι καλλιτεχνικοί είναι δημιουργικοί, αισθητικοί και εκφραστικοί. Προτιμούν τις δραστηριότητες που απαιτούν φαντασία και δημιουργικότητα, όπως η τέχνη, η μουσική, η φωτογραφία και η σχεδίαση.' ),
            array( "id" => 4, "name" => 'Κοινωνικός (Social)', "symbol" => 'S', "value" => 0, "color" => '#FFD700', "description" => 'Οι κοινωνικοί είναι επικοινωνιακοί, συναισθηματικοί και εξυπηρετικοί. Προτιμούν τις δραστηριότητες που απαιτούν επαφή με άλλους ανθρώπους, όπως η κοινωνική εργασία, η διδασκαλία, η ψυχολογία και η καθοδήγηση.' ),
            array( "id" => 5, "name" => 'Επιχειρηματικός (Enterprising)', "symbol" => 'E', "value" => 0, "color" => '#FF8C00', "description" => 'Οι επιχειρηματικοί είναι φιλόδοξοι, αποφασιστικοί με αυτοπεποίθηση. Προτιμούν τις δραστηριότητες που απαιτούν ηγετικές ικανότητες και επιχειρηματική δεξιότητα, όπως η διοίκηση, η πωλήσεις, οι επιχειρηματικές ευκαιρίες και η πολιτική.' ),
            array( "id" => 6, "name" => 'Οργανωτικός (Organizer)', "symbol" => 'C', "value" => 0, "color" => '#808080', "description" => 'Οι συμβατικοί είναι ακριβείς, οργανωτικοί και εξυπηρετικοί. Προτιμούν τις δραστηριότητες που απαιτούν τάξη και δομημένη διαδικασία, όπως η λογιστική, η διοίκηση γραφείου, η τεχνολογία πληροφορικής και η δημόσια διοίκηση. Συνήθως είναι ακριβείς και προσεκτικοί στις λεπτομέρειες και προσανατολίζονται στο να ακολουθούν προκαθορισμένους κανόνες και διαδικασίες.' ),
        ];
      
        foreach ($results as $item) {
            foreach ($riasec as &$riasecItem) {
                if (intVal($item->type) == $riasecItem['id']) {
                    $riasecItem["value"] += $item->total;
                    break;
                }
            }
        }

        usort($riasec, function($a, $b) {
            return $b['value'] <=> $a['value'];
        });
        $maxValues = array_slice($riasec, 0, 3);

        return $maxValues;
    }

    public function testSubmissions( $userEmail ) {
        $userId = User::select('id')->where('email', $userEmail)->first();
       
        if( $userId === null ) {
            return response()->json([
                'message' => 'user-not-found', 
                'email' => $userEmail,
                'answers' => null,
            ]);
        }
     
        $answers = DB::table('answers')
            ->join('questions', 'question_id', '=', 'questions.id')
            ->select('questions.title', 'answers.answer', 'questions.type' )
            ->where('user_id', $userId)
            ->get()
            ->toArray();

        return Inertia::render('Tests/Submissions/ViewTestResults', [
            'results' => [
                'riasec' => self::calculateRIASECByUserId( $userId ),
                'answers' => $answers
            ]
        ]);
    }
    
    public function getHollandTestResults($submissionId) {
        $submission = Submission::findOrFail($submissionId);

        $userId = $submission->user_id;
        $testId = $submission->test_id;

        $hollandCode = array_map(function($item) {
            return $item['id'];
        }, self::calculateRIASECByUserId( $userId, $submissionId ));

        $filteredSkills = self::getSkillsByHollandCode( $hollandCode );
        $filteredInterests = self::getInterestsByHollandCode( $hollandCode );
        $filteredValues = self::getCareerValuesByHollandCode( $hollandCode );
        $filteredCareers = self::getCareersByHollandCode( $hollandCode );
        $filteredHollandCodes = self::calculateRIASECByUserId( $userId, $submissionId );

        return Inertia::render('Tests/Submissions/Demo', [
            'userId' => $userId,
            'testSubmissionId' => $submissionId,
            'hollandCodeId' => $hollandCode,
            'hollandCodes' => $filteredHollandCodes,
            'skills' => $filteredSkills,
            'interests' => $filteredInterests,
            'values' => $filteredValues,
            'careers' => $filteredCareers,
        ]);
    }
    

    // public function showTestResults( $submissionId ) {
    //     $submission = Submission::find($submissionId);
    //     if ($submission) {
    //         $userId = $submission->user_id;
    //         $userId = User::select('id')->where('id', $userId)->first();

    //         $answers = DB::table('answers')
    //             ->join('questions', 'question_id', '=', 'questions.id')
    //             ->select('questions.title', 'answers.answer', 'questions.type' )
    //             ->where('user_id', $userId->id)
    //             ->get()
    //             ->toArray();

    //         return Inertia::render('Tests/Submissions/Show', [
    //             'answers' => $answers,
    //             'riasec' => self::calculateRIASECByUserId( $userId ),
    //         ]);
    //     } else {
    //         // Submission not found
    //     }
    //     // $answers = DB::table('answers')
    //     //     ->join('questions', 'question_id', '=', 'questions.id')
    //     //     ->select('questions.type', 'answers.question_id' )
    //     //     ->where('user_id', $userId->id)
    //     //     ->where('answer', 'yes')
    //     //     ->get()->toArray();

    //     // $counterList = [
    //     //     "Realistic" => 0,
    //     //     "Investigative" => 0,
    //     //     "Artistic" => 0,
    //     //     "Social" => 0,
    //     //     "Enterprising" => 0,
    //     //     "Conventional" => 0,
    //     // ];

    //     // foreach ($answers as $key => $value) {
    //     //     switch( $value->type ){
    //     //         case 1:
    //     //             $counterList['Realistic']++;
    //     //             break;
    //     //         case 2:
    //     //             $counterList['Investigative']++;
    //     //             break;
    //     //         case 3:
    //     //             $counterList['Artistic']++;
    //     //             break;
    //     //         case 4:
    //     //             $counterList['Social']++;
    //     //             break;
    //     //         case 5:
    //     //             $counterList['Enterprising']++;
    //     //             break;
    //     //         case 6:
    //     //             $counterList['Conventional']++;
    //     //             break;
    //     //         default:
    //     //             break;
    //     //     }
    //     // }

       
    // }

    public function start( $id ) {
        $test = Test::where('id', $id )->with('questions')->get()->first();

        return Inertia::render('Tests/Submissions/Create', [
            'response' => [],
            'test' => $test
        ]);
    }

    // public function  () {
    //     // =================================== CALCULATE ANSWERS =========
    //     $userId = User::select('id')->where('email', 'vtzivaras@gmail.com')->first();

    //     $answers = DB::table('answers')
    //         ->join('questions', 'question_id', '=', 'questions.id')
    //         ->select('questions.type', 'answers.question_id' )
    //         ->where('user_id', $userId->id)
    //         ->where('answer', 'yes')
    //         ->get()->toArray();

    //     $counterList = [
    //         "Πρακτικός" => 0,
    //         "Ερευνητικός" => 0,
    //         "Καλλιτεχνικός" => 0,
    //         "Κοινωνικός" => 0,
    //         "Επιχειρηματικός" => 0,
    //         "Οργανωτικός" => 0,
    //     ];

    //     foreach ($answers as $key => $value) {
    //         switch( $value->type ){
    //             case 1:
    //                 $counterList['Πρακτικός']++;
    //                 break;
    //             case 2:
    //                 $counterList['Ερευνητικός']++;
    //                 break;
    //             case 3:
    //                 $counterList['Καλλιτεχνικός']++;
    //                 break;
    //             case 4:
    //                 $counterList['Κοινωνικός']++;
    //                 break;
    //             case 5:
    //                 $counterList['Επιχειρηματικός']++;
    //                 break;
    //             case 6:
    //                 $counterList['Οργανωτικός']++;
    //                 break;
    //             default:
    //                 break;
    //         }
    //     }

    //     return Inertia::render('Tests/Submissions/ViewTestResults', [
    //         'response' => [
    //             'status' => 'success'
    //         ],
    //         'riasec' => $counterList,
    //     ]);
    // }
    
    /* PUBLIC FOR EVERYONE */
    public function getHollandTest() {
        return self::start( 1 );
    }

    public static function userAlreadyExist( Request $request ) {
        $user = User::where('email', '=', $request->user['email'] )->first();
        if( $user !== null  ) return true;
        return false;
    }

    // public static function getHollandTestResults( $userEmail ) {
    //     $user = User::where('email', $userEmail)->first();
    //     $answers = DB::table('answers')
    //         ->join('questions', 'question_id', '=', 'questions.id')
    //         ->select('questions.type', 'answers.question_id' )
    //         ->where('user_id', $user->id)
    //         ->where('answer', 'yes')
    //         ->get()->toArray();

    //     $riasecScore = ["r" => 0,"i" => 0,"a" => 0,"s" => 0, "e" => 0, "c" => 0,];
    //     foreach ($answers as $key => $value) {
    //         switch( $value->type ){
    //             case 1:
    //                 $riasecScore['r']++;
    //                 break;
    //             case 2:
    //                 $riasecScore['i']++;
    //                 break;
    //             case 3:
    //                 $riasecScore['a']++;
    //                 break;
    //             case 4:
    //                 $riasecScore['s']++;
    //                 break;
    //             case 5:
    //                 $riasecScore['e']++;
    //                 break;
    //             case 6:
    //                 $riasecScore['c']++;
    //                 break;
    //             default:
    //                 break;
    //         }
    //     }

    //     // Keep the TOP 3 personality types of the test results (highest score)
    //     unset($riasecScore[array_search(min($riasecScore), $riasecScore)]);
    //     unset($riasecScore[array_search(min($riasecScore), $riasecScore)]);
    //     unset($riasecScore[array_search(min($riasecScore), $riasecScore)]);

    //     return $riasecScore;
    // }

    // private static function sendHollandTestResultsToEmail() {
    //     ///$mail_from = "hello@futuregeneration.gr";
    //     //$mail_to = $userEmail;
    //     //$mail_subject = "Αποτελέσματα του Test Καριέρας by FutureGeneration";

    //     //$headers = "MIME-Version: 1.0" . "\r\n";
    //     //$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    //     //$headers .= "From: " . $mail_from . "\r\n";       
        
    //     $content = '
    //                 <h1 class="text-center">Κάνε το επόμενο βήμα στην καριέρα σου</h1>

    //                 <p>Λαμβάνεις αυτό το email έπειτα από την επιτυχή ολοκλήρωση του test καριέρας του <a target="_blank" href="https://futuregeneration.gr/">FutureGeneration</a>. Το test βασίζεται στην θεωρία του γνωστού ψυχολόγου <a target="_blank" href="https://futuregeneration.gr/holland-analysh-proswpikothtas/">John Holland</a>. Σύμφωνα με τον οποίο η προσωπικότητα του κάθε ατόμου μπορεί να αναλυθεί σε 6 άξονες. Μέσα από το test που μόλις έκανες, καταφέραμε και βρηκαμε τους 3 επικρατέστερους άξονες, βάση των οποίων καλείσαι να ανακαλύψεις τις επαγγελματικές επιλογές που μπορείς να κάνεις.</p>
                    
    //                 <p>Γνωρίζουμε πως για κάποιους μπορεί να είναι εύκολο και για άλλους πολύ δύσκολο.</p>

    //                 <p>Αν χρειάζεται επιπλέον βοήθεια, έναν άνθρωπο που θα κατανοήσει τους προβληματισμούς σου και θα σε ακούσει, θα σε συμβουλέψει και θα σου δώσει λύσεις στις απορίες σου τότε σου έχουμε ευχάριστα νέα. Κάνε τώρα αίτηση για μια δωρεάν συνεδρία επαγγελματικού προσανατολισμού και συμβουλευτικής με την ομάδα μας. Έτσι, μπορείς να ανακαλύψεις νέα μονοπάτια για το μέλλον και την καριέρα σου.</p>
                    
    //                 <p>Δεν έχεις να χάσεις τίποτα, κάνε κλίκ και κατοχύρωσε ένα ψηφιακό ραντεβού με τους συμβούλους επαγγελματικού προσανατολισμού, δωρεάν, απλά και γρήγορα!</p>
    //                 <a target="_blank" href="https://calendly.com/futuregenerationgr/careerguidance"><strong>Κλείσε το δωρεάν ραντεβού σου!</strong></a>

    //                 <p>Η προσωπικότητά του χαρακτηρίζεται από τους 3 παρακάτω άξονες. Κάνε κλίκ σε όλες τις κατηγορίες και ανακάλυψε τι επαγγέλματα μπορείς να κάνεις.</p>
    //                 <ul>
    //                     @if( !empty($testResults[\'r\']) )
    //                     <li><a target="_blank" href="https://futuregeneration.gr/ola-ta-epaggelmata">Πρακτικός </a> - επικεντρώνεται στα φυσικά αντικείμενα</li>
    //                     @endif

    //                     @if( !empty($testResults[\'i\']) )
    //                     <li><a target="_blank" href="https://futuregeneration.gr/ola-ta-epaggelmata">Ερευνητικός </a> - επικεντρώνεται στις ιδέες</li>
    //                     @endif

    //                     @if( !empty($testResults[\'a\']) )
    //                     <li><a target="_blank" href="https://futuregeneration.gr/ola-ta-epaggelmata">Καλλιτεχνικός </a> - επικεντρώνεται στην τέχνη και στις ιδέες</li>
    //                     @endif

    //                     @if( !empty($testResults[\'s\']) )
    //                     <li><a target="_blank" href="https://futuregeneration.gr/ola-ta-epaggelmata">Κοινωνικός </a> - επικεντρώνεται στους ανθρώπους</li>
    //                     @endif

    //                     @if( !empty($testResults[\'e\']) )
    //                     <li><a target="_blank" href="https://futuregeneration.gr/ola-ta-epaggelmata">Επιχειρηματικός </a> - επικεντρώνεται σε ανθρώπους και δεδομένα</li>
    //                     @endif

    //                     @if( !empty($testResults[\'c\']) )
    //                     <li><a target="_blank" href="https://futuregeneration.gr/ola-ta-epaggelmata">Οργανωτικός / Διοικητικός </a> - επικεντρώνεται στην σκέψη και στα δεδομένα</li>
    //                     @endif
    //                 </ul>

    //                 <h3>Θέλεις να σε βοηθήσουμε στις επιλογές σου;</h3>

    //                 <a target="_blank" href="https://calendly.com/futuregenerationgr/careerguidance"><strong>Κλείσε το δωρεάν ραντεβού σου!</strong></a>

    //                 <h3>Συχνές ερωτήσεις</h3>
    //                 Ειναι οκ να είσαι προβληματισμένος-η και να έχεις ερωτήσεις και απορίες για το μέλλον. Παρακάτω θα βρεις μια λίστα με τις πιο συχνές ερωτήσεις που έχουμε λάβει.
    //                 <ul>
    //                     <li><a target="_blank" href="https://futuregeneration.gr/ikigai/">Πως να βρεις το ιδανικό επάγγελμα που πραγματικά σου αρέσει;</a></li>
    //                     <li><a target="_blank" href="https://futuregeneration.gr/poia-douleia-sou-aresei/">Tips για να βρεις την δουλειά που σου ταιριάζει.</a></li>
    //                     <li><a target="_blank" href="https://futuregeneration.gr/allagh-karieras-ti-prepei-na-gnwrizeis/">Πως να ξεκινήσεις την αλλαγή καριέρας χωρίς να μείνεις άνεργος-η;</a></li>
    //                     <li><a target="_blank" href="https://futuregeneration.gr/6-tips-gia-veltiwsi-sugkentrwsis/">Πως να διαχειριστείς την έλειψη συγκέντρωσης;</a></li>
    //                     <li><a target="_blank" href="https://futuregeneration.gr/ofeli-ethelontismou/">Ποια είναι τα οφέλη του εθελοντισμού;</a></li>
    //                     <li><a target="_blank" href="https://futuregeneration.gr/pio-shmantika-soft-skills-2022/">Ποια είναι τα softskills που ζητούν οι εργοδότες σήμερα;</a></li>
    //                 </ul>

    //                 <h3>Συνδέσου μαζί μας</h3>
    //                 <p>Το FutureGeneration, σχεδιάστηκε για να είναι δίπλα σου σε όλη την πορεία της καριέρας σου. Από το σχολείο μέχρι και την αλλαγή καριέρας στα 45. Σε κάθε σημείο, της εξέλιξής σου, πιστεύουμε οτι χρειάζεσαι διαφορετική υποστήριξη. Ακολούθησέ μας στα social που χρησιμοποιείς, στείλε μας ένα μήνυμα, βοήθησε αυτή την κοινότητα να γίνει λίγο καλύτερη από χθές. Μπορούμε όλοι να αφήσουμε το στίγμα μας για έναν κόσμο, μια γενιά του μέλλοντος καλύτερη από αυτή που ζούμε.</p>
                    
    //                 <table>
    //                     <tr>
    //                     <td>
    //                         <a target="_blank" href="https://www.instagram.com/futuregenerationgr/">
    //                         <img src="/images/instagram_logo.png" alt="FutureGeneration Instagram Profile">
    //                         </a>
    //                     </td>
    //                     <td>
    //                         <a target="_blank" href="https://www.linkedin.com/company/futuregeneration">
    //                         <img src="/images/linkedin_logo.png" alt="FutureGeneration Linkedin Profile">
    //                         </a>
    //                     </td>
    //                     <td>
    //                         <a target="_blank" href="https://www.facebook.com/futuregenerationgr">
    //                         <img src="/images/fb_logo.png" alt="FutureGeneration Facebook Profile">
    //                         </a>
    //                     </td>
    //                     </tr>
    //                 </table>

    //                 <br />

    //                 <p>Copyright © 2022 FutureGeneration, All rights reserved.</p>
    //                 <p>Λαμβάνεις αυτό το email επειδή έκανες κάποια ενέργεια στην πλατφόρμα του futuregeneration.gr ή στα social media.</p>
    //                 <p style="font-style: italic; font-size: 11px;">
    //                     Για απεγγραφή από την λίστα των ενημερώσεων του FutureGeneration κάνε<a href="https://careerexplorer.gr/unsubscribe/?email="{{ $userEmail }}> κλίκ εδώ </a>.
    //                 </p>
    //             </body>
    //             </html>';

    //                 // Try send the email to the user. Return hashvalue if success and false if not.
    //             //  $mailRes = mail($mail_to, $mail_subject, $content, $headers);
    //                         return $content;
    //             // return ( $mailRes === false ) ? false : true;
    // }

    // ====================================================================
    // WORKING WITH SENDGRID
    // ====================================================================
    // private static function sendEmailUsingSendgrid() {
    //     // $mail_from = "hello@futuregeneration.gr";
    //     // $mail_to = 'tyqeni@finews.biz';
    //     // $mail_subject = "Αποτελέσματα του Test Καριέρας by FutureGeneration";

    //     // $headers = "MIME-Version: 1.0" . "\r\n";
    //     // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    //     // $headers .= "From: " . 'hello@futuregeneration.gr' . "\r\n";       
        
    //     // $content = 'some html';

    //     //mail($mail_to, $mail_subject, $content, $headers);

    //     //Mail::to( $userEmail )->send(new TestEmail($userEmail, $testResults));

    //     // $email = new \SendGrid\Mail\Mail(); 
    //     // $email->setFrom("test@example.com", "Example User");
    //     // $email->setSubject("Sending with SendGrid is Fun");
    //     // $email->addTo("test@example.com", "Example User");
    //     // $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
    //     // $email->addContent(
    //     //     "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
    //     // );
    //     // $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

    //     // try {
    //     //     $response = $sendgrid->send($email);
    //     //     print $response->statusCode() . "\n";
    //     //     print_r($response->headers());
    //     //     print $response->body() . "\n";
    //     // } catch (Exception $e) {
    //     //     echo 'Caught exception: '. $e->getMessage() ."\n";
    //     // }
    // }
}
