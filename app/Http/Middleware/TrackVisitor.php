<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Shetabit\Visitor\Models\Visit;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Ne pas tracker les requêtes AJAX, API ou admin
        if ($request->ajax() || 
            $request->expectsJson() || 
            $request->is('admin*') || 
            $request->is('_*') ||
            $request->is('livewire*')) {
            return $response;
        }

        // Ne tracker que les requêtes GET réussies
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            try {
                $this->recordVisit($request);
            } catch (\Exception $e) {
                // Logger l'erreur silencieusement sans interrompre la requête
                logger('Visitor tracking error: ' . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * Enregistrer une visite
     */
    private function recordVisit(Request $request): void
    {
        $userAgent = $request->userAgent() ?? '';

        Visit::create([
            'ip' => $request->ip(),
            'method' => $request->method(),
            'request' => $request->path(),
            'url' => $request->fullUrl(),
            'referer' => $request->header('referer'),
            'languages' => implode(',', $request->getLanguages()),
            'useragent' => $userAgent,
            'headers' => json_encode($request->headers->all()),
            'device' => $this->detectDevice($userAgent),
            'platform' => $this->detectPlatform($userAgent),
            'browser' => $this->detectBrowser($userAgent),
        ]);
    }

    /**
     * Détecter le type d'appareil
     */
    private function detectDevice(string $userAgent): string
    {
        if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile/i', $userAgent)) {
            return 'mobile';
        }
        if (preg_match('/ipad|tablet/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Détecter la plateforme
     */
    private function detectPlatform(string $userAgent): string
    {
        if (preg_match('/windows/i', $userAgent)) return 'Windows';
        if (preg_match('/mac|darwin/i', $userAgent)) return 'macOS';
        if (preg_match('/linux/i', $userAgent)) return 'Linux';
        if (preg_match('/android/i', $userAgent)) return 'Android';
        if (preg_match('/iphone|ipad|ipod/i', $userAgent)) return 'iOS';
        return 'Unknown';
    }

    /**
     * Détecter le navigateur
     */
    private function detectBrowser(string $userAgent): string
    {
        if (preg_match('/chrome/i', $userAgent)) return 'Chrome';
        if (preg_match('/firefox/i', $userAgent)) return 'Firefox';
        if (preg_match('/safari/i', $userAgent)) return 'Safari';
        if (preg_match('/edge/i', $userAgent)) return 'Edge';
        if (preg_match('/opera/i', $userAgent)) return 'Opera';
        return 'Unknown';
    }
}