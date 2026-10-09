@php
    $trustDefaults = [
        1 => ['title' => '10,000+', 'subtitle' => 'Happy Gentlemen'],
        2 => ['title' => '4.9/5 Rating', 'subtitle' => 'From 2,000+ Reviews'],
        3 => ['title' => 'Premium', 'subtitle' => 'Luxury Packaging'],
        4 => ['title' => 'Easy Returns', 'subtitle' => '7-DAY EXCHANGE POLICY'],
        5 => ['title' => '100% Authentic', 'subtitle' => 'Original Products'],
    ];
    $trustCategoryId = null;
    if (request()->is('single-product')) {
        $trustCategoryId = $product->category_id ?? null;
    } elseif (request()->is('shop', 'shop/*', 'category/*')) {
        $trustCategoryId = $currentCategory->id ?? null;
    }
    $trustKeys = [];
    foreach (range(1, 5) as $slot) {
        foreach (['title', 'subtitle'] as $field) {
            $trustKeys[] = "trust_global_{$slot}_{$field}";
            if ($trustCategoryId) {
                $trustKeys[] = "trust_category_{$trustCategoryId}_{$slot}_{$field}";
            }
        }
    }
    $trustContent = collect();
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('home_sections')) {
            $trustContent = \App\Models\HomeSection::query()->whereIn('section_key', $trustKeys)
                ->pluck('content', 'section_key');
        }
    } catch (\Throwable $exception) {
        // Keep the strip visible while the content table is unavailable.
    }
    $trustText = static function (int $slot, string $field) use ($trustCategoryId, $trustContent, $trustDefaults): string {
        $categoryValue = $trustCategoryId ? trim((string) $trustContent->get("trust_category_{$trustCategoryId}_{$slot}_{$field}")) : '';
        $globalValue = trim((string) $trustContent->get("trust_global_{$slot}_{$field}"));

        return $categoryValue !== '' ? $categoryValue : ($globalValue !== '' ? $globalValue : $trustDefaults[$slot][$field]);
    };
@endphp
<style>
/* ==========================================================================
   GLOBAL 5-PILLAR TRUST FEATURES STRIP
   ========================================================================== */
.knp-global-trust-strip {
    background-color: #FFFFFF;
    border-top: 1px solid #ECEAE5;
    border-bottom: 1px solid #ECEAE5;
    padding: 24px 0;
    width: 100%;
    position: relative;
    z-index: 10;
}

.knp-global-trust-strip .knp-trust-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 clamp(16px, 3vw, 40px);
}

.knp-global-trust-strip .knp-trust-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 20px;
    align-items: center;
}

.knp-global-trust-strip .knp-trust-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    padding: 4px 8px;
}

.knp-global-trust-strip .knp-trust-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    color: #B40016;
}

.knp-global-trust-strip .knp-trust-icon svg {
    width: 26px;
    height: 26px;
    display: block;
}

.knp-global-trust-strip .knp-trust-text {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.25;
    min-width: 0;
}

.knp-global-trust-strip .knp-trust-title {
    display: block;
    font-family: 'Montserrat', 'Manrope', -apple-system, BlinkMacSystemFont, sans-serif !important;
    font-size: 13.5px;
    font-weight: 700 !important;
    color: #111111 !important;
    letter-spacing: -0.2px;
    margin-bottom: 3px;
    white-space: nowrap;
}

.knp-global-trust-strip .knp-trust-desc {
    display: block;
    font-family: 'Montserrat', 'Manrope', -apple-system, BlinkMacSystemFont, sans-serif !important;
    font-size: 11.5px;
    font-weight: 500;
    color: #666666 !important;
    white-space: nowrap;
}

/* Tablet Responsive */
@media (max-width: 1199.98px) {
    .knp-global-trust-strip .knp-trust-grid {
        gap: 12px;
    }
    .knp-global-trust-strip .knp-trust-item {
        gap: 10px;
        padding: 4px 6px;
    }
    .knp-global-trust-strip .knp-trust-title {
        font-size: 12.5px;
    }
    .knp-global-trust-strip .knp-trust-desc {
        font-size: 10.5px;
    }
}

