<?php

namespace App\Imports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class TasksImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $jobOrderType = $row['job_order_type'] ?? '';
        $jobOrderTypeId = $this->getJobOrderTypeId($jobOrderType);

        $type = $row['type'] ?? '';
        $typeId = $this->getTypeId($type);

        // Skip invalid rows to prevent SQL error
        if (is_null($jobOrderTypeId)) {
            \Log::warning("Skipping row due to missing Job Order Type:", ['job_order_type' => $jobOrderType]);
            return null;
        }

        if (is_null($typeId)) {
            \Log::warning("Skipping row due to missing Type:", ['type' => $type]);
            return null;
        }


        return new Task([
            'building' => $row['building'],  // Matches directly
            'district' => $row['district'],
            'area' => $row['area'],
            'job_order_type_id' => $jobOrderTypeId,
            'job_order_status' => $row['job_order_status'],  // 'Job Order Status' in Excel
            'contractor_status' => $row['contractor_status'],  // 'Contractor Status' in Excel
            'assigned_time' => $row['assigned_time'],  // 'Assigned Time' in Excel
            'customer_name' => $row['customer_service'],  // 'Customer Service' in Excel
            'customer_username' => $row['assigned_to'],  // 'Assigned To' in Excel
            'customer_mobile' => $row['customer_service_mobile'],  // 'Customer Mobile' in Excel (if exists)
            'customer_address' => $row['customer_service_address'],  // 'Customer Service Address' in Excel
            'splitting' => $row['splitting'],  // 'Splitting' in Excel
            'sspl_no_planned' => $row['planned_sspl_no'],  // 'Planned SSPL NO' in Excel
            'rspl_no' => $row['planned_rspl_no'],  // 'Planned RSPL NO' in Excel
            'through' => $row['through'],  // 'Through' in Excel
            'core_color' => $row['core_color'],  // 'Core Color' in Excel
            'note' => $row['note'],  // 'Note' in Excel
            'customer_service_status' => $row['customer_service_status'],  // 'Customer Service Status' in Excel
            'postal_code_status' => $row['postal_code_status'],  // 'Postal Code Status' in Excel
            'type_id' => $typeId,
            'date_of_task' => now(),  
        ]);
    }


    private function getJobOrderTypeId($jobOrderType)
    {
        if (empty(trim($jobOrderType))) {
            return null; // Skip empty rows
        }

        $mapping = [
            'inside building installation' => 1,
            'entrance' => 2,
            'entrance 2' => 3,
            'reallocate home box' => 4,
            'sspl installation' => 5,
            'rollout' => 6,
        ];

        $normalizedType = strtolower(trim($jobOrderType));

        if (!array_key_exists($normalizedType, $mapping)) {
            throw new \Exception("Invalid Job Order Type: " . json_encode($jobOrderType)); // Log the issue
        }

        return $mapping[$normalizedType];
    }


    private function getTypeId($type)
    {
        if (empty(trim($type))) {
            return null; // Skip empty rows
        }

        $mapping = [
            'building' => 1,
            'villa' => 2,
        ];

        $normalizedType = strtolower(trim($type));

        if (!array_key_exists($normalizedType, $mapping)) {
            \Log::error("Invalid Type: " . json_encode($type));
            return null;
        }

        return $mapping[$normalizedType];
    }


}


