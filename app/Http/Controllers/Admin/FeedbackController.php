<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Feedback;
use App\Models\FeedbackPhoto;
use App\Models\Financial;
use App\Models\JobOrderType;



use App\Enums\EntryMethod;


class FeedbackController extends Controller
{
    public function storeFeedback(Request $request, $taskId)
    {
        $request->validate([
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        // Check if feedback already exists for the given task
        $feedback = Feedback::where('task_id', $taskId)->first();

        // Handle table data for jobOrderTypeId === 5 or 6
        $tableData = [];
        if ($request->has('root_splitter_no')) {
            foreach ($request->input('root_splitter_no') as $index => $rootSplitterNo) {
                $tableData[] = [
                    'root_splitter_no' => $rootSplitterNo,
                    'start_reading' => $request->input('start_reading')[$index] ?? null,
                    'postal_code' => $request->input('postal_code')[$index] ?? null,
                    'end_reading' => $request->input('end_reading')[$index] ?? null,
                    'length' => $request->input('length')[$index] ?? null,
                    'location' => $request->input('location')[$index] ?? null,
                    'date' => $request->input('date')[$index] ?? null,
                    'u' => $request->input('u')[$index] ?? null,
                    'clamps' => $request->input('clamps')[$index] ?? null,
                    'studs' => $request->input('studs')[$index] ?? null,
                    'postal' => $request->input('postal')[$index] ?? null,
                ];
            }
        }

        $table2Data = [];
        if ($request->has('splitter_no')) {
            foreach ($request->input('splitter_no') as $index => $splitterNo) {
                $table2Data[] = [
                    'splitter_no' => $splitterNo,
                    'joint_no' => $request->input('joint_no')[$index] ?? null,
                    'power_in' => $request->input('power_in')[$index] ?? null,
                    'power_out' => $request->input('power_out')[$index] ?? null,
                    'core_color' => $request->input('core_color')[$index] ?? null,
                    'splitter_qty' => $request->input('splitter_qty')[$index] ?? null,
                ];
            }
        }

        $table3Data = [];
        if ($request->has('from_joint_root_splitter_no')) {
            foreach ($request->input('from_joint_root_splitter_no') as $index => $fromJointRootSplitterNo) {
                $table3Data[] = [
                    'from_joint_root_splitter_no' => $fromJointRootSplitterNo,
                    'start_reading' => $request->input('start_reading')[$index] ?? null,
                    'to_joint_root_splitter_no' => $request->input('to_joint_root_splitter_no')[$index] ?? null,
                    'end_reading' => $request->input('end_reading')[$index] ?? null,
                    'fo_size' => $request->input('fo_size')[$index] ?? null,
                    'length_rollout' => $request->input('length_rollout')[$index] ?? null,
                    'port' => $request->input('port')[$index] ?? null,
                    'postal' => $request->input('postal')[$index] ?? null,
                    'date' => $request->input('date')[$index] ?? null,
                    'location' => $request->input('location')[$index] ?? null,
                ];
            }
        }

        $dataToSave = $request->except(
            'photos', '_token',
            'root_splitter_no', 'start_reading', 'postal_code', 'end_reading', 'length',
            'splitter_no', 'joint_no', 'power_in', 'power_out', 'core_color', 'splitter_qty',
            'from_joint_root_splitter_no', 'to_joint_root_splitter_no', 'fo_size'
        );

        $dataToSave['table1'] = $tableData; // Data for jobOrderTypeId === 5
        $dataToSave['table2'] = $table2Data; // Data for jobOrderTypeId === 5
        $dataToSave['table3'] = $table3Data; // Data for jobOrderTypeId === 6

        if ($feedback) {
            // Update existing feedback
            $feedbackData = json_decode($feedback->data, true) ?? [];
            $updatedData = array_merge($feedbackData, $dataToSave); // Merge new data with existing
            $feedback->data = json_encode($updatedData);
            $feedback->save();
        } else {
            // Create new feedback
            $feedback = Feedback::create([
                'task_id' => $taskId,
                'data' => json_encode($dataToSave), // Store other inputs as JSON
            ]);
        }

        // Save uploaded photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $the_file_path = uploadImage('assets/admin/uploads', $photo);
                FeedbackPhoto::create([
                    'feedback_id' => $feedback->id,
                    'photo_path' => $the_file_path,
                ]);
            }
        }

        // For Financial

        $feedbackData = json_decode($feedback->data, true);
        // Get the JobOrderType ID from the Task
        $task = \App\Models\Task::find($taskId);
        if (!$task || !$task->job_order_type_id) {
            return redirect()->back()->with('error', 'Job Order Type not found.');
        }

        $jobOrderType = JobOrderType::find($task->job_order_type_id);
        if (!$jobOrderType) {
            return redirect()->back()->with('error', 'Job Order Type not found.');
        }


        if($task->job_order_type_id == 1 || $task->job_order_type_id == 4)
        {
            // Inside Building Installation and Reallocate Home Box ***********************

        // Extract pipes type, quantity, and router value
        $pipesType = $feedbackData['pipes'] ?? null;
        $quantity = $feedbackData['quantity_pipes'] ?? 0;
        $router = $feedbackData['router'] ?? 2; // 1 means router is installed
        $mada_tv = $feedbackData['mada_tv'] ?? 2; // 1 means mada_tv is installed
        $le7am_sh3raat = $feedbackData['le7am_sh3raat'] ?? 2;



        // Determine the price based on pipes type
        $price = 0;
        if ($pipesType == 1) {
            $price = $jobOrderType->price_of_mwaseer;
        } elseif ($pipesType == 2) {
            $price = $jobOrderType->price_of_trankat;
        } elseif ($pipesType == 3) {
            $price = $jobOrderType->price_of_brabesh;
        } elseif ($pipesType == 4 ) {
            $price = $jobOrderType->price_of_tadkek; // Fixed amount
        } elseif ($pipesType == 5) {
            $price = $jobOrderType->price_of_tadkek_msar_close; // Fixed amount
        }

        // Calculate base total_of_inside
        $totalInside = ($pipesType == 4 || $pipesType == 5) ? $price : ($quantity * $price);

        // If router is installed (value == 1), add the router price to the total
        if ($router == 1) {
            $totalInside += $jobOrderType->price_of_tarkeeb_router;
        }

        if ($mada_tv == 1) {
            $totalInside += $jobOrderType->price_of_mada_tv;
        }


        if ($le7am_sh3raat == 1) {
            $totalInside += $jobOrderType->price_of_le7am_sh3raat;
        }

        // Save total in the Financials table
        Financial::updateOrCreate(
            ['task_id' => $taskId],
            [
                'total_of_inside' => $totalInside, // Save only total_of_inside
                'total_of_entrance' => null,
                'total_of_Sspl' => null,
                'total_of_rollout' => null,
            ]
          );
        }else if($task->job_order_type_id == 2 || $task->job_order_type_id == 3 ){

               // Entrance and Entrance 2 ************
                $jobOrderType  = JobOrderType::find(2);
                $cost = 0;
                $entry_method_price = 0;
                $pipes_after_5m_price = 0;                
                $feedbackData = json_decode($task->feedback->data, true);


                if ($feedbackData['entry_method'] == EntryMethod::HAWA2E) {
                    
                    $entry_method_price   = $jobOrderType->hawa2e;

                    if (isset($feedbackData['pipes_after_5m']) && $feedbackData['pipes_after_5m'] != NULL) {

                       $pipes_after_5m_price = $feedbackData['pipes_after_5m'] * $jobOrderType->hawa2e_mwaseer_after_5m;
                        
                    }else{

                       $pipes_after_5m_price = 0;

                    }

                }elseif ($feedbackData['entry_method'] == EntryMethod::MAWASEER) {

                    $entry_method_price = $jobOrderType->mawaseer;

                    if (isset($feedbackData['pipes_after_5m']) && $feedbackData['pipes_after_5m'] != NULL) {

                       $pipes_after_5m_price = $feedbackData['pipes_after_5m'] * $jobOrderType->mawaseer_mwaseer_after_5m;
                        
                    }else{

                       $pipes_after_5m_price = 0;

                    }

                }elseif ($feedbackData['entry_method'] == EntryMethod::TADKEEK) {

                    $entry_method_price = $jobOrderType->tadkeek;

                    if (isset($feedbackData['pipes_after_5m']) && $feedbackData['pipes_after_5m'] != NULL) {

                       $pipes_after_5m_price = $feedbackData['pipes_after_5m'] * $jobOrderType->tadkeek_mwaseer_after_5m;
                        
                    }else{

                       $pipes_after_5m_price = 0;

                    }


                }elseif ($feedbackData['entry_method'] == EntryMethod::TADKEK_MSAR_CLOSE) {

                    $entry_method_price = $jobOrderType->tadkek_msar_close;


                    if (isset($feedbackData['pipes_after_5m']) && $feedbackData['pipes_after_5m'] != NULL) {

                       $pipes_after_5m_price = $feedbackData['pipes_after_5m'] * $jobOrderType->tadkek_msar_close_mwaseer_after_5m;
                        
                    }else{

                       $pipes_after_5m_price = 0;

                    }                    

                }elseif ($feedbackData['entry_method'] == EntryMethod::TATHMENA) {

                    $entry_method_price = $jobOrderType->tathmena;


                    if (isset($feedbackData['pipes_after_5m']) && $feedbackData['pipes_after_5m'] != NULL) {

                       $pipes_after_5m_price = $feedbackData['pipes_after_5m'] * $jobOrderType->tathmena_mwaseer_after_5m;
                        
                    }else{

                       $pipes_after_5m_price = 0;

                    }                        
                
                }else{

                    $entry_method_price = 0;
                    $pipes_after_5m_price = 0;

                }

                if (isset($feedbackData['link_distance_after_120m']) && $feedbackData['link_distance_after_120m'] != NULL) {
                    
                    $link_distance_after_120m_price = $feedbackData['link_distance_after_120m'] * $jobOrderType->price_from_engineer;
                
                }else{

                    $link_distance_after_120m_price = 0;

                }


                  

                $cost = $link_distance_after_120m_price + $entry_method_price + $pipes_after_5m_price;


                // Save Financials Data
                Financial::updateOrCreate(
                    ['task_id' => $taskId],
                    [
                        'total_of_inside' => null, // From previous calculation
                        'total_of_entrance' => $cost, // Calculated now
                        'total_of_Sspl' => null,
                        'total_of_rollout' => null,
                    ]
                );


        }else if($task->job_order_type_id == 5 ){
        // SSPL ******************************

            // Ensure `table1` exists in the feedback data
            $table1Data = $feedbackData['table1'] ?? [];

            // Extract lengths safely
            $lengths = collect($table1Data)
                ->pluck('length')
                ->map(fn($value) => is_numeric($value) ? (float) $value : 0) // Ensure numeric values
                ->toArray();

            // Extract Tarkeb Marwaha and Lham Shara safely
            $tarkebMarwaha = array_map('intval', $feedbackData['tarkeb_marwaha'] ?? []);
            $lhamShara = array_map('intval', $feedbackData['lham_shara'] ?? []);

            // Get Job Order Type Prices (Ensure non-null values)
            $pricePerMeter = $jobOrderType->price_of_1m_per_length ?? 0;
            $priceTarkebMarwaha = $jobOrderType->price_of_tarkeb_marwaha ?? 0;
            $priceOneShara = $jobOrderType->price_of_one_shara ?? 0;

            // Summation of Lengths (Ensure valid sum)
            $totalLength = !empty($lengths) ? array_sum($lengths) : 0;

            // Summation of Lham Shara (Ensure valid sum)
            $totalLhamShara = !empty($lhamShara) ? array_sum($lhamShara) : 0;

            // Check if Tarkeb Marwaha Exists (Ensure correct boolean check)
            $tarkebMarwahaExists = in_array(1, $tarkebMarwaha, true);

            // Initialize total SSPL calculation
            $totalSspl = ($totalLength * $pricePerMeter);

            // Add Tarkeb Marwaha Cost if exists
            if ($tarkebMarwahaExists) {
                $totalSspl += $priceTarkebMarwaha;
            }

            // Add Lham Shara Calculation
            $totalSspl += ($totalLhamShara * $priceOneShara);

            // Debugging: Check values before saving
            \Log::info("SSPL Calculation Debug:", [
                'lengths' => $lengths,
                'totalLength' => $totalLength,
                'pricePerMeter' => $pricePerMeter,
                'tarkebMarwaha' => $tarkebMarwaha,
                'tarkebMarwahaExists' => $tarkebMarwahaExists,
                'priceTarkebMarwaha' => $priceTarkebMarwaha,
                'lhamShara' => $lhamShara,
                'totalLhamShara' => $totalLhamShara,
                'priceOneShara' => $priceOneShara,
                'totalSspl' => $totalSspl,
            ]);

            // Save Financials Data (Ensure `totalSspl` is not null)
            Financial::updateOrCreate(
                ['task_id' => $taskId],
                [
                    'total_of_inside' => null, // From previous calculations
                    'total_of_entrance' => null, // From previous calculations
                    'total_of_Sspl' => ($totalSspl > 0) ? $totalSspl : null, // Ensure valid value
                    'total_of_rollout' => null, // From previous calculations
                ]
            );

        }else if($task->job_order_type_id == 6){

           // Rollout ************************************************************************************************* ****

            $jobOrderType  = JobOrderType::find(6);
            $cost = 0;

            foreach ($feedbackData['table3'] as $item) {
                $foSize = intval($item['fo_size']);
                $price = 0; // Default price

                if ($jobOrderType) {
                    if (in_array($foSize, [8, 12, 24])) {
                        $price = $jobOrderType->price_of_8_12_24;
                    } elseif (in_array($foSize, [48, 72, 96, 144])) {
                        $price = $jobOrderType->price_of_48_72_96_144;
                    }

                    $cost += intval($item['length_rollout']) * $price;
                }
            }

            // Save Financials Data
            Financial::updateOrCreate(
                ['task_id' => $taskId],
                [
                    'total_of_inside' => null, // From previous calculations
                    'total_of_entrance' => null, // From previous calculations
                    'total_of_Sspl' => null, // From previous calculations
                    'total_of_rollout' => $cost, // Calculated now
                ]
            );
        }

        return redirect()->back()->with('success', __('Feedback submitted successfully.'));
    }



}
