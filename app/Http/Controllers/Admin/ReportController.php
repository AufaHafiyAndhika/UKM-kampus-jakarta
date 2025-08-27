<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ukm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display event reports index
     */
    public function index(Request $request)
    {
        $query = Event::with(['ukm'])
                     ->whereNotNull('proposal_file')
                     ->orWhereNotNull('rab_file')
                     ->orWhereNotNull('lpj_file');

        // Filter by UKM
        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->ukm_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('start_datetime', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('start_datetime', '<=', $request->end_date);
        }

        // Search by title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('ukm', function ($ukmQuery) use ($search) {
                      $ukmQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $events = $query->orderBy('start_datetime', 'desc')->paginate(15);

        // Get UKMs for filter dropdown
        $ukms = Ukm::orderBy('name')->get();

        // Get statistics
        $stats = $this->getReportStatistics();

        return view('admin.reports.index', compact('events', 'ukms', 'stats'));
    }

    /**
     * Show detailed report for specific event
     */
    public function show(Event $event)
    {
        $event->load(['ukm', 'registrations.user', 'attendances.user']);
        
        return view('admin.reports.show', compact('event'));
    }

    /**
     * Download event file (proposal, rab, lpj)
     */
    public function downloadFile(Event $event, $type)
    {
        $allowedTypes = ['proposal', 'rab', 'lpj'];
        
        if (!in_array($type, $allowedTypes)) {
            abort(404, 'File type not found');
        }

        $fileField = $type . '_file';
        $filePath = $event->$fileField;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        $fileName = $this->generateFileName($event, $type);
        
        return Storage::disk('public')->download($filePath, $fileName);
    }

    /**
     * View file in browser (for PDFs)
     */
    public function viewFile(Event $event, $type)
    {
        $allowedTypes = ['proposal', 'rab', 'lpj'];

        if (!in_array($type, $allowedTypes)) {
            abort(404, 'File type not found');
        }

        $fileField = $type . '_file';
        $filePath = $event->$fileField;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        $fullPath = Storage::disk('public')->path($filePath);
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Determine MIME type based on extension
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $mimeType = $mimeTypes[$extension] ?? mime_content_type($fullPath);

        // Only PDFs can be viewed inline, others should be downloaded
        if ($extension === 'pdf') {
            return response()->file($fullPath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline'
            ]);
        } else {
            // For non-PDF files, force download
            $fileName = $this->generateFileName($event, $type);
            return Storage::disk('public')->download($filePath, $fileName);
        }
    }

    /**
     * Export reports to Excel/CSV
     */
    public function export(Request $request)
    {
        $query = Event::with(['ukm'])
                     ->whereNotNull('proposal_file')
                     ->orWhereNotNull('rab_file')
                     ->orWhereNotNull('lpj_file');

        // Apply same filters as index
        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->ukm_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_datetime', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('start_datetime', '<=', $request->end_date);
        }

        $events = $query->orderBy('start_datetime', 'desc')->get();

        // Generate CSV
        $filename = 'laporan_kegiatan_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($events) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'No',
                'Judul Kegiatan',
                'UKM',
                'Tanggal Mulai',
                'Tanggal Selesai',
                'Status',
                'Lokasi',
                'Max Peserta',
                'Proposal',
                'RAB',
                'LPJ',
                'Dibuat Pada'
            ]);

            // CSV Data
            foreach ($events as $index => $event) {
                fputcsv($file, [
                    $index + 1,
                    $event->title,
                    $event->ukm->name ?? '-',
                    $event->start_datetime->format('d/m/Y H:i'),
                    $event->end_datetime->format('d/m/Y H:i'),
                    ucfirst($event->status),
                    $event->location,
                    $event->max_participants ?? 'Tidak terbatas',
                    $event->proposal_file ? 'Ada' : 'Tidak ada',
                    $event->rab_file ? 'Ada' : 'Tidak ada',
                    $event->lpj_file ? 'Ada' : 'Tidak ada',
                    $event->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get report statistics
     */
    private function getReportStatistics()
    {
        $totalEvents = Event::count();
        $eventsWithProposal = Event::whereNotNull('proposal_file')->count();
        $eventsWithRab = Event::whereNotNull('rab_file')->count();
        $eventsWithLpj = Event::whereNotNull('lpj_file')->count();
        $completedEvents = Event::where('status', 'completed')->count();

        return [
            'total_events' => $totalEvents,
            'events_with_proposal' => $eventsWithProposal,
            'events_with_rab' => $eventsWithRab,
            'events_with_lpj' => $eventsWithLpj,
            'completed_events' => $completedEvents,
            'proposal_percentage' => $totalEvents > 0 ? round(($eventsWithProposal / $totalEvents) * 100, 1) : 0,
            'rab_percentage' => $totalEvents > 0 ? round(($eventsWithRab / $totalEvents) * 100, 1) : 0,
            'lpj_percentage' => $totalEvents > 0 ? round(($eventsWithLpj / $totalEvents) * 100, 1) : 0,
        ];
    }

    /**
     * Generate appropriate filename for download
     */
    private function generateFileName(Event $event, $type)
    {
        $typeNames = [
            'proposal' => 'Proposal',
            'rab' => 'RAB',
            'lpj' => 'LPJ'
        ];

        $typeName = $typeNames[$type] ?? ucfirst($type);
        $eventSlug = $event->slug;
        $ukmName = str_replace(' ', '_', $event->ukm->name ?? 'UKM');

        // Get the original file extension
        $fileField = $type . '_file';
        $filePath = $event->$fileField;
        $originalExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        return "{$typeName}_{$ukmName}_{$eventSlug}.{$originalExtension}";
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSize($filePath)
    {
        if (!Storage::disk('public')->exists($filePath)) {
            return 'N/A';
        }

        $bytes = Storage::disk('public')->size($filePath);
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