@media (max-width: 991.98px) {
    .knp-global-trust-strip .knp-trust-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px 10px;
    }
    .knp-global-trust-strip .knp-trust-item {
        justify-content: flex-start;
    }
}

/* Mobile Responsive */
@media (max-width: 767.98px) {
    .knp-global-trust-strip {
        padding: 18px 0;
    }
    .knp-global-trust-strip .knp-trust-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px 8px;
    }
    .knp-global-trust-strip .knp-trust-item {
        justify-content: flex-start;
        gap: 10px;
        padding: 4px 6px;
    }
    .knp-global-trust-strip .knp-trust-item:last-child {
        grid-column: span 2;
        justify-content: center;
    }
    .knp-global-trust-strip .knp-trust-icon {
        width: 24px;
        height: 24px;
    }
    .knp-global-trust-strip .knp-trust-icon svg {
        width: 22px;
        height: 22px;
    }
    .knp-global-trust-strip .knp-trust-title {
        font-size: 12px;
        margin-bottom: 2px;
    }
    .knp-global-trust-strip .knp-trust-desc {
        font-size: 10px;
    }
}
</style>

<section class="knp-global-trust-strip" aria-label="House of KNP Trust Pillars">
    <div class="knp-trust-container">
        <div class="knp-trust-grid">
            <!-- 1. Happy Gentlemen -->
            <div class="knp-trust-item">
                <div class="knp-trust-icon">
                    <svg viewBox="0 0 24 24" fill="#B40016" aria-hidden="true">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                    </svg>
                </div>
                <div class="knp-trust-text">
                    <strong class="knp-trust-title">{{ $trustText(1, 'title') }}</strong>
                    <span class="knp-trust-desc">{{ $trustText(1, 'subtitle') }}</span>
                </div>
            </div>

            <!-- 2. Ratings & Reviews -->
            <div class="knp-trust-item">
                <div class="knp-trust-icon">
                    <svg viewBox="0 0 24 24" fill="#B40016" aria-hidden="true">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </div>
                <div class="knp-trust-text">
                    <strong class="knp-trust-title">{{ $trustText(2, 'title') }}</strong>
                    <span class="knp-trust-desc">{{ $trustText(2, 'subtitle') }}</span>
                </div>
            </div>

            <!-- 3. Luxury Packaging -->
            <div class="knp-trust-item">
                <div class="knp-trust-icon">
                    <svg viewBox="0 0 24 24" fill="#B40016" aria-hidden="true">
                        <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.65-.5-.65C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-2 .89-2 2v3c0 .55.45 1 1 1h1v8c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8h1c.55 0 1-.45 1-1V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1h-2.22l.72-.96C13.85 4.41 14.39 4 15 4zM9 4c.61 0 1.15.41 1.5 1.04l.72.96H9c-.55 0-1-.45-1-1s.45-1 1-1zm2 16H6v-7h5v7zm0-9H4V8h7v3zm7 9h-5v-7h5v7zm1-9h-6V8h6v3z"/>
                    </svg>
                </div>
                <div class="knp-trust-text">
                    <strong class="knp-trust-title">{{ $trustText(3, 'title') }}</strong>
                    <span class="knp-trust-desc">{{ $trustText(3, 'subtitle') }}</span>
                </div>
            </div>

            <!-- 4. Easy Returns / Exchange -->
            <div class="knp-trust-item">
                <div class="knp-trust-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#B40016" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                </div>
                <div class="knp-trust-text">
                    <strong class="knp-trust-title">{{ $trustText(4, 'title') }}</strong>
                    <span class="knp-trust-desc" style="text-transform: uppercase; font-size: 11px; letter-spacing: 0.3px;">{{ $trustText(4, 'subtitle') }}</span>
                </div>
            </div>

            <!-- 5. 100% Authentic -->
            <div class="knp-trust-item">
                <div class="knp-trust-icon">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="11" fill="#B40016"/>
                        <path d="M7.5 12.2l3.2 3.3 6.3-6.5" stroke="#FFFFFF" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
                <div class="knp-trust-text">
                    <strong class="knp-trust-title">{{ $trustText(5, 'title') }}</strong>
                    <span class="knp-trust-desc">{{ $trustText(5, 'subtitle') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>
