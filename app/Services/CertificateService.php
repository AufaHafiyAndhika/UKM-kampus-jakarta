<?php

namespace App\Services;

use App\Models\EventAttendance;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateService
{
    /**
     * Generate certificate for attendance
     */
    public function generateCertificate(EventAttendance $attendance)
    {
        $event = $attendance->event;
        $user = $attendance->user;

        // Check if event has certificate template
        if (!$event->certificate_template) {
            throw new \Exception('Event tidak memiliki template sertifikat.');
        }

        // Check if attendance is verified
        if ($attendance->verification_status !== 'verified' || $attendance->status !== 'present') {
            throw new \Exception('Absensi belum diverifikasi atau tidak hadir.');
        }

        // Generate certificate HTML
        $certificateHtml = $this->generateCertificateHtml($attendance);

        // Generate PDF
        $pdf = Pdf::loadHTML($certificateHtml)
                  ->setPaper('A4', 'landscape')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'defaultFont' => 'Arial',
                      'dpi' => 150,
                  ]);

        // Generate filename
        $filename = 'certificates/' . $event->slug . '_' . $user->student_id . '_' . time() . '.pdf';

        // Save PDF to storage
        Storage::disk('public')->put($filename, $pdf->output());

        // Update attendance record
        $attendance->update([
            'certificate_generated' => true,
            'certificate_file' => $filename,
        ]);

        return $filename;
    }

    /**
     * Generate certificate HTML with template overlay
     */
    private function generateCertificateHtml(EventAttendance $attendance)
    {
        $event = $attendance->event;
        $user = $attendance->user;

        // Try template-based certificate if template exists
        if ($event->certificate_template) {
            return $this->generateTemplateBasedCertificateFixed($event, $user);
        }

        // Fallback to simple certificate
        return $this->generateSimpleCertificate($event, $user);
    }

    /**
     * Generate default certificate without template
     */
    private function generateDefaultCertificate($event, $user)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Sertifikat - ' . $event->title . '</title>
            <style>
                @page {
                    margin: 20mm;
                    size: A4 landscape;
                }
                body {
                    margin: 0;
                    padding: 40px;
                    font-family: "Times New Roman", serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: black;
                    text-align: center;
                    min-height: calc(100vh - 80px);
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }
                .certificate-border {
                    border: 8px solid #000;
                    padding: 60px 40px;
                    background: rgba(255, 255, 255, 0.9);
                    backdrop-filter: blur(10px);
                    border-radius: 20px;
                }
                .certificate-header {
                    font-size: 60px;
                    font-weight: bold;
                    margin-bottom: 60px;
                    text-transform: uppercase;
                    letter-spacing: 6px;
                }
                .certificate-subtitle {
                    font-size: 32px;
                    margin-bottom: 60px;
                    font-style: italic;
                    letter-spacing: 2px;
                }
                .participant-name {
                    font-size: 52px;
                    font-weight: bold;
                    margin: 70px 0;
                    text-transform: uppercase;
                    letter-spacing: 4px;
                    border-bottom: 4px solid #000;
                    padding-bottom: 25px;
                    display: inline-block;
                    min-width:80%;
                }
                .event-info {
                    font-size: 24px;
                    margin: 40px 0;
                    line-height: 2;
                }
                .event-title {
                    font-size: 36px;
                    font-weight: 600;
                    margin: 40px 0;
                    color: #000;
                    padding: 0 50px;
                }
                .event-date {
                    font-size: 18px;
                    margin: 25px 0;
                }
                .certificate-footer {
                    margin-top: 40px;
                    font-size: 14px;
                    opacity: 0.8;
                    line-height: 1.8;
                }
                .certificate-id {
                    position: absolute;
                    bottom: 20px;
                    right: 30px;
                    font-size: 12px;
                    opacity: 0.7;
                }
            </style>
        </head>
        <body>
            <div class="certificate-border">
                <div class="certificate-header">SERTIFIKAT</div>
                <div class="certificate-subtitle">Certificate of Participation</div>

                <div class="event-info">
                    Diberikan kepada:
                </div>

                <div class="participant-name">
                    ' . strtoupper($user->name) . '
                </div>

                <div class="event-info">
                    Atas partisipasinya dalam kegiatan:
                </div>

                <div class="event-title">
                    ' . $event->title . '
                </div>

                <div class="event-date">
                    Tanggal: ' . $event->start_datetime->format('d F Y') . '
                </div>

                <div class="event-info">
                    Diselenggarakan oleh: ' . $event->ukm->name . '
                </div>

                <div class="certificate-footer">
                    Universitas Telkom<br>
                    Bandung, ' . now()->format('d F Y') . '
                </div>
            </div>

            <div class="certificate-id">
                ID: ' . $event->slug . '-' . $user->student_id . '-' . date('Ymd') . '
            </div>
        </body>
        </html>';
    }

    /**
     * Generate certificate with uploaded template as background
     */
    private function generateTemplateBasedCertificate($event, $user)
    {
        // Try multiple paths for better compatibility
        $templatePaths = [
            public_path('storage/' . $event->certificate_template),
            storage_path('app/public/' . $event->certificate_template),
            base_path('public/storage/' . $event->certificate_template)
        ];

        $templateBase64 = '';
        $templatePath = null;

        // Find the first existing path
        foreach ($templatePaths as $path) {
            if (file_exists($path)) {
                $templatePath = $path;
                break;
            }
        }

        if ($templatePath && file_exists($templatePath)) {
            try {
                $imageData = file_get_contents($templatePath);
                $mimeType = mime_content_type($templatePath);
                $templateBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);

                Log::info('Template image loaded successfully', [
                    'path' => $templatePath,
                    'size' => strlen($imageData),
                    'mime' => $mimeType,
                    'base64_length' => strlen($templateBase64)
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to load template image', [
                    'path' => $templatePath,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            Log::warning('Template file not found in any path', [
                'template' => $event->certificate_template,
                'checked_paths' => $templatePaths
            ]);
        }

        // Enhanced HTML with better template handling
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat - ' . $event->title . '</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            width: 297mm;
            height: 210mm;
            position: relative;
        }
        .background-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .certificate-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
            box-sizing: border-box;
        }
        .certificate-title {
            font-size: 44px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 2px 2px 4px rgba(255,255,255,0.8);
        }
        .certificate-subtitle {
            font-size: 24px;
            margin-bottom: 25px;
            color: #000;
            font-style: italic;
            opacity: 0.9;
        }
        .given-to {
            font-size: 18px;
            margin-bottom: 15px;
            color: #000;
            opacity: 0.8;
        }
        .participant-name {
            font-size: 50px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 30px 0;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            display: inline-block;
            text-shadow: 2px 2px 4px rgba(255,255,255,0.8);
            background: rgba(255,255,255,0.1);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .activity-text {
            font-size: 18px;
            margin: 20px 0 15px 0;
            color: #000;
            opacity: 0.8;
        }
        .event-title {
            font-size: 38px;
            font-weight: bold;
            margin: 20px 0 25px 0;
            color: #2c3e50;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
        }
        .event-details {
            font-size: 20px;
            margin: 12px 0;
            color: #000;
            line-height: 1.8;
            font-weight: 500;
        }
        .appreciation-text {
            font-size: 16px;
            margin-top: 25px;
            color: #000;
            font-style: italic;
            opacity: 0.9;
            line-height: 1.6;
        }
        .certificate-id {
            position: absolute;
            bottom: 20px;
            right: 30px;
            font-size: 10px;
            color: #666;
            opacity: 0.7;
            background: rgba(255,255,255,0.8);
            padding: 5px 10px;
            border-radius: 5px;
            z-index: 3;
        }
    </style>
</head>
<body>';

        // Add background image if template exists
        if ($templateBase64) {
            $html .= '
    <div class="background-container">
        <img src="' . $templateBase64 . '" class="background-image" alt="Certificate Template" />
    </div>';
        } else {
            // Fallback gradient background
            $html .= '
    <div class="background-container" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>';
        }

        // Get custom positioning for this template
        $positioning = $this->getCertificatePositioning($event);

        $html .= '
    <div class="certificate-content" style="' . $positioning['container'] . '">
        <div class="certificate-title" style="' . $positioning['title'] . '">SERTIFIKAT</div>
        <div class="certificate-subtitle" style="' . $positioning['subtitle'] . '">Certificate of Participation</div>
        <div class="given-to" style="' . $positioning['given_to'] . '">Diberikan kepada:</div>
        <div class="participant-name" style="' . $positioning['name'] . '">' . strtoupper($user->name) . '</div>
        <div class="activity-text" style="' . $positioning['activity'] . '">Yang telah mengikuti kegiatan:</div>
        <div class="event-title" style="' . $positioning['event_title'] . '">' . $event->title . '</div>
        <div class="event-details" style="' . $positioning['date'] . '">Tanggal: ' . $event->start_datetime->format('d F Y') . '</div>
        <div class="event-details" style="' . $positioning['organizer'] . '">Diselenggarakan oleh: ' . $event->ukm->name . '</div>
        <div class="event-details" style="' . $positioning['location'] . '">Lokasi: ' . $event->location . '</div>
        <div class="appreciation-text" style="' . $positioning['appreciation'] . '">Sertifikat ini diberikan sebagai bentuk apresiasi atas partisipasi aktif</div>
    </div>

    <div class="certificate-id">
        ID: ' . $event->slug . '-' . ($user->student_id ?? $user->nim ?? $user->id) . '-' . date('Ymd') . '
    </div>
</body>
</html>';

        return $html;
    }

    /**
     * Generate template-based certificate with GD image overlay
     */
    private function generateTemplateBasedCertificateFixed($event, $user)
    {
        // Clear any potential cache
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        // Force regenerate with fresh positioning
        return $this->generateTemplateBasedCertificate($event, $user);
    }

    /**
     * Force regenerate certificate with new layout (for testing)
     */
    public function forceRegenerateWithNewLayout($event, $user)
    {
        // Clear cache
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        // Get fresh positioning
        $positioning = $this->getCertificatePositioning($event);

        // Generate with timestamp to ensure uniqueness
        $html = $this->generateTemplateBasedCertificate($event, $user);

        // Add timestamp comment to force regeneration
        $html .= '<!-- Generated at: ' . now()->format('Y-m-d H:i:s') . ' -->';

        return $html;
    }

    /**
     * Get certificate positioning based on template
     * Customize spacing and positioning for different templates
     */
    private function getCertificatePositioning($event)
    {
        // Default positioning (perfectly center-aligned with better spacing)
        $defaultPositioning = [
            'container' => 'justify-content: center; align-items: center; padding-top: 150px; padding-left: 60px; padding-right: 60px; text-align: center; min-height: calc(100% - 300px);',
            'title' => 'margin-bottom: 25px; font-size: 44px; font-weight: bold; text-transform: uppercase; letter-spacing: 3px;',
            'subtitle' => 'margin-bottom: 30px; font-size: 24px; font-style: italic; opacity: 0.9;',
            'given_to' => 'margin-bottom: 20px; font-size: 18px; opacity: 0.8;',
            'name' => 'margin: 35px 0; font-size: 50px; font-weight: bold; letter-spacing: 3px; text-transform: uppercase; border-bottom: 3px solid #000; padding-bottom: 15px; display: inline-block;',
            'activity' => 'margin: 30px 0 20px 0; font-size: 18px; opacity: 0.8;',
            'event_title' => 'margin: 25px 0 30px 0; font-size: 38px; font-weight: bold; color: #2c3e50;',
            'date' => 'margin: 18px 0; font-size: 20px; line-height: 1.8;',
            'organizer' => 'margin: 18px 0; font-size: 20px; line-height: 1.8;',
            'location' => 'margin: 18px 0; font-size: 20px; line-height: 1.8;',
            'appreciation' => 'margin-top: 35px; font-size: 16px; font-style: italic; line-height: 1.6; opacity: 0.9;'
        ];

        // Template-specific positioning
        // You can customize based on template filename or event properties
        $templateName = basename($event->certificate_template ?? '');

        // Custom positioning for specific templates
        // Check for multiple possible template names
        $customTemplates = [
            'rQUuuutqBwYrgYUbgI0xUVAGDj8hjb05rgZ9WYZc',
            'iCefnksDkb9lTJA4smTteZzYZMSQNb9RzaVkwM0p'
        ];

        $isCustomTemplate = false;
        foreach ($customTemplates as $template) {
            if (strpos($templateName, $template) !== false) {
                $isCustomTemplate = true;
                break;
            }
        }

        if ($isCustomTemplate) {
            // Enhanced center positioning for custom templates - MOVED MUCH LOWER
            return [
                'container' => 'justify-content: center; align-items: center; padding-top: 280px; padding-left: 80px; padding-right: 80px; text-align: center; min-height: calc(100% - 400px);',
                'title' => 'margin-bottom: 15px; font-size: 28px; display: none;', // Hide default title
                'subtitle' => 'margin-bottom: 20px; font-size: 24px; display: none;', // Hide subtitle
                'given_to' => 'margin-bottom: 20px; font-size: 22px; display: none;', // Hide "Diberikan kepada"
                'name' => 'margin: 25px 0; font-size: 48px; font-weight: bold; color: #000; text-shadow: 2px 2px 4px rgba(255,255,255,0.8); background: rgba(255,255,255,0.1); padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); line-height: 1.3; letter-spacing: 2px; text-transform: uppercase;',
                'activity' => 'margin: 20px 0 15px 0; font-size: 22px; display: none;', // Hide "Yang telah mengikuti"
                'event_title' => 'margin: 20px 0 20px 0; font-size: 36px; font-weight: bold; color: #000; line-height: 1.3; text-shadow: 1px 1px 2px rgba(255,255,255,0.8);',
                'date' => 'margin: 12px 0; font-size: 22px; color: #000; line-height: 1.6; font-weight: 500;',
                'organizer' => 'margin: 12px 0; font-size: 22px; color: #000; line-height: 1.6; font-weight: 500;',
                'location' => 'margin: 12px 0; font-size: 22px; color: #000; line-height: 1.6; font-weight: 500;',
                'appreciation' => 'margin-top: 30px; font-size: 18px; font-style: italic; color: #000; display: none;' // Hide appreciation
            ];
        }

        // Add more template-specific positioning here
        // Example for another template:
        /*
        if (strpos($templateName, 'another-template') !== false) {
            return [
                'container' => 'justify-content: center; align-items: flex-end; padding-bottom: 100px;',
                'name' => 'margin: 20px 0; font-size: 40px; color: #ffffff;',
                // ... other positioning
            ];
        }
        */

        return $defaultPositioning;
    }

    /**
     * Easy method to customize certificate layout
     * Call this method to adjust spacing and positioning
     */
    public function customizeCertificateLayout($templateName, $customSettings = [])
    {
        // Default enhanced settings
        $defaultSettings = [
            'container_padding_top' => '150px',
            'container_padding_sides' => '100px',
            'name_font_size' => '42px',
            'name_margin' => '30px 0',
            'name_line_height' => '1.6',
            'event_title_font_size' => '32px',
            'event_title_margin' => '25px 0 30px 0',
            'detail_font_size' => '18px',
            'detail_margin' => '15px 0',
            'detail_line_height' => '1.8'
        ];

        // Merge with custom settings
        $settings = array_merge($defaultSettings, $customSettings);

        return [
            'container' => "justify-content: center; align-items: center; padding-top: {$settings['container_padding_top']}; padding-left: {$settings['container_padding_sides']}; padding-right: {$settings['container_padding_sides']}; text-align: center;",
            'title' => 'display: none;',
            'subtitle' => 'display: none;',
            'given_to' => 'display: none;',
            'name' => "margin: {$settings['name_margin']}; font-size: {$settings['name_font_size']}; font-weight: bold; color: #000; text-shadow: none; background: none; padding: 0; border-radius: 0; box-shadow: none; line-height: {$settings['name_line_height']};",
            'activity' => 'display: none;',
            'event_title' => "margin: {$settings['event_title_margin']}; font-size: {$settings['event_title_font_size']}; font-weight: bold; color: #000; line-height: 1.5;",
            'date' => "margin: {$settings['detail_margin']}; font-size: {$settings['detail_font_size']}; color: #000; line-height: {$settings['detail_line_height']};",
            'organizer' => "margin: {$settings['detail_margin']}; font-size: {$settings['detail_font_size']}; color: #000; line-height: {$settings['detail_line_height']};",
            'location' => "margin: {$settings['detail_margin']}; font-size: {$settings['detail_font_size']}; color: #000; line-height: {$settings['detail_line_height']};",
            'appreciation' => 'display: none;'
        ];
    }

    /**
     * Copy template to public directory for direct access
     */
    private function copyTemplateToPublic($templatePath)
    {
        try {
            // Source path
            $sourcePath = storage_path('app/public/' . $templatePath);

            if (!file_exists($sourcePath)) {
                Log::warning('Template source file not found', ['path' => $sourcePath]);
                return false;
            }

            // Create public certificates directory if not exists
            $publicDir = public_path('certificates');
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }

            // Generate unique filename
            $filename = 'template_' . time() . '_' . basename($templatePath);
            $destinationPath = $publicDir . '/' . $filename;

            // Copy file
            if (copy($sourcePath, $destinationPath)) {
                Log::info('Template copied to public directory', [
                    'source' => $sourcePath,
                    'destination' => $destinationPath
                ]);
                return 'certificates/' . $filename;
            } else {
                Log::error('Failed to copy template to public directory');
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception copying template to public', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Generate simple certificate with black text
     */
    private function generateSimpleCertificate($event, $user)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Sertifikat - ' . $event->title . '</title>
            <style>
                @page {
                    margin: 20mm;
                    size: A4 landscape;
                }
                body {
                    margin: 0;
                    padding: 40px;
                    font-family: "Times New Roman", serif;
                    background: white;
                    color: black;
                    text-align: center;
                    min-height: calc(100vh - 80px);
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                }
                .certificate-border {
                    border: 8px solid #000;
                    padding: 80px 60px;
                    background: white;
                    border-radius: 20px;
                    max-width: 90%;
                    width: 100%;
                    box-sizing: border-box;
                }
                .certificate-header {
                    font-size: 52px;
                    font-weight: bold;
                    margin-bottom: 35px;
                    text-transform: uppercase;
                    letter-spacing: 4px;
                    color: black;
                    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
                }
                .certificate-subtitle {
                    font-size: 26px;
                    margin-bottom: 45px;
                    font-style: italic;
                    color: black;
                    opacity: 0.9;
                }
                .participant-name {
                    font-size: 48px;
                    font-weight: bold;
                    margin: 35px 0;
                    text-transform: uppercase;
                    letter-spacing: 3px;
                    border-bottom: 4px solid #000;
                    padding-bottom: 15px;
                    display: inline-block;
                    color: black;
                    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
                    min-width: 60%;
                }
                .event-info {
                    font-size: 22px;
                    margin: 25px 0;
                    line-height: 1.8;
                    color: black;
                    opacity: 0.9;
                }
                .event-title {
                    font-size: 32px;
                    font-weight: 600;
                    margin: 25px 0;
                    color: #2c3e50;
                    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
                }
                .event-date {
                    font-size: 20px;
                    margin: 18px 0;
                    color: black;
                    font-weight: 500;
                }
                .certificate-footer {
                    margin-top: 45px;
                    font-size: 16px;
                    color: black;
                    line-height: 1.6;
                    opacity: 0.8;
                }
                .certificate-id {
                    position: absolute;
                    bottom: 20px;
                    right: 30px;
                    font-size: 12px;
                    color: black;
                }
            </style>
        </head>
        <body>
            <div class="certificate-border">
                <div class="certificate-header">SERTIFIKAT</div>
                <div class="certificate-subtitle">Certificate of Participation</div>

                <div class="event-info">
                    Diberikan kepada:
                </div>

                <div class="participant-name">
                    ' . strtoupper($user->name) . '
                </div>

                <div class="event-info">
                    Yang telah mengikuti kegiatan:
                </div>

                <div class="event-title">
                    ' . $event->title . '
                </div>

                <div class="event-date">
                    Tanggal: ' . $event->start_datetime->format('d F Y') . '
                </div>

                <div class="event-info">
                    Diselenggarakan oleh: ' . $event->ukm->name . '
                </div>

                <div class="event-info">
                    Lokasi: ' . $event->location . '
                </div>

                <div class="certificate-footer">
                    Sertifikat ini diberikan sebagai bentuk apresiasi atas partisipasi aktif<br>
                    Universitas Telkom Jakarta<br>
                    ' . now()->format('d F Y') . '
                </div>
            </div>

            <div class="certificate-id">
                ID: ' . $event->slug . '-' . ($user->student_id ?? $user->nim ?? $user->id) . '-' . date('Ymd') . '
            </div>
        </body>
        </html>';
    }

    /**
     * Download certificate - Direct PDF generation
     */
    public function downloadCertificate(EventAttendance $attendance)
    {
        if (!$attendance->canDownloadCertificate()) {
            throw new \Exception('Sertifikat tidak dapat didownload.');
        }

        $event = $attendance->event;
        $user = $attendance->user;

        // Generate certificate HTML directly - use template if available
        if ($event->certificate_template) {
            $certificateHtml = $this->generateTemplateBasedCertificateFixed($event, $user);
        } else {
            $certificateHtml = $this->generateSimpleCertificate($event, $user);
        }

        // Generate PDF directly without saving to storage first
        $pdf = Pdf::loadHTML($certificateHtml)
                  ->setPaper('A4', 'landscape')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'defaultFont' => 'Arial',
                      'dpi' => 150,
                      'isRemoteEnabled' => true, // Enable remote images
                      'chroot' => public_path(), // Allow access to public directory
                  ]);

        // Mark as downloaded
        $attendance->markCertificateDownloaded();

        $filename = 'Sertifikat_' . $event->title . '_' . $user->name . '.pdf';

        // Return PDF directly
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Generate certificate with custom positioning (advanced version)
     */
    public function generateCertificateAdvanced(EventAttendance $attendance, $namePosition = null)
    {
        $event = $attendance->event;
        $user = $attendance->user;

        // Default name position (center of certificate)
        $defaultPosition = [
            'x' => 50, // percentage from left
            'y' => 50, // percentage from top
            'font_size' => 36,
            'color' => '#2c3e50'
        ];

        $position = $namePosition ?: $defaultPosition;

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Sertifikat - ' . $event->title . '</title>
            <style>
                @page {
                    margin: 0;
                    size: A4 landscape;
                }
                body {
                    margin: 0;
                    padding: 0;
                    font-family: "Times New Roman", serif;
                    background-image: url("' . asset('storage/' . $event->certificate_template) . '");
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                    width: 297mm;
                    height: 210mm;
                    position: relative;
                }
                .participant-name {
                    position: absolute;
                    left: ' . $position['x'] . '%;
                    top: ' . $position['y'] . '%;
                    transform: translate(-50%, -50%);
                    font-size: ' . $position['font_size'] . 'px;
                    font-weight: bold;
                    color: ' . $position['color'] . ';
                    text-transform: uppercase;
                    letter-spacing: 3px;
                    text-align: center;
                    white-space: nowrap;
                }
            </style>
        </head>
        <body>
            <div class="participant-name">
                ' . strtoupper($user->name) . '
            </div>
        </body>
        </html>';

        // Generate PDF
        $pdf = Pdf::loadHTML($html)
                  ->setPaper('A4', 'landscape')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'defaultFont' => 'Times-Roman',
                      'dpi' => 300,
                  ]);

        // Generate filename
        $filename = 'certificates/' . $event->slug . '_' . $user->student_id . '_' . time() . '.pdf';

        // Save PDF to storage
        Storage::disk('public')->put($filename, $pdf->output());

        // Update attendance record
        $attendance->update([
            'certificate_generated' => true,
            'certificate_file' => $filename,
        ]);

        return $filename;
    }

    /**
     * Generate certificate with custom name positioning
     */
    public function generateCertificateWithCustomPosition(EventAttendance $attendance, $namePosition = null)
    {
        $event = $attendance->event;
        $user = $attendance->user;

        // Check if event has certificate template
        if (!$event->certificate_template) {
            throw new \Exception('Event tidak memiliki template sertifikat.');
        }

        // Default position (center) - changed color to black
        $defaultPosition = [
            'top' => '50%',
            'left' => '50%',
            'font_size' => '48px',
            'color' => '#000000', // Changed from #2c3e50 to black
            'transform' => 'translate(-50%, -50%)'
        ];

        $position = $namePosition ?: $defaultPosition;

        // Try multiple paths for better compatibility
        $templatePaths = [
            public_path('storage/' . $event->certificate_template),
            storage_path('app/public/' . $event->certificate_template),
            base_path('public/storage/' . $event->certificate_template)
        ];

        $templateBase64 = '';
        $templatePath = null;

        // Find the first existing path
        foreach ($templatePaths as $path) {
            if (file_exists($path)) {
                $templatePath = $path;
                break;
            }
        }

        // Convert image to base64 for embedding in HTML
        if ($templatePath && file_exists($templatePath)) {
            try {
                $imageData = file_get_contents($templatePath);
                $mimeType = mime_content_type($templatePath);
                $templateBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);

                Log::info('Template image loaded successfully for certificate', [
                    'path' => $templatePath,
                    'size' => strlen($imageData),
                    'mime' => $mimeType
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to load template image for certificate', [
                    'path' => $templatePath,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            Log::warning('Template file not found in any path for certificate', [
                'template' => $event->certificate_template,
                'checked_paths' => $templatePaths
            ]);
        }

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Sertifikat - ' . $event->title . '</title>
            <style>
                @page {
                    margin: 0;
                    size: A4 landscape;
                }
                body {
                    margin: 0;
                    padding: 0;
                    font-family: "Times New Roman", serif;
                    width: 297mm;
                    height: 210mm;
                    position: relative;
                    ' . ($templateBase64 ? 'background-image: url("' . $templateBase64 . '");' : 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);') . '
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                }
                .participant-name {
                    position: absolute;
                    top: ' . $position['top'] . ';
                    left: ' . $position['left'] . ';
                    transform: ' . $position['transform'] . ';
                    font-size: ' . $position['font_size'] . ';
                    font-weight: bold;
                    color: ' . $position['color'] . ';
                    text-transform: uppercase;
                    letter-spacing: 4px;
                    text-shadow: 2px 2px 4px rgba(255,255,255,0.8);
                    white-space: nowrap;
                    text-align: center;
                    background: rgba(255,255,255,0.2);
                    border-radius: 10px;
                    padding: 15px 25px;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                }
                .certificate-id {
                    position: absolute;
                    bottom: 20px;
                    right: 30px;
                    font-size: 10px;
                    color: #666;
                    opacity: 0.7;
                    background: rgba(255,255,255,0.8);
                    padding: 5px 10px;
                    border-radius: 5px;
                }
            </style>
        </head>
        <body>
            <div class="participant-name">
                ' . strtoupper($user->name) . '
            </div>

            <div class="certificate-id">
                ID: ' . $event->slug . '-' . ($user->student_id ?? $user->nim ?? $user->id) . '-' . date('Ymd') . '
            </div>
        </body>
        </html>';

        // Generate PDF
        $pdf = Pdf::loadHTML($html)
                  ->setPaper('A4', 'landscape')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'defaultFont' => 'Times-Roman',
                      'dpi' => 300,
                  ]);

        // Generate filename
        $filename = 'certificates/' . $event->slug . '_' . ($user->student_id ?? $user->nim ?? $user->id) . '_' . time() . '.pdf';

        // Save PDF to storage
        Storage::disk('public')->put($filename, $pdf->output());

        // Update attendance record
        $attendance->update([
            'certificate_generated' => true,
            'certificate_file' => $filename,
        ]);

        return $filename;
    }

    /**
     * Generate CSS-only certificate (no GD dependency)
     */
    private function generateCssOnlyCertificate($event, $user)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Sertifikat - ' . $event->title . '</title>
            <style>
                @page {
                    margin: 0;
                    size: A4 landscape;
                }
                body {
                    margin: 0;
                    padding: 40px;
                    font-family: "Times New Roman", serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: black;
                    text-align: center;
                    min-height: calc(100vh - 80px);
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    width: 297mm;
                    height: 210mm;
                    box-sizing: border-box;
                }
                .certificate-border {
                    border: 8px solid #000;
                    padding: 60px 40px;
                    background: rgba(255, 255, 255, 0.9);
                    backdrop-filter: blur(10px);
                    border-radius: 20px;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                }
                .certificate-header {
                    font-size: 48px;
                    font-weight: bold;
                    margin-bottom: 30px;
                    text-transform: uppercase;
                    letter-spacing: 4px;
                    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
                }
                .certificate-subtitle {
                    font-size: 18px;
                    margin-bottom: 40px;
                    opacity: 0.9;
                    font-style: italic;
                }
                .participant-name {
                    font-size: 42px;
                    font-weight: bold;
                    margin: 30px 0;
                    text-transform: uppercase;
                    letter-spacing: 3px;
                    border-bottom: 3px solid #000;
                    padding-bottom: 10px;
                    display: inline-block;
                    text-shadow: 2px 2px 4px rgba(255,255,255,0.8);
                }
                .event-title {
                    font-size: 28px;
                    font-weight: 600;
                    margin: 20px 0;
                    color: #000;
                    text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
                }
                .event-date {
                    font-size: 18px;
                    margin: 15px 0;
                    opacity: 0.9;
                }
                .event-info {
                    font-size: 16px;
                    margin: 10px 0;
                    opacity: 0.8;
                }
                .certificate-footer {
                    margin-top: 40px;
                    font-size: 14px;
                    opacity: 0.7;
                }
                .certificate-id {
                    position: absolute;
                    bottom: 30px;
                    right: 50px;
                    font-size: 12px;
                    opacity: 0.6;
                    background: rgba(255,255,255,0.2);
                    padding: 5px 10px;
                    border-radius: 5px;
                }
                .ukm-info {
                    font-size: 20px;
                    margin: 20px 0;
                    font-weight: 500;
                }
            </style>
        </head>
        <body>
            <div class="certificate-border">
                <div class="certificate-header">SERTIFIKAT</div>
                <div class="certificate-subtitle">Certificate of Participation</div>

                <div>Diberikan kepada:</div>

                <div class="participant-name">
                    ' . strtoupper($user->name) . '
                </div>

                <div>Yang telah mengikuti kegiatan:</div>

                <div class="event-title">
                    ' . $event->title . '
                </div>

                <div class="event-date">
                    Tanggal: ' . $event->start_datetime->format('d F Y') . '
                </div>

                <div class="ukm-info">
                    Diselenggarakan oleh: ' . $event->ukm->name . '
                </div>

                <div class="event-info">
                    Lokasi: ' . $event->location . '
                </div>

                <div class="certificate-footer">
                    Sertifikat ini diberikan sebagai bentuk apresiasi atas partisipasi aktif
                </div>
            </div>

            <div class="certificate-id">
                ID: ' . $event->slug . '-' . ($user->student_id ?? $user->nim ?? $user->id) . '-' . date('Ymd') . '
            </div>
        </body>
        </html>';
    }


}
