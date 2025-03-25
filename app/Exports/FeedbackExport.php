<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FeedbackExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $taskId;
    protected $jobOrderTypeId;

    public function __construct($taskId)
    {
        $this->taskId = $taskId;
        $task = Task::findOrFail($taskId);
        $this->jobOrderTypeId = $task->job_order_type_id;
    }

    public function array(): array
    {
        $task = Task::with('feedback')->findOrFail($this->taskId);

        if (!$task->feedback || !$task->feedback->data) {
            return [];
        }

        $feedbackData = json_decode($task->feedback->data, true);

        if (!is_array($feedbackData) || empty($feedbackData)) {
            return [];
        }

        // Convert the feedback data to an array format based on job_order_type_id
        $rows = [];

            // For job order type 6, use table3 data to create multiple rows
            if ($this->jobOrderTypeId == 6 && isset($feedbackData['table3']) && is_array($feedbackData['table3'])) {
                foreach ($feedbackData['table3'] as $rowData) {
                    $rows[] = $this->formatRow($rowData, $task, true);
                }
            } else {
                // Default for other types is to add a single row
                $rows[] = $this->formatRow($feedbackData, $task);
            }

            return $rows;
    }

    public function headings(): array
    {
        // Define specific headers based on job_order_type_id
        if ($this->jobOrderTypeId == 1) {
            return [
                '#',
                'اسم المشترك',
                'رقم المشترك',
                'مواسير بعد 5 متر',
                'قيمة المواسير',
                'حالة المشترك',
                'السعر',
                'رقم البوستل',
                'نوع المبنى',
                'ملاحظات المشرف',
                'ملاحظات المقاول',
                'نوع امر العمل',
                'حالة أمر العمل'
            ];
        } elseif ($this->jobOrderTypeId == 2) {
            return [
                '#',
                'بوسنل',
                'طريقة الدخول',
                'مواسير بعد 5 متر',
                'قيمة المواسير',
                'سعر طريقة الدخول',
                'حالة المبنى',
                'نوع المبنى',
                'ملاحظات المشرف',
                'ملاحظات المقاول',
                'نوع امر العمل',
                'حالة امر العمل',
                'ملاحظات العمالة'
            ];
        }
        elseif ($this->jobOrderTypeId == 6) {
            return [
                'JOINT OR Root Splitter NO.',
                'Start Reading',
                'JOINT OR Root Splitter NO.',
                'End Reading',
                'FO Size (عدد الشعرات)',
                'Length (m)',
                'date',
                'مرابط',
                'كابنات'
            ];
        }

        // Default headers if job order type is not specified
        $task = Task::with('feedback')->findOrFail($this->taskId);

        if (!$task->feedback || !$task->feedback->data) {
            return [];
        }

        return array_keys(json_decode($task->feedback->data, true));
    }

    protected function formatRow($feedbackData, $task,$isTable3 = false)
    {
        static $rowNumber = 0;
        $rowNumber++;

        // Format row based on job_order_type_id
        if ($this->jobOrderTypeId == 1) {
            return [
                $rowNumber, // #
                $task->customer_name ?? '',
                $task->customer_username ?? '',
                $feedbackData['quantity_pipes'] ?? '', // مواسير بعد 5 متر
                $task->jobOrderType->price_of_mwaseer ?? '', // قيمة المواسير
                '', // حالة المشترك
                $task->jobOrderType->price_of_mwaseer *   $feedbackData['quantity_pipes'] ?? '', // السعر
                $feedbackData['postal_number'] ?? '', // رقم البوستل
                '', // نوع المبنى
                '', // ملاحظات المشرف
                $feedbackData['note'] ?? '',// ملاحظات المقاول
                $task->jobOrderType->name ?? '', // نوع امر العمل
                $task->job_order_status ?? ''
            ];
        } elseif ($this->jobOrderTypeId == 2) {
            $entryMethodText = $this->getEntryMethodText($feedbackData['entry_method'] ?? '');
            $entryMethodPrice = $this->getEntryMethodPrice($task->jobOrderType, $feedbackData['entry_method'] ?? '');
            return [
                $rowNumber, // #
                $feedbackData['postal_number'] ?? '', // بوسنل
                $entryMethodText, // طريقة الدخول
                $feedbackData['quantity_pipes'] ?? '', // مواسير بعد 5 متر
                $task->jobOrderType->price_of_mwaseer ?? '', // قيمة المواسير
                $entryMethodPrice, // سعر طريقة الدخول
                '', // حالة المبنى
                '', // نوع المبنى
                '', // ملاحظات المشرف
                $feedbackData['note'] ?? '', // ملاحظات المقاول
                $task->jobOrderType->name ?? '', // نوع امر العمل
                $task->job_order_status ?? '', // حالة امر العمل
                '' // ملاحظات العمالة
            ];
        } elseif ($this->jobOrderTypeId == 6 && $isTable3) {
            // Format for table3 data
            return [
                $feedbackData['from_joint_root_splitter_no'] ?? '', // JOINT OR Root Splitter NO.
                $feedbackData['start_reading'] ?? '', // Start Reading
                $feedbackData['to_joint_root_splitter_no'] ?? '', // JOINT OR Root Splitter NO.
                $feedbackData['end_reading'] ?? '', // End Reading
                $feedbackData['fo_size'] ?? '', // FO Size (عدد الشعرات)
                $feedbackData['length_rollout'] ?? '', // Length (m)
                $feedbackData['date'] ?? '', // date
                $feedbackData['port'] ?? '', // مرابط
                $feedbackData['postal'] ?? '' // كابنات
            ];
        }

        // Default return if job order type is not specified
        return array_values($feedbackData);
    }

    private function getEntryMethodText($value)
    {
        $methods = [
            '1' => 'هوائي',
            '2' => 'مواسير',
            '3' => 'تدكيك',
            '4' => 'تدكيك مسار مغلق',
            '5' => 'تثمينة'
        ];

        return $methods[$value] ?? $value;
    }

    private function getEntryMethodPrice($jobOrderType, $entryMethod)
    {
        if (!$jobOrderType) {
            return '';
        }

        switch ($entryMethod) {
            case '1':
                return $jobOrderType->hawa2e ?? 0;
            case '2':
                return $jobOrderType->mawaseer ?? 0;
            case '3':
                return $jobOrderType->tadkeek ?? 0;
            case '4':
                return $jobOrderType->tadkek_msar_close ?? 0;
            case '5':
                return $jobOrderType->tathmena ?? 0;
            default:
                return 0;
        }
    }
    
    private function getPipesText($value)
    {
        $pipes = [
            '1' => 'مواسير',
            '2' => 'ترنكات',
            '3' => 'برابيش',
            '4' => 'تدكيك',
            '5' => 'تدكيك بمسار مغلق'
        ];

        return $pipes[$value] ?? $value;
    }

    private function getYesNoText($value)
    {
        return $value == '1' ? 'نعم' : 'لا';
    }

    public function styles(Worksheet $sheet)
    {
        // Set RTL direction for Arabic content
        $sheet->setRightToLeft(true);

        // Make first row bold
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
