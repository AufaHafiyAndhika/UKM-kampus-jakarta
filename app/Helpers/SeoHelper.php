<?php

namespace App\Helpers;

class SeoHelper
{
    /**
     * Generate SEO meta tags for pages
     */
    public static function generateMeta($data = [])
    {
        $defaults = [
            'title' => config('app.name', 'UKM Telkom Jakarta'),
            'description' => 'Website resmi Unit Kegiatan Mahasiswa Telkom Jakarta. Bergabunglah dengan berbagai UKM dan ikuti kegiatan menarik untuk mengembangkan potensi diri.',
            'keywords' => 'UKM, Telkom Jakarta, mahasiswa, kegiatan, organisasi, ekstrakurikuler, pengembangan diri',
            'image' => asset('images/og-default.jpg'),
            'url' => url()->current(),
            'type' => 'website',
            'author' => 'UKM Telkom Jakarta',
            'robots' => 'index, follow',
            'canonical' => url()->current(),
        ];

        return array_merge($defaults, $data);
    }

    /**
     * Generate structured data (JSON-LD) for organization
     */
    public static function getOrganizationSchema()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            'name' => 'UKM Telkom Jakarta',
            'alternateName' => 'Unit Kegiatan Mahasiswa Telkom Jakarta',
            'url' => url('/'),
            'logo' => asset('storage/Telkom.png'),
            'description' => 'Platform digital untuk mengelola dan mengembangkan Unit Kegiatan Mahasiswa di Telkom Jakarta.',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Jl. Gegerkalong Hilir No.47',
                'addressLocality' => 'Bandung',
                'addressRegion' => 'Jawa Barat',
                'postalCode' => '40152',
                'addressCountry' => 'ID'
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+62-22-7566456',
                'contactType' => 'customer service',
                'availableLanguage' => 'Indonesian'
            ],
            'sameAs' => [
                'https://www.instagram.com/telkomuniversityjakarta/',
                'https://www.facebook.com/telkomuniversityjakarta',
                'https://twitter.com/telkomuniv_jkt'
            ]
        ];
    }

    /**
     * Generate structured data for UKM
     */
    public static function getUkmSchema($ukm)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $ukm->name,
            'description' => $ukm->description,
            'url' => route('ukms.show', $ukm->slug),
            'logo' => $ukm->logo ? asset('storage/' . $ukm->logo) : asset('images/default-ukm.png'),
            'memberOf' => [
                '@type' => 'EducationalOrganization',
                'name' => 'Telkom University Jakarta'
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'email' => $ukm->email,
                'telephone' => $ukm->phone,
                'contactType' => 'customer service'
            ]
        ];
    }

    /**
     * Generate structured data for events
     */
    public static function getEventSchema($event)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event->title,
            'description' => $event->description,
            'startDate' => $event->start_datetime,
            'endDate' => $event->end_datetime,
            'location' => [
                '@type' => 'Place',
                'name' => $event->location,
                'address' => $event->location
            ],
            'organizer' => [
                '@type' => 'Organization',
                'name' => $event->ukm->name,
                'url' => route('ukms.show', $event->ukm->slug)
            ],
            'image' => $event->poster ? asset('storage/' . $event->poster) : asset('images/default-event.jpg'),
            'url' => route('events.show', $event->slug),
            'offers' => [
                '@type' => 'Offer',
                'price' => $event->registration_fee ?? 0,
                'priceCurrency' => 'IDR',
                'availability' => $event->registration_open ? 'InStock' : 'OutOfStock'
            ]
        ];
    }

    /**
     * Generate breadcrumb structured data
     */
    public static function getBreadcrumbSchema($breadcrumbs)
    {
        $items = [];
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url']
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        ];
    }

    /**
     * Clean and optimize text for SEO
     */
    public static function cleanText($text, $maxLength = 160)
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        if (strlen($text) > $maxLength) {
            $text = substr($text, 0, $maxLength - 3) . '...';
        }
        
        return $text;
    }

    /**
     * Generate keywords from content
     */
    public static function generateKeywords($content, $baseKeywords = [])
    {
        $defaultKeywords = ['UKM', 'Telkom Jakarta', 'mahasiswa', 'kegiatan', 'organisasi'];
        $keywords = array_merge($defaultKeywords, $baseKeywords);
        
        // Extract keywords from content (simple implementation)
        $words = str_word_count(strtolower(strip_tags($content)), 1);
        $commonWords = ['dan', 'atau', 'yang', 'untuk', 'dengan', 'dari', 'ke', 'di', 'pada', 'adalah', 'akan', 'telah'];
        $words = array_diff($words, $commonWords);
        $wordCounts = array_count_values($words);
        arsort($wordCounts);
        
        $extractedKeywords = array_slice(array_keys($wordCounts), 0, 5);
        $keywords = array_merge($keywords, $extractedKeywords);
        
        return implode(', ', array_unique($keywords));
    }
}
